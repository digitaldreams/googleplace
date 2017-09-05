<?php
namespace GooglePlace\Helpers;

use GooglePlace\Request;

/**
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 * see Docs
 * https://developers.google.com/places/web-service/photos
 */
class PlacePhoto
{
    public static $storagePath;
    public static $thumbPath;
    protected $attributes;
    public static $maxWidth;

    protected $api_endpoint = 'https://maps.googleapis.com/maps/api/place/photo';

    public function __construct($attributes)
    {
        $this->attributes = $attributes;
    }

    public function __get($name)
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }
        return false;
    }

    public function save($storagePath = '', $maxWidth = '')
    {
        $pathToSave = '';
        if (!empty($storagePath)) {
            $pathToSave = $storagePath;
        } elseif (!empty(static::$storagePath)) {
            $pathToSave = static::$storagePath;
        } else {
            throw new \Exception('Storage path must be defined');
        }
        $fileName = trim($pathToSave, "/") . '/' . uniqid() . '.jpg';

        $ch = curl_init($this->url($maxWidth));
        $fp = fopen($fileName, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

    }

    /**
     * Get Google Image URL
     * @param string $maxWidth
     * @return string
     */
    public function url($maxWidth = '')
    {
        $params = [
            'photoreference' => $this->attributes['photo_reference'],
            'key' => Request::$api_key
        ];
        if (!empty($maxWidth)) {
            $params['maxwidth'] = $maxWidth;
        } elseif (!empty(static::$maxWidth)) {
            $params['maxwidth'] = $maxWidth;
        } else {
            $params['maxwidth'] = isset($this->attributes['width']) ? $this->attributes['width'] : 400;
        }

        return $this->api_endpoint . '?' . http_build_query($params);
    }

}