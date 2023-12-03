<?php

declare(strict_types=1);

namespace App\State\Processor;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Thing;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class ThingCreateProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: PersistProcessor::class)]
        private ProcessorInterface $persistProcessor,
        #[Autowire(service: MercureProcessor::class)]
        private ProcessorInterface $mercureProcessor,
        private HttpClientInterface $client,
        private DecoderInterface $decoder
    ) {
    }

    // https://github.com/api-platform/api-platform/issues/2303
    /**
     * @param Thing $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Thing
    {
        // TODO: Import Properties from Level 1 in Json
        //$data->setName($data->getName());
        $data->setDateCreated(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
        $data->setDateModified(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
        //$data->setProperties($data->getProperties());

        // save entity
        $data = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

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

        return $data;
    }
}
