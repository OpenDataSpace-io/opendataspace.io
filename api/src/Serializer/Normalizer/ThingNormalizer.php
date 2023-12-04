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
        private IriConverterInterface $iriConverter)
    {
        $this->iriConverter = $iriConverter;
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        
        /*$dateCreated = $data['dateCreated'];
        $dateModified = $data['dateModified'];

        if($format === 'jsonld'){
            if (isset($data['properties']['hydra:member'][0])){
                $data = $data['properties']['hydra:member'][0];
            }
            //$data['@id'] = $this->iriConverter->getIriFromResource($object);
            $data['@id'] = $object->getId();
        }else{
            if(isset($data['properties'][0])){
                $data = $data['properties'][0];
            }
            $data['id'] = $object->getId();
        }

        if(isset($object->getProperties()['@type'])){
            $data['@type'] = $object->getProperties()['@type'];
        }
        
        $data["@context"] = "https://schema.org/";
        // TODO: Set Type from properties['@type']
        $data['@type'] = 'Thing';
        
        $data['dateCreated'] = $dateCreated;
        $data['dateModified'] = $dateModified;
        // DEBUG
        $data['context'] = $context;
        $data['format'] = $format;*/

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
