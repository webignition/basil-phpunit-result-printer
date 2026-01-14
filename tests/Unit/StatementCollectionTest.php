<?php

declare(strict_types=1);

namespace Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use webignition\BasilModels\Model\StatementInterface;
use webignition\BasilPhpUnitResultPrinter\StatementCollection;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTestCase;

class StatementCollectionTest extends AbstractBaseTestCase
{
    /**
     * @param StatementInterface[] $expected
     */
    #[DataProvider('getHandledStatementsDataProvider')]
    public function testGetHandledStatements(
        StatementCollection $collection,
        ?StatementInterface $failedStatement,
        array $expected
    ): void {
        self::assertEquals($expected, $collection->getHandledStatements($failedStatement));
    }

    /**
     * @return array<mixed>
     */
    public static function getHandledStatementsDataProvider(): array
    {
        $statements = [];

        for ($i = 0; $i < 10; ++$i) {
            $statement = \Mockery::mock(StatementInterface::class);
            $statement
                ->shouldReceive('getIndex')
                ->andReturn($i)
            ;

            $statements[$i] = $statement;
        }

        return [
            'empty' => [
                'collection' => new StatementCollection([]),
                'failedStatement' => null,
                'expected' => [],
            ],
            'single statement, no failed statement' => [
                'collection' => new StatementCollection([
                    $statements[0],
                ]),
                'failedStatement' => null,
                'expected' => [
                    $statements[0],
                ],
            ],
            'three statements, no failed statement' => [
                'collection' => new StatementCollection([
                    $statements[0],
                    $statements[3],
                    $statements[7],
                ]),
                'failedStatement' => null,
                'expected' => [
                    $statements[0],
                    $statements[3],
                    $statements[7],
                ],
            ],
            'single statement, is failed' => [
                'collection' => new StatementCollection([
                    $statements[0],
                ]),
                'failedStatement' => $statements[0],
                'expected' => [],
            ],
            'three statements, first is failed' => [
                'collection' => new StatementCollection([
                    $statements[0],
                    $statements[1],
                    $statements[2],
                ]),
                'failedStatement' => $statements[0],
                'expected' => [],
            ],
            'three statements, second is failed' => [
                'collection' => new StatementCollection([
                    $statements[0],
                    $statements[1],
                    $statements[2],
                ]),
                'failedStatement' => $statements[1],
                'expected' => [
                    $statements[0],
                ],
            ],
            'three statements, third is failed' => [
                'collection' => new StatementCollection([
                    $statements[0],
                    $statements[1],
                    $statements[2],
                ]),
                'failedStatement' => $statements[2],
                'expected' => [
                    $statements[0],
                    $statements[1],
                ],
            ],
        ];
    }
}
