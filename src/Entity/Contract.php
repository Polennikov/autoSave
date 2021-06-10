<?php

namespace App\Entity;

use App\Repository\ContractRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Model\ContractDto;
/**
 * @ORM\Entity(repositoryClass=ContractRepository::class)
 */
class Contract
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
    private $date_start;

    /**
     * @ORM\Column(type="date")
     */
    private $date_end;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $purpose;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $agent_id;

    /**
     * @ORM\ManyToOne(targetEntity=auto::class, inversedBy="contracts")
     */
    private $auto;

    /**
     * @ORM\Column(type="date")
     */
    private $date_start_one;

    /**
     * @ORM\Column(type="date")
     */
    private $date_end_one;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_start_two;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_end_two;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_start_three;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_end_three;

    /**
     * @ORM\OneToMany(targetEntity=RelationDriver::class, mappedBy="contracts")
     */
    private $relationDrivers;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $diagnostic_card;

    /**
     * @ORM\Column(type="boolean")
     */
    private $non_limited;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $driver_one;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $driver_two;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $driver_three;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $driver_four;

    /**
     * @ORM\Column(type="boolean", length=255)
     */
    private $period_two;

    /**
     * @ORM\Column(type="boolean", length=255)
     */
    private $period_three;

    /**
     * @ORM\Column(type="boolean")
     */
    private $trailer;

    public function __construct()
    {
        $this->relationDrivers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTimeInterface $date_start): self
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeInterface $date_end): self
    {
        $this->date_end = $date_end;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPurpose(): ?string
    {
        return $this->purpose;
    }

    public function setPurpose(string $purpose): self
    {
        $this->purpose = $purpose;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAgentId(): ?string
    {
        return $this->agent_id;
    }

    public function setAgentId(string $agent_id): self
    {
        $this->agent_id = $agent_id;

        return $this;
    }

    public function getAuto(): ?auto
    {
        return $this->auto;
    }

    public function setAuto(?auto $auto): self
    {
        $this->auto = $auto;

        return $this;
    }

    public function getDateStartOne(): ?\DateTimeInterface
    {
        return $this->date_start_one;
    }

    public function setDateStartOne(\DateTimeInterface $date_start_one): self
    {
        $this->date_start_one = $date_start_one;

        return $this;
    }

    public function getDateEndOne(): ?\DateTimeInterface
    {
        return $this->date_end_one;
    }

    public function setDateEndOne(\DateTimeInterface $date_end_one): self
    {
        $this->date_end_one = $date_end_one;

        return $this;
    }

    public function getDateStartTwo(): ?\DateTimeInterface
    {
        return $this->date_start_two;
    }

    public function setDateStartTwo(?\DateTimeInterface $date_start_two): self
    {
        $this->date_start_two = $date_start_two;

        return $this;
    }

    public function getDateEndTwo(): ?\DateTimeInterface
    {
        return $this->date_end_two;
    }

    public function setDateEndTwo(?\DateTimeInterface $date_end_two): self
    {
        $this->date_end_two = $date_end_two;

        return $this;
    }

    public function getDateStartThree(): ?\DateTimeInterface
    {
        return $this->date_start_three;
    }

    public function setDateStartThree(?\DateTimeInterface $date_start_three): self
    {
        $this->date_start_three = $date_start_three;

        return $this;
    }

    public function getDateEndThree(): ?\DateTimeInterface
    {
        return $this->date_end_three;
    }

    public function setDateEndThree(?\DateTimeInterface $date_end_three): self
    {
        $this->date_end_three = $date_end_three;

        return $this;
    }

   /* public static function fromDto(ContractDto $contractDto,Auto $auto): self
    {
        $contract= new self();
        $contract->setDateStart(new \DateTime($contractDto->date_start));
        $contract->setDateEnd(new \DateTime($contractDto->date_end));
        $contract->setDateStartOne(new \DateTime($contractDto->date_start_one));
        $contract->setDateEndOne(new \DateTime($contractDto->date_end_one));

        if($contractDto->date_start_two!='null'){
            $contract->setDateStartTwo(new \DateTime($contractDto->date_start_two));
            $contract->setDateEndTwo(new \DateTime($contractDto->date_end_two));
        }
        if($contractDto->date_start_three!='null'){
            $contract->setDateStartThree(new \DateTime($contractDto->date_start_three));
            $contract->setDateEndThree(new \DateTime($contractDto->date_end_three));
        }

        $contract->setAmount($contractDto->amount);
        $contract->setPurpose($contractDto->purpose);
        $contract->setStatus($contractDto->status);
        $contract->setAuto($auto);
        $contract->setDiagnosticCard($contractDto->diagnostic_card);
        $contract->setNonLimited($contractDto->non_limited);
        $contract->setAgentId($contractDto->agent_id);

        return $contract;
    }*/

    /**
     * @return Collection|RelationDriver[]
     */
    public function getRelationDrivers(): Collection
    {
        return $this->relationDrivers;
    }

    public function addRelationDriver(RelationDriver $relationDriver): self
    {
        if (!$this->relationDrivers->contains($relationDriver)) {
            $this->relationDrivers[] = $relationDriver;
            $relationDriver->setContracts($this);
        }

        return $this;
    }

    public function removeRelationDriver(RelationDriver $relationDriver): self
    {
        if ($this->relationDrivers->removeElement($relationDriver)) {
            // set the owning side to null (unless already changed)
            if ($relationDriver->getContracts() === $this) {
                $relationDriver->setContracts(null);
            }
        }

        return $this;
    }

    public function getDiagnosticCard(): ?string
    {
        return $this->diagnostic_card;
    }

    public function setDiagnosticCard(string $diagnostic_card): self
    {
        $this->diagnostic_card = $diagnostic_card;

        return $this;
    }

    public function getNonLimited(): ?bool
    {
        return $this->non_limited;
    }

    public function setNonLimited(bool $non_limited): self
    {
        $this->non_limited = $non_limited;

        return $this;
    }

    public function getPeriodTwo(): ?bool
    {
        return $this->period_two;
    }

    public function setPeriodTwo(bool $period_two): self
    {
        $this->period_two = $period_two;

        return $this;
    }
    public function getPeriodThree(): ?bool
    {
        return $this->period_three;
    }

    public function setPeriodThree(bool $period_three): self
    {
        $this->period_three = $period_three;

        return $this;
    }

    public function getDriverOne(): ?string
    {
        return $this->driver_one;
    }

    public function setDriverOne(string $driver_one): self
    {
        $this->driver_one = $driver_one;

        return $this;
    }

    public function getDriverTwo(): ?string
    {
        return $this->driver_two;
    }

    public function setDriverTwo(string $driver_two): self
    {
        $this->driver_two = $driver_two;

        return $this;
    }

    public function getDriverThree(): ?string
    {
        return $this->driver_three;
    }

    public function setDriverThree(string $driver_three): self
    {
        $this->driver_three = $driver_three;

        return $this;
    }

    public function getDriverFour(): ?string
    {
        return $this->driver_four;
    }

    public function setDriverFour(string $driver_four): self
    {
        $this->driver_four = $driver_four;

        return $this;
    }

    public function getTrailer(): ?bool
    {
        return $this->trailer;
    }

    public function setTrailer(bool $trailer): self
    {
        $this->trailer = $trailer;

        return $this;
    }
}
