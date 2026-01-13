<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Statement;

use webignition\BasilPhpUnitResultPrinter\Enum\StatementType;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\Statement;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\StatementInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\Transformation;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTestCase;

class PassedAssertionStatementTest extends AbstractBaseTestCase
{
    /**
     * @dataProvider createDataProvider
     *
     * @param array<mixed> $transformations
     */
    public function testCreate(
        string $source,
        array $transformations,
        StatementInterface $expectedStatement
    ): void {
        $statement = new Statement(
            StatementType::ASSERTION,
            $source,
            (string) new Status(Status::STATUS_PASSED),
            $transformations
        );

        self::assertEquals($expectedStatement, $statement);
    }

    /**
     * @return array<mixed>
     */
    public static function createDataProvider(): array
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
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '$page.url is "http://example.com/"',
                    (string) new Status(Status::STATUS_PASSED),
                ),
            ],
            'invalid transformations' => [
                'source' => '$page.url is "http://example.com/"',
                'transformations' => [
                    new \stdClass(),
                ],
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '$page.url is "http://example.com/"',
                    (string) new Status(Status::STATUS_PASSED),
                ),
            ],
            'valid transformations' => [
                'source' => '$".selector" exists',
                'transformations' => $transformations,
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" exists',
                    (string) new Status(Status::STATUS_PASSED),
                    $transformations,
                ),
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param array<mixed> $expectedData
     */
    public function testGetData(StatementInterface $statement, array $expectedData): void
    {
        self::assertSame($expectedData, $statement->getData());
    }

    /**
     * @return array<mixed>
     */
    public static function getDataDataProvider(): array
    {
        $statusPassed = (string) new Status(Status::STATUS_PASSED);

        $resolutionTransformation = new Transformation(
            Transformation::TYPE_RESOLUTION,
            '$page_import_name.elements.element_name exists'
        );

        return [
            'no transformations' => [
                'statement' => new Statement(
                    StatementType::ASSERTION,
                    '$page.url is "http://example.com/"',
                    (string) new Status(Status::STATUS_PASSED),
                ),
                'expectedData' => [
                    'type' => 'assertion',
                    'source' => '$page.url is "http://example.com/"',
                    'status' => $statusPassed,
                ],
            ],
            'invalid transformations' => [
                'statement' => new Statement(
                    StatementType::ASSERTION,
                    '$page.url is "http://example.com/"',
                    (string) new Status(Status::STATUS_PASSED),
                ),
                'expectedData' => [
                    'type' => 'assertion',
                    'source' => '$page.url is "http://example.com/"',
                    'status' => $statusPassed,
                ],
            ],
            'valid transformations' => [
                'statement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" exists',
                    (string) new Status(Status::STATUS_PASSED),
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
