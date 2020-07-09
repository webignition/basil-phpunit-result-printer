<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel\Statement;

use webignition\BasilPhpUnitResultPrinter\FooModel\Statement\ActionStatement;
use webignition\BasilPhpUnitResultPrinter\FooModel\Statement\Transformation;
use webignition\BasilPhpUnitResultPrinter\FooModel\Status;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class ActionStatementTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     *
     * @param string $source
     * @param string $status
     * @param array<mixed> $transformations
     * @param ActionStatement $expectedStatement
     */
    public function testCreate(
        string $source,
        string $status,
        array $transformations,
        ActionStatement $expectedStatement
    ) {
        $statement = new ActionStatement($source, $status, $transformations);

        self::assertEquals($expectedStatement, $statement);
    }

    public function createDataProvider(): array
    {
        $statusPassed = (string) new Status(Status::STATUS_PASSED);
        $statusFailed = (string) new Status(Status::STATUS_FAILED);

        return [
            'passed, no transformations' => [
                'source' => 'click $".selector"',
                'status' => $statusPassed,
                'transformations' => [],
                'expectedStatement' => new ActionStatement(
                    'click $".selector"',
                    $statusPassed
                ),
            ],
            'passed, has invalid transformations' => [
                'source' => 'click $".selector"',
                'status' => $statusPassed,
                'transformations' => [
                    new \stdClass(),
                ],
                'expectedStatement' => new ActionStatement(
                    'click $".selector"',
                    $statusPassed
                ),
            ],
            'passed, has transformations' => [
                'source' => 'click $".selector"',
                'status' => $statusPassed,
                'transformations' => [
                    new Transformation(
                        Transformation::TYPE_RESOLUTION,
                        'click $page_import_name.elements.element_name'
                    ),
                ],
                'expectedStatement' => new ActionStatement(
                    'click $".selector"',
                    $statusPassed,
                    [
                        new Transformation(
                            Transformation::TYPE_RESOLUTION,
                            'click $page_import_name.elements.element_name'
                        ),
                    ]
                ),
            ],
            'failed' => [
                'source' => 'click $".selector"',
                'status' => $statusFailed,
                'transformations' => [],
                'expectedStatement' => new ActionStatement(
                    'click $".selector"',
                    $statusFailed
                ),
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param ActionStatement $statement
     * @param array<mixed> $expectedData
     */
    public function testGetData(ActionStatement $statement, array $expectedData)
    {
        self::assertSame($expectedData, $statement->getData());
    }

    public function getDataDataProvider(): array
    {
        $statusPassed = (string) new Status(Status::STATUS_PASSED);
        $statusFailed = (string) new Status(Status::STATUS_FAILED);

        $resolutionTransformation = new Transformation(
            Transformation::TYPE_RESOLUTION,
            'click $page_import_name.elements.element_name'
        );

        return [
            'passed, no transformations' => [
                'statement' => new ActionStatement(
                    'click $".selector"',
                    $statusPassed,
                ),
                'expectedData' => [
                    'type' => 'action',
                    'source' => 'click $".selector"',
                    'status' => $statusPassed,
                ],
            ],
            'passed, has invalid transformations' => [
                'statement' => new ActionStatement(
                    'click $".selector"',
                    $statusPassed,
                    [
                        new \stdClass(),
                    ]
                ),
                'expectedData' => [
                    'type' => 'action',
                    'source' => 'click $".selector"',
                    'status' => $statusPassed,
                ],
            ],
            'passed, has transformations' => [
                'statement' => new ActionStatement(
                    'click $".selector"',
                    $statusPassed,
                    [
                        $resolutionTransformation,
                    ]
                ),
                'expectedData' => [
                    'type' => 'action',
                    'source' => 'click $".selector"',
                    'status' => $statusPassed,
                    'transformations' => [
                        $resolutionTransformation->getData(),
                    ],
                ],
            ],
            'failed' => [
                'statement' => new ActionStatement(
                    'click $".selector"',
                    $statusFailed,
                ),
                'expectedData' => [
                    'type' => 'action',
                    'source' => 'click $".selector"',
                    'status' => $statusFailed,
                ],
            ],
        ];
    }
}
