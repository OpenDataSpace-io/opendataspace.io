<?php

declare(strict_types=1);

namespace App\DataFixtures\Story;

use App\DataFixtures\Factory\BookFactory;
use App\DataFixtures\Factory\BookmarkFactory;
use App\DataFixtures\Factory\ReviewFactory;
use App\DataFixtures\Factory\UserFactory;
use App\Enum\BookCondition;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Zenstruck\Foundry\Story;

final class DefaultStory extends Story
{
    public function __construct(private readonly DecoderInterface $decoder) {}

    public function build(): void
    {
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
