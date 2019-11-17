<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Service\JokeApi;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testEmptyFields()
    {
        $client = static::createClient();
        $formHandleUrl = $client->getContainer()->get('router')->generate('form_handler');

        $client->request('POST', $formHandleUrl);
        $this->assertTrue($client->getResponse()->isRedirect());
        $this->assertEquals(301, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Error: empty email', $crawler->filter('.flash-notice')->text());
    }

    public function testEmptyEmail()
    {
        $client = static::createClient();
        $formHandleUrl = $client->getContainer()->get('router')->generate('form_handler');
        $client->request('POST', $formHandleUrl, ['category' => 'test']);
        $crawler = $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Error: empty email', $crawler->filter('.flash-notice')->text());
    }

    public function testEmptyCategory()
    {
        $client = static::createClient();
        $formHandleUrl = $client->getContainer()->get('router')->generate('form_handler');
        $client->request('POST', $formHandleUrl, ['email' => 'example@google.com']);
        $crawler = $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Error: empty category', $crawler->filter('.flash-notice')->text());
    }


    public function testSuccess()
    {
        $client = static::createClient();
        $formHandleUrl = $client->getContainer()->get('router')->generate('form_handler');

        /** @var JokeApi $api */
        $api = $client->getContainer()->get('AppBundle\Service\JokeApi');
        $category = $api->getCategories();

        $client->request('POST', $formHandleUrl, [
            'email' => 'example@google.com',
            'category' => $category[0]
        ]);

        $crawler = $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Joke has sent to example@google.com', $crawler->filter('.flash-notice')->text());
    }
}
