<?php
// src/Controller/ProductController.php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Supplier;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/products')]
class ProductController extends AbstractController
{
    #[Route('', name: 'product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();
        
        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'hasIngredients' => $product->isHasIngredients(),
                'description' => $product->getDescription(),
                'supplier_id' => $product->getSupplier() ? $product->getSupplier()->getId() : null
            ];
        }
        
        return $this->json($data);
    }

    #[Route('/{id}', name: 'product_show', methods: ['GET'])]
    public function show(Product $product): JsonResponse
    {
        $data = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'hasIngredients' => $product->isHasIngredients(),
            'description' => $product->getDescription(),
            'supplier_id' => $product->getSupplier() ? $product->getSupplier()->getId() : null
        ];
        
        return $this->json($data);
    }

    #[Route('', name: 'product_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $product = new Product();
        $product->setName($data['name']);
        $product->setPrice($data['price']);
        $product->setHasIngredients($data['hasIngredients'] ?? false);
        $product->setDescription($data['description'] ?? null);

        if (isset($data['supplierId'])) {
            $supplier = $entityManager->getRepository(Supplier::class)->find($data['supplierId']);
            if ($supplier) {
                $product->setSupplier($supplier);
            }
        }

        $entityManager->persist($product);
        $entityManager->flush();

        $responseData = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'hasIngredients' => $product->isHasIngredients(),
            'description' => $product->getDescription(),
            'supplier_id' => $product->getSupplier() ? $product->getSupplier()->getId() : null
        ];

        return $this->json($responseData, 201);
    }

    #[Route('/{id}', name: 'product_update', methods: ['PUT'])]
    public function update(Product $product, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $product->setName($data['name'] ?? $product->getName());
        $product->setPrice($data['price'] ?? $product->getPrice());
        $product->setHasIngredients($data['hasIngredients'] ?? $product->isHasIngredients());
        $product->setDescription($data['description'] ?? $product->getDescription());

        if (isset($data['supplierId'])) {
            $supplier = $entityManager->getRepository(Supplier::class)->find($data['supplierId']);
            if ($supplier) {
                $product->setSupplier($supplier);
            }
        } elseif (array_key_exists('supplierId', $data) && $data['supplierId'] === null) {
            $product->setSupplier(null);
        }

        $entityManager->flush();

        $responseData = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'hasIngredients' => $product->isHasIngredients(),
            'description' => $product->getDescription(),
            'supplier_id' => $product->getSupplier() ? $product->getSupplier()->getId() : null
        ];

        return $this->json($responseData);
    }

    #[Route('/{id}', name: 'product_delete', methods: ['DELETE'])]
    public function delete(Product $product, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->json(['message' => 'Product deleted successfully']);
    }
}