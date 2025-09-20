<?php
// src/Controller/IngredientController.php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Product;
use App\Entity\Stock;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/ingredients')]
class IngredientController extends AbstractController
{
    #[Route('', name: 'ingredient_index', methods: ['GET'])]
    public function index(Request $request, IngredientRepository $ingredientRepository): JsonResponse
    {
        $productId = $request->query->get('product_id');
        
        if ($productId) {
            $ingredients = $ingredientRepository->findBy(['product' => $productId]);
        } else {
            $ingredients = $ingredientRepository->findAll();
        }
        
        $data = [];
        foreach ($ingredients as $ingredient) {
            $data[] = [
                'id' => $ingredient->getId(),
                'name' => $ingredient->getName(),
                'quantity' => $ingredient->getQuantity(),
                'unit' => $ingredient->getUnit(),
                'product_id' => $ingredient->getProduct()->getId(),
                'stock_id' => $ingredient->getStock()->getId()
            ];
        }
        
        return $this->json($data);
    }

    #[Route('/{id}', name: 'ingredient_show', methods: ['GET'])]
    public function show(Ingredient $ingredient): JsonResponse
    {
        $data = [
            'id' => $ingredient->getId(),
            'name' => $ingredient->getName(),
            'quantity' => $ingredient->getQuantity(),
            'unit' => $ingredient->getUnit(),
            'product_id' => $ingredient->getProduct()->getId(),
            'stock_id' => $ingredient->getStock()->getId()
        ];
        
        return $this->json($data);
    }

    #[Route('', name: 'ingredient_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $ingredient = new Ingredient();
        $ingredient->setName($data['name']);
        $ingredient->setQuantity($data['quantity']);
        $ingredient->setUnit($data['unit']);

        $product = $entityManager->getRepository(Product::class)->find($data['productId']);
        if ($product) {
            $ingredient->setProduct($product);
        }

        $stock = $entityManager->getRepository(Stock::class)->find($data['stockId']);
        if ($stock) {
            $ingredient->setStock($stock);
        }

        $entityManager->persist($ingredient);
        $entityManager->flush();

        // Retorna os dados serializados manualmente
        $responseData = [
            'id' => $ingredient->getId(),
            'name' => $ingredient->getName(),
            'quantity' => $ingredient->getQuantity(),
            'unit' => $ingredient->getUnit(),
            'product_id' => $ingredient->getProduct()->getId(),
            'stock_id' => $ingredient->getStock()->getId()
        ];

        return $this->json($responseData, 201);
    }

    #[Route('/{id}', name: 'ingredient_update', methods: ['PUT'])]
    public function update(Ingredient $ingredient, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $ingredient->setName($data['name'] ?? $ingredient->getName());
        $ingredient->setQuantity($data['quantity'] ?? $ingredient->getQuantity());
        $ingredient->setUnit($data['unit'] ?? $ingredient->getUnit());

        if (isset($data['productId'])) {
            $product = $entityManager->getRepository(Product::class)->find($data['productId']);
            if ($product) {
                $ingredient->setProduct($product);
            }
        }

        if (isset($data['stockId'])) {
            $stock = $entityManager->getRepository(Stock::class)->find($data['stockId']);
            if ($stock) {
                $ingredient->setStock($stock);
            }
        }

        $entityManager->flush();

        // Retorna os dados serializados manualmente
        $responseData = [
            'id' => $ingredient->getId(),
            'name' => $ingredient->getName(),
            'quantity' => $ingredient->getQuantity(),
            'unit' => $ingredient->getUnit(),
            'product_id' => $ingredient->getProduct()->getId(),
            'stock_id' => $ingredient->getStock()->getId()
        ];

        return $this->json($responseData);
    }

    #[Route('/{id}', name: 'ingredient_delete', methods: ['DELETE'])]
    public function delete(Ingredient $ingredient, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($ingredient);
        $entityManager->flush();

        return $this->json(['message' => 'Ingredient deleted successfully']);
    }
}