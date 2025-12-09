<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Identifier;

use webignition\BasilPhpUnitResultPrinter\Model\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTestCase;
use webignition\ObjectReflector\ObjectReflector;

class PropertiesTest extends AbstractBaseTestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $type, string $locator, int $position): void
    {
        $properties = new Properties($type, $locator, $position);

        self::assertSame($type, ObjectReflector::getProperty($properties, 'type'));
        self::assertSame($locator, ObjectReflector::getProperty($properties, 'locator'));
        self::assertSame($position, ObjectReflector::getProperty($properties, 'position'));
        self::assertNull(ObjectReflector::getProperty($properties, 'attribute'));
        self::assertNull(ObjectReflector::getProperty($properties, 'parent'));
    }

    /**
     * @return array<mixed>
     */
    public static function createDataProvider(): array
    {
        return [
            'css' => [
                'type' => Properties::TYPE_CSS,
                'locator' => '.selector',
                'position' => 1,
            ],
            'xpath' => [
                'type' => Properties::TYPE_XPATH,
                'locator' => '//div/p',
                'position' => 1,
            ],
        ];
    }

    public function testWithAttribute(): void
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
     * @param array<mixed> $expectedData
     */
    public function testGetData(Properties $properties, array $expectedData): void
    {
        self::assertSame($expectedData, $properties->getData());
    }

    /**
     * @return array<mixed>
     */
    public static function getDataDataProvider(): array
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

    /**
     * @dataProvider hasAttributeDataProvider
     */
    public function testHasAttribute(Properties $properties, bool $expectedHasAttribute): void
    {
        self::assertSame($expectedHasAttribute, $properties->hasAttribute());
    }

    /**
     * @return array<mixed>
     */
    public static function hasAttributeDataProvider(): array
    {
        return [
            'not has attribute' => [
                'properties' => new Properties(
                    Properties::TYPE_CSS,
                    '.selector',
                    1
                ),
                'expectedHasAttribute' => false,
            ],
            'has attribute' => [
                'properties' => (new Properties(
                    Properties::TYPE_CSS,
                    '.selector',
                    1
                ))->withAttribute('attribute'),
                'expectedHasAttribute' => true,
            ],
        ];
    }
}
