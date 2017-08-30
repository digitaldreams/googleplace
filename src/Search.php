<?php
namespace GooglePlace;

use GooglePlace\Helpers\Client\Client;

/**
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class Search
{
    /**
     * @var string
     */
    protected $apiKey = '';

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var array
     */
    protected $response = [];

    /**
     * @var array
     */
    protected $errors = [];

    protected $client;

    public function __construct(array $params)
    {
        $this->params = $this->validateParams($params);
        $this->client = new Client();
    }

    /**
     *
     */
    public function radar()
    {

    }

    /**
     *
     */
    public function text()
    {

    }

    public function validateParams($params)
    {
        //do some filtering so only valid params are passed to api call
        return $params;
    }

    /**
     *
     * @return array
     */
    public function response()
    {
        return $this->response;
    }

    /**
     *
     */
    public function collection()
    {

    }

}