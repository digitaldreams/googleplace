<?php
namespace GooglePlace\Services;

use GooglePlace\Request;

/**
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 * see Docs
 * https://developers.google.com/maps/documentation/timezone/intro
 */
class Timezone extends Request
{
    protected $api_endpoint = 'https://maps.googleapis.com/maps/api/timezone/json';
    protected $validParams = ['location', 'timestamp', 'language'];

    public function __construct($params = [])
    {
        if (!isset($params['timestamp'])) {
            $params['timestamp'] = time();
        }
        parent::__construct($params);
    }

}