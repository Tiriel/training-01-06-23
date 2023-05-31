<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    /**
     * @dataProvider providePublicUrlsAndMethods
     */
    public function testPublicUrlsReturn200Ok(string $method, string $url): void
    {
        $client = static::createClient();
        $client->request($method, $url);

        $this->assertResponseIsSuccessful();
    }

    public function providePublicUrlsAndMethods(): iterable
    {
        $urls = [
            'app_main_index' => ['GET', '/'],
            'app_main_contact' => ['GET', '/contact'],
            'app_movie_index' => ['GET', '/book'],
            'app_hello_index' => ['GET', '/hello'],
        ];

        foreach ($urls as $name => $methodAndUrl) {
            yield $name => $methodAndUrl;
        }
    }
}
