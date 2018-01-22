<?php
namespace GooglePlace\Services;

namespace GooglePlace\Services;

use Illuminate\Support\Collection;
use GooglePlace\Request;

/**
 * Class Geocoding
 * @package GooglePlace\Services
 *
 * Convert String address to Place Object and lat lng string as well
 *
 * See docs
 * https://developers.google.com/maps/documentation/geocoding/intro#ReverseGeocoding
 */
class Geocoding extends Request
{
    /*
     * API endpoint
     */
    protected $api_endpoint = 'geocode/json';

    /**
     * @var array
     */
    protected $validParams = ['address', 'latlng', 'language', 'result_type', 'location_type', 'region'];

    /**
     * Geocoding constructor.
     * @param array $params
     */
    public function __construct(array $params)
    {
        if (isset($params['latlng']) && is_array($params['latlng'])) {
            $params['latlng'] = implode(",", $params['latlng']);
        }
        parent::__construct($params);
    }

    /**
     * Fetch and process result
     * @return Collection
     */
    public function places()
    {
        if (empty($this->response)) {
            $this->get();
        }
        $retArr = [];
        $results = $this->response->results;
        if (is_array($results)) {
            foreach ($results as $place) {
                $retArr[] = new Place($place);
            }
        }
        return new Collection($retArr);
    }
}