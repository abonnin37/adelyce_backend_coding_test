<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/api/users/', name: 'get_users', methods: ['GET'])]
    public function getAllUsers(UserRepository $userRepository): JsonResponse
    {
        return $this->json($userRepository->findAll(), Response::HTTP_OK, [], ['groups' => 'get_user']);
    }
}
