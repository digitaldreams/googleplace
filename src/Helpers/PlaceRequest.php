<?php
namespace GooglePlace\Helpers;

use GooglePlace\Services\Place;
use Illuminate\Database\Eloquent\Collection;

trait PlaceRequest
{
    /**
     * @var String
     */
    protected $nextPageToken;

    public function places()
    {
        if (empty($this->response)) {
            $this->get();
        }
        $retArr = [];
        $results = $this->response->results;
        if (is_array($results)) {
            foreach ($results as $place) {
                $retArr[] = new Place($place);
            }
        }
        return new Collection($retArr);
    }

    public function hasNextPage()
    {
        $token = $this->getNextPageToken();
        return !empty($token);
    }

    /**
     * @return mixed
     */
    public function nextPage()
    {
        if ($this->hasNextPage()) {
            $this->pagetoken = $this->nextPageToken;
            sleep(1);
            return $this->get();
        }
    }

    /**
     * @return string
     */
    public function getNextPageToken()
    {
        return $this->nextPageToken = trim($this->response->next_page_token);
    }


}