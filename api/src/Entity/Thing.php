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
        AbstractNormalizer::GROUPS => ['Thing:read:admin', 'Enum:read'],
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
    types: ['https://schema.org/Thing',],
    operations: [
        new GetCollection(
            itemUriTemplate: '/things/{id}{._format}'
        ),
        new Get(),
    ],
    normalizationContext: [
        AbstractNormalizer::GROUPS => ['Thing:read', 'Enum:read'],
        AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
    ]
)]
#[ORM\Entity(repositoryClass: ThingRepository::class)]
#[UniqueEntity(fields: ['thing'])]
#[ApiResource]
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
    #[Groups(groups: ['Thing:read', 'Thing:read:admin', 'Thingmark:read'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $name = null;

    #[ApiProperty(
        types: ['https://schema.org/dateCreated'],
        example: 'The date on which the CreativeWork was created or the item was added to a DataFeed.'
    )]
    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeInterface $dateCreated = null;

    #[ApiProperty(
        types: ['https://schema.org/dateModified'],
        example: 'The date on which the CreativeWork was most recently modified or when the items entry was modified within a DataFeed.'
    )]
    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeInterface $dateModified = null;

    #[ORM\Column]
    private array $properties = [];

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
}
