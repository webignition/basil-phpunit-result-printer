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
    public function testCreate(string $type, string $locator, int $position)
    {
        $properties = new Properties($type, $locator, $position);

        self::assertSame($type, ObjectReflector::getProperty($properties, 'type'));
        self::assertSame($locator, ObjectReflector::getProperty($properties, 'locator'));
        self::assertSame($position, ObjectReflector::getProperty($properties, 'position'));
        self::assertNull(ObjectReflector::getProperty($properties, 'attribute'));
        self::assertNull(ObjectReflector::getProperty($properties, 'parent'));
    }

    public function createDataProvider(): array
    {
        return [
            'css' => [
                'type' => Properties::TYPE_CSS,
                'locator' => '.selector',
                'position' => 1,
                'parent' => null,
            ],
            'xpath' => [
                'type' => Properties::TYPE_XPATH,
                'locator' => '//div/p',
                'position' => 1,
                'parent' => null,
            ],
        ];
    }

    public function testWithAttribute()
    {
        $properties = new Properties(Properties::TYPE_CSS, '.selector', 1);
        self::assertNull(ObjectReflector::getProperty($properties, 'attribute'));

        $attribute = 'attribute_name';
        $properties = $properties->withAttribute($attribute);
        self::assertSame($attribute, ObjectReflector::getProperty($properties, 'attribute'));
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
            'css, without attribute, without parent' => [
                'properties' => new Properties(Properties::TYPE_CSS, '.selector', 1),
                'expectedData' => [
                    'type' => Properties::TYPE_CSS,
                    'locator' => '.selector',
                    'position' => 1,
                ],

            ],
            'xpath, without attribute, without parent' => [
                'properties' => new Properties(Properties::TYPE_XPATH, '//div/p', 1),
                'expectedData' => [
                    'type' => Properties::TYPE_XPATH,
                    'locator' => '//div/p',
                    'position' => 1,
                ],
            ],
            'css, with attribute, without parent' => [
                'properties' => (new Properties(
                    Properties::TYPE_CSS,
                    '.selector',
                    1
                ))->withAttribute('attribute_name'),
                'expectedData' => [
                    'type' => Properties::TYPE_CSS,
                    'locator' => '.selector',
                    'position' => 1,
                    'attribute' => 'attribute_name',
                ],

            ],
            'css, without attribute, with parent' => [
                'properties' => (new Properties(
                    Properties::TYPE_CSS,
                    '.child',
                    2
                ))->withParent(new Properties(
                    Properties::TYPE_CSS,
                    '.parent',
                    1
                )),
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
            'css, with attribute, with parent' => [
                'properties' => (new Properties(
                    Properties::TYPE_CSS,
                    '.child',
                    2
                ))
                    ->withAttribute('attribute_name')
                    ->withParent(new Properties(
                        Properties::TYPE_CSS,
                        '.parent',
                        1
                    )),
                'expectedData' => [
                    'type' => Properties::TYPE_CSS,
                    'locator' => '.child',
                    'position' => 2,
                    'attribute' => 'attribute_name',
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
