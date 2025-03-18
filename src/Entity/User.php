<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: "users")]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private string $email;

    #[ORM\Column(type: "string")]
    private string $password;

    #[ORM\Column(type: "string", length: 100)]
    private string $name;

    #[ORM\Column(type: "string", length: 100)]
    private string $surname;

    #[ORM\Column(type: "integer")]
    private $age;


    public function getId(): ?int { return $this->id; }
    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }
    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): self { $this->password = $password; return $this; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function getAge(): ?int { return $this->age; }
    public function getSurname(): string { return $this->surname; }
    public function setSurname(string $surname): self { $this->surname = $surname; return $this; }
    public function setAge(int $age): self { $this->age = $age; return $this; }

    public function getRoles(): array { return ['ROLE_USER']; }
    public function getUserIdentifier(): string { return $this->email; }
    public function eraseCredentials(): void {}
}
