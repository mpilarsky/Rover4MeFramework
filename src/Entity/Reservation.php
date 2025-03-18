<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "reservations")]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private User $user;

    #[ORM\Column(type: "string", length: 255)]
    private string $name;

    #[ORM\Column(type: "string", length: 255)]
    private string $location;

    #[ORM\Column(type: "string", length: 50)]
    private string $frame_size;

    #[ORM\Column(type: "string", length: 10)]
    private string $theme;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $reservation_date;

    #[ORM\Column(type: "time")]
    private \DateTimeInterface $start_time;

    #[ORM\Column(type: "time")]
    private \DateTimeInterface $end_time;

    #[ORM\Column(type: "string", length: 20)]
    private string $bike_type;

    public function getId(): ?int { return $this->id; }
    public function getUser(): User { return $this->user; }
    public function setUser(User $user): self { $this->user = $user; return $this; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function getLocation(): string { return $this->location; }
    public function setLocation(string $location): self { $this->location = $location; return $this; }
    public function getFrameSize(): string { return $this->frame_size; }
    public function setFrameSize(string $frame_size): self { $this->frame_size = $frame_size; return $this; }
    public function getTheme(): string { return $this->theme; }
    public function setTheme(string $theme): self { $this->theme = $theme; return $this; }
    public function getReservationDate(): \DateTimeInterface { return $this->reservation_date; }
    public function setReservationDate(\DateTimeInterface $reservation_date): self { $this->reservation_date = $reservation_date; return $this; }
    public function getStartTime(): \DateTimeInterface { return $this->start_time; }
    public function setStartTime(\DateTimeInterface $start_time): self { $this->start_time = $start_time; return $this; }
    public function getEndTime(): \DateTimeInterface { return $this->end_time; }
    public function setEndTime(\DateTimeInterface $end_time): self { $this->end_time = $end_time; return $this; }
    public function getBikeType(): string { return $this->bike_type; }
    public function setBikeType(string $bike_type): self { $this->bike_type = $bike_type; return $this; }
}
