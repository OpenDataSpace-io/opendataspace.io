<?php

declare(strict_types=1);

namespace App\DataFixtures\Factory;

use App\Entity\Thing;
use Doctrine\ORM\EntityRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ModelFactory<Thing>
 *
 * @method        Thing|Proxy                       create(array|callable $attributes = [])
 * @method static Thing|Proxy                       createOne(array $attributes = [])
 * @method static Thing|Proxy                       find(object|array|mixed $criteria)
 * @method static Thing|Proxy                       findOrCreate(array $attributes)
 * @method static Thing|Proxy                       first(string $sortedField = 'id')
 * @method static Thing|Proxy                       last(string $sortedField = 'id')
 * @method static Thing|Proxy                       random(array $attributes = [])
 * @method static Thing|Proxy                       randomOrCreate(array $attributes = [])
 * @method static EntityRepository|RepositoryProxy repository()
 * @method static Thing[]|Proxy[]                   all()
 * @method static Thing[]|Proxy[]                   createMany(int $number, array|callable $attributes = [])
 * @method static Thing[]|Proxy[]                   createSequence(iterable|callable $sequence)
 * @method static Thing[]|Proxy[]                   findBy(array $attributes)
 * @method static Thing[]|Proxy[]                   randomRange(int $min, int $max, array $attributes = [])
 * @method static Thing[]|Proxy[]                   randomSet(int $number, array $attributes = [])
 *
 * @psalm-method        Proxy<Thing> create(array|callable $attributes = [])
 * @psalm-method static Proxy<Thing> createOne(array $attributes = [])
 * @psalm-method static Proxy<Thing> find(object|array|mixed $criteria)
 * @psalm-method static Proxy<Thing> findOrCreate(array $attributes)
 * @psalm-method static Proxy<Thing> first(string $sortedField = 'id')
 * @psalm-method static Proxy<Thing> last(string $sortedField = 'id')
 * @psalm-method static Proxy<Thing> random(array $attributes = [])
 * @psalm-method static Proxy<Thing> randomOrCreate(array $attributes = [])
 * @psalm-method static RepositoryProxy<Thing> repository()
 * @psalm-method static list<Proxy<Thing>> all()
 * @psalm-method static list<Proxy<Thing>> createMany(int $number, array|callable $attributes = [])
 * @psalm-method static list<Proxy<Thing>> createSequence(iterable|callable $sequence)
 * @psalm-method static list<Proxy<Thing>> findBy(array $attributes)
 * @psalm-method static list<Proxy<Thing>> randomRange(int $min, int $max, array $attributes = [])
 * @psalm-method static list<Proxy<Thing>> randomSet(int $number, array $attributes = [])
 */
final class ThingFactory extends ModelFactory
{
    private array $data;

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     */
    public function __construct()
    {
        parent::__construct();

        //$this->data = json_decode(file_get_contents(__DIR__.'/../things.json'), true);
        //shuffle($this->data);
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function getDefaults(): array
    {
        $name = 'Thing';
        // TODO: id Entity = Properties @id
        $id = Uuid::v4();
        $data = [
            '@context' => 'https://schema.org/',
            '@type' => 'Thing',
            '@id' => '/things/'.$id,
            'name' => $name,
            'description' => self::faker()->text(),
            "url" => self::faker()->url(),
            "logo" => self::faker()->imageUrl(),
            "image" => self::faker()->imageUrl(),
            "telephone" => self::faker()->phoneNumber(),
            "email" => self::faker()->email(),
            "openingHours" => "Mo,Tu,We,Th,Fr,Sa,Su 10:00-20:00",
            "openingHoursSpecification" => [
                [
                    "@type" => "OpeningHoursSpecification",
                    "validFrom" => "2023-12-24",
                    "validThrough" => "2023-12-25",
                    "opens" => "10:00",
                    "closes" => "20:00"
                ]
            ],
            "priceRange" => "££",
            "paymentAccepted" => "Cash, Credit Card",
            "geo" => [
                "@type" => "GeoCoordinates",
                "latitude" => "52.628",
                "longitude" => "1.293"
            ],
            // "latitude" => "52.628",
            // "longitude" => "1.293"
            "address" => [
                "@type" => "PostalAddress",
                "streetAddress" => "Theatre Street",
                "addressLocality" => "Norwich",
                "addressRegion"=> "Norfolk",
                "postalCode" => "NR2 1RL",
                "addressCountry" => "GB"
            ],
            "sameAs" => [
                "https://www.facebook.com/theatreroyalnorwich",
                "https://twitter.com/TheatreRNorwich",
                "https://www.youtube.com/user/TheatreRoyalNorwich",
                "https://plus.google.com/100115377993168660095",
                "https://www.instagram.com/theatreroyalnorwich/",
                "https://www.pinterest.com/theatreroyalnorwich/",
                "https://www.linkedin.com/company/theatre-royal-norwich"
            ]
        ];

        return [
            'name' => $name,
            'dateCreated' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime('-1 month')),
            'dateModified' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime('-1 month')),
            'properties' => $data
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
             ->afterInstantiate(function (Thing $thing): void {
                $name = 'Thing';
                     
                //$id = Uuid::v4();
                $id = $thing->getId();

                $thing->setName($name);
                $thing->setDateCreated(\DateTimeImmutable::createFromMutable(self::faker()->dateTime('-1 month')),);
                $thing->setDateModified(\DateTimeImmutable::createFromMutable(self::faker()->dateTime('-1 month')),);
                $data = [
                    '@context' => 'https://schema.org/',
                    '@type' => 'Thing',
                    '@id' => '/things/'.$id,
                    'name' => $name,
                    'description' => self::faker()->text(),
                    "url" => self::faker()->url(),
                    "logo" => self::faker()->imageUrl(),
                    "image" => self::faker()->imageUrl(),
                    "telephone" => self::faker()->phoneNumber(),
                    "email" => self::faker()->email(),
                    "openingHours" => "Mo,Tu,We,Th,Fr,Sa,Su 10:00-20:00",
                    "openingHoursSpecification" => [
                        [
                            "@type" => "OpeningHoursSpecification",
                            "validFrom" => "2023-12-24",
                            "validThrough" => "2023-12-25",
                            "opens" => "10:00",
                            "closes" => "20:00"
                        ]
                    ],
                    "priceRange" => "££",
                    "paymentAccepted" => "Cash, Credit Card",
                    "geo" => [
                        "@type" => "GeoCoordinates",
                        "latitude" => "52.628",
                        "longitude" => "1.293"
                    ],
                    "address" => [
                        "@type" => "PostalAddress",
                        "streetAddress" => "Theatre Street",
                        "addressLocality" => "Norwich",
                        "addressRegion"=> "Norfolk",
                        "postalCode" => "NR2 1RL",
                        "addressCountry" => "GB"
                    ],
                    "sameAs" => [
                        "https://www.facebook.com/theatreroyalnorwich",
                        "https://twitter.com/TheatreRNorwich",
                        "https://www.youtube.com/user/TheatreRoyalNorwich",
                        "https://plus.google.com/100115377993168660095",
                        "https://www.instagram.com/theatreroyalnorwich/",
                        "https://www.pinterest.com/theatreroyalnorwich/",
                        "https://www.linkedin.com/company/theatre-royal-norwich"
                    ]
                ];
                $thing->setProperties($data);

                return;
             })
        ;
    }

    protected static function getClass(): string
    {
        return Thing::class;
    }
}
