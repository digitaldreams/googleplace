<?php

namespace GooglePlace\Services;

use GooglePlace\Helpers\PlaceRequest;
use GooglePlace\Response;
use GooglePlace\Request;

/**
 * Class Nearby
 * @package GooglePlace\Services
 *
 * Search Places near to a specific location. For example Bank in New York
 *
 * see Docs
 * https://developers.google.com/places/web-service/search
 */
class Nearby extends Request
{
    use PlaceRequest;

    /**
     * @var array
     */
    protected $validParams = [
        'location', 'radius', 'type', 'rankby', 'keyword', 'language', 'minprice', 'maxprice', 'name', 'opennow', 'pagetoken'
    ];
    
    /**
     * @var string
     */
    protected $api_endpoint = 'place/nearbysearch/json';

    /**
     * Default Radius if rankby is not specified. 15km
     * @var int
     */
    public static $defaultRadius = 15000;

    /**
     * Nearby constructor.
     * @param array $params
     */
    public function __construct(array $params)
    {
        if (!isset($params['radius']) && !isset($params['rankby'])) {
            $params['radius'] = static::$defaultRadius;
        }
        parent::__construct($params);
    }
}
