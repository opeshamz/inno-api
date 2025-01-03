<?php


namespace App\Repositories;

use App\Models\Article;
use App\Interfaces\ArticleRepositoryInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class ArticleRepository implements ArticleRepositoryInterface
{
  public function createOrUpdate(array $data): array
 {
     $article =  Article::updateOrCreate(
         ['title' => $data['title']],
         [
             'content' => $data["content"],
             'publish_date' => isset($article['publishedAt'])
                 ? Carbon::parse($data["publish_date"])->format('Y-m-d H:i:s')
                 : Carbon::now()->format('Y-m-d H:i:s'),
             'category' => $data["category"],
             'source' => $data["source"],
             'author' => $data["author"],
             'news_url' => $data["news_url"],
             "img_url" => $data["img_url"]
         ]
     );
     return $article->toArray();
 }

     public function getArticles(array $filters = []): array
    {

        // For better performance and faster search, consider implementing Elasticsearch.
        $query = DB::table('articles');
        if (isset($filters['search'])) {
            $query->where('content', 'like', '%' . $filters['search'] . '%');
        }
        if (isset($filters['category'])) {
            $query->whereIn('category', (array) $filters['category']);
        }
        if (isset($filters['source'])) {
            $query->whereIn('source', (array) $filters['source']);
        }
        if (isset($filters['author'])) {
            $query->whereIn('author', (array) $filters['author']);
        }
        if (isset($filters['startDate'])) {
            $query->where('publish_date', '=', $filters['startDate']);
        }
        if (isset($filters['startDate']) && isset($filters['endDate'])) {
            $query->whereBetween('publish_date', [$filters['startDate'], $filters['endDate']]);
        }
        $query->orderBy('created_at', 'desc');
        $articles = $query->paginate(10);
        return $articles->toArray();
    }

     public function getArticlesDetails(string $id): array
    {
     return   Article::where('id', $id)->first();
    }
     public function getArticlesCategorySourcesAuthors(string $data): array
    {
        // Implement search functionality here for an enhanced user experience.
        // Additionally, pagination should be supported, with the frontend implementing infinite scroll for seamless navigation.
         return Article::select($data)
            ->distinct()
            ->pluck($data)
            ->toArray();
    }
}

