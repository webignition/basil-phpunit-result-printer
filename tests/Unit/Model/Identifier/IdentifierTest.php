<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Identifier;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Identifier\PropertiesFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\Model\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class IdentifierTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $source, Properties $properties)
    {
        $identifier = new Identifier($source, $properties);

        $this->assertSame($source, ObjectReflector::getProperty($identifier, 'source'));
        $this->assertSame($properties, ObjectReflector::getProperty($identifier, 'properties'));
    }

    public function createDataProvider(): array
    {
        return [
            'default' => [
                'source' => '$".selector"',
                'identifier' => \Mockery::mock(Properties::class),
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param Identifier $identifier
     * @param array<mixed> $expectedData
     */
    public function testGetData(Identifier $identifier, array $expectedData)
    {
        self::assertSame($expectedData, $identifier->getData());
    }

    public function getDataDataProvider(): array
    {
        $propertiesFactory = PropertiesFactory::createFactory();
        $properties = $propertiesFactory->create('$".selector"') ?? \Mockery::mock(Properties::class);

        return [
            'default' => [
                'identifier' => new Identifier('$".selector"', $properties),
                'expectedData' => [
                    'source' => '$".selector"',
                    'properties' => $properties->getData(),
                ],

            ],
        ];
    }

    /**
     * @dataProvider isAttributeDataProvider
     */
    public function testIsAttribute(Identifier $identifier, bool $expectedIsAttribute)
    {
        self::assertSame($expectedIsAttribute, $identifier->isAttribute());
    }

    public function isAttributeDataProvider(): array
    {
        $elementProperties = \Mockery::mock(Properties::class);
        $elementProperties
            ->shouldReceive('hasAttribute')
            ->andReturnFalse();

        $attributeProperties = \Mockery::mock(Properties::class);
        $attributeProperties
            ->shouldReceive('hasAttribute')
            ->andReturnTrue();

        return [
            'not attribute' => [
                'identifier' => new Identifier('$".selector"', $elementProperties),
                'expectedHasAttribute' => false,
            ],
            'is attribute' => [
                'identifier' => new Identifier('$".selector".attribute_name', $attributeProperties),
                'expectedHasAttribute' => true,
            ],
        ];
    }
}
