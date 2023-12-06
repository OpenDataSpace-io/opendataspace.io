<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Form;

class FormFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
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
                        "street_address",
                        "city",
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
        ]);
        $manager->persist($form);

        $manager->flush();
    }
}
