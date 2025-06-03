<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class UserControllerTest extends ApiTestCase
{
    public function testRegisterUser(): void
    {
        $response = static::createClient()->request('POST', '/api/v1/signup', [
            'json' => [
                'email' => 'test@example.com',
                'password' => 'password123',
                'name' => 'Jan',
                'surname' => 'Kowalski',
                'age' => 30,
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains(['message' => 'User registered successfully']);
    }

    public function testLoginUser(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/v1/login', [
            'json' => [
                'email' => 'test@example.com',
                'password' => 'password123',
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains(['email' => 'test@example.com']);
    }

    public function testLogoutUser(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/v1/logout');

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains(['message' => 'Logged out successfully']);
    }
}
