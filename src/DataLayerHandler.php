<?php
namespace Anthonypauwels\DataLayer;

/**
 * Helper class for handling DataLayer object with Google Tag Manager
 *
 * @package Anthonypauwels\DataLayer
 * @author Anthony Pauwels <hello@anthonypauwels.be>
 */
class DataLayerHandler
{
    /** @var array */
    protected array $data = [];

    /** @var SessionHandlerInterface */
    protected SessionHandlerInterface $session;

    /** @var string */
    protected string $gtm_id;

    /** @var string */
    const SESSION_KEY = 'datalayer';

    /**
     * DataLayer constructor.
     *
     * @param SessionHandlerInterface $session
     * @param string $gtm_id
     */
    public function __construct(SessionHandlerInterface $session, string $gtm_id)
    {
        $this->session = $session;
        $this->google_id = $gtm_id;

        $this->load();
    }

    /**
     * Load data from the session
     */
    public function load(): void
    {
        $this->data = array_merge( $this->session->get(), [] );
    }

    /**
     * Clear all data from the array
     */
    public function clear(): void
    {
        $this->session->put( [] );
    }

    /**
     * Save the data into the session
     */
    public function save(): void
    {
        $this->session->put( $this->data );
    }

    /**
     * Get the data array
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Push a value with name into the data array
     *
     * @param array|string $name
     * @param array|string|null $value
     * @param array $options
     * @example
     * DataLayer::push( 'name', 'value' );
     * DataLayer::push( [ 'name' => 'value' ] );
     *
     */
    public function push(array|string $name, array|string $value = null, array $options = ['echo' => false, 'session' => false]): void
    {
        if ( is_array( $name ) ) {
            $options = $value;

            $this->pushArray( $name, $options );

            return;
        }

        if ( $options[ 'echo' ] ) {
            $this->pushData( [ $name => $value ] );

            return;
        }

        $this->data[ $name ] = $value;

        if ( $options[ 'session' ] ) {
            $this->save();
        }
    }

    /**
     * Push a full array into the data
     *
     * @param array $data
     * @param array $options
     */
    public function pushArray(array $data, array $options = ['echo' => false, 'session' => false]): void
    {
        if ( $options[ 'echo' ] ) {
            $this->pushData( $data );

            return;
        }

        $this->data = array_merge( $this->data, $data );

        if ( $options[ 'session' ] ) {
            $this->save();
        }
    }

    /**
     * Print the datalayer object into the page; options can be used to control the init and the script
     *
     * @param array $options
     */
    public function publish(array $options = ['init' => true, 'script' => true, 'clear' => true]): void
    {
        if ( isset( $options[ 'init' ] ) && $options[ 'init' ] === true ) {
            $this->init();
        }

        if ( !empty( $this->data ) ) {
            $this->pushData( $this->data, isset( $options[ 'clear' ] ) && $options[ 'clear' ] === true );
        }

        if ( isset( $options[ 'script' ] ) && $options[ 'script' ] === true ) {
            $this->script( $this->google_id );
        }
    }

    /**
     * Clear all data from the array at the end of the script
     */
    public function __destruct()
    {
        $this->data = [];
    }

    /**
     * Init the JS DataLayer object
     */
    public function init():void
    {
        ?><script>window.dataLayer = window.dataLayer || [];</script><?php
    }

    /**
     * Print the Google tag manager init script with given Google id
     *
     * @param string|null $gtm_id
     */
    public function script(?string $gtm_id = null):void
    {
        $gtm_id = !$gtm_id ?: $this->google_id;

        ?><!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!=='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','<?= $gtm_id ?>');</script>
        <!-- End Google Tag Manager --><?php
    }

    /**
     * Push the data into the JS DataLayer object
     *
     * @param array $data
     * @param bool $clear
     */
    public function pushData(array $data, bool $clear = false):void
    {
        ?><script>
            window.dataLayer.push(<?= json_encode( $data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT ); ?>);
        </script><?php

        if ( $clear ) {
            $this->clear();
        }
    }

    /**
     * Print the Google tag manager no-script tag with given google id
     *
     * @param string|null $gtm_id
     * @return void
     */
    public function noScript(?string $gtm_id = null):void
    {
        $gtm_id = !$gtm_id ?: $this->google_id;

        ?><!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= $gtm_id; ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) --><?php
    }

    /**
     * Dump & Die the data; debug purpose
     */
    public function dd(): void
    {
        if ( function_exists( 'dd' ) ) {
            dd( $this->data );
        }

        if ( class_exists('Kint') ) {
            Kint::dump( $this->data );
            exit;
        }

        echo '<pre>';
        var_dump( $this->data );
        echo '</pre>';
        exit;
    }
}
