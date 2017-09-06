<?php
namespace GooglePlace\Services;

namespace GooglePlace\Services;

use Illuminate\Database\Eloquent\Collection;
use GooglePlace\Request;

/**
 * Class Geocoding
 * @package GooglePlace\Services
 * See docs
 * https://developers.google.com/maps/documentation/geocoding/intro#ReverseGeocoding
 */
class Geocoding extends Request
{
    protected $api_endpoint = 'https://maps.googleapis.com/maps/api/geocode/json';
    protected $validParams = ['address', 'latlng', 'language', 'result_type', 'location_type', 'region'];

    public function __construct(array $params)
    {
        if (isset($params['latlng']) && is_array($params['latlng'])) {
            $params['latlng'] = implode(",", $params['latlng']);
        }
        parent::__construct($params);
    }

    /**
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