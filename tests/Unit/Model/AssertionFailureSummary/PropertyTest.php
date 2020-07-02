<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\Property;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class PropertyTest extends AbstractBaseTest
{
    /**
     * @dataProvider renderDataProvider
     */
    public function testRender(Property $property, string $expectedRenderedString)
    {
        self::assertSame($expectedRenderedString, $property->render());
    }

    public function renderDataProvider(): array
    {
        return [
            'no padding' => [
                'property' => new Property('key', 'value'),
                'expectedRenderedString' => '- key: <comment>value</comment>',
            ],
            'padding single space' => [
                'property' => new Property('key', 'value', ' '),
                'expectedRenderedString' => '- key:  <comment>value</comment>',
            ],
            'padding two spaces' => [
                'property' => new Property('key', 'value', '  '),
                'expectedRenderedString' => '- key:   <comment>value</comment>',
            ],
        ];
    }
}
