<?php

namespace App\Controller;

use App\Entity\Item;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ItemController extends AbstractController
{
    #[Route('/api/items', name: 'post_item', methods: ['POST'])]
    public function postItem(Request $request, UserService $userService, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager): JsonResponse
    {
        $connectedUser = $userService->getConnectedUser();
        $item = $serializer->deserialize($request->getContent(), Item::class, 'json');
        $item->setShoppingList($connectedUser->getShoppingList());
        $item->setCreatedAt(new \DateTimeImmutable("now"));
        $item->setUpdatedAt(new \DateTimeImmutable("now"));
        $errors = $validator->validate($item);

        if (count($errors) > 0) {
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($item);
        $entityManager->flush();

        return $this->json($item, Response::HTTP_CREATED, [], ['groups' => ['get_item']]);
    }

    #[Route('/api/items/{id}', name: 'get_item', methods: ['GET'])]
    public function getItem(Item $item): JsonResponse
    {
        return $this->json($item, Response::HTTP_OK, [], ['groups' => ['get_item']]);
    }

    #[Route('/api/items/{id}', name: 'delete_item', methods: ['DELETE'])]
    public function deleteItem(Item $item, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($item);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/items/{id}', name: 'put_item', methods: ['PUT'])]
    public function putItem(Request $request, Item $item, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager): JsonResponse
    {
        $itemFromRequest = $serializer->deserialize($request->getContent(), Item::class, 'json');
        $updatedItem = $item;
        $updatedItem->setName($itemFromRequest->getName());
        $updatedItem->setUpdatedAt(new \DateTimeImmutable("now"));
        $errors = $validator->validate($item);

        if (count($errors) > 0) {
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($item);
        $entityManager->flush();

        return $this->json($item, Response::HTTP_CREATED, [], ['groups' => ['get_item']]);
    }
}
