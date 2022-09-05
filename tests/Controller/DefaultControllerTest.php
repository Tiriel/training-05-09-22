<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function provideUrisAndStatusCodes(): array
    {
        return [
            'index' => ['/', 200],
            'contact' => ['/contact', 200],
            'book_index' => ['/book', 200],
            'book_details' => ['/book/25', 200],
            'toto' => ['/toto', 404],
        ];
    }

    /**
     * @dataProvider provideUrisAndStatusCodes
     * @group smoke
     */
    public function testPublicUrls(string $uri, int $statusCode): void
    {
        $client = static::createClient();
        $client->request('GET', $uri);

        $this->assertResponseStatusCodeSame($statusCode);
    }
}
