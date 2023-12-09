<?php

namespace App\Serializer\Normalizer;

use ApiPlatform\Api\IriConverterInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Uid\Uuid;
use App\Repository\ThingRepository;
use App\Entity\Thing;
use Symfony\Component\HttpFoundation\RequestStack;


//https://api-platform.com/docs/v3.1/core/content-negotiation/#writing-a-custom-normalizer

class ThingNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function __construct(
        //private RouterInterface $router,
        private ObjectNormalizer $normalizer,
        private IriConverterInterface $iriConverter,
        private ThingRepository $repository,
        private RequestStack $requestStack)
    {
        $this->iriConverter = $iriConverter;
    }

    public function normalize($object, string $format = null, array $context = []): array
    {   
        /*$dateCreated = $object->dateCreated;
        $dateModified = $data['dateModified'];
        */

        //$data = $object->getProperties();

        /*if($format === 'jsonld'){
            /*if (isset($data['properties']['hydra:member'][0])){
                $data = $data['properties']['hydra:member'][0];
            }*/
            //$data['@id'] = $this->iriConverter->getIriFromResource($object);
            //$data['@id'] = '/things/'.$object->getId();
            //$data["@context"] = "https://schema.org/";
            // TODO: Set Type from properties['@type']
            //$data['@type'] = 'Thing';
            /*if(isset($object->getProperties()['@type'])){
                $data['@type'] = $object->getProperties()['@type'];
            }
        }
        $data['identifier'] = $object->getId();
        $data['dateCreated'] = $dateCreated;
        $data['dateModified'] = $dateModified;
        // DEBUG
        //$data['context'] = $context;
        //$data['format'] = $format;

        ksort($data);

        /** @var array $data */
        $data = $this->normalizer->normalize($object, $format, $context);

        $thing = $this->repository->find($object->getId());

        $data = $thing->getProperties();
        
        $date['@context'] = 'https://schema.org/';
        $data['@id'] = '/things/'.$object->getId();
        // TODO Set Type from properties['@type']
        //$data['@type'] = 'Thing';
        $data['identifier'] = $object->getId();
        $data['dateCreated'] = $thing->getDateCreated()->format('Y-m-d\TH:i:s\Z');
        $data['dateModified'] = $thing->getDateModified()->format('Y-m-d\TH:i:s\Z');

        /*if($format === 'jsonld'){
            unset($data['hydra:totalItems']);
            unset($data['hydra:member']);
            unset($data['hydra:search']);
        }*/

        ksort($data);

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof \App\Entity\Thing;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request) {
            $body = json_decode($request->getContent(), true);
        }

        $thing = new Thing();
        
        if (isset($body['name'])) {
            $thing->setName($body['name']);
        }

        $id = Uuid::v4();

        $thing->setId($id);
        $thing->setDateCreated(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
        $thing->setDateModified(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
        $thing->setProperties($body);
        //$thing->setIri('/things/'.$id);

        $data = $this->normalizer->denormalize($thing, $class, $format, $context);

        return $data;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $data instanceof \App\Entity\Thing;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Thing::class => false,
        ];
    }
}
