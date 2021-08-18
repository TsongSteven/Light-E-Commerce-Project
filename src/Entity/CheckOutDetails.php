<?php

namespace App\Entity;

use App\Repository\CheckOutDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CheckOutDetailsRepository::class)
 */
class CheckOutDetails
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,  nullable=true)
     */
    private $razorpay_id;

    /**
     * @ORM\Column(type="string", length=255,  nullable=true)
     */
    private $order_id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $payment_done;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentDone(): ?bool
    {
        return $this->payment_done;
    }

    public function setPaymentDone(bool $payment_done): self
    {
        $this->payment_done = $payment_done;

        return $this;
    }
}
