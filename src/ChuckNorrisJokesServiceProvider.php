<?php

namespace Ttnppedr\ChuckNorrisJokes;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Ttnppedr\ChuckNorrisJokes\Console\ChuckNorrisJoke;
use Ttnppedr\ChuckNorrisJokes\Http\Controllers\ChuckNorrisController;

class ChuckNorrisJokesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ChuckNorrisJoke::class,
            ]);
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'chuck-norris');

        $this->publishes([
           __DIR__ . '/../resources/views' => resource_path('views/vendor/chuck-norris')
        ], 'view');

        $this->publishes([
            __DIR__ . '/../config/chuck-norris.php' => base_path('config/chuck-norris.php')
        ], 'config');

        if (! class_exists('CreateJokesTable')) {
            $this->publishes([
                __DIR__ . '/../databases/migrations/create_jokes_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_jokes_table.php',)
            ], 'migrations');
        }

        Route::get(config('chuck-norris.route'), ChuckNorrisController::class);
    }

    public function register()
    {
        $this->app->bind('chuck-norris', function() {
            return new JokeFactory();
        });

        $this->mergeConfigFrom(__DIR__ . '/../config/chuck-norris.php', 'chuck-norris');
    }
}
