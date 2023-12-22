<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\DataFixtures\Factory\ThingFactory;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\FactoryCollection;
use Zenstruck\Foundry\Test\Factories;

final class ThingTest extends ApiTestCase
{
    use Factories;

    private Client $client;

    protected function setup(): void
    {
        $this->client = self::createClient();
    }

    #[DataProvider('getUrls')]
    #[Test]
    public function asAnonymousICanGetACollectionOfThings(FactoryCollection $factory, string $url, int $hydraTotalItems): void
    {
        // Cannot use Factory as data provider because ThingFactory has a service dependency
        $factory->create();

        $response = $this->client->request('GET', $url);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
            'hydra:totalItems' => $hydraTotalItems,
        ]);
        self::assertCount(min($hydraTotalItems, 30), $response->toArray()['hydra:member']);
    }

    public static function getUrls(): iterable
    {
        yield 'all things' => [
            ThingFactory::new()->many(35),
            '/things',
            35,
        ];
        yield 'things filtered by name' => [
            ThingFactory::new()->sequence(static function () {
                yield ['name' => 'Hyperion'];
                foreach (range(1, 10) as $i) {
                    yield [];
                }
            }),
            '/thing?name=yperio',
            1,
        ];
    }

    #[Test]
    public function asAnonymousICannotGetAnInvalidThing(): void
    {
        ThingFactory::createOne();

        $this->client->request('GET', '/thing/invalid');

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function asAnonymousICanGetAThing(): void
    {
        $thing = ThingFactory::createOne();

        $this->client->request('GET', '/things/' . $thing->getId());

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
            '@id' => '/thing/' . $thing->getId(),
            'name' => $thing->getName(),
        ]);
    }
}
