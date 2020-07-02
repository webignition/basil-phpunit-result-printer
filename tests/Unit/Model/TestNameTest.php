<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model;

use webignition\BasilPhpUnitResultPrinter\Model\TestName;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class TestNameTest extends AbstractBaseTest
{
    /**
     * @dataProvider renderDataProvider
     */
    public function testRender(TestName $testName, string $expectedRenderedString)
    {
        self::assertSame($expectedRenderedString, $testName->render());
    }

    public function renderDataProvider(): array
    {
        return [
            'default' => [
                'testName' => new TestName('/test.yml'),
                'expectedRenderedString' => '<test-name>/test.yml</test-name>',
            ],
        ];
    }
}
