<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

//https://api-platform.com/docs/v3.1/core/content-negotiation/#writing-a-custom-normalizer

class ThingNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function __construct(private ObjectNormalizer $normalizer)
    {
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        
        $dateCreated = $data['dateCreated'];
        $dateModified = $data['dateModified'];

        if(isset($data['properties'][0])){
            $data = $data['properties'];
        }

        if (isset($data['properties']['hydra:member'][0])){
            $data = $data['properties']['hydra:member'][0];
        }
        
        $data["@context"] = "https://schema.org/";
        // TODO: Set Type from properties['@type']
        $data['@type'] = 'Thing';
        $data['@id'] = $object->getId();
        
        $data['dateCreated'] = $dateCreated;
        $data['dateModified'] = $dateModified;
        // DEBUG
        $data['context'] = $context;
        $data['format'] = $format;
        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof \App\Entity\Thing;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
