<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Thing;
use Symfony\Component\Uid\Uuid;

class ThingFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 50; $i++) {
            $thing = new Thing();
            $id = Uuid::v4();
            $thing->setId($id);
            $thing->setName('Thing '.$i);
            $thing->setDateCreated(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
            $thing->setDateModified(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
            $thing->setProperties([
                '@context' => 'https://schema.org/',
                '@type' => 'Thing',
                '@id' => $id,
                'name' => 'Thing '.$i,
                'description' => 'Description of Thing '.$i,
                "url" => "http://www.example.com/thing15",
                "logo" => "http://www.example.com/thing15/logo.png",
                "image" => "http://www.example.com/thing15/image.png",
                "telephone" => "1234567890",
                "email" => "info@example.com",
                "openingHours" => "Mo,Tu,We,Th,Fr,Sa,Su 09:00-18:00",
                "openingHoursSpecification" => [
                    [
                        "@type" => "OpeningHoursSpecification",
                        "validFrom" => "2023-12-24",
                        "validThrough" => "2021-12-24",
                        "opens" => "09:00",
                        "closes" => "18:00"
                    ]
                ],
                "priceRange" => "$$$",
                "paymentAccepted" => "Cash, Credit Card",

                "geo" => [
                    "@type" => "GeoCoordinates",
                    "latitude" => "12.345",
                    "longitude" => "67.890"
                ],
                "address"=> [
                    "@type" => "PostalAddress",
                    "streetAddress" => "123 Main St",
                    "addressLocality "=> "City",
                    "addressRegion" => "State",
                    "postalCode" => "12345",
                    "addressCountry" => "US"
                ],
                "sameAs" => [
                    "https://www.facebook.com/thing15",
                    "https://twitter.com/thing15",
                    "https://www.youtube.com/thing15",
                    "https://plus.google.com/thing15",
                    "https://www.instagram.com/thing15/",
                    "https://www.pinterest.com/thing15/",
                    "https://www.linkedin.com/company/thing15"
                ]
            ]);
            $manager->persist($thing);
        }

        $manager->flush();
    }
}
