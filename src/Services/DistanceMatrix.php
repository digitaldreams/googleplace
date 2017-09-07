<?php
namespace GooglePlace\Services;

use GooglePlace\Helpers\DistanceMatrixElement;
use GooglePlace\Request;
use Illuminate\Support\Collection;

/**
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 *
 * Calculate distance in km and duration in min between two places
 *
 * See docs
 * https://developers.google.com/maps/documentation/distance-matrix/start
 */
class DistanceMatrix extends Request
{
    /**
     * API Endpoint
     *
     * @var string
     */
    protected $api_endpoint = 'https://maps.googleapis.com/maps/api/distancematrix/json';

    /**
     * See Documentation
     * https://developers.google.com/maps/documentation/distance-matrix/intro#DistanceMatrixRequests
     * @var array
     */
    protected $validParams = ['origins', 'destinations', 'mode', 'language', 'region', 'avoid', 'units', 'arrival_time', 'departure_time', 'traffic_model', 'transit_mode'];

    /**
     * DistanceMatrix constructor.
     * @param array $params
     */
    public function __construct($params = [])
    {
        if (isset($params['origins']) && is_array($params['origins'])) {
            $params['origins'] = implode("|", $params['origins']);
        }
        if (isset($params['destinations']) && is_array($params['destinations'])) {
            $params['destinations'] = implode("|", $params['destinations']);
        }
        parent::__construct($params);
    }

    /**
     * Fetch and process response so it can be easily understand.
     * 
     * @return Collection
     */
    public function calculate()
    {
        $retArr = [];
        $response = $this->get();
        $response->body;
        if ($response->status == 'OK') {
            $origins = $response->origin_addresses;
            $destinations = $response->destination_addresses;
            foreach ($response->rows as $originIndex => $row) {
                $origin = $origins[$originIndex];

                foreach ($row['elements'] as $destinationIndex => $element) {
                    $destination = $destinations[$destinationIndex];
                    $retArr[] = new DistanceMatrixElement($element, $origin, $destination);
                }
            }
        }
        return new Collection($retArr);
    }

}