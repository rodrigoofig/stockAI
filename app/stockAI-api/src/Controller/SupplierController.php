<?php
// src/Controller/SupplierController.php

namespace App\Controller;

use App\Entity\Supplier;
use App\Repository\SupplierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/suppliers')]
class SupplierController extends AbstractController
{
    #[Route('', name: 'supplier_index', methods: ['GET'])]
    public function index(SupplierRepository $supplierRepository): JsonResponse
    {
        $suppliers = $supplierRepository->findAll();
        
        $data = [];
        foreach ($suppliers as $supplier) {
            $data[] = $this->serializeSupplier($supplier);
        }
        
        return $this->json($data);
    }

    #[Route('/{id}', name: 'supplier_show', methods: ['GET'])]
    public function show(Supplier $supplier): JsonResponse
    {
        return $this->json($this->serializeSupplier($supplier));
    }

    #[Route('', name: 'supplier_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $supplier = new Supplier();
        $supplier->setName($data['name']);
        $supplier->setFone($data['fone'] ?? null);
        $supplier->setCel($data['cel'] ?? null);
        $supplier->setEmail($data['email'] ?? null);
        $supplier->setAddress($data['address'] ?? null);
        $supplier->setNif($data['nif'] ?? null);
        $supplier->setUrlApi($data['urlApi'] ?? null);
        $supplier->setToken($data['token'] ?? null);
        $supplier->setRequestType($data['requestType'] ?? null);

        $entityManager->persist($supplier);
        $entityManager->flush();

        return $this->json($this->serializeSupplier($supplier), 201);
    }

    #[Route('/{id}', name: 'supplier_update', methods: ['PUT'])]
    public function update(Supplier $supplier, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $supplier->setName($data['name'] ?? $supplier->getName());
        $supplier->setFone($data['fone'] ?? $supplier->getFone());
        $supplier->setCel($data['cel'] ?? $supplier->getCel());
        $supplier->setEmail($data['email'] ?? $supplier->getEmail());
        $supplier->setAddress($data['address'] ?? $supplier->getAddress());
        $supplier->setNif($data['nif'] ?? $supplier->getNif());
        $supplier->setUrlApi($data['urlApi'] ?? $supplier->getUrlApi());
        $supplier->setToken($data['token'] ?? $supplier->getToken());
        $supplier->setRequestType($data['requestType'] ?? $supplier->getRequestType());

        $entityManager->flush();

        return $this->json($this->serializeSupplier($supplier));
    }

    #[Route('/{id}', name: 'supplier_delete', methods: ['DELETE'])]
    public function delete(Supplier $supplier, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($supplier);
        $entityManager->flush();

        return $this->json(['message' => 'Supplier deleted successfully']);
    }

    /**
     * Serialize Supplier entity to avoid circular references
     */
    private function serializeSupplier(Supplier $supplier): array
    {
        return [
            'id' => $supplier->getId(),
            'name' => $supplier->getName(),
            'fone' => $supplier->getFone(),
            'cel' => $supplier->getCel(),
            'email' => $supplier->getEmail(),
            'address' => $supplier->getAddress(),
            'nif' => $supplier->getNif(),
            'urlApi' => $supplier->getUrlApi(),
            'token' => $supplier->getToken(),
            'requestType' => $supplier->getRequestType()
            // Não incluir products ou stocks para evitar referência circular
        ];
    }
}