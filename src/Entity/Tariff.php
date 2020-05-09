<?php

namespace App\Entity;

use App\Repository\TariffRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TariffRepository::class)
 */
class Tariff
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $refundable;

    /**
     * @ORM\Column(type="boolean")
     */
    private $exchangeable;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $baggage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Flight", inversedBy="tariffs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $flight;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRefundable(): ?bool
    {
        return $this->refundable;
    }

    public function setRefundable(bool $refundable): self
    {
        $this->refundable = $refundable;

        return $this;
    }

    public function getExchangeable(): ?bool
    {
        return $this->exchangeable;
    }

    public function setExchangeable(bool $exchangeable): self
    {
        $this->exchangeable = $exchangeable;

        return $this;
    }

    public function getBaggage(): ?string
    {
        return $this->baggage;
    }

    public function setBaggage(string $baggage): self
    {
        $this->baggage = $baggage;

        return $this;
    }

    public function getFlight(): ?Flight
    {
        return $this->flight;
    }

    public function setFlight(?Flight $flight): self
    {
        $this->flight = $flight;

        return $this;
    }
}
