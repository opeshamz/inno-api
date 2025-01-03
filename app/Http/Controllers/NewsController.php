<?php

namespace App\Http\Controllers;

use App\Interfaces\ArticleRepositoryInterface;
use App\Interfaces\PreferenceInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class NewsController extends Controller
{
    private $news;
    private $preference;
    public function __construct(
        ArticleRepositoryInterface $news,
        PreferenceInterface $preference)
    {
       $this->news =  $news;
       $this->preference = $preference;
    }
    final public function getArticles(Request $request): JsonResponse
    {
        $allowedFilters = ['source', 'category', 'search', 'startDate', 'endDate', "page"];
        $unexpectedFilters = array_diff(array_keys($request->all()), $allowedFilters);
        if (!empty($unexpectedFilters)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid filter(s) passed: ' . implode(', ', $unexpectedFilters),
                'allowed_filters' => $allowedFilters,
                'data' => []
            ], 400);
        }
        $user = $request->user();
        try {
            $filters = $this->prepareFilters($request, $user);
            $articles = $this->news->getArticles($filters);
            return response()->json([
                'status' => 'success',
                'message' => 'Articles fetched successfully.',
                'data' => $articles
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching articles: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching articles.',
                'data' => []
            ], 500);
        }
    }
    private function prepareFilters(Request $request, User $user): array
    {
        $preference = $this->preference->getUserPreference($user->id);
        $filters = [
            'source' => $request->input('source', $preference->sources ?? []),
            'category' => $request->input('category', $preference->categories ?? []),
            'author' => $preference->authors ?? null,
            'startDate' => $request->has('startDate')
                ? Carbon::parse($request->input('startDate'))->toDateString()
                : null,
            'endDate' => $request->has('endDate')
                ? Carbon::parse($request->input('endDate'))->toDateString()
                : Carbon::now()->toDateString()
        ];
        return array_filter($filters, function($value) {
            return !empty($value) || $value === "0" || $value=== null ;
        });
    }
    final public function getArticlesCategories(Request $request): JsonResponse{
        try{
            $article = $this->news->getArticlesCategorySourcesAuthors('category');
            return response()->json([
                "status" => 'success',
                "message" => "Category fetched successfully.",
                'data' => $article
            ], 200);
        } catch (\Exception $e){
            Log::error('User preference : '.$e->getMessage());
            return response()->json([
                "status" => 'error',
                "message" => "An error occurred while fetching.",
                'data' => []
            ], 500);
        }
    }
    final public function getArticlesAuthor(Request $request): JsonResponse{
        try{
            $article = $this->news->getArticlesCategorySourcesAuthors('author');
            return response()->json([
                "status" => 'success',
                "message" => "Authors fetched successfully.",
                'data' => $article
            ], 200);
        } catch (\Exception $e){
            Log::error('User preference : '.$e->getMessage());
            return response()->json([
                "status" => 'error',
                "message" => "An error occurred while fetching.",
                'data' => []
            ], 500);
        }
    }

    final public function getArticlesSource(Request $request): JsonResponse{
        try{

            $article = $this->news->getArticlesCategorySourcesAuthors('source');
            return response()->json([
                "status" => 'success',
                "message" => "Sources fetched successfully.",
                'data' => $article
            ], 200);
        } catch (\Exception $e){
            Log::error('User preference : '.$e->getMessage());
            return response()->json([
                "status" => 'error',
                "message" => "An error occurred while fetching.",
                'data' => []
            ], 500);
        }
    }
    final public function getArticleDetails(string $id, Request $request): JsonResponse{
        try{
            $article = $this->news->getArticlesDetails($id);
            return response()->json([
                "status" => 'success',
                "message" => "Article details fetched successfully.",
                'data' => $article
            ], 200);
        } catch (\Exception $e){
            Log::error('User preference : '.$e->getMessage());
            return response()->json([
                "status" => 'error',
                "message" => "An error occurred while fetching.",
                'data' => []
            ], 500);
        }
    }

}
