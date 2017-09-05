<?php

namespace GooglePlace\Helpers;


class DistanceMatrixElement
{
    protected $origin;
    protected $destination;
    protected $element;

    public function __construct($element, $origin = '', $destination = '')
    {
        $this->element = $element;
        $this->origin = $origin;
        $this->destination = $destination;
    }

    /**
     * @return string
     */
    public function origin()
    {
        return $this->origin;
    }

    /**
     * @return string
     */
    public function destination()
    {
        return $this->destination;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->element[$name])) {
            return $this->element[$name];
        }
        return false;
    }


}