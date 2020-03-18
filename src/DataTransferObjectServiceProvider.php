<?php

namespace bkief29\DTO;

use Illuminate\Support\ServiceProvider;

class DataTransferObjectServiceProvider extends ServiceProvider
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
        $this->app->alias(DataTransferObject::class, 'dto');
    }

}