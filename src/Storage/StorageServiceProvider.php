<?php

namespace Isern\Storage;

use Illuminate\Support\ServiceProvider;

class StorageServiceProvider extends ServiceProvider
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
        $this->registerTag();
    }

    /**
     * Register the tag bind.
     *
     * @return void
     */
    protected function registerCategory()
    {
        $this->app->bind(
            'Isern\Storage\Category\CategoryRepositoryInterface',
            'Isern\Storage\Category\EloquentCategoryRepository'
        );
    }

    /**
     * Register the tag bind.
     *
     * @return void
     */
    protected function registerTag()
    {
        $this->app->bind(
            'Isern\Storage\Tag\TagRepositoryInterface',
            'Isern\Storage\Tag\EloquentTagRepository'
        );
    }

}
