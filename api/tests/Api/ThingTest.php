<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\DataFixtures\Factory\ThingFactory;
use App\DataFixtures\Story\ThingStory;
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
        // Cannot use Factory as data provider because ThingFactory has a service dependency
        //$factory->create();
        //ThingFactory::createMany(35);

        $response = $this->client->request('GET', $url);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
            'hydra:totalItems' => $hydraTotalItems,
        ]);
        self::assertCount(min($hydraTotalItems, 30), $response->toArray()['hydra:member']);

        $collectionJson = $response->toArray();
        $items = $collectionJson['hydra:member'];
        foreach ($items as $item) {
            self::assertArrayHasKey('@id', $item);
            self::assertArrayHasKey('@type', $item);
            self::assertArrayHasKey('name', $item);
        }
        
    }

    public function getUrls(): iterable
    {
        yield 'all things' => [
            //ThingFactory::new()->many(35),
            ThingFactory::createMany(35),
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
            '/things?name=Thing',
            1,
        ];
    }

    public function testAsAdminUserICanGetACollectionOfThingsOrderedByName(): void
    {
        ThingFactory::createOne(['name' => 'Thing AC']);
        ThingFactory::createOne(['name' => 'Thing AB']);
        ThingFactory::createOne(['name' => 'Thing AA']);

        $response = $this->client->request('GET', '/things?order[name]=asc');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertEquals('Thing', $response->toArray()['hydra:member'][0]['name']);
        //self::assertEquals('AC Thing Hyperion', $response->toArray()['hydra:member'][1]['name']);
        //self::assertEquals('AB Thing The Wandering Earth', $response->toArray()['hydra:member'][2]['name']);
    }

    public function testAsAnonymousICanGetAThing(): void
    {
        $thing = ThingFactory::createOne();

        $this->client->request('GET', '/things/'.$thing->getId());

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
            '@id' => '/things/'.$thing->getId(),
            //'name' => $thing->getName(),
        ]);
    }
}
