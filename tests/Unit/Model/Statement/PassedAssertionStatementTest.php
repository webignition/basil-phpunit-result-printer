<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Statement;

use webignition\BasilPhpUnitResultPrinter\Model\Statement\PassedAssertionStatement;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\Transformation;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
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
        $transformations = [
            new Transformation(
                Transformation::TYPE_RESOLUTION,
                '$page_import_name.elements.element_name exists'
            ),
        ];

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
                'transformations' => $transformations,
                'expectedStatement' => new PassedAssertionStatement('$".selector" exists', $transformations),
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
        $statusPassed = (string) new Status(Status::STATUS_PASSED);

        $resolutionTransformation = new Transformation(
            Transformation::TYPE_RESOLUTION,
            '$page_import_name.elements.element_name exists'
        );

        return [
            'no transformations' => [
                'statement' => new PassedAssertionStatement('$page.url is "http://example.com/"'),
                'expectedData' => [
                    'type' => 'assertion',
                    'source' => '$page.url is "http://example.com/"',
                    'status' => $statusPassed,
                ],
            ],
            'invalid transformations' => [
                'statement' => new PassedAssertionStatement('$page.url is "http://example.com/"'),
                'expectedData' => [
                    'type' => 'assertion',
                    'source' => '$page.url is "http://example.com/"',
                    'status' => $statusPassed,
                ],
            ],
            'valid transformations' => [
                'statement' => new PassedAssertionStatement(
                    '$".selector" exists',
                    [
                        $resolutionTransformation,
                    ]
                ),
                'expectedData' => [
                    'type' => 'assertion',
                    'source' => '$".selector" exists',
                    'status' => $statusPassed,
                    'transformations' => [
                        $resolutionTransformation->getData(),
                    ],
                ],
            ],
        ];
    }
}