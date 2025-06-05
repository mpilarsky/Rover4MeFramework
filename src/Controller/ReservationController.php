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
use Symfony\Component\Serializer\Annotation\Groups;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

#[Route('/api/v1')]
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
	
	#[OA\Get(
    path: '/api/v1/reservations',
    summary: 'Pobranie wszystkich rezerwacji zalogowanego użytkownika',
    responses: [
        new OA\Response(response: 200, description: "List of reservations"),
        new OA\Response(response: 401, description: "Unauthorized")
    ]
	)]

    #[Route('/reservations', methods: ['GET'])]
    public function getReservations(Request $request): JsonResponse
    {
		$userId = $request->query->get('user_id');
		$user = $this->userRepository->find($userId);

		if (!$user) {
			return $this->json(['error' => 'Unauthorized'], 401);
		}

		$reservations = $this->reservationRepository->findBy(['user' => $user]);
        $data = [];

		foreach ($reservations as $reservation) {
			$data[] = [
				'id' => $reservation->getId(),
				'name' => $reservation->getName(),
				'location' => $reservation->getLocation(),
				'frame_size' => $reservation->getFrameSize(),
				'theme' => $reservation->getTheme(),
				'reservation_date' => $reservation->getReservationDate()->format('Y-m-d'),
				'start_time' => $reservation->getStartTime()->format('H:i:s'),
				'end_time' => $reservation->getEndTime()->format('H:i:s'),
				'bike_type' => $reservation->getBikeType(),
				'user_id' => $reservation->getUser()->getId(),
			];
		}

		return $this->json($data, 200);
    }

	#[OA\Post(
    path: '/api/v1/addReservation',
    summary: 'Dodanie nowej rezerwacji',
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["user_id", "name", "location", "frame_size", "theme", "reservation_date", "start_time", "end_time", "bike_type"],
            type: "object",
            properties: [
                new OA\Property(property: "user_id", type: "integer"),
                new OA\Property(property: "name", type: "string"),
                new OA\Property(property: "location", type: "string"),
                new OA\Property(property: "frame_size", type: "string"),
                new OA\Property(property: "theme", type: "string"),
                new OA\Property(property: "reservation_date", type: "string", format: "date"),
                new OA\Property(property: "start_time", type: "string", format: "time"),
                new OA\Property(property: "end_time", type: "string", format: "time"),
                new OA\Property(property: "bike_type", type: "string")
            ]
        )
    ),
    responses: [
        new OA\Response(response: 201, description: "Reservation added"),
        new OA\Response(response: 404, description: "User not found")
    ]
	)]

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

        return $this->json(['message' => 'Reservation added'], 201);
    }
}
