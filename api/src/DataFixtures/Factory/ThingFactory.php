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

        $this->data = json_decode(file_get_contents(__DIR__.'/../things.json'), true);
        shuffle($this->data);
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function getDefaults(): array
    {
        $data = $this->data[array_rand($this->data)];

        return [
            'name' => $data['name'],
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
            ->published() // published by default
            ->instantiateWith(function (array $attributes) {
                return new Thing(); // custom instantiation for this factory
            })
            ->afterPersist(function () {}) // default event for this factory
        ;
    }

    protected static function getClass(): string
    {
        return Thing::class;
    }
}