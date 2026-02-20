<?php

namespace App\Entity;

use App\Repository\ReserveRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReserveRepository::class)]
class Reserve
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $reserveDate = null;

    #[ORM\Column]
    private ?bool $state = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $details = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $deletedAt = null;

    #[ORM\ManyToOne(inversedBy: 'reserve')]
    private ?DonkeyReserve $donkeyReserve = null;

    #[ORM\ManyToOne(inversedBy: 'reserve')]
    private ?ClientReserve $clientReserve = null;

    #[ORM\OneToOne(inversedBy: 'reserve', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pay $pay = null;

    #[ORM\ManyToOne(inversedBy: 'reserves')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Service $service = null;

    #[ORM\ManyToOne(inversedBy: 'reserves')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employee $employee = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReserveDate(): ?\DateTime
    {
        return $this->reserveDate;
    }

    public function setReserveDate(\DateTime $reserveDate): static
    {
        $this->reserveDate = $reserveDate;

        return $this;
    }

    public function isState(): ?bool
    {
        return $this->state;
    }

    public function setState(bool $state): static
    {
        $this->state = $state;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTime $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getDonkeyReserve(): ?DonkeyReserve
    {
        return $this->donkeyReserve;
    }

    public function setDonkeyReserve(?DonkeyReserve $donkeyReserve): static
    {
        $this->donkeyReserve = $donkeyReserve;

        return $this;
    }

    public function getClientReserve(): ?ClientReserve
    {
        return $this->clientReserve;
    }

    public function setClientReserve(?ClientReserve $clientReserve): static
    {
        $this->clientReserve = $clientReserve;

        return $this;
    }

    public function getPay(): ?Pay
    {
        return $this->pay;
    }

    public function setPay(Pay $pay): static
    {
        $this->pay = $pay;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): static
    {
        $this->service = $service;

        return $this;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }
}
