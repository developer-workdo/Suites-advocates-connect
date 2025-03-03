<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;
use App\Models\CaseLawByAreaCategory;
use App\Models\CaseLawByArea;
use App\Models\User;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use PhpOffice\PhpWord\IOFactory;

class ScrapeLegalCases extends Command
{
    protected $signature = 'scrape:legal-cases';

    protected $description = 'Scrape legal cases from various websites';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->scrapeULII();
        $this->scrapeAfricanLII();

        // $this->scrapeWorldLII();
        // $this->scrapeBAILII();
        $this->info('Scraping process completed at' . now()->toDateTimeString());
    }

    protected function scrapeCases($mainUrl, $basePath, $pdfBaseUrl)
    {
        $client = new Client();
        $crawler = $client->request('GET', $basePath);

        $crawler->filter('.flow-columns-group .card')->each(function ($card) use ($client, $mainUrl, $pdfBaseUrl) {
            $header = $card->filter('h5.card-header')->text();
            if ($header === 'Case indexes') {
                $card->filter('ul.list-group li.list-group-item a')->each(function ($node) use ($client, $mainUrl, $pdfBaseUrl) {
                    $title = $node->text();
                    $url = $node->attr('href');
                    $category = CaseLawByAreaCategory::firstOrCreate([
                        'name' => $title,
                        'created_by' => User::where('type', 'super admin')->first()->id ?? 1,
                    ]);
                    $caseCrawler = $client->request('GET', $mainUrl . $url);
                    $caseCrawler->filter('.doc-table tr .cell-title a')->each(function ($node) use ($mainUrl, $client, $category, $pdfBaseUrl) {
                        $title = $node->text();
                        $url = $node->attr('href');
                        $caseLaw = CaseLawByArea::updateOrCreate(
                            [
                                'case_law_by_area_category_id' => $category->id,
                                'title' => $title
                            ],
                            [
                                'description' => null
                            ]
                        );

                        $caseCrawler = $client->request('GET', $pdfBaseUrl . $url);
                        $node = $caseCrawler->filter('dt:contains("Media Neutral Citation") + dd button, dt:contains("Nukuu ya Vyombo vya") + dd button');
                        $domain = null;
                        if ($node->count() > 0) {
                            $dataValueHtml = $node->attr('data-value-html');

                            $dom = new \DOMDocument();
                            libxml_use_internal_errors(true);
                            $dom->loadHTML($dataValueHtml);
                            libxml_clear_errors();
                            $xpath = new \DOMXPath($dom);
                            $href = $xpath->evaluate('string(//a/@href)');

                            if ($href) {
                                $parsedUrl = parse_url($href);
                                $domain = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
                            }
                        } else {
                            $linkedinLink = $caseCrawler->filter('.btn.btn-link.share-link')->reduce(function ($node) {
                                return strpos($node->attr('href'), 'https://www.linkedin.com/sharing/share-offsite/?url=') === 0;
                            })->attr('href');

                            if ($linkedinLink) {
                                $parsedLinkedinUrl = parse_url($linkedinLink);
                                parse_str($parsedLinkedinUrl['query'], $queryParams);
                                $extractedUrl = $queryParams['url'] ?? '';
                                $parsedExtractedUrl = parse_url($extractedUrl);
                                $scheme = $parsedExtractedUrl['scheme'] ?? 'https';
                                $host = $parsedExtractedUrl['host'] ?? '';

                                $domain = $scheme . '://' . $host;
                            }
                        }
                        if ($domain) {
                            $caseCrawler->filter('.btn.btn-primary.btn-shrink-sm')->each(function ($pdfNode) use ($caseLaw, $title, $domain) {
                                $pdfUrl = $pdfNode->attr('href');
                                $text = $pdfNode->text();
                                if ($pdfUrl) {
                                    $fullPdfUrl = rtrim($domain, '/') . '/' . ltrim($pdfUrl, '/');
                                    $filePath = $this->handleFileDownload($fullPdfUrl, $title, $text);
                                    $caseLaw->update(['document' => $filePath]);
                                }
                            });
                        }
                    });
                });
            }
        });
    }

    protected function handleFileDownload($fullPdfUrl, $filename, $text)
    {
        $fileExtension = pathinfo(parse_url($fullPdfUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: (
            Str::contains($text, 'PDF') ? 'pdf' : (Str::contains($text, 'DOCX') ? 'docx' : (Str::contains($text, 'RTF') ? 'rtf' : 'pdf'))
        );
        $filename = pathinfo($filename, PATHINFO_FILENAME) . '.' . $fileExtension;
        try {
            $response = Http::timeout(300)->get($fullPdfUrl);

            if ($response->successful()) {
                $fileContent = $response->body();
                $filePath = 'uploads/case-law-documents/' . $filename;
                Storage::put($filePath, $fileContent);
                return $filePath;
            } else {
                $this->error('Failed to download file from URL: ' . $fullPdfUrl);
            }
        } catch (RequestException $e) {
            $this->error($e->getMessage());
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    protected function convertRtfToPdf($rtfContent, $filename)
    {
        $tempRtfFile = tempnam(sys_get_temp_dir(), 'rtf');
        file_put_contents($tempRtfFile, $rtfContent);
        try {
            $phpWord = IOFactory::load($tempRtfFile, 'RTF');
        } catch (\Exception $e) {
            $this->error('Error loading RTF file: ' . $e->getMessage());
        }
        $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
        ob_start();
        $htmlWriter->save('php://output');
        $htmlContent = ob_get_clean();
        if (empty($htmlContent)) {
        }
        try {
            $mpdf = new Mpdf();
            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';
            $mpdf->WriteHTML($htmlContent);
            $pdfContent = $mpdf->Output('', 'S');
        } catch (\Exception $e) {
            $this->error('Error writing HTML to PDF: ' . $e->getMessage());
        }
        unlink($tempRtfFile);
        return $pdfContent;
    }

    protected function scrapeULII()
    {
        $this->scrapeCases('https://ulii.org', 'https://ulii.org', 'https://ulii.org');
    }

    protected function scrapeAfricanLII()
    {
        $this->scrapeCases('https://africanlii.org', 'https://africanlii.org/taxonomy', 'https://africanlii.org');
    }

    protected function scrapeWorldLII()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'http://www.worldlii.org/countries.html');

        $category = CaseLawByAreaCategory::firstOrCreate([
            'name' => 'WorldLII',
            'created_by' => User::where('type', 'super admin')->first()->id ?? 1,
        ]);

        $cases = $crawler->filter('.liiListing li a')->each(function ($node) {
            return [
                'title' => $node->text(),
                'url' => $node->attr('href'),
            ];
        });

        foreach ($cases as $case) {
            CaseLawByArea::updateOrCreate([
                'case_law_by_area_category_id' => $category->id,
                'title' => $case['title']
            ], [
                'description' => $case['url'],
            ]);
        }
    }

    protected function scrapeBAILII()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://www.bailii.org/databases.html');

        $category = CaseLawByAreaCategory::firstOrCreate([
            'name' => 'BAILII',
            'created_by' => User::where('type', 'super admin')->first()->id ?? 1,
        ]);

        $cases = $crawler->filter('.databaseList a')->each(function ($node) {
            return [
                'title' => $node->text(),
                'url' => $node->attr('href'),
            ];
        });

        foreach ($cases as $case) {
            CaseLawByArea::updateOrCreate(
                [
                    'case_law_by_area_category_id' => $category->id,
                    'title' => $case['title'],
                ],
                [
                    'description' => $case['url'],
                ]
            );
        }
    }
}
