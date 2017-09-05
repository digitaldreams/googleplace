<?php

namespace GooglePlace\Services;

use GooglePlace\Response;
use GooglePlace\Request;

class Nearby extends Request
{
    protected $params = [];
    protected $validParams = [
        'location', 'radius', 'type', 'rankby', 'keyword', 'language', 'minprice', 'maxprice', 'name', 'opennow', 'pagetoken'
    ];
    protected $required_params = ['location', 'radius', 'rankby'];
    protected $api_endpoint = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json';
    protected $defaultRadius = 15000;

    public function __construct(array $params)
    {
        if (!isset($params['radius']) && !isset($params['rankby'])) {
            $params['radius'] = $this->defaultRadius;
        }
        parent::__construct($params);

    }


}