<?php
namespace GooglePlace;

use GuzzleHttp\Client;

class Request
{
    public static $api_key;

    /**
     * @var
     */
    protected $api_endpoint;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var array
     */
    protected $validParams = [];
    /**
     * @var array
     */
    protected $required_params = [];

    protected $client;

    /**
     * Request constructor.
     * @param string $api_endpoint
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
     * @return \GooglePlace\Response
     */
    public function get()
    {
        $this->insertApiKey();

        $response = $this->client->get($this->api_endpoint, ['query' => $this->params]);
        return $this->response = new Response($response);
    }

    /**
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
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}