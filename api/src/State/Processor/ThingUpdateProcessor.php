<?php

declare(strict_types=1);

namespace App\State\Processor;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Thing;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Repository\ThingRepository;
use App\Dto\ThingInput;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class ThingUpdateProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: PersistProcessor::class)]
        private ProcessorInterface $persistProcessor,
        #[Autowire(service: MercureProcessor::class)]
        private ProcessorInterface $mercureProcessor,
        private ThingRepository $repository,
        private RequestStack $requestStack
    ) {
    }

    // https://github.com/api-platform/api-platform/issues/2303
    /**
     * @param Thing $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []):Thing
    {

        $thing = $this->repository->find($uriVariables['id']);
        if (!$thing) {
            throw new \Exception('Thing not found');
        }

        $request = $this->requestStack->getCurrentRequest();
        if ($request) {
            $body = json_decode($request->getContent(), true);
        }
        
        if (isset($body['name'])) {
            $thing->setName($body['name']);
        }
        $thing->setDateModified(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
        // TODO: only update changed properties --> add changed properties to $thing->getProperties() and save it
        $thingProperties = $thing->getProperties();
        $mergedProperties = array_merge($thingProperties, $body);
        //$uniqueProperties = array_unique($mergedProperties);
        $thing->setProperties($mergedProperties);
        
        // save entity
        $data = $this->persistProcessor->process($thing, $operation, $uriVariables, $context);

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
        return $data instanceof Thing;
    }
}
