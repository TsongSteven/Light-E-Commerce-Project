<?php

namespace App\Entity;

use App\Repository\OrderProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderProductRepository::class)
 */
class OrderProduct
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
    private $size;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $item_ids;

    /**
     * @ORM\ManyToOne(targetEntity=GuestUser::class, inversedBy="orderproduct")
     */
    private $guestUser;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class, inversedBy="orderProducts")
     */
    private $item;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $product_no;



    public function __construct()
    {

        $this->guestUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }


    public function getItemIds(): ?string
    {
        return $this->item_ids;
    }

    public function setItemIds(string $item_ids): self
    {
        $this->item_ids = $item_ids;

        return $this;
    }

    public function getGuestUser(): ?GuestUser
    {
        return $this->guestUser;
    }

    public function setGuestUser(?GuestUser $guestUser): self
    {
        $this->guestUser = $guestUser;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getProductNo(): ?string
    {
        return $this->product_no;
    }

    public function setProductNo(string $product_no): self
    {
        $this->product_no = $product_no;

        return $this;
    }
}
