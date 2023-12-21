<?php

declare(strict_types=1);

namespace App\DataFixtures\Factory;

use App\Entity\Form;
use App\Repository\FormRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Form>
 *
 * @method        Form|Proxy                     create(array|callable $attributes = [])
 * @method static Form|Proxy                     createOne(array $attributes = [])
 * @method static Form|Proxy                     find(object|array|mixed $criteria)
 * @method static Form|Proxy                     findOrCreate(array $attributes)
 * @method static Form|Proxy                     first(string $sortedField = 'id')
 * @method static Form|Proxy                     last(string $sortedField = 'id')
 * @method static Form|Proxy                     random(array $attributes = [])
 * @method static Form|Proxy                     randomOrCreate(array $attributes = [])
 * @method static FormRepository|RepositoryProxy repository()
 * @method static Form[]|Proxy[]                 all()
 * @method static Form[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Form[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Form[]|Proxy[]                 findBy(array $attributes)
 * @method static Form[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Form[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 *
 * @psalm-method        Proxy<Form> create(array|callable $attributes = [])
 * @psalm-method static Proxy<Form> createOne(array $attributes = [])
 * @psalm-method static Proxy<Form> find(object|array|mixed $criteria)
 * @psalm-method static Proxy<Form> findOrCreate(array $attributes)
 * @psalm-method static Proxy<Form> first(string $sortedField = 'id')
 * @psalm-method static Proxy<Form> last(string $sortedField = 'id')
 * @psalm-method static Proxy<Form> random(array $attributes = [])
 * @psalm-method static Proxy<Form> randomOrCreate(array $attributes = [])
 * @psalm-method static RepositoryProxy<Form> repository()
 * @psalm-method static list<Proxy<Form>> all()
 * @psalm-method static list<Proxy<Form>> createMany(int $number, array|callable $attributes = [])
 * @psalm-method static list<Proxy<Form>> createSequence(iterable|callable $sequence)
 * @psalm-method static list<Proxy<Form>> findBy(array $attributes)
 * @psalm-method static list<Proxy<Form>> randomRange(int $min, int $max, array $attributes = [])
 * @psalm-method static list<Proxy<Form>> randomSet(int $number, array $attributes = [])
 */
final class FormFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'code' => self::faker()->word(1),
            'dateCreated' => self::faker()->dateTime(),
            'dateModified' => self::faker()->dateTime(),
            'name' => self::faker()->text(20),
            'jsonSchema' => [
                'title' => self::faker()->text(20),
                'description' => self::faker()->text(20),
                'type' => 'object',
                'properties' => [
                    'name' => [
                        'type' => 'string',
                        'minLength' => 3,
                        'maxLength' => 20,
                    ],
                    'price' => [
                        'type' => 'integer',
                        'minimum' => 0,
                    ],
                    'currency' => [
                        'type' => 'string',
                        'enum' => [
                            'CHF',
                            'EUR',
                            'USD',
                        ],
                    ],
                ],
                'required' => [
                    'name',
                    'price',
                    'currency',
                ],
            ],
            'uiSchema' => [
                'name' => [
                    'ui:autofocus' => true,
                    'ui:emptyValue' => '',
                ],
                'price' => [
                    'ui:widget' => 'updown',
                    'ui:title' => 'Price',
                    'ui:description' => 'Price of Product',
                ],
                'currency' => [
                    'ui:widget' => 'radio',
                    'ui:title' => 'Currency',
                    'ui:description' => 'Currency of Price',
                ],
            ],
            'formData' => [
                'name' => self::faker()->text(20),
                'price' => self::faker()->numberBetween(0, 100),
                'currency' => self::faker()->randomElement(['CHF', 'EUR', 'USD']),
            ],
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Form $form): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Form::class;
    }
}