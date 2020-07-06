<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\FooModel\Statement;

use webignition\BasilPhpUnitResultPrinter\FooModel\Statement\PassedAssertionStatement;
use webignition\BasilPhpUnitResultPrinter\FooModel\Statement\Transformation;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class PassedAssertionStatementTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     *
     * @param string $source
     * @param array<mixed> $transformations
     * @param PassedAssertionStatement $expectedStatement
     */
    public function testCreate(
        string $source,
        array $transformations,
        PassedAssertionStatement $expectedStatement
    ) {
        $statement = new PassedAssertionStatement($source, $transformations);

        self::assertEquals($expectedStatement, $statement);
    }

    public function createDataProvider(): array
    {
        return [
            'no transformations' => [
                'source' => '$page.url is "http://example.com/"',
                'transformations' => [],
                'expectedStatement' => new PassedAssertionStatement('$page.url is "http://example.com/"'),
            ],
            'invalid transformations' => [
                'source' => '$page.url is "http://example.com/"',
                'transformations' => [
                    new \stdClass(),
                ],
                'expectedStatement' => new PassedAssertionStatement('$page.url is "http://example.com/"'),
            ],
            'valid transformations' => [
                'source' => '$".selector" exists',
                'transformations' => [
                    new Transformation(
                        Transformation::TYPE_RESOLUTION,
                        '$page_import_name.elements.element_name exists'
                    ),
                ],
                'expectedStatement' => new PassedAssertionStatement(
                    '$".selector" exists',
                    [
                        new Transformation(
                            Transformation::TYPE_RESOLUTION,
                            '$page_import_name.elements.element_name exists'
                        ),
                    ]
                ),
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param PassedAssertionStatement $statement
     * @param array<mixed> $expectedData
     */
    public function testGetData(PassedAssertionStatement $statement, array $expectedData)
    {
        self::assertSame($expectedData, $statement->getData());
    }

    public function getDataDataProvider(): array
    {
        return [
            'no transformations' => [
                'statement' => new PassedAssertionStatement('$page.url is "http://example.com/"'),
                'expectedData' => [
                    'type' => 'assertion',
                    'source' => '$page.url is "http://example.com/"',
                    'status' => 'passed',
                ],
            ],
            'invalid transformations' => [
                'statement' => new PassedAssertionStatement('$page.url is "http://example.com/"'),
                'expectedData' => [
                    'type' => 'assertion',
                    'source' => '$page.url is "http://example.com/"',
                    'status' => 'passed',
                ],
            ],
            'valid transformations' => [
                'statement' => new PassedAssertionStatement(
                    '$".selector" exists',
                    [
                        new Transformation(
                            Transformation::TYPE_RESOLUTION,
                            '$page_import_name.elements.element_name exists'
                        ),
                    ]
                ),
                'expectedData' => [
                    'type' => 'assertion',
                    'source' => '$".selector" exists',
                    'status' => 'passed',
                    'transformations' => [
                        [
                            'type' => Transformation::TYPE_RESOLUTION,
                            'source' => '$page_import_name.elements.element_name exists',
                        ],
                    ],
                ],
            ],
        ];
    }
}
