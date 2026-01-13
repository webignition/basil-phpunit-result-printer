<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Statement;

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

class FailedAssertionStatementTest extends AbstractBaseTestCase
{
    /**
     * @dataProvider createDataProvider
     *
     * @param array<mixed> $transformations
     */
    public function testCreate(
        string $source,
        AssertionFailureSummaryInterface $summary,
        array $transformations,
        StatementInterface $expectedStatement
    ): void {
        $statement = new Statement(
            StatementType::ASSERTION,
            $source,
            (string) new Status(Status::STATUS_FAILED),
            $transformations
        )->withFailureSummary($summary);

        self::assertEquals($expectedStatement, $statement);
    }

    /**
     * @return array<mixed>
     */
    public static function createDataProvider(): array
    {
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
            'no transformations' => [
                'source' => '$".selector" exists',
                'summary' => $existenceSummary,
                'transformations' => [],
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" exists',
                    (string) new Status(Status::STATUS_FAILED),
                )->withFailureSummary($existenceSummary),
            ],
            'invalid transformations' => [
                'source' => '$".selector" exists',
                'summary' => $existenceSummary,
                'transformations' => [
                    new \stdClass(),
                ],
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" exists',
                    (string) new Status(Status::STATUS_FAILED),
                )->withFailureSummary($existenceSummary),
            ],
            'valid transformations' => [
                'source' => '$".selector" exists',
                'summary' => $existenceSummary,
                'transformations' => $transformations,
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" exists',
                    (string) new Status(Status::STATUS_FAILED),
                    $transformations
                )->withFailureSummary($existenceSummary),
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
            'no transformations' => [
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
            'invalid transformations' => [
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
            'valid transformations' => [
                'statement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" exists',
                    (string) new Status(Status::STATUS_FAILED),
                    $transformations
                )->withFailureSummary($existenceSummary),
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
            'no transformations, has invalid locator exception' => [
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
}
