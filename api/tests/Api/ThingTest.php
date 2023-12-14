<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\DataFixtures\Factory\ThingFactory;
use Zenstruck\Foundry\FactoryCollection;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class ThingTest extends ApiTestCase
{
    use Factories;
    use ResetDatabase;

    private Client $client;

    protected function setup(): void
    {
        $this->client = self::createClient();
    }

    /**
     * @dataProvider getUrls
     */
    public function testAsAnonymousICanGetACollectionOfThings(FactoryCollection $factory, string $url, int $hydraTotalItems): void
    {
        // Cannot use Factory as data provider because BookFactory has a service dependency
        $factory->create();

        $response = $this->client->request('GET', $url);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
            'hydra:totalItems' => $hydraTotalItems,
        ]);
        self::assertCount(min($hydraTotalItems, 30), $response->toArray()['hydra:member']);
        self::assertMatchesJsonSchema(file_get_contents(__DIR__.'/schemas/Book/collection.json'));
    }

    public function getUrls(): iterable
    {
        yield 'all things' => [
            ThingFactory::new()->many(35),
            '/things',
            35,
        ];
        yield 'things filtered by name' => [
            ThingFactory::new()->sequence(function () {
                yield ['name' => 'Thing'];
                foreach (range(1, 10) as $i) {
                    yield [];
                }
            }),
            '/books?title=yperio',
            1,
        ];
    }

    public function testAsAdminUserICanGetACollectionOfThingsOrderedByName(): void
    {
        ThingFactory::createOne(['name' => 'Thing Hyperion']);
        ThingFactory::createOne(['name' => 'Thing The Wandering Earth']);
        ThingFactory::createOne(['name' => 'Thing Ball Lightning']);

        $response = $this->client->request('GET', '/things?order[name]=asc');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertEquals('Thing Ball Lightning', $response->toArray()['hydra:member'][0]['name']);
        self::assertEquals('Thing Hyperion', $response->toArray()['hydra:member'][1]['name']);
        self::assertEquals('Thing The Wandering Earth', $response->toArray()['hydra:member'][2]['name']);
        self::assertMatchesJsonSchema(file_get_contents(__DIR__.'/schemas/Thing/collection.json'));
    }

    public function testAsAnonymousICanGetAThing(): void
    {
        $thing = ThingFactory::createOne();

        $this->client->request('GET', '/things/'.$thing->getId());

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
            '@id' => '/thing/'.$thing->getId(),
            'name' => $thing->getName(),
        ]);
        self::assertMatchesJsonSchema(file_get_contents(__DIR__.'/schemas/Thing/item.json'));
    }
}
