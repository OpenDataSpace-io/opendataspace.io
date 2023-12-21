<?php

declare(strict_types=1);

namespace App\State\Processor;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Thing;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

/**
 * @implements ProcessorInterface<Thing>
 */
final readonly class ThingPersistProcessor implements ProcessorInterface
{
    /**
     * @param PersistProcessor $persistProcessor
     * @param MercureProcessor $mercureProcessor
     */
    public function __construct(
        #[Autowire(service: PersistProcessor::class)]
        private ProcessorInterface $persistProcessor,
        #[Autowire(service: MercureProcessor::class)]
        private ProcessorInterface $mercureProcessor,
        private DecoderInterface $decoder
    ) {
    }

    /**
     * @param Thing $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Thing
    {

        // save entity
        $data = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        // publish on Mercure
        foreach (['/admin/things/{id}{._format}', '/things/{id}{._format}'] as $uriTemplate) {
            $this->mercureProcessor->process(
                $data,
                $operation,
                $uriVariables,
                $context + [
                    'item_uri_template' => $uriTemplate,
                ]
            );
        }

        return $data;
    }
}