<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item
{
    public const perPage = 5;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Please Enter Product Title.")
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @Assert\NotBlank(message="Please Enter Product Content.")
     * @ORM\Column(type="text", length=65535)
     */
    private $content;


    // /**
    //  * @ORM\Column(type="string", length=255)
    //  */
    // private $price;

    // /**
    //  * @ORM\ManyToOne(targetEntity=Size::class, inversedBy="items" , cascade={"persist"})
    //  */
    // private $sizes;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="items")
     */
    private $category;

    /**
     * @Assert\NotBlank(message="Please Enter Size & Quantity Attributes.")
     * @ORM\OneToMany(targetEntity=SizeQtyAttributes::class, mappedBy="item", cascade={"persist"}, fetch="EAGER")
     */
    private $sizeQtyAttr;

    /**
     * @ORM\OneToMany(targetEntity=OrderProduct::class, mappedBy="item")
     */
    private $orderProducts;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stock_flag;

    // /**
    //  * @ORM\Column(type="string", length=255)
    //  */
    // private $new_price;


    public function __construct()
    {
        $this->sizeQtyAttr = new ArrayCollection();
        $this->orderProducts = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }


    // public function getPrice(): ?string
    // {
    //     return $this->price;
    // }

    // public function setPrice(string $price): self
    // {
    //     $this->price = $price;

    //     return $this;
    // }

    public function getSizes(): ?Size
    {
        return $this->sizes;
    }

    public function setSizes(?Size $sizes): self
    {
        $this->sizes = $sizes;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|SizeQtyAttributes[]
     */
    public function getSizeQtyAttr(): Collection
    {
        return $this->sizeQtyAttr;
    }

    public function addSizeQtyAttr(SizeQtyAttributes $sizeQtyAttr): self
    {
        if (!$this->sizeQtyAttr->contains($sizeQtyAttr)) {
            $this->sizeQtyAttr[] = $sizeQtyAttr;
            $sizeQtyAttr->setItem($this);
        }

        return $this;
    }

    public function removeSizeQtyAttr(SizeQtyAttributes $sizeQtyAttr): self
    {
        if ($this->sizeQtyAttr->removeElement($sizeQtyAttr)) {
            // set the owning side to null (unless already changed)
            if ($sizeQtyAttr->getItem() === $this) {
                $sizeQtyAttr->setItem(null);
            }
        }

        return $this;
    }

    public function getStockFlag(): ?bool
    {
        return $this->stock_flag;
    }

    public function setStockFlag(bool $stock_flag): self
    {
        $this->stock_flag = $stock_flag;

        return $this;
    }

    // public function getNewPrice(): ?string
    // {
    //     return $this->new_price;
    // }

    // public function setNewPrice(string $new_price): self
    // {
    //     $this->new_price = $new_price;

    //     return $this;
    // }



}
