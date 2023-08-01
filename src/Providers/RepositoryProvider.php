<?php

namespace Matmper\Providers;

use Illuminate\Support\ServiceProvider;
use Matmper\Repositories\BaseRepository;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/repository.php' => config_path('repository.php'),
            ]);

            $this->commands([
                \Matmper\Commands\CreateRepository::class,
            ]);
        }
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(BaseRepository::class);
    }
}
