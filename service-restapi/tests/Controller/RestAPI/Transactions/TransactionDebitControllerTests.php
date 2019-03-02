<?php

namespace App\Tests\Controller\RestAPI\Transactions;

use App\Util\Test\FixtureTestCase;

class TransactionDebitControllerTests extends FixtureTestCase
{
    public function testDebitAmount()
    {
        $newAccount = [
            'account_id' => 3,
            'transaction_kind' => 'withdraw',
            'amount' => 1000,
        ];

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/transacciones/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode($newAccount)
        );
    
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('result', $responseData);
        $this->assertEquals('created', $responseData['result']);

        $this->assertArrayHasKey('current_amount', $responseData);
        $this->assertEquals(2000, $responseData['current_amount']);
    }

    public function testDebitExtraAmount()
    {
        $newAccount = [
            'account_id' => 1,
            'transaction_kind' => 'withdraw',
            'amount' => 5000,
        ];

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/transacciones/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode($newAccount)
        );
    
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('The amount withdraw exceeds the current amount of the account', $responseData['message']);
    }

    public function testDebitPay()
    {
        $newAccount = [
            'account_id' => 10,
            'transaction_kind' => 'pay',
            'amount' => 50,
        ];

        $client = static::createClient();
        $client->request(
            'POST',
            '/api/transacciones/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode($newAccount)
        );
    
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        
        $responseData = json_decode($response->getContent(), true);
        
        $this->assertArrayHasKey('result', $responseData);
        $this->assertEquals('created', $responseData['result']);

        $this->assertArrayHasKey('current_amount', $responseData);
        $this->assertEquals(2550, $responseData['current_amount']);
    }

    public function testDebitDelete()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/transacciones/7',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode($newAccount)
        );

        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));

        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('result', $responseData);
        $this->assertEquals('deleted', $responseData['result']);

        $this->assertArrayHasKey('deleted', $responseData);
        $this->assertEquals(500, $responseData['current_amount']);
    }
}
