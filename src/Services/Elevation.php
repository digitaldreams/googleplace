<?php
namespace GooglePlace\Services;

use GooglePlace\Request;

/**
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 * see Docs
 * https://developers.google.com/maps/documentation/elevation/start
 */
class Elevation extends Request
{
    protected $api_endpoint = 'https://maps.googleapis.com/maps/api/elevation/json';
    protected $validParams = ['locations', 'path', 'samples'];
    public function __construct(array $params)
    {
        parent::__construct($params);
    }
}