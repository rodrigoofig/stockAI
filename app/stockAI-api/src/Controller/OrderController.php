<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\Stock;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/orders')]
class OrderController extends AbstractController
{
    #[Route('', name: 'order_list', methods: ['GET'])]
    public function list(EntityManagerInterface $em): Response
    {
        $orders = $em->getRepository(Order::class)->findAll();
        $data = [];

        foreach ($orders as $order) {
            $items = [];
            foreach ($order->getOrderItems() as $item) {
                $items[] = [
                    'product' => $item->getProduct()->getName(),
                    'quantity' => $item->getQuantity(),
                    'price' => $item->getPrice(),
                ];
            }
            $data[] = [
                'id' => $order->getId(),
                'totalPrice' => $order->getTotalPrice(),
                'orderDate' => $order->getOrderDate()?->format('Y-m-d H:i:s'),
                'items' => $items
            ];
        }

        return $this->json($data);
    }

    #[Route('/{id}', name: 'order_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $em): Response
    {
        $order = $em->getRepository(Order::class)->find($id);
        if (!$order) {
            return $this->json(['error' => 'Order not found'], 404);
        }


        $items = [];
        foreach ($order->getOrderItems() as $item) {
            $items[] = [
                'product' => $item->getProduct()->getName(),
                'quantity' => $item->getQuantity(),
                'price' => $item->getPrice(),
            ];
        }


        $data = [
            'id' => $order->getId(),
            'totalPrice' => $order->getTotalPrice(),
            'orderDate' => $order->getOrderDate()?->format('Y-m-d H:i:s'),
            'items' => $items
        ];


        return $this->json($data);
    }

    #[Route('', name: 'order_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);

        $order = new Order();
        $order->setOrderDate(new \DateTimeImmutable());
        $order->setTotalPrice($data['totalPrice'] ?? 0);
        $em->persist($order);

        foreach ($data['items'] as $itemData) {
            $product = $em->getRepository(Product::class)->find($itemData['product_id']);
            if (!$product) {
                continue;
            }

            $orderItem = new OrderItem();
            $orderItem->setProduct($product);
            $orderItem->setQuantity($itemData['quantity']);
            $orderItem->setPrice($product->getPrice());
            $order->addOrderItem($orderItem);
            $em->persist($orderItem);

            // Atualiza estoque
            $this->updateStock($product, $itemData['quantity'], $em);
        }

        $em->flush();

        return $this->json(['message' => 'Order created and stock updated']);
    }

    #[Route('/{id}', name: 'order_update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $order = $em->getRepository(Order::class)->find($id);
        if (!$order) {
            return $this->json(['error' => 'Order not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        // devolve estoque dos itens antigos
        foreach ($order->getOrderItems() as $oldItem) {
            $this->restoreStock($oldItem->getProduct(), $oldItem->getQuantity(), $em);
            $em->remove($oldItem);
        }

        $order->setTotalPrice($data['totalPrice'] ?? $order->getTotalPrice());

        foreach ($data['items'] as $itemData) {
            $product = $em->getRepository(Product::class)->find($itemData['product_id']);
            if (!$product) {
                continue;
            }

            $orderItem = new OrderItem();
            $orderItem->setProduct($product);
            $orderItem->setQuantity($itemData['quantity']);
            $orderItem->setPrice($product->getPrice());
            $order->addOrderItem($orderItem);
            $em->persist($orderItem);

            // Atualiza estoque
            $this->updateStock($product, $itemData['quantity'], $em);
        }

        $em->flush();

        return $this->json(['message' => 'Order updated and stock adjusted']);
    }

    #[Route('/{id}', name: 'order_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $order = $em->getRepository(Order::class)->find($id);
        if (!$order) {
            return $this->json(['error' => 'Order not found'], 404);
        }

        // devolve estoque dos itens
        foreach ($order->getOrderItems() as $item) {
            $this->restoreStock($item->getProduct(), $item->getQuantity(), $em);
            $em->remove($item);
        }

        $em->remove($order);
        $em->flush();

        return $this->json(['message' => 'Order deleted and stock restored']);
    }

    private function updateStock(Product $product, int $quantity, EntityManagerInterface $em): void
    {
        if (!$product->isHasIngredients()) {
            // baixa direto do estoque do produto
            foreach ($product->getStocks() as $stock) {
                $stock->setQuantity($stock->getQuantity() - $quantity);
                $em->persist($stock);
            }
        } else {
            // baixa ingredientes
            foreach ($product->getIngredients() as $ingredient) {
                $stock = $ingredient->getStock();
                if ($stock) {
                    $qtdSaida = $ingredient->getQuantity() * $quantity;
                    $stock->setQuantity($stock->getQuantity() - $qtdSaida);
                    $em->persist($stock);
                }
            }
        }
    }

    private function restoreStock(Product $product, int $quantity, EntityManagerInterface $em): void
    {
        if (!$product->isHasIngredients()) {
            foreach ($product->getStocks() as $stock) {
                $stock->setQuantity($stock->getQuantity() + $quantity);
                $em->persist($stock);
            }
        } else {
            foreach ($product->getIngredients() as $ingredient) {
                $stock = $ingredient->getStock();
                if ($stock) {
                    $qtdEntrada = $ingredient->getQuantity() * $quantity;
                    $stock->setQuantity($stock->getQuantity() + $qtdEntrada);
                    $em->persist($stock);
                }
            }
        }
    }
}
