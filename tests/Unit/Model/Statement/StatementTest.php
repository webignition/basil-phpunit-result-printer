<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Statement;

use PHPUnit\Framework\Attributes\DataProvider;
use webignition\BasilModels\Parser\AssertionParser;
use webignition\BasilPhpUnitResultPrinter\Enum\StatementType;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\AssertionFailureSummaryFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\NodeSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\AssertionFailureSummaryInterface;
use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\Existence;
use webignition\BasilPhpUnitResultPrinter\Model\ExceptionData\InvalidLocatorExceptionData;
use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\Statement;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\StatementInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\Transformation;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTestCase;

class StatementTest extends AbstractBaseTestCase
{
    /**
     * @param Transformation[] $transformations
     */
    #[DataProvider('createActionDataProvider')]
    #[DataProvider('createFailedAssertionDataProvider')]
    #[DataProvider('createPassedAssertionDataProvider')]
    public function testCreate(
        StatementType $statementType,
        string $source,
        string $status,
        ?AssertionFailureSummaryInterface $failureSummary,
        array $transformations,
        StatementInterface $expectedStatement
    ): void {
        $statement = new Statement(
            $statementType,
            $source,
            $status,
        )->withTransformations($transformations);

        if ($failureSummary instanceof AssertionFailureSummaryInterface) {
            $statement = $statement->withFailureSummary($failureSummary);
        }

        self::assertEquals($expectedStatement, $statement);
    }

    /**
     * @return array<mixed>
     */
    public static function createActionDataProvider(): array
    {
        $statusPassed = (string) new Status(Status::STATUS_PASSED);
        $statusFailed = (string) new Status(Status::STATUS_FAILED);

        return [
            'action, passed, no transformations' => [
                'statementType' => StatementType::ACTION,
                'source' => 'click $".selector"',
                'status' => $statusPassed,
                'failureSummary' => null,
                'transformations' => [],
                'expectedStatement' => new Statement(
                    StatementType::ACTION,
                    'click $".selector"',
                    $statusPassed
                ),
            ],
            'action, passed, has transformations' => [
                'statementType' => StatementType::ACTION,
                'source' => 'click $".selector"',
                'status' => $statusPassed,
                'failureSummary' => null,
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
                )->withTransformations([
                    new Transformation(
                        Transformation::TYPE_RESOLUTION,
                        'click $page_import_name.elements.element_name'
                    ),
                ]),
            ],
            'action, failed' => [
                'statementType' => StatementType::ACTION,
                'source' => 'click $".selector"',
                'status' => $statusFailed,
                'failureSummary' => null,
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
     * @return array<mixed>
     */
    public static function createFailedAssertionDataProvider(): array
    {
        $status = (string) new Status(Status::STATUS_FAILED);

        $assertionParser = AssertionParser::create();
        $existsAssertion = $assertionParser->parse('$".selector" exists', 0);

        $assertionFailureSummaryFactory = AssertionFailureSummaryFactory::createFactory();

        $existenceSummary = $assertionFailureSummaryFactory->create(
            $existsAssertion,
            '',
            ''
        ) ?? \Mockery::mock(Existence::class);

        $transformations = [
            new Transformation(
                Transformation::TYPE_DERIVATION,
                'click $".selector"'
            ),
            new Transformation(
                Transformation::TYPE_RESOLUTION,
                'click $page_import_name.elements.element_name'
            ),
        ];

        return [
            'assertion, failed, no transformations' => [
                'statementType' => StatementType::ASSERTION,
                'source' => '$".selector" exists',
                'status' => $status,
                'failureSummary' => $existenceSummary,
                'transformations' => [],
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" exists',
                    $status,
                )->withFailureSummary($existenceSummary),
            ],
            'assertion, failed, valid transformations' => [
                'statementType' => StatementType::ASSERTION,
                'source' => '$".selector" exists',
                'status' => $status,
                'failureSummary' => $existenceSummary,
                'transformations' => $transformations,
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" exists',
                    $status,
                )
                    ->withFailureSummary($existenceSummary)
                    ->withTransformations($transformations),
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function createPassedAssertionDataProvider(): array
    {
        $status = (string) new Status(Status::STATUS_PASSED);

        $transformations = [
            new Transformation(
                Transformation::TYPE_RESOLUTION,
                '$page_import_name.elements.element_name exists'
            ),
        ];

        return [
            'no transformations' => [
                'statementType' => StatementType::ASSERTION,
                'source' => '$page.url is "http://example.com/"',
                'status' => $status,
                'failureSummary' => null,
                'transformations' => [],
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '$page.url is "http://example.com/"',
                    (string) new Status(Status::STATUS_PASSED),
                ),
            ],
            'valid transformations' => [
                'statementType' => StatementType::ASSERTION,
                'source' => '$".selector" exists',
                'status' => $status,
                'failureSummary' => null,
                'transformations' => $transformations,
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" exists',
                    (string) new Status(Status::STATUS_PASSED),
                )->withTransformations($transformations),
            ],
        ];
    }

    /**
     * @param array<mixed> $expectedData
     */
    #[DataProvider('actionGetDataDataProvider')]
    #[DataProvider('failedAssertionGetDataDataProvider')]
    #[DataProvider('passedAssertionGetDataDataProvider')]
    public function testGetData(StatementInterface $statement, array $expectedData): void
    {
        self::assertSame($expectedData, $statement->getData());
    }

    /**
     * @return array<mixed>
     */
    public static function actionGetDataDataProvider(): array
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
            'action, passed, no transformations' => [
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
            'action, passed, has transformations' => [
                'statement' => new Statement(
                    StatementType::ACTION,
                    'click $".selector"',
                    $statusPassed,
                )->withTransformations([
                    $resolutionTransformation,
                ]),
                'expectedData' => [
                    'type' => 'action',
                    'source' => 'click $".selector"',
                    'status' => $statusPassed,
                    'transformations' => [
                        $resolutionTransformation->getData(),
                    ],
                ],
            ],
            'action, failed' => [
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
            'action, failed, has invalid locator exception' => [
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

    /**
     * @return array<mixed>
     */
    public static function failedAssertionGetDataDataProvider(): array
    {
        $assertionParser = AssertionParser::create();
        $existsAssertion = $assertionParser->parse('$".selector" exists', 0);

        $assertionFailureSummaryFactory = AssertionFailureSummaryFactory::createFactory();

        $existenceSummary = $assertionFailureSummaryFactory->create(
            $existsAssertion,
            '',
            ''
        ) ?? \Mockery::mock(Existence::class);

        $statusFailed = (string) new Status(Status::STATUS_FAILED);

        $derivationTransformation = new Transformation(
            Transformation::TYPE_DERIVATION,
            'click $".selector"'
        );

        $resolutionTransformation = new Transformation(
            Transformation::TYPE_RESOLUTION,
            'click $page_import_name.elements.element_name'
        );

        $transformations = [
            $derivationTransformation,
            $resolutionTransformation,
        ];

        $nodeSource = NodeSourceFactory::createFactory()->create('$"a[href=https://example.com]"');

        $invalidLocatorExceptionData = new InvalidLocatorExceptionData(
            'css',
            'a[href=https://example.com]',
            $nodeSource ?? \Mockery::mock(NodeSource::class)
        );

        return [
            'assertion, failed, no transformations' => [
                'statement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" exists',
                    (string) new Status(Status::STATUS_FAILED),
                )->withFailureSummary($existenceSummary),
                'expectedData' => [
                    'type' => 'assertion',
                    'source' => '$".selector" exists',
                    'status' => $statusFailed,
                    'summary' => $existenceSummary->getData(),
                ],
            ],
            'assertion, failed, valid transformations' => [
                'statement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" exists',
                    (string) new Status(Status::STATUS_FAILED),
                )
                    ->withFailureSummary($existenceSummary)
                    ->withTransformations($transformations),
                'expectedData' => [
                    'type' => 'assertion',
                    'source' => '$".selector" exists',
                    'status' => $statusFailed,
                    'transformations' => [
                        $derivationTransformation->getData(),
                        $resolutionTransformation->getData(),
                    ],
                    'summary' => $existenceSummary->getData(),
                ],
            ],
            'assertion, failed, no transformations, has invalid locator exception' => [
                'statement' => new Statement(
                    StatementType::ASSERTION,
                    '$"a[href=https://example.com]" exists',
                    (string) new Status(Status::STATUS_FAILED),
                )
                    ->withExceptionData($invalidLocatorExceptionData)
                    ->withFailureSummary($existenceSummary),
                'expectedData' => [
                    'type' => 'assertion',
                    'source' => '$"a[href=https://example.com]" exists',
                    'status' => $statusFailed,
                    'exception' => $invalidLocatorExceptionData->getData(),
                    'summary' => $existenceSummary->getData(),
                ],
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function passedAssertionGetDataDataProvider(): array
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
            'valid transformations' => [
                'statement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" exists',
                    (string) new Status(Status::STATUS_PASSED),
                )->withTransformations([
                    $resolutionTransformation,
                ]),
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
