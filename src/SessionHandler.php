<?php
namespace Anthonypauwels\DataLayer;

/**
 * Default Session Handler
 *
 * @package Anthonypauwels\DataLayer
 * @author Anthony Pauwels <hello@anthonypauwels.be>
 */
class SessionHandler implements SessionHandlerInterface
{
    /**
     * Put a value into session global var
     *
     * @param array $value
     */
    public function put(array $value): void
    {
        $_SESSION[ DataLayerHandler::SESSION_KEY ] = $value;
    }

    /**
     * Get a value from session global var or return a default value if it do not exists
     *
     * @param array $default
     * @return array
     */
    public function get(array $default = []): array
    {
        if ( isset( $_SESSION[ DataLayerHandler::SESSION_KEY ] ) ) {
            return $_SESSION[ DataLayerHandler::SESSION_KEY ];
        }

        return $default;
    }
}