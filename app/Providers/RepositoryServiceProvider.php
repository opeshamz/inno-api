<?php


namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepository;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\ArticleRepositoryInterface;
use App\Repositories\ArticleRepository;
use App\Repositories\PreferenceRepository;
use App\Interfaces\PreferenceInterface;

class RepositoryServiceProvider extends ServiceProvider
{

    public function register()
{
    $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
    $this->app->bind(PreferenceInterface::class, PreferenceRepository::class);
}
}
