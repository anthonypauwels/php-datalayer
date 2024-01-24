<?php
namespace Anthonypauwels\DataLayer\Laravel;

use Illuminate\Support\Facades\Blade;
use Anthonypauwels\DataLayer\DataLayerHandler;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * ServiceProvider.
 * Register the DataLayer helper class as a singleton into Laravel
 *
 * @package Anthonypauwels\DataLayer
 * @author Anthony Pauwels <hello@anthonypauwels.be>
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register the DataLayer
     */
    public function register():void
    {
        $this->app->singleton('datalayer', function ( $app ) {
            return new DataLayerHandler( new SessionHandler( $app['session'] ), config('datalayer.gtm_id') );
        } );

        Blade::directive('datalayerInit', function () {
            DataLayer::init();
        } );

        Blade::directive('datalayerScript', function (?string $gtm_id = null) {
            DataLayer::script( $gtm_id );
        } );

        Blade::directive('datalayerPush', function (array $data, bool $clear = false) {
            DataLayer::pushData( $data, $clear );
        } );

        Blade::directive('datalayerPublish', function (array $options = ['init' => true, 'script' => true, 'reset' => true]) {
            DataLayer::publish( $options );
        } );

        Blade::directive('datalayerNoScript', function (?string $gtm_id = null) {
            DataLayer::noScript( $gtm_id );
        } );
    }

    /**
     * Bootstrap
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/config.php' => config_path('datalayer.php'),
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides():array
    {
        return [ DataLayerHandler::class ];
    }
}
