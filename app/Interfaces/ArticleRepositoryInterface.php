<?php


namespace App\Interfaces;

interface ArticleRepositoryInterface
{

    public function createOrUpdate(array $data): array;
    public function getArticles(array $filters = []): array;
    public function getArticlesDetails(string $id): array;
    public function getArticlesCategorySourcesAuthors(string $data): array;
}
