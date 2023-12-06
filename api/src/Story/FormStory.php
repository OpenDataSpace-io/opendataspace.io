<?php

namespace App\Story;

use App\Factory\FormFactory;
use Zenstruck\Foundry\Story;

final class FormStory extends Story
{
    public function build(): void
    {
        // TODO build your story here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#stories)
        FormFactory::createMany(3);
    }
}
