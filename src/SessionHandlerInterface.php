<?php
namespace Anthonypauwels\DataLayer;

/**
 * Interface for Session
 *
 * @package Anthonypauwels\DataLayer
 * @author Anthony Pauwels <hello@anthonypauwels.be>
 */
interface SessionHandlerInterface
{
    /**
     * Put a value into session global var
     *
     * @param array $value
     */
    public function put(array $value);

    /**
     * Get a value from session global var or return a default value if it do not exists
     *
     * @param array $default
     * @return array
     */
    public function get(array $default = []): array;
}