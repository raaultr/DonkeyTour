<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]

#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type_service', type: 'string')]
#[ORM\DiscriminatorMap([
    'service' => Service::class,
    'despedida' => Despedida::class,
    'therapy' => Therapy::class,
    'tour' => Tour::class
])]

class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $type = null;

    #[ORM\Column]
    private ?float $basePrice = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $duration = null;

    #[ORM\Column]
    private ?int $maxAphor = null;

    #[ORM\Column(length: 20)]
    private ?string $leenguage = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $deletedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    private ?Donkey $donkey = null;

    /**
     * @var Collection<int, Reserve>
     */
    #[ORM\OneToMany(targetEntity: Reserve::class, mappedBy: 'service')]
    private Collection $reserves;

    public function __construct()
    {
        $this->reserves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getBasePrice(): ?float
    {
        return $this->basePrice;
    }

    public function setBasePrice(float $basePrice): static
    {
        $this->basePrice = $basePrice;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDuration(): ?\DateTime
    {
        return $this->duration;
    }

    public function setDuration(\DateTime $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getMaxAphor(): ?int
    {
        return $this->maxAphor;
    }

    public function setMaxAphor(int $maxAphor): static
    {
        $this->maxAphor = $maxAphor;

        return $this;
    }

    public function getLeenguage(): ?string
    {
        return $this->leenguage;
    }

    public function setLeenguage(string $leenguage): static
    {
        $this->leenguage = $leenguage;

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

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

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

    public function getDonkey(): ?Donkey
    {
        return $this->donkey;
    }

    public function setDonkey(?Donkey $donkey): static
    {
        $this->donkey = $donkey;

        return $this;
    }

    /**
     * @return Collection<int, Reserve>
     */
    public function getReserves(): Collection
    {
        return $this->reserves;
    }

    public function addReserf(Reserve $reserf): static
    {
        if (!$this->reserves->contains($reserf)) {
            $this->reserves->add($reserf);
            $reserf->setService($this);
        }

        return $this;
    }

    public function removeReserf(Reserve $reserf): static
    {
        if ($this->reserves->removeElement($reserf)) {
            // set the owning side to null (unless already changed)
            if ($reserf->getService() === $this) {
                $reserf->setService(null);
            }
        }

        return $this;
    }
}
