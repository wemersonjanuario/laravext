<?php namespace Inline\Laravext;

use Illuminate\Support\ServiceProvider;

class LaravextServiceProvider extends ServiceProvider
{
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
    public function boot()
    {
        $this->publishes([__DIR__ . '/../../resources/config/laravext.php' => config_path('laravext.php')], 'config');
        $this->publishes([__DIR__ . '/../../resources/views' => base_path('resources/views/vendor/laravext')], 'views');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'laravext');

        $this->publishes([
            __DIR__.'/../../resources/lang' => base_path('resources/lang/vendor/laravext', 'lang'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../resources/config/laravext.php', 'laravext');
                $this->app->bind('laravext', function () {
                    return new Laravext();
                });


    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('laravext');
    }


}