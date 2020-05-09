<?php

namespace App\Entity;

use App\Repository\FlightRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FlightRepository::class)
 */
class Flight
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $departurePoint;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $arrivalPoint;

    /**
     * @ORM\Column(type="datetime")
     */
    private $departureDatetime;

    /**
     * @ORM\Column(type="datetime")
     */
    private $arrivalDatetime;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $airCompany;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $flightNumber;

    /**
     * @ORM\Column(type="float")
     */
    private $cost;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tariff", mappedBy="flight", orphanRemoval=true)
     */
    private $tariffs;

    public function __construct()
    {
        $this->tariffs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeparturePoint(): ?string
    {
        return $this->departurePoint;
    }

    public function setDeparturePoint(string $departurePoint): self
    {
        $this->departurePoint = $departurePoint;

        return $this;
    }

    public function getArrivalPoint(): ?string
    {
        return $this->arrivalPoint;
    }

    public function setArrivalPoint(string $arrivalPoint): self
    {
        $this->arrivalPoint = $arrivalPoint;

        return $this;
    }

    public function getDepartureDatetime(): ?\DateTimeInterface
    {
        return $this->departureDatetime;
    }

    public function setDepartureDatetime(\DateTimeInterface $departureDatetime): self
    {
        $this->departureDatetime = $departureDatetime;

        return $this;
    }

    public function getArrivalDatetime(): ?\DateTimeInterface
    {
        return $this->arrivalDatetime;
    }

    public function setArrivalDatetime(\DateTimeInterface $arrivalDatetime): self
    {
        $this->arrivalDatetime = $arrivalDatetime;

        return $this;
    }

    public function getAirCompany(): ?string
    {
        return $this->airCompany;
    }

    public function setAirCompany(string $airCompany): self
    {
        $this->airCompany = $airCompany;

        return $this;
    }

    public function getFlightNumber(): ?string
    {
        return $this->flightNumber;
    }

    public function setFlightNumber(string $flightNumber): self
    {
        $this->flightNumber = $flightNumber;

        return $this;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(float $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * @return Collection|Tariff[]
     */
    public function getTariffs(): Collection
    {
        return $this->tariffs;
    }

    public function addtariff(Tariff $tariff): self
    {
        if (!$this->tariffs->contains($tariff)) {
            $this->tariffs[] = $tariff;
            $tariff->setDeveloper($this);
        }

        return $this;
    }

    public function removeTariff(tariff $tariff): self
    {
        if ($this->tariffs->contains($tariff)) {
            $this->tariffs->removeElement($tariff);
            // set the owning side to null (unless already changed)
            if ($tariff->getFlight() === $this) {
                $tariff->setFlight(null);
            }
        }

        return $this;
    }
}
