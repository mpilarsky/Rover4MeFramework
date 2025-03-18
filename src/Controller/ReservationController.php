<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class ReservationController extends AbstractController
{
    private ReservationRepository $reservationRepository;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        ReservationRepository $reservationRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->reservationRepository = $reservationRepository;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/reservations', methods: ['GET'])]
    public function getReservations(): JsonResponse
    {
        $reservations = $this->reservationRepository->findAll();
        return $this->json($reservations);
    }

    #[Route('/addReservation', methods: ['POST'])]
    public function addReservation(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->userRepository->find($data['user_id']);

        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        $reservation = new Reservation();
        $reservation->setUser($user)
            ->setName($data['name'])
            ->setLocation($data['location'])
            ->setFrameSize($data['frame_size'])
            ->setTheme($data['theme'])
            ->setReservationDate(new \DateTime($data['reservation_date']))
            ->setStartTime(new \DateTime($data['start_time']))
            ->setEndTime(new \DateTime($data['end_time']))
            ->setBikeType($data['bike_type']);

        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return $this->json(['message' => 'Reservation added']);
    }
}
