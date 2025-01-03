<?php

namespace App\Console\Commands;

use App\Interfaces\ArticleRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScrapeNewsApiAI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:newsapiai';

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
        $key = env('NEWSAIAPI_KEY');
        $url = env('NEWSAIAPI_BASE_URL');
        try {
            $queryParams = [
               'action' => 'getArticles',
//             'keyword' => 'Tesla Inc',
                "categoryUri" => "dmoz/Business",
                'ignoreSourceGroupUri' => 'paywall/paywalled_sources',
                'articlesPage' => 1,
                'articlesCount' => 100,
                'articlesSortBy' => 'date',
                'articlesSortByAsc' => false,
                'dataType' => ['news', 'blog', 'pr'],
                'forceMaxDataTimeWindow' => 31,
                'resultType' => 'articles',
                'apiKey' => 'a0ca15be-d266-41ee-99df-2fbe2d9a1dab',
                'lang"=>"eng',
                'articleBodyLen' => -1,
     ];
            $response = Http::get( "https://eventregistry.org/api/v1/article/getArticles",  $queryParams);
            if ($response->successful()) {
                $articles = $response->json('articles.results');
                foreach ($articles as $article) {
                    $dto =[
                        "title"=> $article['title'],
                        "content"=> $article['body'] ?? '',
                        "publish_date"=> $article['date'],
                        "category"=> 'business',
                        "source"=> $article['source']['title'],
                        "author"=> $article['author']['name'] ?? null,
                          'news_url'=> $article['url'],
                        'img_url'=> $article['urlToImage'] ?? '',
                    ];
                    $this->articleRepository->createOrUpdate($dto);
                }
                $this->info("Articles from newsapi fetched successfully.");
            }
        } catch (\Exception $e) {
            // A third-party error logger can be integrated here for better error management
            Log::error("Error fetching articles from newsapi ai: " . $e->getMessage());
            $this->error("Error occurred while fetching articles from newsapi ai. Check logs for details.");
        }
    }
}
