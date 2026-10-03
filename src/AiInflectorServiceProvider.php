<?php

declare(strict_types=1);

namespace AiInflector\AiInflector;

use AiInflector\AiInflector\Console\Commands\AiInflectorCacheClearCommand;
use AiInflector\AiInflector\Console\Commands\AiInflectorCommand;
use Illuminate\Support\ServiceProvider;

class AiInflectorServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ai-inflector.php', 'ai-inflector');

        $this->app->singleton(AiInflector::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/ai-inflector.php' => config_path('ai-inflector.php'),
        ], ['ai-inflector', 'ai-inflector-config']);

        $this->commands([
            AiInflectorCommand::class,
            AiInflectorCacheClearCommand::class,
        ]);
    }
}
