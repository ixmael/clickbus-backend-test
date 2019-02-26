<?php

namespace App\Tests\Controller\RestAPI;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testGetUser()
    {
        $client = static::createClient();
        $client->request('GET', '/api/usuarios/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
