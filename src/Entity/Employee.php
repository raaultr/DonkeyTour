<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee extends User
{
    #[ORM\Column]
    private ?int $socialSecurity = null;

    public function getSocialSecurity(): ?int
    {
        return $this->socialSecurity;
    }

    public function setSocialSecurity(int $socialSecurity): static
    {
        $this->socialSecurity = $socialSecurity;

        return $this;
    }
}
