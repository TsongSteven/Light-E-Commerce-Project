<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvoiceRepository::class)
 */
class Invoice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $invoice_file;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInoivceFile(): ?CheckOutDetails
    {
        return $this->inoivce_file;
    }

    public function setInoivceFile(?CheckOutDetails $inoivce_file): self
    {
        $this->inoivce_file = $inoivce_file;

        return $this;
    }

    public function getInvoiceFile(): ?string
    {
        return $this->invoice_file;
    }

    public function setInvoiceFile(string $invoice_file): self
    {
        $this->invoice_file = $invoice_file;

        return $this;
    }
}
