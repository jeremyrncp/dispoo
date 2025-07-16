<?php

namespace App\Entity;

use App\Repository\TimeslotRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimeslotRepository::class)]
class Timeslot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $days = [];

    #[ORM\Column]
    private ?\DateTime $startTime = null;

    #[ORM\Column]
    private ?\DateTime $endTime = null;

    #[ORM\Column(nullable: true)]
    private ?int $numberAppointments = null;

    #[ORM\Column]
    private ?int $delayBetweenAppointments = null;

    #[ORM\ManyToOne(inversedBy: 'timeslots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDays(): array
    {
        return $this->days;
    }

    public function setDays(array $days): static
    {
        $this->days = $days;

        return $this;
    }

    public function getStartTime(): ?\DateTime
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTime $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTime
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTime $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getNumberAppointments(): ?int
    {
        return $this->numberAppointments;
    }

    public function setNumberAppointments(?int $numberAppointments): static
    {
        $this->numberAppointments = $numberAppointments;

        return $this;
    }

    public function getDelayBetweenAppointments(): ?int
    {
        return $this->delayBetweenAppointments;
    }

    public function setDelayBetweenAppointments(int $delayBetweenAppointments): static
    {
        $this->delayBetweenAppointments = $delayBetweenAppointments;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
