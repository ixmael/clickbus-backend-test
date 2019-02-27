<?php

namespace App\Tests\Controller\RestAPI\Account;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountControllerTest extends WebTestCase
{
    public function testExistsGetUsers()
    {
        $client = static::createClient();
        $client->request('GET', '/api/cuentas/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testExistsGetUser()
    {
        $client = static::createClient();
        $client->request('GET', '/api/cuentas/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testExistsAddUser()
    {
        $client = static::createClient();
        $client->request('POST', '/api/cuentas/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testExistsUpdateUser()
    {
        $client = static::createClient();
        $client->request('PUT', '/api/cuentas/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testExistsDeleteUser()
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/cuentas/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
