<?php

namespace App\Tests\Controller\RestAPI\Transaction;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TransactionControllerTest extends WebTestCase
{
    public function testExistsGetTransactions()
    {
        $client = static::createClient();
        $client->request('GET', '/api/transacciones/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testExistsGetTransaction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/transacciones/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testExistsAddTransactions()
    {
        $client = static::createClient();
        $client->request('POST', '/api/transacciones/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testExistsDeleteTransactions()
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/transacciones/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
