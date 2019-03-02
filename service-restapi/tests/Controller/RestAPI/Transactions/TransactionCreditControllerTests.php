<?php

namespace App\Tests\Controller\RestAPI\Transactions;

use App\Util\Test\FixtureTestCase;

class TransactionCreditControllerTests extends FixtureTestCase
{
    public function testCreditAmount()
    {
        $newTransaction = [
            'account_id' => 5,
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
            \json_encode($newTransaction)
        );
    
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('result', $responseData);
        $this->assertEquals('created', $responseData['result']);

        $this->assertArrayHasKey('current_amount', $responseData);
        $this->assertEquals(900, $responseData['current_amount']);
    }

    public function testTransactionExtraAmount()
    {
        $newTransaction = [
            'account_id' => 4,
            'transaction_kind' => 'withdraw',
            'amount' => 4600,
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
        $this->assertEquals('The amount withdraw exceeds the current amount of the account', $responseData['message']);
    }
}
