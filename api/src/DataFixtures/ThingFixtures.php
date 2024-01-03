<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Thing;

class ThingFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = json_decode(file_get_contents(__DIR__ . '/things.json'), true);
        $things = [];
        foreach ($data as $datum) {
            $thing = new Thing();

            $thing->setName($datum['name']);
            $thing->setProperties($datum);
            $thing->setDateCreated(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
            $thing->setDateModified(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));

            $manager->persist($thing);
            $things[] = $thing;
        }
        $manager->flush();
    }
}