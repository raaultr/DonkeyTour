<?php

namespace App\Entity;

use App\Repository\TherapyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TherapyRepository::class)]
class Therapy extends Service
{
    #[ORM\Column(length: 30)]
    private ?string $place = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $details = null;

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): static
    {
        $this->place = $place;

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
}
