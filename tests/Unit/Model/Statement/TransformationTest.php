<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Statement;

use webignition\BasilPhpUnitResultPrinter\Model\Statement\Transformation;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\ObjectReflector\ObjectReflector;

class TransformationTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $type, string $source): void
    {
        $transformation = new Transformation($type, $source);

        $this->assertSame($type, ObjectReflector::getProperty($transformation, 'type'));
        $this->assertSame($source, ObjectReflector::getProperty($transformation, 'source'));
    }

    /**
     * @return array<mixed>
     */
    public function createDataProvider(): array
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
     * @dataProvider getDataDataProvider
     *
     * @param array<mixed> $expectedData
     */
    public function testGetData(Transformation $transformation, array $expectedData): void
    {
        self::assertSame($expectedData, $transformation->getData());
    }

    /**
     * @return array<mixed>
     */
    public function getDataDataProvider(): array
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
