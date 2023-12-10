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
            $name = 'Thing '.$i;
            $thing->setName($name);
            $thing->setDateCreated(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
            $thing->setDateModified(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
            $thing->setProperties([
                '@context' => 'https://schema.org',
                '@type' => 'Thing',
                'name' => $name,
                'description' => 'Description of Thing '.$i.'. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Condimentum vitae sapien pellentesque habitant. Lacus suspendisse faucibus interdum posuere lorem ipsum. Tristique senectus et netus et malesuada. Nec tincidunt praesent semper feugiat nibh sed pulvinar proin gravida. Viverra suspendisse potenti nullam ac tortor vitae purus faucibus ornare. Nibh mauris cursus mattis molestie a. Elementum eu facilisis sed odio morbi quis. Fames ac turpis egestas sed tempus. Consectetur purus ut faucibus pulvinar elementum integer. Ac tincidunt vitae semper quis lectus nulla at volutpat diam. In ornare quam viverra orci sagittis eu volutpat odio. Sit amet luctus venenatis lectus magna fringilla. Pretium nibh ipsum consequat nisl vel. Nunc id cursus metus aliquam eleifend mi.',
                "url" => "http://www.example.com",
                "logo" => "https://dummyimage.com/300x300/000/fff",
                "image" => "https://dummyimage.com/300x300/000/fff",
                "telephone" => "1234567890",
                "email" => "info@example.com",
                "openingHours" => ["Mo-Fr 10:00-19:00", "Sa 10:00-22:00", "Su 10:00-21:00"],
                "openingHoursSpecification" => [
                    [
                        "@type" => "OpeningHoursSpecification",
                        "validFrom" => "2023-12-24",
                        "validThrough" => "2021-12-24",
                        "opens" => "09:00",
                        "closes" => "18:00"
                    ],
                    [
                        "@type" => "OpeningHoursSpecification",
                        "validFrom" => "2023-12-25",
                        "validThrough" => "2021-12-25",
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
