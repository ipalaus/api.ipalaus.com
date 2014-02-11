<?php

namespace Isern\Services;

use Illuminate\Support\ServiceProvider;

class ServicesServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTag();
    }

    /**
     * Register the tag service.
     *
     * @return void
     */
    protected function registerTag()
    {
        $this->app->bindShared('Isern\Services\TagService', function($app) {
            $service = $app->build('Isern\Services\TagService');
            return $service;
        });
    }

}
