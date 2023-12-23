<?php

declare(strict_types=1);

namespace App\DataFixtures\Story;

use App\DataFixtures\Factory\ThingFactory;
use App\DataFixtures\Factory\UserFactory;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Zenstruck\Foundry\Story;
use App\Entity\Thing;

final class ThingStory extends Story
{
    public function __construct(private readonly DecoderInterface $decoder) {}

    public function build(): void
    {
        // Import things
        $things = []; // store things in array to pick 30 random ones later without the default one
        $data = $this->decoder->decode(file_get_contents(__DIR__ . '/../things.json'), 'json');
        foreach ($data as $datum) {
            $thing = new Thing();

            $thing->setName($datum['name']);
            $thing->setProperties($datum);
            $thing->setDateCreated(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
            $thing->setDateModified(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));

            $things[] = $thing;
        }

        // Create default user
        UserFactory::createOne([
            'email' => 'john.doe@example.com',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'roles' => ['ROLE_USER'],
        ]);

        // Create admin user
        UserFactory::createOne([
            'email' => 'chuck.norris@example.com',
            'firstName' => 'Chuck',
            'lastName' => 'Norris',
            'roles' => ['ROLE_ADMIN'],
        ]);
    }
}
