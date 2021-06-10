<?php

namespace App\Entity;

use App\Repository\DtpRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Model\DtpDto;

/**
 * @ORM\Entity(repositoryClass=DtpRepository::class)
 */
class Dtp
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date_dtp;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adress_dtp;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $degree;

    /**
     * @ORM\Column(type="boolean")
     */
    private $initiator;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="dtps")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=Auto::class, inversedBy="dtps")
     */
    private $autos;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDtp(): ?\DateTimeInterface
    {
        return $this->date_dtp;
    }

    public function setDateDtp(\DateTimeInterface $date_dtp): self
    {
        $this->date_dtp = $date_dtp;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAdressDtp(): ?string
    {
        return $this->adress_dtp;
    }

    public function setAdressDtp(string $adress_dtp): self
    {
        $this->adress_dtp = $adress_dtp;

        return $this;
    }

    public function getDegree(): ?string
    {
        return $this->degree;
    }

    public function setDegree(string $degree): self
    {
        $this->degree = $degree;

        return $this;
    }

    public function getInitiator(): ?bool
    {
        return $this->initiator;
    }

    public function setInitiator(bool $initiator): self
    {
        $this->initiator = $initiator;

        return $this;
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

    public function getAutos(): ?Auto
    {
        return $this->autos;
    }

    public function setAutos(?Auto $autos): self
    {
        $this->autos = $autos;

        return $this;
    }

    public static function fromDto(DtpDto $dtpDto, User $users, Auto $autos): self
    {
        $dtp= new self();
        $dtp->setDateDtp(new \DateTime($dtpDto->date_dtp));
        $dtp->setDescription($dtpDto->description);
        $dtp->setAdressDtp($dtpDto->adress_dtp);
        $dtp->setDegree($dtpDto->degree);
        $dtp->setInitiator($dtpDto->initiator);
        $dtp->setUsers($users);
        $dtp->setAutos($autos);

        return $dtp;
    }
}
