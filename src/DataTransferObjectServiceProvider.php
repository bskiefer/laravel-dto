<?php

namespace bkief29\DTO;

use bkief29\DTO\Facades\DTO;
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
     * Bootstrap any package services.
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/dto.php' => config_path('dto.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->alias(DataTransferObject::class, 'dto');
        $this->app->bind(DTO::class, function ($t, $args) {
            return new class($args) extends DataTransferObject {
            };
        });
        $this->app->alias(DTO::class, 'dto');

        $this->mergeConfigFrom(
            __DIR__ . '/../config/dto.php',
            'dto'
        );
    }
}
