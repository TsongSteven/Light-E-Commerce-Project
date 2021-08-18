<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="subcategory")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="parent")
     */
    private $subcategory;

    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="category")
     */
    private $items;

    /**
     * @ORM\OneToMany(targetEntity=FrontCategory::class, mappedBy="category")
     */
    private $frontCategories;

    /**
     * @ORM\OneToMany(targetEntity=TopProd::class, mappedBy="category")
     */
    private $topProds;

    public function __construct()
    {
        $this->subcategory = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->frontCategories = new ArrayCollection();
        $this->topProds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getSubcategory(): Collection
    {
        return $this->subcategory;
    }

    public function addSubcategory(self $subcategory): self
    {
        if (!$this->subcategory->contains($subcategory)) {
            $this->subcategory[] = $subcategory;
            $subcategory->setParent($this);
        }

        return $this;
    }

    public function removeSubcategory(self $subcategory): self
    {
        if ($this->subcategory->removeElement($subcategory)) {
            // set the owning side to null (unless already changed)
            if ($subcategory->getParent() === $this) {
                $subcategory->setParent(null);
            }
        }

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
            $item->setCategory($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getCategory() === $this) {
                $item->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FrontCategory[]
     */
    public function getFrontCategories(): Collection
    {
        return $this->frontCategories;
    }

    public function addFrontCategory(FrontCategory $frontCategory): self
    {
        if (!$this->frontCategories->contains($frontCategory)) {
            $this->frontCategories[] = $frontCategory;
            $frontCategory->setCategory($this);
        }

        return $this;
    }

    public function removeFrontCategory(FrontCategory $frontCategory): self
    {
        if ($this->frontCategories->removeElement($frontCategory)) {
            // set the owning side to null (unless already changed)
            if ($frontCategory->getCategory() === $this) {
                $frontCategory->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TopProd[]
     */
    public function getTopProds(): Collection
    {
        return $this->topProds;
    }

    public function addTopProd(TopProd $topProd): self
    {
        if (!$this->topProds->contains($topProd)) {
            $this->topProds[] = $topProd;
            $topProd->setCategory($this);
        }

        return $this;
    }

    public function removeTopProd(TopProd $topProd): self
    {
        if ($this->topProds->removeElement($topProd)) {
            // set the owning side to null (unless already changed)
            if ($topProd->getCategory() === $this) {
                $topProd->setCategory(null);
            }
        }

        return $this;
    }
}
