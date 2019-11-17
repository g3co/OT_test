<?php

namespace AppBundle\Service;

use AppBundle\Service\Interfaces\IJokeApi;
use GuzzleHttp\Client;
use Exception;

class JokeApi implements IJokeApi
{
    const API_URL = 'http://api.icndb.com/';
    const SUCCESS_RESPONSE = 'success';

    protected $client;

    /**
     * JokeApi constructor.
     */
    public function __construct()
    {
        $this->client = new Client(['base_uri' => self::API_URL]);
    }

    /**
     * Getting list of categories
     * @return array
     * @throws Exception
     */
    public function getCategories(): array
    {
        $data = $this->client->get('categories');
        $content = $data->getBody()->getContents();
        $response = json_decode($content, true);

        if (empty($response['type']) || $response['type'] !== self::SUCCESS_RESPONSE) {
            throw new Exception('Wrong response from api');
        }

        return $response['value'];
    }

    /**
     * Getting joke from API
     * @param string $category
     * @return string
     * @throws Exception
     */
    public function getJoke(string $category): string
    {
        $data = $this->client->get('jokes/random', [
            'query' => [
                'limitTo' => $category
            ]
        ]);

        $content = $data->getBody()->getContents();
        $response = json_decode($content, true);
        if (empty($response['type']) || $response['type'] !== self::SUCCESS_RESPONSE) {
            throw new Exception('Wrong response from api');
        }

        return $response['value']['joke'];
    }
}
