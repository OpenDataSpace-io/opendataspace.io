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

        /*if (isset($context['api_denormalize']) && $context['api_denormalize'] === true) {
            // GET_COLLECTION Anforderung
            // Modifizieren Sie die Daten entsprechend
        } else {
            // GET
            $dateCreated = $data['dateCreated'];
            $dateModified = $data['dateModified'];
            if($format == 'json'){
                $data = $data['properties'][0];

            }
            if($format == 'jsonld'){
                $data = $data['properties']['hydra:member'][0];
            }
            $data['dateCreated'] = $dateCreated;
            $data['dateModified'] = $dateModified;
        }*/
        

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
