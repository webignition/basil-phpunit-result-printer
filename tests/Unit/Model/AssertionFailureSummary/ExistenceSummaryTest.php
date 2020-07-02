<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\AssertionFailureSummary;

use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\ExistenceSummary;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\DomElementIdentifier\AttributeIdentifier;
use webignition\DomElementIdentifier\ElementIdentifier;

class ExistenceSummaryTest extends AbstractBaseTest
{
    /**
     * @dataProvider renderDataProvider
     */
    public function testRender(ExistenceSummary $existenceSummary, string $expectedRenderedString)
    {
        self::assertSame($expectedRenderedString, $existenceSummary->render());
    }

    public function renderDataProvider(): array
    {
        return [
            'element exists' => [
                'existenceSummary' => new ExistenceSummary(
                    new ElementIdentifier('.selector'),
                    'exists'
                ),
                'expectedRenderedString' =>
                    '* Element <comment>$".selector"</comment> identified by:' . "\n" .
                    '    - CSS selector: <comment>.selector</comment>' . "\n" .
                    '    - ordinal position: <comment>1</comment>' . "\n" .
                    '  does not exist',
            ],
            'descendant element exists' => [
                'existenceSummary' => new ExistenceSummary(
                    (new ElementIdentifier('.child'))
                        ->withParentIdentifier(new ElementIdentifier('.parent')),
                    'exists'
                ),
                'expectedRenderedString' =>
                    '* Element <comment>$".parent" >> $".child"</comment> identified by:' . "\n" .
                    '    - CSS selector: <comment>.child</comment>' . "\n" .
                    '    - ordinal position: <comment>1</comment>' . "\n" .
                    '  with parent:' . "\n" .
                    '    - CSS selector: <comment>.parent</comment>' . "\n" .
                    '    - ordinal position: <comment>1</comment>' . "\n" .
                    '  does not exist',
            ],
            'element not-exists' => [
                'existenceSummary' => new ExistenceSummary(
                    new ElementIdentifier('.selector'),
                    'not-exists'
                ),
                'expectedRenderedString' =>
                    '* Element <comment>$".selector"</comment> identified by:' . "\n" .
                    '    - CSS selector: <comment>.selector</comment>' . "\n" .
                    '    - ordinal position: <comment>1</comment>' . "\n" .
                    '  does exist',
            ],
            'attribute exists' => [
                'existenceSummary' => new ExistenceSummary(
                    new AttributeIdentifier('.selector', 'attribute_name'),
                    'exists'
                ),
                'expectedRenderedString' =>
                    '* Attribute <comment>$".selector".attribute_name</comment> identified by:' . "\n" .
                    '    - CSS selector: <comment>.selector</comment>' . "\n" .
                    '    - attribute name: <comment>attribute_name</comment>' . "\n" .
                    '    - ordinal position: <comment>1</comment>' . "\n" .
                    '  does not exist',
            ],
            'attribute not-exists' => [
                'existenceSummary' => new ExistenceSummary(
                    new AttributeIdentifier('.selector', 'attribute_name'),
                    'not-exists'
                ),
                'expectedRenderedString' =>
                    '* Attribute <comment>$".selector".attribute_name</comment> identified by:' . "\n" .
                    '    - CSS selector: <comment>.selector</comment>' . "\n" .
                    '    - attribute name: <comment>attribute_name</comment>' . "\n" .
                    '    - ordinal position: <comment>1</comment>' . "\n" .
                    '  does exist',
            ],
        ];
    }
}
