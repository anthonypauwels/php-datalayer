<?php
namespace Anthonypauwels\DataLayer\Laravel;

use Illuminate\Session\SessionManager;
use Anthonypauwels\DataLayer\DataLayerHandler;
use Anthonypauwels\DataLayer\SessionHandlerInterface;

/**
 * Session Handler to wrap Laravel SessionManager
 *
 * @package Anthonypauwels\DataLayer
 * @author Anthony Pauwels <hello@anthonypauwels.be>
 */
class SessionHandler implements SessionHandlerInterface
{
    /** @var SessionManager */
    protected SessionManager $session;

    /**
     * SessionHandler constructor
     *
     * @param SessionManager $session
     */
    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    /**
     * Put a value into laravel session
     *
     * @param array $value
     */
    public function put(array $value):void
    {
        $this->session->put( DataLayerHandler::SESSION_KEY, $value );
    }

    /**
     * Get a value from laravel session or return a default value if it do not exists
     *
     * @param array $default
     * @return mixed
     */
    public function get(array $default = []):array
    {
        return $this->session->get( DataLayerHandler::SESSION_KEY, $default );
    }
}
