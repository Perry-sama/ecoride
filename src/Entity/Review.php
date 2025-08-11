<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

#[ORM\Entity(repositoryClass: "App\Repository\ReviewRepository")]
#[ORM\Table(name: "review")]
class Review
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private User $author;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private User $driver;

    #[ORM\ManyToOne(targetEntity: Trajet::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private Trajet $trip;

    #[ORM\Column(type:"text")]
    private string $content;

    #[ORM\Column(type:"string", length: 20)]
    private string $status = 'pending';

    #[ORM\Column(type:"datetime_immutable")]
    private DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    // Getters / Setters complets

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): static
    {
        $this->author = $author;
        return $this;
    }

    public function getDriver(): User
    {
        return $this->driver;
    }

    public function setDriver(User $driver): static
    {
        $this->driver = $driver;
        return $this;
    }

    public function getTrip(): Trajet
    {
        return $this->trip;
    }

    public function setTrip(Trajet $trip): static
    {
        $this->trip = $trip;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
