<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee extends User
{
    #[ORM\Column]
    private ?int $socialSecurity = null;

    /**
     * @var Collection<int, Reserve>
     */
    #[ORM\OneToMany(targetEntity: Reserve::class, mappedBy: 'employee')]
    private Collection $reserves;

    public function __construct()
    {
        $this->reserves = new ArrayCollection();
    }

    public function getSocialSecurity(): ?int
    {
        return $this->socialSecurity;
    }

    public function setSocialSecurity(int $socialSecurity): static
    {
        $this->socialSecurity = $socialSecurity;

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
            $reserf->setEmployee($this);
        }

        return $this;
    }

    public function removeReserf(Reserve $reserf): static
    {
        if ($this->reserves->removeElement($reserf)) {
            // set the owning side to null (unless already changed)
            if ($reserf->getEmployee() === $this) {
                $reserf->setEmployee(null);
            }
        }

        return $this;
    }
}
