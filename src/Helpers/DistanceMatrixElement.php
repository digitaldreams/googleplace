<?php

namespace GooglePlace\Helpers;

/**
 * Class DistanceMatrixElement
 *
 * This is a helper class for DistanceMatrix which fetch distance related data and return this class as a single element.
 *
 * @package GooglePlace\Helpers
 * See docs
 * https://developers.google.com/maps/documentation/distance-matrix/start
 */
class DistanceMatrixElement
{
    /**
     * @var string Starting address of the location
     */
    protected $origin;

    /**
     * @var string End address of the location
     */
    protected $destination;

    /**
     * Contains single element that is distance between origin x1 to origin y1
     *
     * @var array
     */
    protected $element;

    /**
     * DistanceMatrixElement constructor.
     * @param $element
     * @param string $origin
     * @param string $destination
     */
    public function __construct($element, $origin = '', $destination = '')
    {
        $this->element = $element;
        $this->origin = $origin;
        $this->destination = $destination;
    }

    /**
     * From address or Starting point
     * @return string
     */
    public function origin()
    {
        return $this->origin;
    }

    /**
     * End address or destination
     * @return string
     */
    public function destination()
    {
        return $this->destination;
    }

    /**
     * User can get any property which is a index of element. Duration and Distance
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