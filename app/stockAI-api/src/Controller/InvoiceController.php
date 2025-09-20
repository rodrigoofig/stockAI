<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/invoices')]
class InvoiceController extends AbstractController
{
    #[Route('', name: 'invoice_index', methods: ['GET'])]
    public function index(InvoiceRepository $invoiceRepository): JsonResponse
    {
        $invoices = $invoiceRepository->findAll();

        $data = [];
        foreach ($invoices as $invoice) {
            $data[] = [
                'id' => $invoice->getId(),
                'createdAt' => $invoice->getCreatedAt()->format('Y-m-d H:i:s'),
                'linkImageInvoice' => $invoice->getLinkImageInvoice(),
                'supplierName' => $invoice->getSupplierName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/{id}', name:'invoice_show', methods: ['GET'])]
    public function show(Invoice $invoice): JsonResponse
    {
        return $this->json([
            'id' => $invoice->getId(),
            'createdAt' => $invoice->getCreatedAt()->format('Y-m-d H:i:s'),
            'linkImageInvoice' => $invoice->getLinkImageInvoice(),
            'supplierName' => $invoice->getSupplierName(),
        ]);
    }   

    #[Route('', name: 'invoice_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['linkImageInvoice'], $data['supplierName'])) {
            return $this->json(['error' => 'Campos obrigatórios: linkImageInvoice, supplierName'], 400);
        }

        $invoice = new Invoice();
        $invoice->setLinkImageInvoice($data['linkImageInvoice']);
        $invoice->setSupplierName($data['supplierName']);

        $em->persist($invoice);
        $em->flush();

        return $this->json([
            'id' => $invoice->getId(),
            'createdAt' => $invoice->getCreatedAt()->format('Y-m-d H:i:s'),
            'linkImageInvoice' => $invoice->getLinkImageInvoice(),
            'supplierName' => $invoice->getSupplierName(),
        ], 201);
    }
}
