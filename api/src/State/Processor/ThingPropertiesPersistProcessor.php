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

final readonly class ThingPropertiesPersistProcessor implements ProcessorInterface
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

    /**
     * @param Thing $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Thing
    {
        $thing = $this->getData($data->getName());
        $data->setName($thing['properties']['name']);
        $data->setDateModified($thing['properties']['dateModified']);
        $data->setProperties($thing['properties']);


        /*$data->author = null;
        if (isset($book['authors'][0]['key'])) {
            $author = $this->getData('https://openlibrary.org'.$book['authors'][0]['key'].'.json');
            if (isset($author['name'])) {
                $data->author = $author['name'];
            }
        }*/

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

    private function getData(string $uri): array
    {
        return $this->decoder->decode($this->client->request(Request::METHOD_GET, $uri, [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ])->getContent(), 'json');
    }
}