<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserService
{
    private User $user;

    public function __construct(Security $security, UserRepository $userRepository)
    {
        if (empty($security->getToken()) || empty($security->getToken()->getUser())) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, "L'utilisateur n'est pas défini. Veuillez vous connecter");
        }

        $userEmail = $security->getToken()->getUser()->getUserIdentifier();
        $this->user = $userRepository->findOneBy(['email' => $userEmail]);
    }

    public function getConnectedUser() : User
    {
        return $this->user;
    }
}