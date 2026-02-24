<?php

namespace App\Entity;

use App\Repository\DonkeyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DonkeyRepository::class)]
class Donkey
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $nombre = null;

    #[ORM\Column(nullable: true)]
    private ?int $years = null;

    #[ORM\Column(length: 50)]
    private ?string $race = null;

    #[ORM\Column]
    private ?float $kilogram = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $deletedAt = null;

    #[ORM\Column]
    private ?bool $disponible = null;

    #[ORM\Column]
    private ?float $maxWeightr = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoUrl = null;

    #[ORM\ManyToOne(inversedBy: 'donkeys')]
    #[ORM\JoinColumn(nullable: true)] // CAMBIADO A TRUE para que no de error al crear burros nuevos
    private ?DonkeyReserve $reserve = null;

    /**
     * @var Collection<int, Service>
     */
    #[ORM\OneToMany(targetEntity: Service::class, mappedBy: 'donkey')]
    private Collection $services;

    public function __construct()
    {
        $this->services = new ArrayCollection();
        // Inicialización automática
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTime();
        $this->disponible = true; // Por defecto disponible
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getYears(): ?int
    {
        return $this->years;
    }

    public function setYears(?int $years): static
    {
        $this->years = $years;
        return $this;
    }

    public function getRace(): ?string
    {
        return $this->race;
    }

    public function setRace(string $race): static
    {
        $this->race = $race;
        return $this;
    }

    public function getKilogram(): ?float
    {
        return $this->kilogram;
    }

    public function setKilogram(float $kilogram): static
    {
        $this->kilogram = $kilogram;
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

    public function isDisponible(): ?bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): static
    {
        $this->disponible = $disponible;
        return $this;
    }

    public function getMaxWeightr(): ?float
    {
        return $this->maxWeightr;
    }

    public function setMaxWeightr(float $maxWeightr): static
    {
        $this->maxWeightr = $maxWeightr;
        return $this;
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photoUrl;
    }

    public function setPhotoUrl(?string $photoUrl): static
    {
        $this->photoUrl = $photoUrl;
        return $this;
    }

    public function getReserve(): ?DonkeyReserve
    {
        return $this->reserve;
    }

    public function setReserve(?DonkeyReserve $reserve): static
    {
        $this->reserve = $reserve;
        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setDonkey($this);
        }
        return $this;
    }

    public function removeService(Service $service): static
    {
        if ($this->services->removeElement($service)) {
            if ($service->getDonkey() === $this) {
                $service->setDonkey(null);
            }
        }
        return $this;
    }
}