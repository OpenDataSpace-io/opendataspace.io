<?php

namespace App\Serializer\Normalizer;

use ApiPlatform\Api\IriConverterInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Uid\Uuid;

//https://api-platform.com/docs/v3.1/core/content-negotiation/#writing-a-custom-normalizer

class ThingNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function __construct(
        private ObjectNormalizer $normalizer,
        private readonly IriConverterInterface $iriConverter)
    {
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        //$object->setId(UUID::v4());
        dump($object);
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
        $data['id'] = $object->getId(); // neeeded for josnld IRI
        
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

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return $this->normalizer->denormalize($data, $class, $format, $context);
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $this->normalizer->supportsDenormalization($data, $type, $format);
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
