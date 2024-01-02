<?php

declare(strict_types=1);

namespace App\Tests\Api\Admin;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\DataFixtures\Factory\ThingFactory;
use App\DataFixtures\Factory\UserFactory;
use App\Entity\Thing;
use App\Repository\ThingRepository;
use App\Tests\Api\Admin\Trait\UsersDataProviderTrait;
use App\Tests\Api\Trait\SecurityTrait;
use App\Tests\Api\Trait\SerializerTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Update;
use Zenstruck\Foundry\FactoryCollection;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class ThingTest extends ApiTestCase
{
    use Factories;
    use ResetDatabase;
    use SecurityTrait;
    use SerializerTrait;
    use UsersDataProviderTrait;

    private Client $client;

    protected function setup(): void
    {
        $this->client = self::createClient();
    }

    /**
     * @dataProvider getNonAdminUsers
     *
     * @test
     */
    public function asNonAdminUserICannotGetACollectionOfThings(int $expectedCode, string $hydraDescription, ?UserFactory $userFactory): void
    {
        $options = [];
        if ($userFactory) {
            $token = $this->generateToken([
                'email' => $userFactory->create()->email,
            ]);
            $options['auth_bearer'] = $token;
        }

        $this->client->request('GET', '/admin/things', $options);

        self::assertResponseStatusCodeSame($expectedCode);
        self::assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');
        self::assertResponseHeaderSame('link', '<http://www.w3.org/ns/hydra/error>; rel="http://www.w3.org/ns/json-ld#error",<http://localhost/docs.jsonld>; rel="http://www.w3.org/ns/hydra/core#apiDocumentation"');
        self::assertJsonContains([
            '@type' => 'hydra:Error',
            'hydra:title' => 'An error occurred',
            'hydra:description' => $hydraDescription,
        ]);
    }

    /**
     * @dataProvider getUrls
     *
     * @test
     */
    /*
    public function asAdminUserICanGetACollectionOfThings(FactoryCollection $factory, string $url, int $hydraTotalItems, int $itemsPerPage = null): void
    {
        // Cannot use Factory as data provider because ThingFactory has a service dependency
        $factory->create();

        $token = $this->generateToken([
            'email' => UserFactory::createOneAdmin()->email,
        ]);

        $response = $this->client->request('GET', $url, ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
            'hydra:totalItems' => $hydraTotalItems,
        ]);
        self::assertCount(min($itemsPerPage ?? $hydraTotalItems, 30), $response->toArray()['hydra:member']);
        //self::assertMatchesJsonSchema(file_get_contents(__DIR__ . '/schemas/Book/collection.json'));
    }*/

    public static function getUrls(): iterable
    {
        yield 'all things' => [
            ThingFactory::new()->many(35),
            '/admin/things',
            35,
        ];
        yield 'all things using itemsPerPage' => [
            ThingFactory::new()->many(35),
            '/admin/things?itemsPerPage=10',
            35,
            10,
        ];
        yield 'things filtered by name' => [
            ThingFactory::new()->sequence(static function () {
                yield ['name' => 'Eiger'];
                foreach (range(1, 10) as $i) {
                    yield [];
                }
            }),
            '/admin/things?title=eiger',
            1,
        ];
    }

    /**
     * @test
     */
    public function asAdminUserICanGetACollectionOfThingsOrderedByName(): void
    {
        ThingFactory::createOne(['name' => 'Eiger']);
        ThingFactory::createOne(['name' => 'Hotel am See']);
        ThingFactory::createOne(['name' => 'Strandhotel Hüsli']);

        $token = $this->generateToken([
            'email' => UserFactory::createOneAdmin()->email,
        ]);

        $response = $this->client->request('GET', '/admin/things?order[name]=asc', ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertEquals('Eiger', $response->toArray()['hydra:member'][0]['name']);
        self::assertEquals('Hotel am See', $response->toArray()['hydra:member'][1]['name']);
        self::assertEquals('The Wandering Earth', $response->toArray()['hydra:member'][2]['name']);
        //self::assertMatchesJsonSchema(file_get_contents(__DIR__ . '/schemas/Book/collection.json'));
    }

    /**
     * @dataProvider getAllUsers
     *
     * @test
     */
    public function asAnyUserICannotGetAnInvalidThing(?UserFactory $userFactory): void
    {
        ThingFactory::createOne();

        $options = [];
        if ($userFactory) {
            $token = $this->generateToken([
                'email' => $userFactory->create()->email,
            ]);
            $options['auth_bearer'] = $token;
        }

        $this->client->request('GET', '/admin/things/invalid', $options);

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public static function getAllUsers(): iterable
    {
        yield [null];
        yield [UserFactory::new()];
        yield [UserFactory::new(['roles' => ['ROLE_ADMIN']])];
    }

    /**
     * @dataProvider getNonAdminUsers
     *
     * @test
     */
    public function asNonAdminUserICannotGetAThing(int $expectedCode, string $hydraDescription, ?UserFactory $userFactory): void
    {
        $thing = ThingFactory::createOne();

        $options = [];
        if ($userFactory) {
            $token = $this->generateToken([
                'email' => $userFactory->create()->email,
            ]);
            $options['auth_bearer'] = $token;
        }

        $this->client->request('GET', '/admin/things/' . $thing->getId(), $options);

        self::assertResponseStatusCodeSame($expectedCode);
        self::assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');
        self::assertResponseHeaderSame('link', '<http://www.w3.org/ns/hydra/error>; rel="http://www.w3.org/ns/json-ld#error",<http://localhost/docs.jsonld>; rel="http://www.w3.org/ns/hydra/core#apiDocumentation"');
        self::assertJsonContains([
            '@type' => 'hydra:Error',
            'hydra:title' => 'An error occurred',
            'hydra:description' => $hydraDescription,
        ]);
    }

    /**
     * @dataProvider getNonAdminUsers
     *
     * @test
     */
    public function asAdminUserICanGetAThing(): void
    {
        $thing = ThingFactory::createOne();

        $token = $this->generateToken([
            'email' => UserFactory::createOneAdmin()->email,
        ]);

        $this->client->request('GET', '/admin/things/' . $thing->getId(), ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
            '@id' => '/admin/things/' . $thing->getId(),
        ]);
        //self::assertMatchesJsonSchema(file_get_contents(__DIR__ . '/schemas/Book/item.json'));
    }

    /**
     * @dataProvider getNonAdminUsers
     *
     * @test
     */
    public function asNonAdminUserICannotCreateAThing(int $expectedCode, string $hydraDescription, ?UserFactory $userFactory): void
    {
        $options = [];
        if ($userFactory) {
            $token = $this->generateToken([
                'email' => $userFactory->create()->email,
            ]);
            $options['auth_bearer'] = $token;
        }

        $this->client->request('POST', '/admin/things', $options + [
            'json' => [
                'name' => 'Things'
            ],
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json',
            ],
        ]);

        self::assertResponseStatusCodeSame($expectedCode);
        self::assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');
        self::assertResponseHeaderSame('link', '<http://www.w3.org/ns/hydra/error>; rel="http://www.w3.org/ns/json-ld#error",<http://localhost/docs.jsonld>; rel="http://www.w3.org/ns/hydra/core#apiDocumentation"');
        self::assertJsonContains([
            '@type' => 'hydra:Error',
            'hydra:title' => 'An error occurred',
            'hydra:description' => $hydraDescription,
        ]);
    }

    /**
     * @dataProvider getInvalidDataOnCreate
     *
     * @test
     */
    public function asAdminUserICannotCreateAThingWithInvalidData(array $data, int $statusCode, array $expected): void
    {
        $token = $this->generateToken([
            'email' => UserFactory::createOneAdmin()->email,
        ]);

        $this->client->request('POST', '/admin/things', [
            'auth_bearer' => $token,
            'json' => $data,
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json',
            ],
        ]);

        self::assertResponseStatusCodeSame($statusCode);
        self::assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');
        self::assertResponseHeaderSame('link', '<http://www.w3.org/ns/hydra/error>; rel="http://www.w3.org/ns/json-ld#error",<http://localhost/docs.jsonld>; rel="http://www.w3.org/ns/hydra/core#apiDocumentation"');
        self::assertJsonContains($expected);
    }

    public function getInvalidDataOnCreate(): iterable
    {
        yield 'no data' => [
            [],
            Response::HTTP_UNPROCESSABLE_ENTITY,
            [
                '@type' => 'ConstraintViolationList',
                'hydra:title' => 'An error occurred',
                'violations' => [
                    [
                        'propertyPath' => 'name',
                        'message' => 'This value should not be blank.',
                    ]
                ],
            ],
        ];
        yield from $this->getInvalidData();
    }

    public static function getInvalidData(): iterable
    {
        yield 'empty data' => [
            [
                'name' => '',
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY,
            [
                '@type' => 'ConstraintViolationList',
                'hydra:title' => 'An error occurred',
                'hydra:description' => 'Name is empty',
                'violations' => [
                    [
                        'propertyPath' => 'name',
                        'hint' => 'The data must be a string.',
                    ],
                ],
            ],
        ];
    }

    /**
     * @group apiCall
     * @group mercure
     *
     * @test
     */
    public function asAdminUserICanCreateAThing(): void
    {
        $token = $this->generateToken([
            'email' => UserFactory::createOneAdmin()->email,
        ]);

        $response = $this->client->request('POST', '/admin/things', [
            'auth_bearer' => $token,
            'json' => [
                'name' => 'Thing',
            ],
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json',
            ],
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
            'name' => 'Thing',
        ]);
        //self::assertMatchesJsonSchema(file_get_contents(__DIR__ . '/schemas/Book/item.json'));
        $id = preg_replace('/^.*\/(.+)$/', '$1', $response->toArray()['@id']);
        /** @var Thing $thing */
        $thing = self::getContainer()->get(ThingRepository::class)->find($id);
        self::assertCount(2, self::getMercureMessages());
        self::assertEquals(
            new Update(
                topics: ['http://localhost/admin/things/' . $thing->getId()],
                data: self::serialize(
                    $thing,
                    'jsonld',
                    self::getOperationNormalizationContext(Thing::class, '/admin/things/{id}{._format}')
                ),
            ),
            self::getMercureMessage()
        );
        self::assertEquals(
            new Update(
                topics: ['http://localhost/things/' . $thing->getId()],
                data: self::serialize(
                    $thing,
                    'jsonld',
                    self::getOperationNormalizationContext(Thing::class, '/things/{id}{._format}')
                ),
            ),
            self::getMercureMessage(1)
        );
    }

    /**
     * @dataProvider getNonAdminUsers
     *
     * @test
     */
    public function asNonAdminUserICannotUpdateThing(int $expectedCode, string $hydraDescription, ?UserFactory $userFactory): void
    {
        $thing = ThingFactory::createOne();

        $options = [];
        if ($userFactory) {
            $token = $this->generateToken([
                'email' => $userFactory->create()->email,
            ]);
            $options['auth_bearer'] = $token;
        }

        $this->client->request('PUT', '/admin/things/' . $thing->getId(), $options + [
            'json' => [
                'name' => 'Thing',
            ],
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json',
            ],
        ]);

        self::assertResponseStatusCodeSame($expectedCode);
        self::assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');
        self::assertResponseHeaderSame('link', '<http://www.w3.org/ns/hydra/error>; rel="http://www.w3.org/ns/json-ld#error",<http://localhost/docs.jsonld>; rel="http://www.w3.org/ns/hydra/core#apiDocumentation"');
        self::assertJsonContains([
            '@type' => 'hydra:Error',
            'hydra:title' => 'An error occurred',
            'hydra:description' => $hydraDescription,
        ]);
    }

    /**
     * @test
     */
    public function asAdminUserICannotUpdateAnInvalidThing(): void
    {
        ThingFactory::createOne();

        $token = $this->generateToken([
            'email' => UserFactory::createOneAdmin()->email,
        ]);

        $this->client->request('PUT', '/admin/things/invalid', [
            'auth_bearer' => $token,
            'json' => [
                'name' => 'Invalid Thing',
            ],
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json',
            ],
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * @dataProvider getInvalidData
     *
     * @test
     */
    public function asAdminUserICannotUpdateAThingWithInvalidData(array $data, int $statusCode, array $expected): void
    {
        $thing = ThingFactory::createOne();

        $token = $this->generateToken([
            'email' => UserFactory::createOneAdmin()->email,
        ]);

        $this->client->request('PUT', '/admin/things/' . $thing->getId(), [
            'auth_bearer' => $token,
            'json' => $data,
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json',
            ],
        ]);

        self::assertResponseStatusCodeSame($statusCode);
        self::assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');
        self::assertResponseHeaderSame('link', '<http://www.w3.org/ns/hydra/error>; rel="http://www.w3.org/ns/json-ld#error",<http://localhost/docs.jsonld>; rel="http://www.w3.org/ns/hydra/core#apiDocumentation"');
        self::assertJsonContains($expected);
    }

    /**
     * @group apiCall
     * @group mercure
     *
     * @test
     */
    public function asAdminUserICanUpdateAThing(): void
    {
        $thing = ThingFactory::createOne([
            'name' => 'Update Thing',
        ]);
        self::getMercureHub()->reset();

        $token = $this->generateToken([
            'email' => UserFactory::createOneAdmin()->email,
        ]);

        $this->client->request('PUT', '/admin/things/' . $thing->getId(), [
            'auth_bearer' => $token,
            'json' => [
                '@id' => '/things/' . $thing->getId(),
                // Must set all data because of standard PUT
                'name' => 'Thing',
            ],
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json',
            ],
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
            '@id' => '/things/' . $thing->getId(),
            'name' => 'Thing',
        ]);
        //self::assertMatchesJsonSchema(file_get_contents(__DIR__ . '/schemas/Book/item.json'));
        self::assertCount(2, self::getMercureMessages());
        self::assertEquals(
            new Update(
                topics: ['http://localhost/admin/things/' . $thing->getId()],
                data: self::serialize(
                    $thing->object(),
                    'jsonld',
                    self::getOperationNormalizationContext(Thing::class, '/admin/things/{id}{._format}')
                ),
            ),
            self::getMercureMessage()
        );
        self::assertEquals(
            new Update(
                topics: ['http://localhost/things/' . $thing->getId()],
                data: self::serialize(
                    $thing->object(),
                    'jsonld',
                    self::getOperationNormalizationContext(Thing::class, '/things/{id}{._format}')
                ),
            ),
            self::getMercureMessage(1)
        );
    }

    /**
     * @dataProvider getNonAdminUsers
     *
     * @test
     */
    public function asNonAdminUserICannotDeleteAThing(int $expectedCode, string $hydraDescription, ?UserFactory $userFactory): void
    {
        $thing = ThingFactory::createOne();

        $options = [];
        if ($userFactory) {
            $token = $this->generateToken([
                'email' => $userFactory->create()->email,
            ]);
            $options['auth_bearer'] = $token;
        }

        $this->client->request('DELETE', '/admin/things/' . $thing->getId(), $options);

        self::assertResponseStatusCodeSame($expectedCode);
        self::assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');
        self::assertResponseHeaderSame('link', '<http://www.w3.org/ns/hydra/error>; rel="http://www.w3.org/ns/json-ld#error",<http://localhost/docs.jsonld>; rel="http://www.w3.org/ns/hydra/core#apiDocumentation"');
        self::assertJsonContains([
            '@type' => 'hydra:Error',
            'hydra:title' => 'An error occurred',
            'hydra:description' => $hydraDescription,
        ]);
    }

    /**
     * @test
     */
    public function asAdminUserICannotDeleteAnInvalidThing(): void
    {
        ThingFactory::createOne();

        $token = $this->generateToken([
            'email' => UserFactory::createOneAdmin()->email,
        ]);

        $this->client->request('DELETE', '/admin/things/invalid', ['auth_bearer' => $token]);

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * @group mercure
     *
     * @test
     */
    public function asAdminUserICanDeleteAThing(): void
    {
        $thing = ThingFactory::createOne(['name' => 'Eiger']);
        self::getMercureHub()->reset();
        $id = $thing->getId();

        $token = $this->generateToken([
            'email' => UserFactory::createOneAdmin()->email,
        ]);

        $response = $this->client->request('DELETE', '/admin/things/' . $id, ['auth_bearer' => $token]);

        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
        self::assertEmpty($response->getContent());
        ThingFactory::assert()->notExists(['name' => 'Eiger']);
        self::assertCount(2, self::getMercureMessages());
        // todo how to ensure it's a delete update
        self::assertEquals(
            new Update(
                topics: ['http://localhost/admin/things/' . $id],
                data: json_encode(['@id' => 'http://localhost/admin/things/' . $id])
            ),
            self::getMercureMessage()
        );
        self::assertEquals(
            new Update(
                topics: ['http://localhost/things/' . $id],
                data: json_encode(['@id' => 'http://localhost/things/' . $id])
            ),
            self::getMercureMessage(1)
        );
    }
}
