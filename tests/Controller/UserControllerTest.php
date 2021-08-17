<?php

namespace App\Tests\Controller;

use App\Controller\UserController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    public function testIndex(): void
    {
        $client = static::createClient([
            // 'environment' => 'prod',
            'debug'       => false,
        ]);
        $client->request('GET', '/customers');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame('200');
        $this->assertResponseFormatSame('json');
    }

    public function testDetail()
    {
        $client = static::createClient([
            // 'environment' => 'prod',
            'debug'       => false,
        ]);
        $client->request('GET', '/customers/1');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame('200');
        $this->assertResponseFormatSame('json');
    }
}
