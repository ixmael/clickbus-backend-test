<?php

namespace App\Tests\Controller\RestAPI\User;

use App\Util\Test\FixtureTestCase;

class UserControllerTest extends FixtureTestCase
{
    public function testExistsGetUsers()
    {
        $client = static::createClient();
        $client->request('GET', '/api/usuarios/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testExistsGetUser()
    {
        $client = static::createClient();
        $client->request('GET', '/api/usuarios/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testExistsAddUser()
    {
        $client = static::createClient();
        $client->request('POST', '/api/usuarios/');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testExistsUpdateUser()
    {
        $client = static::createClient();
        $client->request('PUT', '/api/usuarios/1');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testExistsDeleteUser()
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/usuarios/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
