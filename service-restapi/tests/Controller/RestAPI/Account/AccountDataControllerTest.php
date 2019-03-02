<?php

namespace App\Tests\Controller\RestAPI\Account;

use App\Util\Test\FixtureTestCase;

class AccountServiceControllerTest extends FixtureTestCase
{
    public function testDataEmpty()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/cuentas/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );
        
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("The request not has data to create an account", $responseData['message']);
    }

    public function testDataUser()
    {
        $newAccount = [
            'user_id' => 5,
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

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("There is not 'accounts_kind' in the request data", $responseData['message']);
    }

    public function testDataCreditAccount()
    {
        $newAccount = [
            'user_id' => 5,
            'account_kind' => 'credit',
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

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("The data is not complete to create a credit account", $responseData['message']);
    }

    public function testDataCreditAccountIncomplete()
    {
        $newAccount = [
            'user_id' => 5,
            'account_kind' => 'credit',
            'credit' => 3000
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

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("The data is not complete to create a credit account", $responseData['message']);
    }

    public function testDataCreditAccountComplete()
    {
        $newAccount = [
            'user_id' => 5,
            'account_kind' => 'credit',
            'credit' => 3000,
            'limit_credit' => 5000,
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

        $this->assertArrayHasKey('id', $responseData);
    }

    public function testDataDebitAccount()
    {
        $newAccount = [
            'user_id' => 5,
            'account_kind' => 'debit',
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

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("The data is not complete to create a debit account", $responseData['message']);
    }

    public function testDataCompleteDebitAccount()
    {
        $newAccount = [
            'user_id' => 5,
            'account_kind' => 'debit',
            'amount' => 5000
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

        $this->assertArrayHasKey('id', $responseData);
    }

    public function testUpdateEmpty()
    {
        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/cuentas/7',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );
        
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("The request not has data to update an account", $responseData['message']);
    }

    public function testUpdateUser()
    {
        $updateAccount = [
            'user_id' => 5,
        ];
        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/cuentas/7',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode($updateAccount)
        );
        
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $responseData);
    }

    public function testUpdateDebitAccount()
    {
        $updateAccount = [
            'account_kind' => 'debit',
        ];
        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/cuentas/7',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode($updateAccount)
        );
        
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("The data is not complete to update a debit account", $responseData['message']);
    }

    public function testUpdateDebitAccountAmount()
    {
        $updateAccount = [
            'account_kind' => 'debit',
            'amount' => 10000,
        ];
        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/cuentas/9',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode($updateAccount)
        );
        
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $responseData);
    }

    public function testUpdateCreditAccount()
    {
        $updateAccount = [
            'account_kind' => 'credit',
        ];
        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/cuentas/7',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode($updateAccount)
        );
        
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("The data is not complete to update a credit account", $responseData['message']);
    }

    public function testUpdateCreditAccountCredit()
    {
        $updateAccount = [
            'account_kind' => 'credit',
            'credit' => 10000,
        ];
        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/cuentas/9',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode($updateAccount)
        );
        
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals("The data is not complete to update a credit account", $responseData['message']);
    }

    public function testUpdateCreditAccountLimit()
    {
        $updateAccount = [
            'account_kind' => 'credit',
            'credit' => 10000,
            'limit_credit' => 15000,
        ];
        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/cuentas/9',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode($updateAccount)
        );
        
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $responseData);
    }
}
