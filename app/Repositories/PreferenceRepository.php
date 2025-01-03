<?php


namespace App\Repositories;


use App\Interfaces\PreferenceInterface;
use App\Models\Preferences;

class PreferenceRepository implements PreferenceInterface
{

    public function createPreference(array $data): array{
        $response = Preferences::create($data);
        return $response->toArray();
    }
    public function getUserPreference(string $userId): Preferences {
        $response = Preferences::where('user_id', $userId)->first();
        return $response;
    }

    public function updateUserPreference(string $userId, array $data): array{
        $preference =  $this->getUserPreference($userId);
        $preference->update($data);
        $response =  $preference->refresh();
        return  $response->toArray();

    }

}
