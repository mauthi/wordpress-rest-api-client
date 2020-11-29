<?php

namespace Vnn\WpApiClient;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->load()
            ->publish();
    }

    private function load()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/wordpress.php', 'wordpress');

        return $this;
    }

    private function publish()
    {
        $this->publishes([
            __DIR__.'/../config/wordpress.php' => config_path('wordpress.php'),
        ], 'config');
    }
}
