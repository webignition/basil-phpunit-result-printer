<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel\Identifier;

use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class PropertiesTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $type, string $locator, int $position, ?Properties $parent)
    {
        self::assertTrue(true);

        $properties = new Properties($type, $locator, $position, $parent);

        $this->assertSame($type, ObjectReflector::getProperty($properties, 'type'));
        $this->assertSame($locator, ObjectReflector::getProperty($properties, 'locator'));
        $this->assertSame($position, ObjectReflector::getProperty($properties, 'position'));
        $this->assertSame($parent, ObjectReflector::getProperty($properties, 'parent'));
    }

    public function createDataProvider(): array
    {
        return [
            'css, without parent' => [
                'type' => Properties::TYPE_CSS,
                'locator' => '.selector',
                'position' => 1,
                'parent' => null,
            ],
            'xpath, without parent' => [
                'type' => Properties::TYPE_XPATH,
                'locator' => '//div/p',
                'position' => 1,
                'parent' => null,
            ],
            'css, with parent' => [
                'type' => Properties::TYPE_CSS,
                'locator' => '.child',
                'position' => 2,
                'parent' => new Properties(
                    Properties::TYPE_CSS,
                    '.parent',
                    1
                ),
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param Properties $properties
     * @param array<mixed> $expectedData
     */
    public function testGetData(Properties $properties, array $expectedData)
    {
        self::assertSame($expectedData, $properties->getData());
    }

    public function getDataDataProvider(): array
    {
        return [
            'css, without parent' => [
                'properties' => new Properties(Properties::TYPE_CSS, '.selector', 1),
                'expectedData' => [
                    'type' => Properties::TYPE_CSS,
                    'locator' => '.selector',
                    'position' => 1,
                ],

            ],
            'xpath, without parent' => [
                'properties' => new Properties(Properties::TYPE_XPATH, '//div/p', 1),
                'expectedData' => [
                    'type' => Properties::TYPE_XPATH,
                    'locator' => '//div/p',
                    'position' => 1,
                ],
            ],
            'css, with parent' => [
                'properties' => new Properties(
                    Properties::TYPE_CSS,
                    '.child',
                    2,
                    new Properties(
                        Properties::TYPE_CSS,
                        '.parent',
                        1
                    )
                ),
                'expectedData' => [
                    'type' => Properties::TYPE_CSS,
                    'locator' => '.child',
                    'position' => 2,
                    'parent' => [
                        'type' => Properties::TYPE_CSS,
                        'locator' => '.parent',
                        'position' => 1,
                    ],
                ],
            ],
        ];
    }
}
