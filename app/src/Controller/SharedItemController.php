<?php

namespace App\Controller;

use App\DataTransferObject\PostShareItem;
use App\Entity\Item;
use App\Entity\User;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SharedItemController extends AbstractController
{
    #[Route('/api/shared-items', name: 'post_shared_item', methods: ['POST'])]
    public function postShareItem(Request $request, UserService $userService, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager): JsonResponse
    {
        $payload = $serializer->deserialize($request->getContent(), PostShareItem::class, 'json');
        $errors = $validator->validate($payload);

        if (count($errors) > 0) {
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        if ($payload->getUserId() == $userService->getConnectedUser()->getId()) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, "L'élément de la liste ne peut pas être partagé avec soit même.");
        }

        $user = $entityManager->getRepository(User::class)->find($payload->getUserId());
        $item = $entityManager->getRepository(Item::class)->find($payload->getItemId());

        if (!$user) {
            throw $this->createNotFoundException("L'utilisateur n'existe pas.");
        }

        if (!$item) {
            throw $this->createNotFoundException("L'élément n'existe pas.");
        }

        $item->addUser($user);

        $entityManager->persist($item);
        $entityManager->flush();

        return $this->json($item, Response::HTTP_OK, [], ['groups' => ['get_item']]);
    }

    #[Route('/api/shared-items', name: 'delete_shared_item', methods: ['DELETE'])]
    public function deleteSharedItem(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager): JsonResponse
    {
        $payload = $serializer->deserialize($request->getContent(), PostShareItem::class, 'json');
        $errors = $validator->validate($payload);

        if (count($errors) > 0) {
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $user = $entityManager->getRepository(User::class)->find($payload->getUserId());
        $item = $entityManager->getRepository(Item::class)->find($payload->getItemId());

        if (!$user) {
            throw $this->createNotFoundException("L'utilisateur n'existe pas.");
        }

        if (!$item) {
            throw $this->createNotFoundException("L'élément n'existe pas.");
        }

        if (!$item->getUsers()->contains($user)) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, "Aucun élément partagé ne correspond à votre demande.");
        }

        $item->removeUser($user);

        $entityManager->persist($item);
        $entityManager->flush();

        return $this->json($item, Response::HTTP_OK, [], ['groups' => ['get_item']]);
    }

    #[Route('/api/shared-items', name: 'get_all_shared_items', methods: ['GET'])]
    public function getAllSharedItems(UserService $userService): JsonResponse
    {
        $connectedUser = $userService->getConnectedUser();

        return $this->json($connectedUser, Response::HTTP_OK, [], ['groups' => ['get_all_shared_items']]);
    }
}
