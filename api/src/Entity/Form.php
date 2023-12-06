<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FormRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormRepository::class)]
#[ApiResource]
class Form
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreated = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateModified = null;

    #[ORM\Column(nullable: true)]
    private ?array $JSONSchema = null;

    #[ORM\Column(nullable: true)]
    private ?array $UISchema = null;

    #[ORM\Column(nullable: true)]
    private ?array $formData = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return $this->dateModified;
    }

    public function setDateModified(\DateTimeInterface $dateModified): static
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): static
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getJSONSchema(): ?array
    {
        return $this->JSONSchema;
    }

    public function setJSONSchema(?array $JSONSchema): static
    {
        $this->JSONSchema = $JSONSchema;

        return $this;
    }

    public function getUISchema(): ?array
    {
        return $this->UISchema;
    }

    public function setUISchema(?array $UISchema): static
    {
        $this->UISchema = $UISchema;

        return $this;
    }

    public function getFormData(): ?array
    {
        return $this->formData;
    }

    public function setFormData(?array $formData): static
    {
        $this->formData = $formData;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }
}
