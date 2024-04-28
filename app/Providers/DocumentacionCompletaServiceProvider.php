<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DocumentacionCompletaService;

class DocumentacionCompletaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(DocumentacionCompletaService::class, function ($app) {
            return new DocumentacionCompletaService();
        });
    }
}
