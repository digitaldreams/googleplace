<?php
namespace GooglePlace\Helpers;
/**
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class PlacePhoto
{
    public static $storagePath;
    public static $thumbPath;
    protected $attributes;

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

    public function save()
    {

    }

    public function url()
    {

    }

}