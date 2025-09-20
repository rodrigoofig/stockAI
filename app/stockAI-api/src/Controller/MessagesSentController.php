<?php

namespace App\Controller;

use App\Entity\MessagesSent;
use App\Repository\MessagesSentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/messages')]
class MessagesSentController extends AbstractController
{
    #[Route('', name: 'messages_list', methods: ['GET'])]
    public function listAll(MessagesSentRepository $messagesSentRepository): JsonResponse
    {
        $messages = $messagesSentRepository->findAll();

        $data = [];
        foreach ($messages as $msg) {
            $data[] = [
                'id' => $msg->getId(),
                'title' => $msg->getTitle(),
                'supplierName' => $msg->getSupplierName(),
                'html' => $msg->getHtml(),
                'recipient' => $msg->getRecipient(),
                'createdAt' => $msg->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        return $this->json($data);
    }

    #[Route('/{id}', name: 'messages_show', methods: ['GET'])]
    public function show(MessagesSentRepository $messagesSentRepository, int $id): JsonResponse
    {
        $msg = $messagesSentRepository->find($id);

        if (!$msg) {
            return $this->json(['error' => 'Mensagem não encontrada'], 404);
        }

        return $this->json([
            'id' => $msg->getId(),
            'title' => $msg->getTitle(),
            'supplierName' => $msg->getSupplierName(),
            'recipient' => $msg->getRecipient(),
            'html' => $msg->getHtml(),
            'createdAt' => $msg->getCreatedAt()->format('Y-m-d H:i:s'),
        ]);
    }

    #[Route('', name: 'messages_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['title'], $data['recipient'], $data['html'],$data['supplierName'])) {
            return $this->json(['error' => 'Campos obrigatórios: title, recipient, html, supplierName'], 400);
        }

        $message = new MessagesSent();
        $message->setTitle($data['title']);
        $message->setRecipient($data['recipient']);
        $message->setHtml($data['html']);
        $message->setSupplierName($data['supplierName']);

        $em->persist($message);
        $em->flush();

        return $this->json([
            'id' => $message->getId(),
            'title' => $message->getTitle(),
            'supplierName' => $message->getSupplierName(),
            'recipient' => $message->getRecipient(),
            'createdAt' => $message->getCreatedAt()->format('Y-m-d H:i:s'),
        ], 201);
    }
}
