<?php

namespace Tests\AppBundle\Service;

use AppBundle\Service\JokeApi;
use PHPUnit\Framework\TestCase;

class JokeApiTest extends TestCase
{
    public function testGetCategories()
    {
        $api = new JokeApi();
        $categories = $api->getCategories();

        $this->assertEquals(true, !empty($categories));
    }

    public function testGetJoke()
    {
        $api = new JokeApi();
        $categories = $api->getCategories();
        $joke = $api->getJoke($categories[0]);

        $this->assertNotEquals('', $joke);
    }
}
