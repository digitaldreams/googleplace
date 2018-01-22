<?php
namespace GooglePlace\Services;

use GooglePlace\Helpers\PlacePhoto;
use GooglePlace\Request;
use Illuminate\Support\Collection;

/**
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 *
 * Most frequently used Class. A single Place which contains details about that place like phone,address, photos and more
 * 
 * See docs
 * https://developers.google.com/places/web-service/details
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

    /**
     * Place constructor.
     * @param array $place
     */

    public function __construct($place = [])
    {
        $this->attributes = $place;
        $params = [];
        if (isset($place['placeid'])) {
            $params['placeid'] = $place['placeid'];
        } elseif (isset($place['place_id'])) {
            $params['placeid'] = $place['place_id'];
        }
        parent::__construct($params);
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
     * Override Parent get method so we can assign attributes from response
     *
     * @return \GooglePlace\Response
     */
    public function get()
    {
        $response = parent::get();
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
     * @return mixed
     */
    public function streetNumber()
    {
        return $this->findAddressType('street_number');
    }

    /**
     * @return mixed
     */
    public function route()
    {
        return $this->findAddressType('route');
    }

    /**
     * @return mixed
     */
    public function locality()
    {
        return $this->findAddressType('locality');

    }

    /**
     * @return mixed
     */
    public function state()
    {
        return $this->findAddressType('administrative_area_level_1');
    }

    /**
     * @return mixed
     */
    public function country()
    {
        return $this->findAddressType('country',false);
    }

    /**
     * @return mixed
     */
    public function postalCode()
    {
        return $this->findAddressType('postal_code');
    }

    /**
     * @return mixed
     */
    public function phone()
    {
        return isset($this->attributes['international_phone_number']) ? $this->attributes['international_phone_number'] : null;
    }

    /**
     * @return mixed
     */
    public function openingHours()
    {
        return isset($this->attributes['opening_hours']['weekday_text']) ? $this->attributes['opening_hours']['weekday_text'] : [];
    }

    /**
     * @return mixed
     */
    public function url()
    {
        return isset($this->attributes['url']) ? $this->attributes['url'] : false;
    }

    /**
     * @return mixed
     */
    public function website()
    {
        return isset($this->attributes['website']) ? $this->attributes['website'] : false;
    }

    /**
     * @return mixed
     */
    public function utcOffset()
    {
        return isset($this->attributes['utc_offset']) ? $this->attributes['utc_offset'] : false;
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
     * @param $type
     * @param bool $shortName
     * @return bool
     */
    protected function findAddressType($type, $shortName = true)
    {
        $addressComponents = isset($this->attributes['address_components']) ? $this->attributes['address_components'] : [];
        foreach ($addressComponents as $address) {
            if (in_array($type, $address['types'])) {
                return $shortName === true ? $address['short_name'] : $address['long_name'];
            }
        }
        return null;
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
        $result = $distanceMatrix->calculate();
        return $result->first();
    }

    /**
     * Get timezone of a place
     *
     * @param bool $object
     * @return \GooglePlace\Response
     */
    public function timezone($object = false)
    {
        $timezone = new Timezone(['location' => $this->latLngStr()]);
        $response = $timezone->get();
        return !empty($object) ? $response : $response->timeZoneId;
    }

    /**
     * Return Elevation in Meter
     *
     * Elevation is height of a place from sea level
     *
     * @param bool $object
     * @return bool|\GooglePlace\Response
     */
    public function elevation($object = false)
    {
        $elevation = new Elevation(['locations' => $this->latLngStr()]);
        $response = $elevation->get();
        $results = $response->results;
        $firstResult = isset($results[0]) ? $results[0] : [];
        $elevationStr = isset($firstResult['elevation']) ? round($firstResult['elevation'], 2) : false;
        return !empty($object) ? $response : $elevationStr;
    }

}