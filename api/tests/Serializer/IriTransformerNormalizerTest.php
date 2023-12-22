<?php

declare(strict_types=1);

namespace App\Tests\Serializer;

use ApiPlatform\Api\IriConverterInterface;
use ApiPlatform\Api\UrlGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Operation\Factory\OperationMetadataFactoryInterface;
use App\Serializer\IriTransformerNormalizer;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class IriTransformerNormalizerTest extends TestCase
{
    private MockObject|NormalizerInterface $normalizerMock;
    private IriConverterInterface|MockObject $iriConverterMock;
    private MockObject|OperationMetadataFactoryInterface $operationMetadataFactoryMock;
    private MockObject|Operation $operationMock;
    private MockObject|\stdClass $objectMock;
    private IriTransformerNormalizer $normalizer;

    protected function setUp(): void
    {
        $this->normalizerMock = $this->createMock(NormalizerInterface::class);
        $this->iriConverterMock = $this->createMock(IriConverterInterface::class);
        $this->operationMetadataFactoryMock = $this->createMock(OperationMetadataFactoryInterface::class);
        $this->operationMock = $this->createMock(Operation::class);
        $this->objectMock = new \stdClass();
        $this->objectMock->user = $this->createMock(\stdClass::class);

        $this->normalizer = new IriTransformerNormalizer($this->iriConverterMock, $this->operationMetadataFactoryMock);
        $this->normalizer->setNormalizer($this->normalizerMock);
    }

    /**
     * @test
     */
    public function itDoesNotSupportInvalidData(): void
    {
        $this->assertFalse($this->normalizer->supportsNormalization(null));
        $this->assertFalse($this->normalizer->supportsNormalization([]));
        $this->assertFalse($this->normalizer->supportsNormalization('string'));
        $this->assertFalse($this->normalizer->supportsNormalization(12345));
        $this->assertFalse($this->normalizer->supportsNormalization(new ArrayCollection([$this->objectMock])));
    }

    /**
     * @test
     */
    public function itDoesNotSupportInvalidContext(): void
    {
        $this->assertFalse($this->normalizer->supportsNormalization($this->objectMock));
        $this->assertFalse($this->normalizer->supportsNormalization($this->objectMock, null, [IriTransformerNormalizer::class => true]));
    }
}
