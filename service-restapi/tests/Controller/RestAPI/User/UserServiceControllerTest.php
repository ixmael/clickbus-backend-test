<?php

namespace App\Tests\Controller\RestAPI\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserServiceControllerTest extends WebTestCase
{
    public function testServiceGetUsers()
    {
        $client = static::createClient();
        $client->request('GET', '/api/usuarios/');

        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('data', $responseData);
    }

    public function testServiceGetUser()
    {
        $client = static::createClient();
        $client->request('GET', '/api/usuarios/3');
        
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('data', $responseData);
    }

    public function testServiceAddUser()
    {
        $newUser = [
            'name' => 'usuario clickbus',
            'email' => 'usuario@clickbus.com',
        ];
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/usuarios/',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode($newUser)
        );
        
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('result', $responseData);
        $this->assertEquals('created', $responseData['result']);
    }

    public function testServiceUpdateUser()
    {
        $updateUser = [
          'name' => 'usuario de clickbus',
          'email' => 'new@clickbus.com',
        ];
        $client = static::createClient();
        $client->request(
            'PUT',
            '/api/usuarios/5',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            \json_encode($updateUser)
        );
        
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        //fwrite(STDERR, print_r($responseData, TRUE));

        $this->assertArrayHasKey('result', $responseData);
        $this->assertEquals('updated', $responseData['result']);
    }

    public function testServiceDeleteUser()
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/usuarios/7');
        
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('result', $responseData);
        $this->assertEquals('deleted', $responseData['result']);
    }
}
