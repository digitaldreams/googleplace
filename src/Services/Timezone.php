<?php
namespace GooglePlace\Services;

use GooglePlace\Request;

/**
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 *
 * Get timezone of a place by lat lng
 *
 * see Docs
 * https://developers.google.com/maps/documentation/timezone/intro
 */
class Timezone extends Request
{
    /**
     * API endpoint
     *
     * @var string
     */
    protected $api_endpoint = 'https://maps.googleapis.com/maps/api/timezone/json';

    /**
     * @var array
     */
    protected $validParams = ['location', 'timestamp', 'language'];

    /**
     * Timezone constructor.
     * @param array $params
     */
    public function __construct($params = [])
    {
        if (!isset($params['timestamp'])) {
            $params['timestamp'] = time();
        }
        parent::__construct($params);
    }

}