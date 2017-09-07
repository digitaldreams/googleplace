<?php
namespace GooglePlace;

use GuzzleHttp\Client;

/**
 * Class Request
 *
 * Parent of all Serivce Class. Calling to Google API are done here.
 *
 * @package GooglePlace
 */
class Request
{
    /**
     * GOOGLE places api key
     *
     * @var
     */
    public static $api_key;

    /**
     * API endpoint of the service
     * @var
     */
    protected $api_endpoint;

    /**
     * Parameter to be passed
     *
     * @var array
     */
    protected $params;

    /**
     * Response get from google
     *
     * @var Response
     */
    protected $response;

    /**
     * Filter which params are valid
     * @var array
     */
    protected $validParams = [];
    /**
     * @var array
     */
    protected $required_params = [];

    /**
     * Guzzle Http Client to send request to google
     * @var Client
     */
    protected $client;

    /**
     * Request constructor.
     * @param array $params
     */
    public function __construct($params = [])
    {
        if (!empty($params)) {
            $this->params = $this->validateParams($params);
        }
        $this->client = new Client();
    }

    /**
     * @param $params
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $this->validateParams($params);
        return $this;
    }

    /**
     * @param $params
     * @return array
     */
    protected function validateParams($params)
    {
        return !empty($this->validParams) ? array_intersect_key($params, array_flip($this->validParams)) : $params;
    }

    /**
     * Sent request via GET method
     *
     * @return \GooglePlace\Response
     */
    public function get()
    {
        $this->insertApiKey();
        $response = $this->client->get($this->api_endpoint, ['query' => $this->params]);
        return $this->response = new Response($response);
    }

    /**
     * send Request via POST method
     * @return \GooglePlace\Response
     */
    public function post()
    {
        $this->insertApiKey();
        $response = $this->client->get($this->api_endpoint, ['form_params' => $this->params]);
        return $this->response = new Response($response);
    }

    /**
     * @param $method
     * @return \GooglePlace\Response
     */
    public function call($method, $params)
    {
        $this->insertApiKey();
        $response = $this->client->{$method}($this->api_endpoint, $params);
        return $this->response = new Response($response);
    }

    /**
     * Get Response object
     *
     * @return Response
     */
    public function response()
    {
        return $this->response;
    }

    /**
     * Final check before send request that API key is set if not then set it
     *
     * @return bool
     */
    private function insertApiKey()
    {
        if (!isset($this->params['key'])) {
            $this->params['key'] = static::$api_key;
            return true;
        }
        return false;
    }

    /**
     * Get the parameters its currently using
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set a Parameter
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if (array_search($name, $this->validParams) !== false) {
            $this->params[$name] = $value;
        }
    }

    /**
     * Get  a Parameter
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return isset($this->params[$name]) ? $this->params[$name] : false;
    }

    /**
     * Check whether has parameter
     * @param $name
     * @return bool
     */
    public function hasParam($name)
    {
        return isset($this->params[$name]);
    }


}