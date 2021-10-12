<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="reservations")
     * @ORM\JoinColumn(nullable=true)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Commande", inversedBy="reservations")
     * @ORM\JoinColumn(nullable=true)
     */
    private $commande;

    public function __construct(Product $product)
    {
        $this->creationDate = new \DateTime("now");
        $this->setProduct($product);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }


    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function restituteStock(): self
    {
        if($this->product){
        $this->product->setStock($this->getProduct()->getStock()+$this->quantity);
                            //setStock($product->getStock()+$reservation->getQuantity());
        $this->setQuantity(0);
    }
        return $this;
    }

    
    public function decrementeStock(int $quantity): self   //  to do !!!!!!!!!!!!
    {
        $this->getProduct()->setStock($this->getProduct()->getStock()+$quantity);
                            //setStock($product->getStock()+$reservation->getQuantity());
        $this->setQuantity(0);
        return $this;
    }

    
}
