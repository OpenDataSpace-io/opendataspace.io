<?php

declare(strict_types=1);

namespace App\DataFixtures\Story;

use App\DataFixtures\Factory\ThingFactory;
use App\DataFixtures\Factory\UserFactory;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Zenstruck\Foundry\Story;

final class ThingStory extends Story
{
    public function __construct(private readonly DecoderInterface $decoder)
    {
    }

    public function build(): void
    {
        // Create default book (must be created first to appear first in list)
        $defaultThing = ThingFactory::createOne([
            'name' => ThingFactory::faker()->text(20),
            'dateCreated' => \DateTimeImmutable::createFromMutable(ThingFactory::faker()->dateTime('-1 month')),
            'dateModified' => \DateTimeImmutable::createFromMutable(ThingFactory::faker()->dateTime('-1 month')),
            'properties' => [
                'name' => ThingFactory::faker()->text(20),
                'description' => ThingFactory::faker()->text(),
            ],
        ]);

        // Default book has reviews (new users are created)
        /*ReviewFactory::createMany(30, [
            'thing' => $defaultThing,
            'publishedAt' => \DateTimeImmutable::createFromMutable(ReviewFactory::faker()->dateTime('-1 week')),
        ]);
        */

        // Import things
        $things = []; // store books in array to pick 30 random ones later without the default one
        $data = $this->decoder->decode(file_get_contents(__DIR__.'/../things.json'), 'json');
        foreach ($data as $datum) {
            $thing = ThingFactory::createOne([
                'name' => $datum['name'],
                'dateCreated' => \DateTimeImmutable::createFromMutable(ThingFactory::faker()->dateTime('-1 month')),
                'dateModified' => \DateTimeImmutable::createFromMutable(ThingFactory::faker()->dateTime('-1 month')),
                'properties' => [
                    'name' => ThingFactory::faker()->words(20),
                    'description' => ThingFactory::faker()->text(),
                ],
            ]);
            
            $things[] = $thing;
        }

        // Create default user
        $defaultUser = UserFactory::createOne([
            'email' => 'john.depp@example.com',
            'firstName' => 'John',
            'lastName' => 'Depp',
            'roles' => ['ROLE_USER'],
        ]);

        // Default user has a review on the default book
        /*ReviewFactory::createOne([
            'thing' => $defaultThing,
            'user' => $defaultUser,
            'rating' => 5,
            'publishedAt' => new \DateTimeImmutable(),
            'body' => 'This is the best SF book ever!',
        ]);*/

        // Default user has bookmarked the default book
        /*BookmarkFactory::createOne([
            'thing' => $defaultThing,
            'user' => $defaultUser,
            'bookmarkedAt' => new \DateTimeImmutable('-1 hour'),
        ]);*/

        // Default user has bookmarked other books
        /*foreach (array_rand($thing, 30) as $key) {
            BookmarkFactory::createOne([
                'user' => $defaultUser,
                'things' => $things[$key],
                'bookmarkedAt' => \DateTimeImmutable::createFromMutable(BookmarkFactory::faker()->dateTime('-1 week')),
            ]);
        }*/

        // Create admin user
        UserFactory::createOne([
            'email' => 'jack.ryan@example.com',
            'firstName' => 'Jack',
            'lastName' => 'Ryan',
            'roles' => ['ROLE_ADMIN'],
        ]);
    }
}
