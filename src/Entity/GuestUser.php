<?php

namespace App\Entity;

use App\Repository\GuestUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GuestUserRepository::class)
 */
class GuestUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Please Enter  Name")
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotBlank(message="Please Enter Email")
     * @Assert\Email(message="Please Enter Valid Email")
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @Assert\NotBlank(message="Please Enter Phone No")
     * @ORM\Column(type="bigint")
     */
    private $phone;

    /**
     * @Assert\NotBlank(message="Please Enter Address")
     * @Assert\Length(min =5, max = 40, minMessage="Address should not be less than 5 characters", maxMessage="Address should not be more than 40 characters")
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pincode;

    /**
     * @ORM\OneToOne(targetEntity=CheckOutDetails::class, cascade={"persist", "remove"})
     */
    private $payment;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $order_confirm;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $OrderStatus;

    /**
     * @ORM\OneToMany(targetEntity=OrderProduct::class, mappedBy="guestUser", cascade={"persist", "remove"})
     */
    private $orderproduct;

    // /**
    //  * @ORM\Column(type="boolean")
    //  */
    // private $flag;

    public function __construct()
    {
        $this->orderproduct = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getaddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPincode(): ?string
    {
        return $this->pincode;
    }

    public function setPincode(string $pincode): self
    {
        $this->pincode = $pincode;

        return $this;
    }

    public function getPayment(): ?CheckOutDetails
    {
        return $this->payment;
    }

    public function setPayment(?CheckOutDetails $payment): self
    {
        $this->payment = $payment;

        return $this;
    }


    public function getOrderConfirm(): ?bool
    {
        return $this->order_confirm;
    }

    public function setOrderConfirm(?bool $order_confirm): self
    {
        $this->order_confirm = $order_confirm;

        return $this;
    }

    public function getOrderStatus(): ?string
    {
        return $this->OrderStatus;
    }

    public function setOrderStatus(string $OrderStatus): self
    {
        $this->OrderStatus = $OrderStatus;

        return $this;
    }

    /**
     * @return Collection|OrderProduct[]
     */
    public function getOrderproduct(): Collection
    {
        return $this->orderproduct;
    }

    public function addOrderproduct(OrderProduct $orderproduct): self
    {
        if (!$this->orderproduct->contains($orderproduct)) {
            $this->orderproduct[] = $orderproduct;
            $orderproduct->setGuestUser($this);
        }

        return $this;
    }

    public function removeOrderproduct(OrderProduct $orderproduct): self
    {
        if ($this->orderproduct->removeElement($orderproduct)) {
            // set the owning side to null (unless already changed)
            if ($orderproduct->getGuestUser() === $this) {
                $orderproduct->setGuestUser(null);
            }
        }

        return $this;
    }

    // public function getFlag(): ?bool
    // {
    //     return $this->flag;
    // }

    // public function setFlag(bool $flag): self
    // {
    //     $this->flag = $flag;

    //     return $this;
    // }
}
