<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;
use App\Models\Legislation;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ScrapeLegislation extends Command
{
    protected $signature = 'scrape:legislation';
    protected $description = 'Scrape legislation from ULII';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->scrapeULIILegislation();

        $this->info('Scraping process completed at' . now()->toDateTimeString());
    }

    protected function scrapeULIILegislation()
    {
        $mainUrl = 'https://ulii.org';

        $baseUrl = $mainUrl . '/legislation';

        $lastPage = $this->getLastPage($baseUrl);

        $this->info("Total pages to scrape: $lastPage");

        $pageUrls = array_map(function ($page) use ($baseUrl) {
            return $baseUrl . '?page=' . $page;
        }, range(1, $lastPage));

        foreach ($pageUrls as $pageUrl) {
            $this->info("Scraping page: " . basename($pageUrl));
            $this->scrapePage($pageUrl, $mainUrl);
        }
    }

    protected function getLastPage($url)
    {
        $client = new Client();
        $crawler = $client->request('GET', $url);

        $paginationItems = $crawler->filter('.pagination li a');

        $pageNumbers = $paginationItems->each(function ($node) {
            $text = trim($node->text());
            return is_numeric($text) ? (int)$text : null;
        });
        $pageNumbers = array_filter($pageNumbers, function ($number) {
            return $number !== null;
        });

        if (count($pageNumbers) > 0) {
            return max($pageNumbers);
        }
        return 1;
    }

    protected function scrapePage($url, $baseUrl)
    {
        $client = new Client();

        $crawler = $client->request('GET', $url);

        $crawler->filter('.doc-table .cell-title a')->each(function ($node) use ($baseUrl) {
            $lawUrl         = $baseUrl . $node->attr('href');
            $title          = $node->text();
            $details        = $this->scrapeLawDetail($lawUrl, $baseUrl);
            Legislation::updateOrCreate(
                ['title' => $title],
                [
                    'document' => $details['document'],
                    'description' => $details['description'],
                    'created_by' => User::where('type', 'super admin')->first()->id ?? 1,
                ]
            );
        });
    }

    protected function scrapeLawDetail($url, $baseUrl)
    {
        $client         = new Client();
        $crawler        = $client->request('GET', $url);
        $description    = $crawler->filter('la-akoma-ntoso')->count() > 0 ? $crawler->filter('la-akoma-ntoso')->html() : '';
        $documentNode   = $crawler->filter('.btn-group.dropdown-center a.btn.btn-primary');
        $documentPath   = null;
        if ($documentNode->count() > 0) {
            $documentUrl = $documentNode->attr('href');
            $documentPath = $this->downloadDocument($documentUrl, $baseUrl);
        }
        return [
            'description' => $description,
            'document' => $documentPath
        ];
    }

    protected function downloadDocument($url, $baseUrl)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get($baseUrl . $url);
        $content = $response->getBody()->getContents();
        $dispositionHeader = $response->getHeader('Content-Disposition');
        $filename = null;
        if ($dispositionHeader) {
            if (preg_match('/filename[^;=\n]*=((["\']).*?\2|[^;\n]*)/', $dispositionHeader[0], $matches)) {
                $filename = trim($matches[1], ' "');
            }
        }
        if (!$filename) {
            $filename = basename($url);
        }
        if (!Storage::exists('uploads/legislation')) {
            Storage::makeDirectory('uploads/legislation');
        }
        $path = 'uploads/legislation/' . $filename;
        Storage::put($path, $content);
        return $path;
    }
}
