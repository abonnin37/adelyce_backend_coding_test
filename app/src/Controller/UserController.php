<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/api/users', name: 'get_users', methods: ['GET'])]
    public function getAllUsers(UserRepository $userRepository, UserService $userService): JsonResponse
    {
        $me = $userService->getConnectedUser();
        $users = $userRepository->findAll();

        $filtredUsers = array_values(array_filter($users, function (User $user) use ($me) {
            return $user->getId() != $me->getId();
        }));

        return $this->json($filtredUsers, Response::HTTP_OK, [], ['groups' => 'get_user']);
    }
}
