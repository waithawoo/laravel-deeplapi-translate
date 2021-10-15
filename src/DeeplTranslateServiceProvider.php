<?php

namespace WaiThaw\DeeplTranslate;
use Illuminate\Support\ServiceProvider;

class DeeplTranslateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/deepltranslate.php', 'deepltranslate');

        $this->app->bind(DeeplTranslate::class, function () {
            return new DeeplTranslate();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/deepltranslate.php' => config_path('deepltranslate.php'),
        ]);
    }
}
