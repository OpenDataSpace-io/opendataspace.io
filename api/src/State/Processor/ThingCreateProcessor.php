<?php

declare(strict_types=1);

namespace App\State\Processor;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Thing;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RequestStack;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\IRIConverterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @implements ProcessorInterface<Thing>
 */
final readonly class ThingCreateProcessor implements ProcessorInterface
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
        private RequestStack $requestStack
    ) {
    }

    // https://github.com/api-platform/api-platform/issues/2303
    /**
     * @param Thing $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Thing
    {
        $request = $this->requestStack->getCurrentRequest();
        $body = [];
        if ($request) {
            $body = json_decode($request->getContent(), true);
        }

        $thing = new Thing();
        
        if (isset($body['name'])) {
            $thing->setName($body['name']);
        }
        
        $body['license'] = 'https://creativecommons.org/licenses/by/4.0/';

        $id = Uuid::v4();

        $thing->setId($id);
        $thing->setDateCreated(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
        $thing->setDateModified(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
        $thing->setProperties($body);

        $data->setId($id);
        $data->setDateCreated(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
        $data->setDateModified(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
        $data->setProperties($body);

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