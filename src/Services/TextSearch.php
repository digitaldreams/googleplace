<?php

namespace GooglePlace\Services;

use GooglePlace\Helpers\PlaceRequest;
use GooglePlace\Request;

/**
 * Class TextSearch
 * @package GooglePlace\Services
 * see Docs
 * https://developers.google.com/places/web-service/search
 */
class TextSearch extends Request
{
    use PlaceRequest;

    /**
     * @var array
     */
    protected $validParams = [
        'query', 'location', 'radius', 'type', 'minprice', 'maxprice', 'opennow', 'pagetoken'
    ];

    /**
     * @var string
     */
    protected $api_endpoint = 'https://maps.googleapis.com/maps/api/place/textsearch/json';

    /**
     * Default Radius if rankby is not specified. 15km
     * @var int
     */
    public static $defaultRadius = 15000;

    public function __construct(array $params)
    {
        if (!isset($params['radius']) && isset($params['location'])) {
            $params['radius'] = static::$defaultRadius;
        }
        parent::__construct($params);
    }
}