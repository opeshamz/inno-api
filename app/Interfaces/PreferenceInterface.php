<?php


namespace App\Interfaces;


use App\Models\Preferences;

interface PreferenceInterface
{

    public function createPreference(array $data): array;
    public function getUserPreference(string $userId): Preferences;
    public function updateUserPreference(string $userId, array $data): array;
//    public function getArticlesCategorySourcesAuthors(): array;

}
