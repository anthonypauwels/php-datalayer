<?php
namespace Anthonypauwels\DataLayer\Laravel;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Anthonypauwels\DataLayer\DataLayerHandler;

/**
 * ServiceProvider.
 * Register the DataLayer helper class as a singleton into Laravel
 *
 * @package Anthonypauwels\DataLayer
 * @author Anthony Pauwels <hello@anthonypauwels.be>
 */
class DataLayerProvider extends ServiceProvider
{
    /**
     * Register the DataLayer
     */
    public function register():void
    {
        $this->app->singleton('datalayer', function ( $app ) {
            return new DataLayerHandler( new SessionHandler( $app['session'] ), env('GOOGLE_ID') );
        } );

        Blade::directive('datalayerInit', function () {
            DataLayer::init();
        } );

        Blade::directive('datalayerScript', function (?string $google_id = null) {
            DataLayer::script( $google_id );
        } );

        Blade::directive('datalayerPush', function (array $data, bool $clear = false) {
            DataLayer::pushData( $data, $clear );
        } );

        Blade::directive('datalayerPublish', function (array $options = ['init' => true, 'script' => true, 'reset' => true]) {
            DataLayer::publish( $options );
        } );

        Blade::directive('datalayerNoScript', function (?string $google_id = null) {
            DataLayer::noScript( $google_id );
        } );
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
