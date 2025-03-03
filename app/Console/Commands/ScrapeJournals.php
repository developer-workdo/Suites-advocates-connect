<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;
use App\Models\Journal;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpClient\HttpClient;

class ScrapeJournals extends Command
{
    protected $signature = 'scrape:journals';
    protected $description = 'Scrape journals from WorldLII';

    public function __construct()
    {
        parent::__construct();
    }

    function resolveUrl($baseUrl, $relativeUrl) {
        $base = parse_url($baseUrl);
        $basePath = isset($base['path']) ? rtrim($base['path'], '/') . '/' : '/';
        $scheme = isset($base['scheme']) ? $base['scheme'] . '://' : '';
        $host = isset($base['host']) ? $base['host'] : '';
        $relativeUrl = ltrim($relativeUrl, '/');
        $newPath = $basePath . $relativeUrl;
        $resolvedUrl = preg_replace('/\/{2,}/', '/', $newPath);
        $resolvedUrl = preg_replace('/\/\.\.\//', '/', $resolvedUrl);
    
        return $scheme . $host . $resolvedUrl;
    }

    public function handle()
    {
        $httpClient = HttpClient::create(['timeout' => 60]);
        $client = new Client($httpClient);
        $crawler = $client->request('GET', 'http://www.worldlii.org/int/journals/');

        $crawler->filter('table#searchScope tr')->each(function ($node) use ($client) {
            $title = $node->filter('a')->text();
            $url = $node->filter('a')->attr('href');
            // $fullUrl = 'http://www.worldlii.org' . $url;
            $fullUrl = $this->resolveUrl('http://www.worldlii.org', $url);
            $journalPageCrawler = $client->request('GET', $fullUrl);

            if ($journalPageCrawler->filter('h3 blockquote a')->count() > 0) {
                $journalPageCrawler->filter('h3 blockquote a')->each(function ($node) use ($client, $fullUrl) {
                    $relativeLink = $node->attr('href');
                    // $letterUrl = $fullUrl . $relativeLink;
                    $letterUrl = $this->resolveUrl($fullUrl, $relativeLink);
                    $letterPageCrawler = $client->request('GET', $letterUrl);
                    $letterPageCrawler->filter('ul li a')->each(function ($node) use ($client, $fullUrl) {
                        $documentTitle = $node->text();
                        $documentUrl = $node->attr('href');
                        // $fullDocumentUrl = $fullUrl . $documentUrl;
                        $fullDocumentUrl = $this->resolveUrl($fullUrl, $documentUrl);

                        Journal::updateOrCreate(
                            ['title' => $documentTitle],
                            [
                                'title'      => $documentTitle,
                                'site_link'  => $fullDocumentUrl,
                                'created_by' => \App\Models\User::where('type', 'super admin')->first()->id ?? 1,
                            ]
                        );

                        // $documentCrawler = $client->request('GET', $fullDocumentUrl);

                        // $documentCrawler->filter('div.make-database object')->each(function ($node) use ($documentTitle, $journal) {
                        //     $pdfUrl = $node->attr('data');
                        //     if ($pdfUrl) {
                        //         $fullPdfUrl = 'http://www.worldlii.org' . '/' . ltrim($pdfUrl, '/');

                        //         try {
                        //             $pdfContent = Http::timeout(300)->get($fullPdfUrl)->body();
                        //             $path = 'uploads/journal-documents/' . basename($fullPdfUrl);
                        //             if (!Storage::exists('uploads/journal-documents')) {
                        //                 Storage::makeDirectory('uploads/journal-documents');
                        //             }

                        //             Storage::put($path, $pdfContent);
                        //             $journal->update(['document' => $path]);
                        //         } catch (\Exception $e) {
                        //             $this->error("Failed to download or save PDF for: $documentTitle". ''.$e->getMessage());
                        //             \Log::error('Failed to download or save PDF: ' . $e->getMessage());
                        //         }
                        //     }
                        // });
                    });
                });
            }
        });
        $this->info('Scraping process completed at' . now()->toDateTimeString());
    }
}
