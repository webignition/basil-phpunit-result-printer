<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Identifier;

use PHPUnit\Framework\Attributes\DataProvider;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Identifier\PropertiesFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\Model\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTestCase;
use webignition\ObjectReflector\ObjectReflector;

class IdentifierTest extends AbstractBaseTestCase
{
    #[DataProvider('createDataProvider')]
    public function testCreate(string $source, Properties $properties): void
    {
        $identifier = new Identifier($source, $properties);

        self::assertSame($source, ObjectReflector::getProperty($identifier, 'source'));
        self::assertSame($properties, ObjectReflector::getProperty($identifier, 'properties'));
    }

    /**
     * @return array<mixed>
     */
    public static function createDataProvider(): array
    {
        return [
            'default' => [
                'source' => '$".selector"',
                'properties' => \Mockery::mock(Properties::class),
            ],
        ];
    }

    /**
     * @param array<mixed> $expectedData
     */
    #[DataProvider('getDataDataProvider')]
    public function testGetData(Identifier $identifier, array $expectedData): void
    {
        self::assertSame($expectedData, $identifier->getData());
    }

    /**
     * @return array<mixed>
     */
    public static function getDataDataProvider(): array
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

    #[DataProvider('isAttributeDataProvider')]
    public function testIsAttribute(Identifier $identifier, bool $expected): void
    {
        self::assertSame($expected, $identifier->isAttribute());
    }

    /**
     * @return array<mixed>
     */
    public static function isAttributeDataProvider(): array
    {
        $elementProperties = \Mockery::mock(Properties::class);
        $elementProperties
            ->shouldReceive('hasAttribute')
            ->andReturnFalse()
        ;

        $attributeProperties = \Mockery::mock(Properties::class);
        $attributeProperties
            ->shouldReceive('hasAttribute')
            ->andReturnTrue()
        ;

        return [
            'not attribute' => [
                'identifier' => new Identifier('$".selector"', $elementProperties),
                'expected' => false,
            ],
            'is attribute' => [
                'identifier' => new Identifier('$".selector".attribute_name', $attributeProperties),
                'expected' => true,
            ],
        ];
    }
}
