<?php

namespace App\Entity;

use App\Repository\SizeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SizeRepository::class)
 */
class Size
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean",  nullable=true)
     */
    private $s;

    /**
     * @ORM\Column(type="boolean",  nullable=true)
     */
    private $m;

    /**
     * @ORM\Column(type="boolean",   nullable=true)
     */
    private $l;

    /**
     * @ORM\Column(type="boolean",  nullable=true)
     */
    private $xl;

    /**
     * @ORM\Column(type="boolean",  nullable=true)
     */
    private $xxl;

    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="sizes")
     */
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getS(): ?bool
    {
        return $this->s;
    }

    public function setS(bool $s): self
    {
        $this->s = $s;

        return $this;
    }

    public function getM(): ?bool
    {
        return $this->m;
    }

    public function setM(bool $m): self
    {
        $this->m = $m;

        return $this;
    }

    public function getL(): ?bool
    {
        return $this->l;
    }

    public function setL(bool $l): self
    {
        $this->l = $l;

        return $this;
    }

    public function getXl(): ?bool
    {
        return $this->xl;
    }

    public function setXl(bool $xl): self
    {
        $this->xl = $xl;

        return $this;
    }

    public function getXxl(): ?bool
    {
        return $this->xxl;
    }

    public function setXxl(bool $xxl): self
    {
        $this->xxl = $xxl;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setSizes($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getSizes() === $this) {
                $item->setSizes(null);
            }
        }

        return $this;
    }

}
