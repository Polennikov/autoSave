<?php

namespace App\Entity;

use App\Repository\RelationDriverRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RelationDriverRepository::class)
 */
class RelationDriver
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="relationDrivers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=Contract::class, inversedBy="relationDrivers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contracts;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function getContracts(): ?Contract
    {
        return $this->contracts;
    }

    public function setContracts(?Contract $contracts): self
    {
        $this->contracts = $contracts;

        return $this;
    }

    public static function fromDto(User $user, Contract $contract): self
    {
        $relationDriver= new self();
        $relationDriver->setUsers($user);
        $relationDriver->setContracts($contract);

        return $relationDriver;
    }
}
