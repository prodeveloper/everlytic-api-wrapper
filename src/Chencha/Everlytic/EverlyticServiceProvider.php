<?php

namespace Chencha\Everlytic;

use Illuminate\Support\ServiceProvider;

class EverlyticServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {
        $this->package('chencha/everlytic');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        \App::bind('everlytic', function() {
            return new \Chencha\Everlytic\Everlytic;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return array();
    }

}
