<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "datetime_immutable")]
    private \DateTimeImmutable $createdAt;

    // base64 string of the invoice image
    #[ORM\Column(type: "text")]
    private string $linkImageInvoice;

    #[ORM\Column(type: "string", length: 255)]
    private string $supplierName;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getLinkImageInvoice(): string
    {
        return $this->linkImageInvoice;
    }

    public function setLinkImageInvoice(string $linkImageInvoice): self
    {
        $this->linkImageInvoice = $linkImageInvoice;
        return $this;
    }

    public function getSupplierName(): string
    {
        return $this->supplierName;
    }

    public function setSupplierName(string $supplierName): self
    {
        $this->supplierName = $supplierName;
        return $this;
    }
}
