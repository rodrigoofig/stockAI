<?php
// src/Controller/OrderController.php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/orders')]
class OrderController extends AbstractController
{
    #[Route('', name: 'order_index', methods: ['GET'])]
    public function index(OrderRepository $orderRepository): JsonResponse
    {
        $orders = $orderRepository->findAll();
        $data = [];
        
        foreach ($orders as $order) {
            $data[] = $this->serializeOrder($order);
        }
        
        return $this->json($data);
    }

    #[Route('/{id}', name: 'order_show', methods: ['GET'])]
    public function show(Order $order): JsonResponse
    {
        return $this->json($this->serializeOrder($order));
    }

    #[Route('', name: 'order_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $order = new Order();
        $order->setTotalPrice($data['totalPrice']);
        $order->setOrderDate(new \DateTimeImmutable($data['orderDate']));

        foreach ($data['products'] as $productData) {
            $orderItem = new OrderItem();
            $product = $entityManager->getRepository(Product::class)->find($productData['productId']);
            
            if ($product) {
                $orderItem->setProduct($product);
                $orderItem->setQuantity($productData['quantity']);
                $orderItem->setPrice($productData['price']);
                $orderItem->setOrder($order);
                
                $order->addOrderItem($orderItem);
                $entityManager->persist($orderItem);
            }
        }

        $entityManager->persist($order);
        $entityManager->flush();

        return $this->json($this->serializeOrder($order), 201);
    }

    #[Route('/{id}', name: 'order_update', methods: ['PUT'])]
    public function update(Order $order, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $order->setTotalPrice($data['totalPrice'] ?? $order->getTotalPrice());
        
        if (isset($data['orderDate'])) {
            $order->setOrderDate(new \DateTimeImmutable($data['orderDate']));
        }

        // Remove existing order items
        foreach ($order->getOrderItems() as $orderItem) {
            $entityManager->remove($orderItem);
        }

        // Add new order items
        if (isset($data['products'])) {
            foreach ($data['products'] as $productData) {
                $orderItem = new OrderItem();
                $product = $entityManager->getRepository(Product::class)->find($productData['productId']);
                
                if ($product) {
                    $orderItem->setProduct($product);
                    $orderItem->setQuantity($productData['quantity']);
                    $orderItem->setPrice($productData['price']);
                    $orderItem->setOrder($order);
                    
                    $order->addOrderItem($orderItem);
                    $entityManager->persist($orderItem);
                }
            }
        }

        $entityManager->flush();

        return $this->json($this->serializeOrder($order));
    }

    #[Route('/{id}', name: 'order_delete', methods: ['DELETE'])]
    public function delete(Order $order, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($order);
        $entityManager->flush();

        return $this->json(['message' => 'Order deleted successfully']);
    }

    /**
     * Serialize Order entity to avoid circular references
     */
    private function serializeOrder(Order $order): array
    {
        $orderItems = [];
        foreach ($order->getOrderItems() as $orderItem) {
            $product = $orderItem->getProduct();
            
            $orderItems[] = [
                'id' => $orderItem->getId(),
                'quantity' => $orderItem->getQuantity(),
                'price' => $orderItem->getPrice(),
                'product' => [
                    'id' => $product->getId(),
                    'name' => $product->getName(),
                    'price' => $product->getPrice(),
                    'hasIngredients' => $product->isHasIngredients(),
                    'description' => $product->getDescription(),
                    // Não incluir supplier ou outras relações para evitar referência circular
                ]
            ];
        }

        return [
            'id' => $order->getId(),
            'totalPrice' => $order->getTotalPrice(),
            'orderDate' => $order->getOrderDate()->format('Y-m-d H:i:s'),
            'orderItems' => $orderItems
        ];
    }
}