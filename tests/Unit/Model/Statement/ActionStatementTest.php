<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Statement;

use webignition\BasilPhpUnitResultPrinter\Enum\StatementType;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\NodeSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Model\ExceptionData\InvalidLocatorExceptionData;
use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\Statement;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\StatementInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\Transformation;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTestCase;

class ActionStatementTest extends AbstractBaseTestCase
{
    /**
     * @dataProvider createDataProvider
     *
     * @param array<mixed> $transformations
     */
    public function testCreate(
        string $source,
        string $status,
        array $transformations,
        StatementInterface $expectedStatement
    ): void {
        $statement = new Statement(StatementType::ACTION, $source, $status, $transformations);

        self::assertEquals($expectedStatement, $statement);
    }

    /**
     * @return array<mixed>
     */
    public static function createDataProvider(): array
    {
        $statusPassed = (string) new Status(Status::STATUS_PASSED);
        $statusFailed = (string) new Status(Status::STATUS_FAILED);

        return [
            'passed, no transformations' => [
                'source' => 'click $".selector"',
                'status' => $statusPassed,
                'transformations' => [],
                'expectedStatement' => new Statement(
                    StatementType::ACTION,
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
                'expectedStatement' => new Statement(
                    StatementType::ACTION,
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
                'expectedStatement' => new Statement(
                    StatementType::ACTION,
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
                'expectedStatement' => new Statement(
                    StatementType::ACTION,
                    'click $".selector"',
                    $statusFailed
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
        $statusFailed = (string) new Status(Status::STATUS_FAILED);

        $resolutionTransformation = new Transformation(
            Transformation::TYPE_RESOLUTION,
            'click $page_import_name.elements.element_name'
        );

        $nodeSource = NodeSourceFactory::createFactory()->create('$"a[href=https://example.com]"');

        $invalidLocatorExceptionData = new InvalidLocatorExceptionData(
            'css',
            'a[href=https://example.com]',
            $nodeSource ?? \Mockery::mock(NodeSource::class)
        );

        return [
            'passed, no transformations' => [
                'statement' => new Statement(
                    StatementType::ACTION,
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
                'statement' => new Statement(
                    StatementType::ACTION,
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
                'statement' => new Statement(
                    StatementType::ACTION,
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
                'statement' => new Statement(
                    StatementType::ACTION,
                    'click $".selector"',
                    $statusFailed,
                ),
                'expectedData' => [
                    'type' => 'action',
                    'source' => 'click $".selector"',
                    'status' => $statusFailed,
                ],
            ],
            'failed, has invalid locator exception' => [
                'statement' => new Statement(
                    StatementType::ACTION,
                    'click $"a[href=https://example.com]"',
                    $statusFailed,
                )->withExceptionData($invalidLocatorExceptionData),
                'expectedData' => [
                    'type' => 'action',
                    'source' => 'click $"a[href=https://example.com]"',
                    'status' => $statusFailed,
                    'exception' => $invalidLocatorExceptionData->getData(),
                ],
            ],
        ];
    }
}
