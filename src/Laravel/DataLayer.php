<?php
namespace Anthonypauwels\DataLayer\Laravel;

use Illuminate\Support\Facades\Facade;
use Anthonypauwels\DataLayer\DataLayerHandler;

/**
 * Facade.
 * Provide quick access methods to the DataLayer helper class
 *
 * @method static DataLayerHandler load()
 * @method static DataLayerHandler clear()
 * @method static DataLayerHandler save()
 * @method static DataLayerHandler getData()
 * @method static DataLayerHandler push($name, $value = null, $options = ['echo' => false, 'session' => false])
 * @method static DataLayerHandler pushArray($data, $options = ['echo' => false, 'session' => false])
 * @method static DataLayerHandler publish($options = ['init' => true, 'script' => true])
 * @method static DataLayerHandler init()
 * @method static DataLayerHandler script($google_id = null)
 * @method static DataLayerHandler scriptPush(array $data)
 * @method static DataLayerHandler noScript($google_id = null)
 * @method static DataLayerHandler dd()
 *
 * @package Anthonypauwels\DataLayer
 * @author Anthony Pauwels <hello@anthonypauwels.be>
 */
class DataLayer extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'datalayer';
    }
}
