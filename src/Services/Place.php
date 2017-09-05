<?php
namespace GooglePlace\Services;

use GooglePlace\Helpers\PlacePhoto;
use GooglePlace\Request;
use Illuminate\Database\Eloquent\Collection;

/**
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class Place extends Request
{
    /**
     * Current or Center place
     * @var
     */
    public static $centerPlace;
    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var
     */
    protected $details;

    /**
     * @var string
     */
    protected $api_endpoint = 'https://maps.googleapis.com/maps/api/place/details/json';

    public function __construct($place)
    {
        $this->attributes = $place;
        parent::__construct(['placeid' => $this->attributes['place_id']]);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (is_array($this->attributes) && isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }
        return false;
    }

    /**
     * @return \GooglePlace\Response
     */
    public function getDetails()
    {
        print_r($this->getParams());
        $response = $this->get();
        $attributes = $response->result;
        if (!empty($attributes)) {
            $this->attributes = $attributes;
        }
        return $response;
    }

    /**
     * Get the lat lng of the place
     * @return array
     */
    public function latLng()
    {
        if ($this->attributes['geometry']['location']) {
            return $this->attributes['geometry']['location'];
        }
        return [];
    }

    /**
     * Lat Lng String
     *
     * @return bool|string
     */
    public function latLngStr()
    {
        $addr = false;
        $latlng = $this->latLng();
        if (isset($latlng['lat']) && isset($latlng['lng'])) {
            $addr = $latlng['lat'] . "," . $latlng['lng'];
        }
        return $addr;
    }

    /**
     * @return bool|mixed
     */
    public function address()
    {
        if (isset($this->attributes['formatted_address'])) {
            return $this->attributes['formatted_address'];
        } elseif (isset($this->attributes['vicinity'])) {
            return $this->attributes['vicinity'];
        }
        return false;
    }

    /**
     * Get Phone Number
     */
    public function phone()
    {
        return isset($this->attributes['international_phone_number']) ? $this->attributes['international_phone_number'] : false;
    }

    /**
     * @return bool|null
     */
    public function openNow()
    {
        return isset($this->attributes['opening_hours']['open_now']) ? $this->attributes['opening_hours']['open_now'] : null;
    }

    /**
     * @return Collection
     */
    public function photos()
    {
        $retArr = [];
        if (isset($this->attributes['photos']) && is_array($this->attributes['photos'])) {
            foreach ($this->attributes['photos'] as $photo) {
                $retArr[] = new PlacePhoto($photo);
            }
        }
        return new Collection($retArr);

    }

    /**
     * @return Collection
     */
    public function reviews()
    {
        $retArr = isset($this->attributes['reviews']) ? isset($this->attributes['reviews']) : [];
        return new Collection($retArr);
    }

    /**
     * @param string $place
     * @return Collection
     * @throws \Exception
     */
    public function distance($place = '')
    {
        $placeStr = '';
        if ($place instanceof Place) {
            $placeStr = $place->latLngStr();
        } elseif (!empty(static::$centerPlace)) {
            if (static::$centerPlace instanceof Place) {
                $placeStr = static::$centerPlace->latLngStr();
            } elseif (is_array(static::$centerPlace) && isset(static::$centerPlace['lat']) && static::$centerPlace['lng']) {
                $placeStr = static::$centerPlace['lat'] . ',' . static::$centerPlace['lng'];
            } else {
                $placeStr = static::$centerPlace;
            }
        } else {
            throw new \Exception('Either $place params are required or set static::$centerPlace in Global scope');
        }
        $distanceMatrix = new DistanceMatrix(['origins' => $placeStr, 'destinations' => $this->latLngStr()]);
        return $distanceMatrix->calculate();
    }

}