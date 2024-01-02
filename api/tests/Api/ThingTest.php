<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\DataFixtures\Factory\ThingFactory;
use Symfony\Component\HttpFoundation\Response;
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
     *
     * @test
     */
    /*public function asAnonymousICanGetACollectionOfThings(FactoryCollection $factory, string $url, int $hydraTotalItems): void
    {
        // Cannot use Factory as data provider because ThingsFactory has a service dependency
        $factory->create();

        $response = $this->client->request('GET', $url);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
            'hydra:totalItems' => $hydraTotalItems,
        ]);
        self::assertCount(min($hydraTotalItems, 30), $response->toArray()['hydra:member']);
    }*/

    public static function getUrls(): iterable
    {
        yield 'all things' => [
            ThingFactory::new()->many(35),
            '/things',
            35,
        ];
        yield 'things filtered by name' => [
            ThingFactory::new()->sequence(static function () {
                yield ['name' => 'eiger'];
                foreach (range(1, 10) as $i) {
                    yield [];
                }
            }),
            '/things?name=eiger',
            1,
        ];
    }

    /**
     * @test
     */
    /*public function asAdminUserICanGetACollectionOfThingsOrderedByName(): void
    {
        ThingFactory::createOne(['name' => 'Hyperion']);
        ThingFactory::createOne(['name' => 'The Wandering Earth']);
        ThingFactory::createOne(['name' => 'Ball Lightning']);

        $response = $this->client->request('GET', '/things?order[name]=asc');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertEquals('Ball Lightning', $response->toArray()['hydra:member'][0]['title']);
        self::assertEquals('Hyperion', $response->toArray()['hydra:member'][1]['title']);
        self::assertEquals('The Wandering Earth', $response->toArray()['hydra:member'][2]['title']);
    }*/

    /**
     * @test
     */
    public function asAnonymousICannotGetAnInvalidThing(): void
    {
        ThingFactory::createOne();

        $this->client->request('GET', '/things/invalid');

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * @test
     */
    public function asAnonymousICanGetAThing(): void
    {
        $thing = ThingFactory::createOne();

        $this->client->request('GET', '/things/' . $thing->getId());

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
            '@id' => '/things/' . $thing->getId(),
            //'name' => $thing->getName(),
        ]);
    }
}
