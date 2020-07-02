<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Step;

use webignition\BaseBasilTestCase\BasilTestCaseInterface;
use webignition\BasilModels\DataSet\DataSet;
use webignition\BasilModels\DataSet\DataSetInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\Model\Step\Name;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class NameTest extends AbstractBaseTest
{
    /**
     * @dataProvider renderDataProvider
     */
    public function testRender(Name $stepName, string $expectedRenderedString)
    {
        self::assertSame($expectedRenderedString, $stepName->render());
    }

    public function renderDataProvider(): array
    {
        return [
            'success' => [
                'stepName' => new Name($this->createBasilTestCase('success step name', Status::SUCCESS)),
                'expectedRenderedString' => '<icon-success /> <success>success step name</success>',
            ],
            'failure' => [
                'stepName' => new Name($this->createBasilTestCase('failure step name', Status::FAILURE)),
                'expectedRenderedString' => '<icon-failure /> <failure>failure step name</failure>',
            ],
            'success with data set' => [
                'stepName' => new Name($this->createBasilTestCase(
                    'success step name',
                    Status::SUCCESS,
                    new DataSet('data set name', [])
                )),
                'expectedRenderedString' => '<icon-success /> <success>success step name: data set name</success>',
            ],
        ];
    }

    private function createBasilTestCase(
        string $name,
        int $status,
        ?DataSetInterface $currentDataSet = null
    ): BasilTestCaseInterface {
        $step = \Mockery::mock(BasilTestCaseInterface::class);

        $step
            ->shouldReceive('getBasilStepName')
            ->andReturn($name);

        $step
            ->shouldReceive('getStatus')
            ->andReturn($status);

        $step
            ->shouldReceive('getCurrentDataSet')
            ->andReturn($currentDataSet);

        return $step;
    }
}
