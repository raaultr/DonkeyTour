<?php

namespace App\Entity;

use App\Repository\TourRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TourRepository::class)]
class Tour extends Service
{
    #[ORM\Column(length: 30)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $itinerary = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $details = null;

    #[ORM\Column]
    private ?int $stops = null;

    #[ORM\Column]
    private ?bool $audioExplanation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getItinerary(): ?string
    {
        return $this->itinerary;
    }

    public function setItinerary(string $itinerary): static
    {
        $this->itinerary = $itinerary;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): static
    {
        $this->details = $details;

        return $this;
    }

    public function getStops(): ?int
    {
        return $this->stops;
    }

    public function setStops(int $stops): static
    {
        $this->stops = $stops;

        return $this;
    }

    public function isAudioExplanation(): ?bool
    {
        return $this->audioExplanation;
    }

    public function setAudioExplanation(bool $audioExplanation): static
    {
        $this->audioExplanation = $audioExplanation;

        return $this;
    }
}
