<?php

namespace App\Console\Commands;

use App\Interfaces\ArticleRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewYorkTimes extends Command
{
    protected $signature = 'scrape:newyorktimes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape news articles from news APIs';

    /**
     * Execute the console command.
     *
     * @return int
     */
    protected $articleRepository;
    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        parent::__construct();
        $this->articleRepository = $articleRepository;
    }

    public function handle()

    {
        $this->info('Command started.');
        $key = env('NEWYORKTIMES_API_KEY');
        $url = env('NEWYORKTIMES_BASE_URL');
        try {
            $queryParams = [
                'api-key' => 'AGxixZTA6tSFdkT5Qx4QCl0W8q8yUGfbs',
            ];
            $sources = [
                "politics",
                'technology',
                'fashion',
                'food',
                'sports',
                'health'];
            foreach($sources as $source) {
                $response = Http::get("https://api.nytimes.com/svc/topstories/v2/{$source}.json", $queryParams);
                if ($response->successful()) {
                    $articles = $response->json('results');
                    foreach ($articles as $article) {
                        $dto = [
                            "title" => $article['title'],
                            "content" => $article['abstract'],
                            "publish_date" => $article['published_date'],
                            "category" => $source,
                            "source" => 'new york times',
                            "author" => $article['byline'] ?? null,
                            'news_url' => $article['url'],
                            'img_url' => $article['multimedia'][0]['url'] ?? '',
                        ];
                        $this->articleRepository->createOrUpdate($dto);
                    }
                    $this->info("Articles from newsapi fetched successfully.");
                }
            }
        } catch (\Exception $e) {
            // A third-party error logger can be integrated here for better error management
            Log::error("Error fetching articles from new york times: " . $e->getMessage());
            $this->error("Error occurred while fetching articles from new york times. Check logs for details.");
        }
    }
}
