<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\ComponentIdentifiedBy;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\DomElementIdentifier\AttributeIdentifier;
use webignition\DomElementIdentifier\ElementIdentifier;

class ComponentIdentifiedByTest extends AbstractBaseTest
{
    /**
     * @dataProvider renderDataProvider
     */
    public function testRender(ComponentIdentifiedBy $componentIdentifiedBy, string $expectedRenderedString)
    {
        self::assertSame($expectedRenderedString, $componentIdentifiedBy->render());
    }

    public function renderDataProvider(): array
    {
        return [
            'element' => [
                'componentIdentifiedBy' => new ComponentIdentifiedBy(
                    new ElementIdentifier('.selector')
                ),
                'expectedRenderedString' => 'element <comment>$".selector"</comment> identified by:',
            ],
            'element with ordinal position' => [
                'componentIdentifiedBy' => new ComponentIdentifiedBy(
                    new ElementIdentifier('.selector', 2)
                ),
                'expectedRenderedString' => 'element <comment>$".selector":2</comment> identified by:',
            ],
            'attribute' => [
                'componentIdentifiedBy' => new ComponentIdentifiedBy(
                    new AttributeIdentifier('.selector', 'attribute_name')
                ),
                'expectedRenderedString' =>
                    'attribute <comment>$".selector".attribute_name</comment> identified by:',
            ],
        ];
    }
}
