<?php

namespace App\Console\Commands;

use App\Interfaces\ArticleRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScrapeNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:newsapi';

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
        $key = env('NEWSAPI_KEY');
        $url = env('NEWSAPI_BASE_URL');
          try {
              $response = Http::get( "https://newsapi.org/v2/everything", [
                  'q' => 'ESPN',
                  'apiKey' => 'f74a2c137ca041b18111dd8b8d429265'
              ]);
              if ($response->successful()) {
                  $articles = $response->json('articles');
                  foreach ($articles as $article) {
                      $dto =[
                          "title"=> $article['title'],
                          "content"=> $article['content'] ?? '',
                          "publish_date"=> $article['publishedAt'] ?? null,
                          "category"=> 'sport',
                          "source"=> $article['source']['name'],
                          "author"=> $article['author'] ?? null,
                          'news_url'=> $article['url'],
                          'img_url'=> $article['urlToImage'] ?? '',
                      ];
                      $this->articleRepository->createOrUpdate($dto);
                  }
                  $this->info("Articles from newsapi fetched successfully.");
              }
          } catch (\Exception $e) {
              // A third-party error logger can be integrated here for better error management
              Log::error("Error fetching articles from newsapi: " . $e->getMessage());
              $this->error("Error occurred while fetching articles from newsapi. Check logs for details.");
          }
    }
}


