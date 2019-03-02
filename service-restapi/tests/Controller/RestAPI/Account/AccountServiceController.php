<?php

namespace App\Tests\Controller\RestAPI\Account;

use App\Util\Test\FixtureTestCase;

class AccountServiceControllerTest extends FixtureTestCase
{
    public function testServiceGetAccounts()
    {
        $client = static::createClient();
        $client->request('GET', '/api/cuentas/');

        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('data', $responseData);
    }

    public function testServiceGetAccount()
    {
        $client = static::createClient();
        $client->request('GET', '/api/cuentas/3');
        
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('data', $responseData);
    }

    public function testServiceAddAccount()
    {
        $newAccount = [
            'user_id' => 5,
            'account_kind' => 'credit',
            'credit' => 5000,
        ];
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/cuentas/',
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
    }

    public function testServiceUpdateAccount()
    {
        $updateAccount = [
            'account_kind' => 'debit',
            'amount' => 3000,
            'user_id' => 3
        ];
        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/cuentas/5',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode($updateAccount)
        );
        
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('result', $responseData);
        $this->assertEquals('updated', $responseData['result']);
    }

    public function testServiceDeleteAccount()
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/cuentas/7');
        
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('result', $responseData);
        $this->assertEquals('deleted', $responseData['result']);
    }
}
