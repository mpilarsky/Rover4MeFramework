<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ReservationControllerTest extends ApiTestCase
{
    private function createTestUser(): User
    {
        /** @var EntityManagerInterface $em */
        $em = static::getContainer()->get(EntityManagerInterface::class);

        $user = new User();
        $user->setEmail('user1@example.com');
        $user->setPassword('pass1234');
        $user->setName('Ala');
        $user->setSurname('Makota');
        $user->setAge(25);

        $em->persist($user);
        $em->flush();

        return $user;
    }

    public function testAddReservation(): void
    {
        $user = $this->createTestUser();

        $response = static::createClient()->request('POST', '/api/v1/addReservation', [
            'json' => [
                'user_id' => $user->getId(),
                'name' => 'Testowa rezerwacja',
                'location' => 'Kraków',
                'frame_size' => 'M',
                'theme' => 'Górski',
                'reservation_date' => '2025-06-05',
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
                'bike_type' => 'MTB',
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains(['message' => 'Reservation added']);
    }

    public function testGetUserReservations(): void
    {
        $user = $this->createTestUser();

        static::createClient()->request('GET', '/api/v1/userReservations/' . $user->getId());

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }
}
