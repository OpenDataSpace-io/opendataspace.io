<?php

declare(strict_types=1);

namespace App\State\Processor;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Thing;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\ThingRepository;
use App\Dto\ThingInput;
use ApiPlatform\Api\IriConverterInterface;

final readonly class ThingUpdateProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: PersistProcessor::class)]
        private ProcessorInterface $persistProcessor,
        #[Autowire(service: MercureProcessor::class)]
        private ProcessorInterface $mercureProcessor,
        private HttpClientInterface $client,
        private DecoderInterface $decoder,
        private ThingRepository $repository,
        private IriConverterInterface $iriConverter
    ) {
    }

    // https://github.com/api-platform/api-platform/issues/2303
    /**
     * @param Thing $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Thing
    {
        $thing = new Thing();
        /*foreach ($data as $property => $value) {
            if (property_exists($thing, $property)) {
                $thing->$property = $value;
            }
        }*/

        $thing->setName('TEST UPDATE');
        //$thing->setId(new UUid::v4());
        //$thing->setDateCreated($data->getDateCreated());
        $thing->setDateCreated(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
        $thing->setDateModified(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
        // TODO: only update changed properties
        $thing->setProperties([$data]);

        //$data->setProperties($data->getBody());
        $thing->setIri($this->iriConverter->getIriFromResource($thing));
        
        // save entity
        $data = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        // TODO: save in Elasticsearch / Algolia
        // TODO: save History

        // publish on Mercure
        /*foreach (['/admin/things/{id}{._format}', '/things/{id}{._format}'] as $uriTemplate) {
            $this->mercureProcessor->process(
                $data,
                $operation,
                $uriVariables,
                $context + [
                    'item_uri_template' => $uriTemplate,
                ]
            );
        }*/

        return $thing;
    }

    public function supports(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): bool
    {
        return $data instanceof Thing && $data instanceof ThingInput;
    }
}
