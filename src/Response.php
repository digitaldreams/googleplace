<?php

namespace GooglePlace;

use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Illuminate\Support\Collection;

/**
 * Class Response
 *
 * All the response from google are handled from this Class.
 *
 * @package GooglePlace
 */
class Response
{
    /**
     * @var GuzzleResponse
     */
    public $response;

    /**
     * Response body. Usually contains data based on params passed
     * @var
     */
    public $body;

    /**
     * Response constructor.
     * @param GuzzleResponse $response
     */
    public function __construct(GuzzleResponse $response)
    {
        $this->response = $response;
        if (is_object($response)) {
            $this->body = json_decode($response->getBody(), true);
        }

    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (is_object($this->response) && method_exists($this->response, $name)) {
            return $this->response->{$name}($arguments);
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (is_array($this->body) && isset($this->body[$name])) {
            return $this->body[$name];
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->response->getStatusCode() === 200;
    }


}