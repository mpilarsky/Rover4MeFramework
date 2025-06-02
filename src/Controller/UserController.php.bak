<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\RequestStack;

#[Route('/api')]
class UserController extends AbstractController
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private RequestStack $requestStack;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    #[Route('/signup', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);


        if (!isset($data['email'], $data['password'], $data['name'], $data['surname'], $data['age'])) {
            return $this->json(['error' => 'Missing required fields'], 400);
        }


        if ($this->userRepository->findOneBy(['email' => $data['email']])) {
            return $this->json(['error' => 'Email already in use'], 400);
        }

        $user = new User();
        $user->setEmail($data['email'])
            ->setPassword($passwordHasher->hashPassword($user, $data['password']))
            ->setName($data['name'])
            ->setSurname($data['surname'])
            ->setAge((int) $data['age']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json(['message' => 'User registered successfully']);
    }

    #[Route('/login', methods: ['POST'])]
    public function login(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {

        $session = $this->requestStack->getSession();

        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';


        $user = $this->userRepository->findOneBy(['email' => $email]);


        if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
            return $this->json(['error' => 'Invalid credentials'], 401);
        }


        $session->set('user', $user->getId());

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'age' => $user->getAge(),
        ]);
    }

    #[Route('/logout', methods: ['POST'])]
    public function logout(): JsonResponse
    {

        $this->session->remove('user');

        return $this->json(['message' => 'Logged out successfully']);
    }

    #[Route('/profile', methods: ['GET'])]
    public function getProfile(): JsonResponse
    {
        $userId = $this->session->get('user');

        if (!$userId) {
            return $this->json(['error' => 'User not logged in'], 401);
        }

        $user = $this->userRepository->find($userId);

        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'age' => $user->getAge(),
        ]);
    }

    #[Route('/users', methods: ['GET'])]
    public function getUsers(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        $usersArray = array_map(fn(User $user) => [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'age' => $user->getAge(),
        ], $users);

        return $this->json($usersArray);
    }
}
