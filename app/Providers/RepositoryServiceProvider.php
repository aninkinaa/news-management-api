<?php

namespace App\Providers;


use App\Repositories\Interfaces\NewsRepositoryInterface;
use App\Repositories\Implementations\NewsRepositoryImplement;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {

        $this->app->bind(
            NewsRepositoryInterface::class,
            NewsRepositoryImplement::class
        );
    }
    // ...
}