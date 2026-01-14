<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Statement;

use PHPUnit\Framework\Attributes\DataProvider;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\Transformation;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTestCase;
use webignition\ObjectReflector\ObjectReflector;

class TransformationTest extends AbstractBaseTestCase
{
    #[DataProvider('createDataProvider')]
    public function testCreate(string $type, string $source): void
    {
        $transformation = new Transformation($type, $source);

        self::assertSame($type, ObjectReflector::getProperty($transformation, 'type'));
        self::assertSame($source, ObjectReflector::getProperty($transformation, 'source'));
    }

    /**
     * @return array<mixed>
     */
    public static function createDataProvider(): array
    {
        return [
            'derivation' => [
                'type' => Transformation::TYPE_DERIVATION,
                'source' => 'click $".selector"',
            ],
            'resolution' => [
                'type' => Transformation::TYPE_RESOLUTION,
                'source' => 'click $page_import_name.elements.element_name',
            ],
        ];
    }

    /**
     * @param array<mixed> $expectedData
     */
    #[DataProvider('getDataDataProvider')]
    public function testGetData(Transformation $transformation, array $expectedData): void
    {
        self::assertSame($expectedData, $transformation->getData());
    }

    /**
     * @return array<mixed>
     */
    public static function getDataDataProvider(): array
    {
        return [
            'derivation' => [
                'transformation' => new Transformation(
                    Transformation::TYPE_DERIVATION,
                    'click $".selector"'
                ),
                'expectedData' => [
                    'type' => Transformation::TYPE_DERIVATION,
                    'source' => 'click $".selector"',
                ],
            ],
            'resolution' => [
                'transformation' => new Transformation(
                    Transformation::TYPE_RESOLUTION,
                    'click $page_import_name.elements.element_name'
                ),
                'expectedData' => [
                    'type' => Transformation::TYPE_RESOLUTION,
                    'source' => 'click $page_import_name.elements.element_name',
                ],
            ],
        ];
    }
}
