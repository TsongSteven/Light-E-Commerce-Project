<?php

namespace App\Entity;

use App\Repository\SizeQtyAttributesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SizeQtyAttributesRepository::class)
 */
class SizeQtyAttributes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please Enter Size/Quantity Attribute.") 
     */
    private $attrName;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class, inversedBy="SizeQtyAttr")
     */
    private $item;

    /**
     * @Assert\NotBlank(message="Please Enter Old Price.")     
     * @ORM\Column(type="string", length=255)
     */
    private $old_price;

    /**
     * @Assert\NotBlank(message="Please Enter New Price.")
     * @ORM\Column(type="string", length=255)
     */
    private $new_price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttrName(): ?string
    {
        return $this->attrName;
    }

    public function setAttrName(string $attrName): self
    {
        $this->attrName = $attrName;

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

    public function getOldPrice(): ?string
    {
        return $this->old_price;
    }

    public function setOldPrice(string $old_price): self
    {
        $this->old_price = $old_price;

        return $this;
    }

    public function getNewPrice(): ?string
    {
        return $this->new_price;
    }

    public function setNewPrice(string $new_price): self
    {
        $this->new_price = $new_price;

        return $this;
    }
}
