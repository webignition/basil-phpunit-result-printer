<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Statement;

use webignition\BasilParser\AssertionParser;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\AssertionFailureSummaryFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\NodeSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\AssertionFailureSummaryInterface;
use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\Existence;
use webignition\BasilPhpUnitResultPrinter\Model\ExceptionData\InvalidLocatorExceptionData;
use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\FailedAssertionStatement;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\Transformation;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class FailedAssertionStatementTest extends AbstractBaseTest
{
    /**
     * @dataProvider createDataProvider
     *
     * @param string $source
     * @param AssertionFailureSummaryInterface $summary
     * @param array<mixed> $transformations
     * @param FailedAssertionStatement $expectedStatement
     */
    public function testCreate(
        string $source,
        AssertionFailureSummaryInterface $summary,
        array $transformations,
        FailedAssertionStatement $expectedStatement
    ) {
        $statement = new FailedAssertionStatement($source, $summary, $transformations);

        self::assertEquals($expectedStatement, $statement);
    }

    public function createDataProvider(): array
    {
        $assertionParser = AssertionParser::create();
        $existsAssertion = $assertionParser->parse('$".selector" exists');

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
                'expectedStatement' => new FailedAssertionStatement('$".selector" exists', $existenceSummary),
            ],
            'invalid transformations' => [
                'source' => '$".selector" exists',
                'summary' => $existenceSummary,
                'transformations' => [
                    new \stdClass(),
                ],
                'expectedStatement' => new FailedAssertionStatement('$".selector" exists', $existenceSummary),
            ],
            'valid transformations' => [
                'source' => '$".selector" exists',
                'summary' => $existenceSummary,
                'transformations' => $transformations,
                'expectedStatement' => new FailedAssertionStatement(
                    '$".selector" exists',
                    $existenceSummary,
                    $transformations
                ),
            ],
        ];
    }

    /**
     * @dataProvider getDataDataProvider
     *
     * @param FailedAssertionStatement $statement
     * @param array<mixed> $expectedData
     */
    public function testGetData(FailedAssertionStatement $statement, array $expectedData)
    {
        self::assertSame($expectedData, $statement->getData());
    }

    public function getDataDataProvider(): array
    {
        $assertionParser = AssertionParser::create();
        $existsAssertion = $assertionParser->parse('$".selector" exists');

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
                'statement' => new FailedAssertionStatement('$".selector" exists', $existenceSummary),
                'expectedData' => [
                    'type' => 'assertion',
                    'source' => '$".selector" exists',
                    'status' => $statusFailed,
                    'summary' => $existenceSummary->getData(),
                ],
            ],
            'invalid transformations' => [
                'statement' => new FailedAssertionStatement('$".selector" exists', $existenceSummary),
                'expectedData' => [
                    'type' => 'assertion',
                    'source' => '$".selector" exists',
                    'status' => $statusFailed,
                    'summary' => $existenceSummary->getData(),
                ],
            ],
            'valid transformations' => [
                'statement' => new FailedAssertionStatement(
                    '$".selector" exists',
                    $existenceSummary,
                    $transformations
                ),
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
                'statement' => (
                    new FailedAssertionStatement('$"a[href=https://example.com]" exists', $existenceSummary)
                )->withExceptionData($invalidLocatorExceptionData),
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
