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
        if (isset($data['@id']))
        {
            $id = $data['@id'];
        }

                if($format == 'json'){
                    if(isset($data['properties'][0])){
                        $data = $data['properties'][0];
                    }
                }
                if($format == 'jsonld'){
                    if (isset($data['properties']['hydra:member'][0])){
                        $data = $data['properties']['hydra:member'][0];
                    }
                }
        if (isset($id)){
            $data['@id'] = $id;
        }

        // TODO: Add @id to properties --> Datafixtures
        if(isset($context['operation_name']))
        {
            if($context['operation_name'] == "_api_/things/{id}{._format}_get")
            {
                $data['@id'] = $context['uri_variables']['id'];
            }
        }
        $data['dateCreated'] = $dateCreated;
        $data['dateModified'] = $dateModified;
        // Debug
        $data['context'] = $context;



        

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
