<?php

declare(strict_types=1);

namespace App\Tests\State\Processor;

use ApiPlatform\Api\IriConverterInterface;
use ApiPlatform\Api\UrlGeneratorInterface;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use ApiPlatform\Metadata\Resource\ResourceMetadataCollection;
use App\Entity\Thing;
use App\State\Processor\MercureProcessor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\HubRegistry;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Serializer\SerializerInterface;

final class MercureProcessorTest extends TestCase
{
    private MockObject|SerializerInterface $serializerMock;
    private HubRegistry $hubRegistry;
    private HubInterface|MockObject $hubMock;
    private IriConverterInterface|MockObject $iriConverterMock;
    private MockObject|ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactoryMock;
    private ResourceMetadataCollection $resourceMetadataCollection;
    private Thing|MockObject $objectMock;
    private MockObject|Operation $operationMock;
    private MercureProcessor $processor;

    protected function setUp(): void
    {
        $this->serializerMock = $this->createMock(SerializerInterface::class);
        $this->hubMock = $this->createMock(HubInterface::class);
        $this->hubRegistry = new HubRegistry($this->hubMock);
        $this->resourceMetadataCollectionFactoryMock = $this->createMock(ResourceMetadataCollectionFactoryInterface::class);
        $this->resourceMetadataCollection = new ResourceMetadataCollection(Thing::class, [
            new ApiResource(operations: [new Get('/admin/things/{id}{._format}')]),
            new ApiResource(operations: [new Get('/things/{id}{._format}')]),
        ]);
        $this->iriConverterMock = $this->createMock(IriConverterInterface::class);
        $this->objectMock = $this->createMock(Thing::class);
        $this->operationMock = $this->createMock(Operation::class);

        $this->processor = new MercureProcessor(
            $this->serializerMock,
            $this->hubRegistry,
            $this->iriConverterMock,
            $this->resourceMetadataCollectionFactoryMock,
            ['jsonld' => null, 'json' => null]
        );
    }

    /**
     * @test
     */
    public function itSendsAMercureUpdate(): void
    {
        $this->resourceMetadataCollectionFactoryMock->expects($this->never())->method('create');
        $this->iriConverterMock
            ->expects($this->once())
            ->method('getIriFromResource')
            ->with($this->objectMock, UrlGeneratorInterface::ABS_URL, $this->operationMock)
            ->willReturn('/things/9aff4b91-31cf-4e91-94b0-1d52bbe23fe6')
        ;
        $this->operationMock
            ->expects($this->once())
            ->method('getNormalizationContext')
            ->willReturn(null)
        ;
        $this->serializerMock
            ->expects($this->once())
            ->method('serialize')
            ->with($this->objectMock, 'jsonld', [])
            ->willReturn(json_encode(['foo' => 'bar']))
        ;
        $this->hubMock
            ->expects($this->once())
            ->method('publish')
            ->with($this->equalTo(new Update(
                topics: ['/things/9aff4b91-31cf-4e91-94b0-1d52bbe23fe6'],
                data: json_encode(['foo' => 'bar']),
            )))
        ;

        $this->processor->process($this->objectMock, $this->operationMock);
    }

    /**
     * @test
     */
    public function itSendsAMercureUpdateWithContextOptions(): void
    {
        $this->resourceMetadataCollectionFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with($this->objectMock::class)
            ->willReturn($this->resourceMetadataCollection)
        ;
        $this->iriConverterMock->expects($this->never())->method('getIriFromResource');
        $this->operationMock->expects($this->never())->method('getNormalizationContext');
        $this->serializerMock->expects($this->never())->method('serialize');
        $this->hubMock
            ->expects($this->once())
            ->method('publish')
            ->with($this->equalTo(new Update(
                topics: ['/admin/things/9aff4b91-31cf-4e91-94b0-1d52bbe23fe6'],
                data: json_encode(['bar' => 'baz']),
            )))
        ;

        $this->processor->process($this->objectMock, $this->operationMock, [], [
            'item_uri_template' => '/admin/things/{id}{._format}',
            'topics' => ['/admin/things/9aff4b91-31cf-4e91-94b0-1d52bbe23fe6'],
            MercureProcessor::DATA => json_encode(['bar' => 'baz']),
        ]);
    }
}
