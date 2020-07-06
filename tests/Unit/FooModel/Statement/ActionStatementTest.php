<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel\Statement;

use webignition\BasilPhpUnitResultPrinter\FooModel\Statement\ActionStatement;
use webignition\BasilPhpUnitResultPrinter\FooModel\Statement\Transformation;
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
        return [
            'passed, no transformations' => [
                'source' => 'click $".selector"',
                'status' => 'passed',
                'transformations' => [],
                'expectedStatement' => new ActionStatement(
                    'click $".selector"',
                    'passed'
                ),
            ],
            'passed, has invalid transformations' => [
                'source' => 'click $".selector"',
                'status' => 'passed',
                'transformations' => [
                    new \stdClass(),
                ],
                'expectedStatement' => new ActionStatement(
                    'click $".selector"',
                    'passed'
                ),
            ],
            'passed, has transformations' => [
                'source' => 'click $".selector"',
                'status' => 'passed',
                'transformations' => [
                    new Transformation(
                        Transformation::TYPE_RESOLUTION,
                        'click $page_import_name.elements.element_name'
                    ),
                ],
                'expectedStatement' => new ActionStatement(
                    'click $".selector"',
                    'passed',
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
                'status' => 'failed',
                'transformations' => [],
                'expectedStatement' => new ActionStatement(
                    'click $".selector"',
                    'failed'
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
        return [
            'passed, no transformations' => [
                'statement' => new ActionStatement(
                    'click $".selector"',
                    'passed',
                ),
                'expectedData' => [
                    'type' => 'action',
                    'source' => 'click $".selector"',
                    'status' => 'passed',
                ],
            ],
            'passed, has invalid transformations' => [
                'statement' => new ActionStatement(
                    'click $".selector"',
                    'passed',
                    [
                        new \stdClass(),
                    ]
                ),
                'expectedData' => [
                    'type' => 'action',
                    'source' => 'click $".selector"',
                    'status' => 'passed',
                ],
            ],
            'passed, has transformations' => [
                'statement' => new ActionStatement(
                    'click $".selector"',
                    'passed',
                    [
                        new Transformation(
                            Transformation::TYPE_RESOLUTION,
                            'click $page_import_name.elements.element_name'
                        ),
                    ]
                ),
                'expectedData' => [
                    'type' => 'action',
                    'source' => 'click $".selector"',
                    'status' => 'passed',
                    'transformations' => [
                        [
                            'type' => Transformation::TYPE_RESOLUTION,
                            'source' => 'click $page_import_name.elements.element_name',
                        ],
                    ],
                ],
            ],
            'failed' => [
                'statement' => new ActionStatement(
                    'click $".selector"',
                    'failed',
                ),
                'expectedData' => [
                    'type' => 'action',
                    'source' => 'click $".selector"',
                    'status' => 'failed',
                ],
            ],
        ];
    }
}
