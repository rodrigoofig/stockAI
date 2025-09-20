<?php
// src/Controller/StockController.php

namespace App\Controller;

use App\Entity\Stock;
use App\Entity\Supplier;
use App\Entity\Product;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/stocks')]
class StockController extends AbstractController
{
    #[Route('', name: 'stock_index', methods: ['GET'])]
    public function index(StockRepository $stockRepository): JsonResponse
    {
        $stocks = $stockRepository->findAll();
        
        $data = [];
        foreach ($stocks as $stock) {
            $data[] = [
                'id' => $stock->getId(),
                'name' => $stock->getName(),
                'quantity' => $stock->getQuantity(),
                'unit' => $stock->getUnit(),
                'supplier_id' => $stock->getSupplier() ? $stock->getSupplier()->getId() : null,
                'product_id' => $stock->getProduct() ? $stock->getProduct()->getId() : null
            ];
        }
        
        return $this->json($data);
    }

    #[Route('/{id}', name: 'stock_show', methods: ['GET'])]
    public function show(Stock $stock): JsonResponse
    {
        $data = [
            'id' => $stock->getId(),
            'name' => $stock->getName(),
            'quantity' => $stock->getQuantity(),
            'unit' => $stock->getUnit(),
            'supplier_id' => $stock->getSupplier() ? $stock->getSupplier()->getId() : null,
            'product_id' => $stock->getProduct() ? $stock->getProduct()->getId() : null
        ];
        
        return $this->json($data);
    }

    #[Route('', name: 'stock_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $stock = new Stock();
        $stock->setName($data['name']);
        $stock->setQuantity($data['quantity']);
        $stock->setUnit($data['unit']);

        if (isset($data['supplierId'])) {
            $supplier = $entityManager->getRepository(Supplier::class)->find($data['supplierId']);
            if ($supplier) {
                $stock->setSupplier($supplier);
            }
        }

        if (isset($data['productId'])) {
            $product = $entityManager->getRepository(Product::class)->find($data['productId']);
            if ($product) {
                $stock->setProduct($product);
            }
        }

        $entityManager->persist($stock);
        $entityManager->flush();

        $responseData = [
            'id' => $stock->getId(),
            'name' => $stock->getName(),
            'quantity' => $stock->getQuantity(),
            'unit' => $stock->getUnit(),
            'supplier_id' => $stock->getSupplier() ? $stock->getSupplier()->getId() : null,
            'product_id' => $stock->getProduct() ? $stock->getProduct()->getId() : null
        ];

        return $this->json($responseData, 201);
    }

    #[Route('/{id}', name: 'stock_update', methods: ['PUT'])]
    public function update(Stock $stock, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $stock->setName($data['name'] ?? $stock->getName());
        $stock->setQuantity($data['quantity'] ?? $stock->getQuantity());
        $stock->setUnit($data['unit'] ?? $stock->getUnit());

        if (isset($data['supplierId'])) {
            $supplier = $entityManager->getRepository(Supplier::class)->find($data['supplierId']);
            if ($supplier) {
                $stock->setSupplier($supplier);
            }
        } elseif (array_key_exists('supplierId', $data) && $data['supplierId'] === null) {
            $stock->setSupplier(null);
        }

        if (isset($data['productId'])) {
            $product = $entityManager->getRepository(Product::class)->find($data['productId']);
            if ($product) {
                $stock->setProduct($product);
            }
        } elseif (array_key_exists('productId', $data) && $data['productId'] === null) {
            $stock->setProduct(null);
        }

        $entityManager->flush();

        $responseData = [
            'id' => $stock->getId(),
            'name' => $stock->getName(),
            'quantity' => $stock->getQuantity(),
            'unit' => $stock->getUnit(),
            'supplier_id' => $stock->getSupplier() ? $stock->getSupplier()->getId() : null,
            'product_id' => $stock->getProduct() ? $stock->getProduct()->getId() : null
        ];

        return $this->json($responseData);
    }

    #[Route('/{id}', name: 'stock_delete', methods: ['DELETE'])]
    public function delete(Stock $stock, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($stock);
        $entityManager->flush();

        return $this->json(['message' => 'Stock deleted successfully']);
    }
}