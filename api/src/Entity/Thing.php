<?php

namespace App\Entity;

use App\Repository\ThingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Doctrine\Common\Filter\SearchFilterInterface;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use App\State\Processor\ThingCreateProcessor;
use App\State\Processor\ThingUpdateProcessor;
use App\State\Processor\ThingRemoveProcessor;
use App\State\Processor\ThingPersistProcessor;

#[ApiResource(
    uriTemplate: '/admin/things{._format}',
    types: ['https://schema.org/Thing'],
    operations: [
        new GetCollection(
            itemUriTemplate: '/admin/things/{id}{._format}',
            paginationClientItemsPerPage: true
        ),
        new Post(
            // Mercure publish is done manually in MercureProcessor through BookPersistProcessor
            processor: ThingPersistProcessor::class,
            itemUriTemplate: '/admin/things/{id}{._format}'
        ),
        new Get(
            uriTemplate: '/admin/things/{id}{._format}'
        ),
        // https://github.com/api-platform/admin/issues/370
        new Put(
            uriTemplate: '/admin/things/{id}{._format}',
            // Mercure publish is done manually in MercureProcessor through BookPersistProcessor
            processor: ThingPersistProcessor::class
        ),
        new Delete(
            uriTemplate: '/admin/things/{id}{._format}',
            // Mercure publish is done manually in MercureProcessor through BookRemoveProcessor
            processor: ThingRemoveProcessor::class
        ),
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => ['Thing:read:admin'],
        AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => ['Thing:write'],
    ],
    // todo waiting for https://github.com/api-platform/core/pull/5844
    //    collectDenormalizationErrors: true,
    security: 'is_granted("ROLE_ADMIN")'
)]
#[ApiResource(
    uriTemplate: '/dashboard/things{._format}',
    types: ['https://schema.org/Thing'],
    operations: [
        new GetCollection(
            itemUriTemplate: '/dashboard/things/{id}{._format}',
            paginationClientItemsPerPage: true
        ),
        new Post(
            // Mercure publish is done manually in MercureProcessor through BookPersistProcessor
            processor: ThingPersistProcessor::class,
            itemUriTemplate: '/dashboard/things/{id}{._format}'
        ),
        new Get(
            uriTemplate: '/dashboard/things/{id}{._format}'
        ),
        // https://github.com/api-platform/admin/issues/370
        new Put(
            uriTemplate: '/dashboard/things/{id}{._format}',
            // Mercure publish is done manually in MercureProcessor through BookPersistProcessor
            processor: ThingPersistProcessor::class
        ),
        new Delete(
            uriTemplate: '/dashboard/things/{id}{._format}',
            // Mercure publish is done manually in MercureProcessor through BookRemoveProcessor
            processor: ThingRemoveProcessor::class
        ),
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => ['Thing:read:app'],
        AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => ['Thing:write'],
    ],
    // todo waiting for https://github.com/api-platform/core/pull/5844
//    collectDenormalizationErrors: true,
    //security: 'is_granted("ROLE_USER")'
)]
#[ApiResource(
    types: ['https://schema.org/Thing',],
    operations: [
        new GetCollection(
            itemUriTemplate: '/things/{id}{._format}'
        ),
        new Get(
            uriTemplate: '/things/{id}{._format}'
        ),
        // TODO: remove if bug fixed with token
        new Post(
            processor: ThingCreateProcessor::class,
            itemUriTemplate: '/things/{id}{._format}',
            security: 'is_granted("ROLE_USER")'
        ),
        new Put(
            uriTemplate: '/things/{id}{._format}',
            processor: ThingUpdateProcessor::class,
            security: 'is_granted("ROLE_USER")'
        ),
        new Delete(
            uriTemplate: '/things/{id}{._format}',
            processor: ThingRemoveProcessor::class,
            security: 'is_granted("ROLE_ADMIN")'
        ),
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => ['Thing:read', 'Enum:read'],
        AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
    ],
    denormalizationContext: [
        AbstractNormalizer::GROUPS => ['Thing:write'],
    ],
)]
#[ORM\Entity(repositoryClass: ThingRepository::class)]
class Thing
{
    /**
     * @see https://schema.org/identifier
     */
    #[ApiProperty(identifier: true, types: ['https://schema.org/identifier'])]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Id]
    private ?Uuid $id = null;

    /**
     * @see https://schema.org/name
     */
    #[ApiFilter(OrderFilter::class)]
    #[ApiFilter(SearchFilter::class, strategy: 'i'.SearchFilterInterface::STRATEGY_PARTIAL)]
    #[ApiProperty(
        types: ['https://schema.org/name'],
        example: 'Hyperion'
    )]
    #[Groups(groups: ['Thing:read', 'Thing:write', 'Thing:read:admin'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $name = null;

    #[ApiProperty(
        types: ['https://schema.org/dateCreated'],
        example: '2022-01-01T00:00:00Z'
    )]
    #[ApiFilter(OrderFilter::class)]
    #[ApiFilter(SearchFilter::class, strategy: 'i'.SearchFilterInterface::STRATEGY_PARTIAL)]
    #[Groups(groups: ['Thing:read', 'Thing:read:admin'])]
    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeInterface $dateCreated = null;

    #[ApiProperty(
        types: ['https://schema.org/dateModified'],
        example: '2022-01-01T00:00:00Z'
    )]
    #[ApiFilter(OrderFilter::class)]
    #[ApiFilter(SearchFilter::class, strategy: 'i'.SearchFilterInterface::STRATEGY_PARTIAL)]
    #[Groups(groups: ['Thing:read', 'Thing:read:admin'])]
    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeInterface $dateModified = null;

    #[ApiProperty]
    #[Groups(groups: ['Thing:read', 'Thing:write', 'Thing:read:admin'])]
    #[ORM\Column]
    private array $properties = [];

    private $iri;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): static
    {
        $this->id = $id;

        return $this;
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

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): static
    {
        $this->dateCreated = $dateCreated;

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

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function setProperties(array $properties): static
    {
        $this->properties = $properties;

        return $this;
    }

    public function getIri(): ?string
    {
        return $this->iri;
    }

    public function setIri(?string $iri): self
    {
        $this->iri = $iri;

        return $this;
    }
}
