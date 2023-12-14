<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Form;

class FormFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadPlaceForm($manager);
        $this->loadOpeningHoursForm($manager);
        $this->loadMapForm($manager);
        $this->loadImageForm($manager);
    }

    public function loadPlaceForm(ObjectManager $manager)
    {
        $form = new Form();
        $form->setName('Place Form');
        $form->setCode('place');
        $form->setDateCreated(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
        $form->setDateModified(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
        $form->setJSONSchema(
            [
                "title" => "Place Form",
                "description" => "A simple form example.",
                "type" => "object",
                "required" => [
                    "name",
                ],
                "properties" => [
                "name" => [
                    "type" => "string",
                    "title" => "Name",
                    "default" => "Test Thing"
                ],
                "description" => [
                    "type" => "string",
                    "title" => "Description",
                ],
                "address" => [
                    "type" => "object",
                    "properties" => [
                        "streetAddress" => [
                            "type" => "string",
                        ],
                        "addressLocality" => [
                            "type" => "string"
                        ],
                        "postalCode" => [
                            "type" => "string"
                        ],
                        "addressCountry" => [
                            "type" => "string"
                        ]
                    ],
                    "required" => [
                        "streetAddress",
                        "addressLocality",
                    ]
                ],
                "geo" => [
                    "type" => "object",
                    "title" => "Geo Location",
                    "properties" => [
                        "latitude" => [
                            "type" => "number",
                            "title" => "Latitude",
                        ],
                        "longitude" => [
                            "type" => "number",
                            "title" => "Longitude",
                        ]
                    ]
                ],
                "telephone" => [
                    "type" => "string",
                    "title" => "Telephone",
                    "minLength" => 10
                ],
                "email" => [
                    "type" => "string",
                    "title" => "Email",
                    "format" => "email"
                ],
                "url" => [
                    "type" => "string",
                    "title" => "Website",
                    "format" => "uri"
                ],
                "openingHours" => [
                    "type" => "array",
                    "title" => "Öffnungszeiten",
                    "items" => [
                    "type" => "object",
                    "properties" => [
                        "dayOfWeek" => [
                            "type" => "string",
                            "title" => "Wochentag",
                            "enum" => [
                                "Monday",
                                "Tuesday",
                                    "Wednesday",
                                    "Thursday",
                                    "Friday",
                                    "Saturday",
                                    "Sunday"
                            ],
                            "enumNames" => [
                                "Montag",
                                "Dienstag",
                                "Mittwoch",
                                "Donnerstag",
                                "Freitag",
                                "Samstag",
                                "Sonntag"
                            ]
                        ],
                        "opens" => [
                            "type" => "string",
                            "title" => "Öffnet",
                            "format" => "time"
                        ],
                        "closes" => [
                            "type" => "string",
                            "title" => "Schließt",
                            "format" => "time"
                        ]
                    ]
                    ]
                ],
                "openingHoursSpecification" =>[
                    "type" => "array",
                    "title" => "spezifische Öffnungszeiten",
                    "items" => [
                    "type" => "object",
                    "properties" => [
                        "date" => [
                        "type" => "string",
                        "title" => "Datum",
                        "format" => "date"
                        ],
                        "opens" => [
                        "type" => "string",
                        "title" => "Öffnet",
                        "format" => "time"
                        ],
                        "closes" => [
                        "type" => "string",
                        "title" => "Schließt",
                        "format" => "time"
                        ],
                        "closed" => [
                        "type" => "boolean",
                        "title" => "Geschlossen",
                        "default" => false
                        ]
                    ]
                    ]
                ],
                "sameAs" => [
                    "type" => "array",
                    "title" => "Links",
                    "items" => [
                        "type" => "string",
                        "title" => "Link",
                        "format" => "uri"
                    ]
                ]
                ]
            ]
        );
        $form->setUISchema([
            "name" => [
                "ui:autofocus" => true,
                "ui:emptyValue" => "",
                "ui:autocomplete" => "name"
            ],
            "description" => [
                "ui:widget" => "textarea"
            ],
            "telephone" => [
                "ui:options" => [
                    "inputType" => "tel"
                ]
                ],
            "email" => [
                "ui:options" => [
                    "inputType" => "email"
                ]
            ],
            "url" => [
                "ui:options" => [
                    "inputType" => "url"
                ]
            ],
            "openingHours" => [
                "ui:options" => [
                    "addable" => false,
                    "orderable" => false,
                    "removable" => false
                ]
            ],
            "openingHoursSpecification" => [
                "ui:options" => [
                    "addable" => false,
                    "orderable" => false,
                    "removable" => false
                ]
            ],
            "sameAs" => [
                "ui:options" => [
                    "addable" => false,
                    "orderable" => false,
                    "removable" => false
                ]
            ]
        ]);
        $form->setFormData([
            "name" => "Thing TEST",
            "description" => "This is a test thing",
            "address" => [
                "streetAddress" => "Test Street 1",
                "addressLocality" => "Test City",
                "postalCode" => "12345",
                "addressCountry" => "DE"
            ],
            "geo" => [
                "latitude" => 52.520008,
                "longitude" => 13.404954
            ],
            "telephone" => "+49 30 12345678",
            "email" => "example@test.ch",
            "url" => "https://example.com",
            "openingHours" =>  [
                    [
                        "dayOfWeek" => "Monday",
                        "opens" => "10:00:00",
                        "closes" => "16:00:00"
                    ],
                    [
                        "dayOfWeek"=> "Tuesday",
                        "opens" => "09:00:00",
                        "closes" => "17:00:00"
                    ],
                    [
                        "dayOfWeek"=> "Wednesday",
                        "opens" => "09:00:00",
                        "closes" => "17:00:00"
                    ],
                    [
                        "dayOfWeek"=> "Thursday",
                        "opens" => "09:00:00",
                        "closes" => "17:00:00"
                    ],
                    [
                        "dayOfWeek"=> "Friday",
                        "opens" => "09:00:00",
                        "closes" => "17:00:00"
                    ]
                ],
                "openingHoursSpecification" => [
                    [
                        "date" => "2021-01-01",
                        "opens" => "10:00:00",
                        "closes" => "16:00:00"
                    ],
                    [
                        "date" => "2021-01-01",
                        "opens" => "09:00:00",
                        "closes" => "17:00:00"
                    ]
                ],
                "sameAs" => [
                    "https://example.com"
                ]
            ]
        );
        $manager->persist($form);

        $manager->flush();
    }

    public function loadOpeningHoursForm(ObjectManager $manager)
    {
        $form = new Form();
        $form->setName('OpeningHours Form');
        $form->setCode('openinghours');
        $form->setDateCreated(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
        $form->setDateModified(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
        $form->setJSONSchema(
            [
                "title" => "OpeningHours Form",
                "description" => "A simple form example.",
                "type" => "object",
                "properties" => [
                    "openingHours" => [
                        "type" => "array",
                        "title" => "Öffnungszeiten",
                        "items" => [
                        "type" => "object",
                        "properties" => [
                            "dayOfWeek" => [
                                "type" => "string",
                                "title" => "Wochentag",
                                "enum" => [
                                    "Monday",
                                    "Tuesday",
                                        "Wednesday",
                                        "Thursday",
                                        "Friday",
                                        "Saturday",
                                        "Sunday"
                                ],
                                "enumNames" => [
                                    "Montag",
                                    "Dienstag",
                                    "Mittwoch",
                                    "Donnerstag",
                                    "Freitag",
                                    "Samstag",
                                    "Sonntag"
                                ]
                            ],
                            "opens" => [
                                "type" => "string",
                                "title" => "Öffnet",
                                "format" => "time"
                            ],
                            "closes" => [
                                "type" => "string",
                                "title" => "Schließt",
                                "format" => "time"
                            ]
                        ]
                        ]
                    ],
                    "openingHoursSpecification" => [
                        "type" => "array",
                        "title" => "spezifische Öffnungszeiten",
                        "items" => [
                            "type" => "object",
                            "properties" => [
                                "@type" => [
                                    "type" => "string",
                                    "title" => "Typ",
                                    "enum" => [
                                        "OpeningHoursSpecification"
                                    ],
                                    "enumNames" => [
                                        "Öffnungszeiten Spezifikation"
                                    ]
                                ],
                                "validFrom" => [
                                    "type" => "string",
                                    "title" => "Datum von",
                                    "format" => "date"
                                ],
                                "validThrough" => [
                                    "type" => "string",
                                    "title" => "Datum bis",
                                    "format" => "date"
                                ],
                                "opens" => [
                                    "type" => "string",
                                    "title" => "Öffnet",
                                    "format" => "time"
                                ],
                                "closes" => [
                                    "type" => "string",
                                    "title" => "Schließt",
                                    "format" => "time"
                                ],
                                "closed" => [
                                    "type" => "boolean",
                                    "title" => "Geschlossen",
                                    "default" => false
                                ]
                            ]
                        ]
                    ],
                ]
            ]
        );
        $form->setUISchema([
            "@type" => [ 'ui:widget' => 'hidden' ],
        ]);
        $form->setFormData([
            "openingHours" =>  [
                    [
                        "dayOfWeek" => "Monday",
                        "opens" => "10:00:00",
                        "closes" => "16:00:00"
                    ],
                    [
                        "dayOfWeek"=> "Tuesday",
                        "opens" => "09:00:00",
                        "closes" => "17:00:00"
                    ],
                    [
                        "dayOfWeek"=> "Wednesday",
                        "opens" => "09:00:00",
                        "closes" => "17:00:00"
                    ],
                    [
                        "dayOfWeek"=> "Thursday",
                        "opens" => "09:00:00",
                        "closes" => "17:00:00"
                    ],
                    [
                        "dayOfWeek"=> "Friday",
                        "opens" => "09:00:00",
                        "closes" => "17:00:00"
                    ]
                ],
                "openingHoursSpecification" => [
                    [
                        "@type" => "OpeningHoursSpecification",
                        "validFrom" => "2021-01-01",
                        "validThrough" => "2021-12-31",
                        "opens" => "10:00:00",
                        "closes" => "16:00:00"
                    ],
                    [
                        "@type" => "OpeningHoursSpecification",
                        "validFrom" => "2021-01-01",
                        "validThrough" => "2021-12-31",
                        "opens" => "09:00:00",
                        "closes" => "17:00:00"
                    ]
                ]
            ]
        );
        $manager->persist($form);

        $manager->flush();
    }

    public function loadMapForm(ObjectManager $manager)
    {
        $form = new Form();
        $form->setName('Map Form');
        $form->setCode('map');
        $form->setDateCreated(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
        $form->setDateModified(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
        $form->setJSONSchema([
            "title" => "Map Form",
            "description" => "A simple form example.",
            "type" => "object",
            "required" => [
                "latitude",
                "longitude",
            ],
            "properties" => [
                "geo" => [
                    "type" => "object",
                    "title" => "Geo Location",
                    "properties" => [
                        "latitude" => [
                            "type" => "number",
                            "title" => "Latitude",
                        ],
                        "longitude" => [
                            "type" => "number",
                            "title" => "Longitude",
                        ]
                    ]
                ]
            ]
        ]);
        $form->setUISchema([
            "geo" => [
                "latitude" => [
                    "ui:autofocus" => true,
                    "ui:emptyValue" => "",
                    "ui:autocomplete" => "latitude"
                ],
                "longitude" => [
                    "ui:autofocus" => true,
                    "ui:emptyValue" => "",
                    "ui:autocomplete" => "longitude"
                ]
            ]
        ]);
        $form->setFormData([
            "geo" => [
                "latitude" => 52.520008,
                "longitude" => 13.404954
            ]
        ]);

        $manager->persist($form);

        $manager->flush();
    }

    public function loadImageForm(ObjectManager $manager)
    {
        $form = new Form();
        $form->setName('Image Form');
        $form->setCode('image');
        $form->setDateCreated(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
        $form->setDateModified(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
        $form->setJSONSchema([
            "title" => "Image Form",
            "description" => "A simple form example.",
            "type" => "object",
            "required" => [
                "image",
            ],
            "properties" => [
                "image" => [
                    "type" => "string",
                    "title" => "Image",
                    "format" => "data-url",
                    "media" => [
                        "type" => "image/*"
                    ]
                ]
            ]
        ]);
        $form->setUISchema([
            "image" => [
                "ui:widget" => "image",
                "ui:options" => [
                    "accept" => ".jpg,.jpeg,.png,.gif,.bmp,.tif,.tiff"
                ]
            ]
        ]);
        $form->setFormData([
            "image" => "https://picsum.photos/200/300"
        ]);

        $manager->persist($form);

        $manager->flush();
    }
}
