<?php

namespace App\Entity;

use App\Repository\DespedidaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DespedidaRepository::class)]
class Despedida extends Service
{
    #[ORM\Column(length: 30)]
    private ?string $tematica = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $details = null;

    #[ORM\Column(length: 30)]
    private ?string $place = null;


    public function getTematica(): ?string
    {
        return $this->tematica;
    }

    public function setTematica(string $tematica): static
    {
        $this->tematica = $tematica;

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

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): static
    {
        $this->place = $place;

        return $this;
    }
}
