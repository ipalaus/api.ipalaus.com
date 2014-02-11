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
        $this->registerCategory();
        $this->registerPost();
        $this->registerTag();
    }

    /**
     * Register the category service.
     *
     * @return void
     */
    protected function registerCategory()
    {
        $this->app->bindShared('Isern\Services\CategoryService', function($app) {
            $service = $app->build('Isern\Services\CategoryService');
            return $service;
        });
    }

    /**
     * Register the post service.
     *
     * @return void
     */
    protected function registerPost()
    {
        $this->app->bindShared('Isern\Services\PostService', function($app) {
            $service = $app->build('Isern\Services\PostService');
            return $service;
        });
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
