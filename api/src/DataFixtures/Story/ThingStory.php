<?php

declare(strict_types=1);

namespace App\DataFixtures\Story;

use App\DataFixtures\Factory\ThingFactory;
use App\DataFixtures\Factory\BookmarkFactory;
use App\DataFixtures\Factory\ReviewFactory;
use App\DataFixtures\Factory\UserFactory;
use App\Enum\BookCondition;
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
            'name' => 'Test Thing',
            'dateCreated' => \DateTimeImmutable::createFromMutable(ReviewFactory::faker()->dateTime('-1 week')),
            'dateModified' => \DateTimeImmutable::createFromMutable(ReviewFactory::faker()->dateTime('-1 week')),
            /*'properties' => [
                'test' => 'test',
                'test2' => 'test2',
            ],*/
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
            $thing = ThingFactory::createOne($datum + [
                'name' => $datum['name'],
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
