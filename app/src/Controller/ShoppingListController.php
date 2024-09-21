<?php

namespace App\Controller;

use App\Repository\ShoppingListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShoppingListController extends AbstractController
{
    #[Route('/api/shopping-list', name: 'get_shopping_list', methods: ['GET'])]
    public function getShoppingList(ShoppingListRepository $shoppingListRepository): JsonResponse
    {
        $shoppingList = $shoppingListRepository->findOneBy(['user' => $this->getUser()]);

        return $this->json($shoppingList, Response::HTTP_OK, [], ['groups' => ['get_shopping_list', 'get_item']]);
    }
}
