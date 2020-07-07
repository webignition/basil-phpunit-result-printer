<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel\Identifier;

use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Properties;
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
                'identifier' => new Properties(Properties::TYPE_CSS, '.selector', 1),
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
        return [
            'default' => [
                'identifier' => new Identifier(
                    '$".selector"',
                    new Properties(Properties::TYPE_CSS, '.selector', 1)
                ),
                'expectedData' => [
                    'source' => '$".selector"',
                    'properties' => [
                        'type' => Properties::TYPE_CSS,
                        'locator' => '.selector',
                        'position' => 1,
                    ],
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
        return [
            'not attribute' => [
                'identifier' => new Identifier(
                    '$".selector"',
                    new Properties(
                        Properties::TYPE_CSS,
                        '.selector',
                        1
                    )
                ),
                'expectedHasAttribute' => false,
            ],
            'is attribute' => [
                'identifier' => new Identifier(
                    '$".selector".attribute_name',
                    (new Properties(
                        Properties::TYPE_CSS,
                        '.selector',
                        1
                    ))->withAttribute('attribute_name')
                ),
                'expectedHasAttribute' => true,
            ],
        ];
    }
}
