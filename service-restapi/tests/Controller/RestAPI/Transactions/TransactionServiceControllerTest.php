<?php

namespace App\Tests\Controller\RestAPI\Account;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TransactionServiceControllerTest extends WebTestCase
{
    public function testServiceGetTransactions()
    {
        $client = static::createClient();
        $client->request('GET', '/api/transacciones/');

        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('data', $responseData);
    }

    public function testServiceGetTransaction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/transacciones/3');
        
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('data', $responseData);
    }

    public function testServiceDeleteTransactions()
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/transacciones/7');
        
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('result', $responseData);
        $this->assertEquals('deleted', $responseData['result']);
    }

    public function testEmpty()
    {
        $newTransaction = [];

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/transacciones/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode($newTransaction)
        );
    
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('The request not has data to create an account', $responseData['message']);
    }

    public function testInvalidData()
    {
        $newTransaction = [
            'message' => 'test'
        ];

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/transacciones/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode($newTransaction)
        );
    
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('There is not account to create a transaction', $responseData['message']);
    }

    public function testAccount()
    {
        $newTransaction = [
            'account_id' => 3,
        ];

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/transacciones/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode($newTransaction)
        );
    
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('There is not a transaction kind to create a transaction', $responseData['message']);
    }

    public function testTransaction()
    {
        $newTransaction = [
            'account_id' => 3,
            'transaction_kind' => 'withdraw',
        ];

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/transacciones/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode($newTransaction)
        );
    
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('There is not the amount to create a transaction', $responseData['message']);
    }
}
