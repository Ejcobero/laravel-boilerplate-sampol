<?php

namespace App\Providers;

use App\Repository\Contracts\EloquentRepositoryInterface;
use App\Repository\Contracts\User\UserRepositoryInterface;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Eloquent\User\UserRepository;
use Illuminate\Support\ServiceProvider;

use App\Repository\Contracts\Book\BookRepositoryInterface;
use App\Repository\Eloquent\Book\BookRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
    }
}
