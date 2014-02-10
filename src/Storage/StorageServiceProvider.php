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
        $this->registerTags();
    }

    /**
     * Register the tags bind.
     *
     * @return void
     */
    protected function registerTags()
    {
        // $this->app->bind(
        //     'Isern\Storage\Tag\TagRepositoryInterface',
        //     'Isern\Storage\Tag\EloquentTagRepository'
        // );
    }

}
