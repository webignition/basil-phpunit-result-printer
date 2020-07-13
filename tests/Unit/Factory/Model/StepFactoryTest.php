<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Factory;

use Facebook\WebDriver\Exception\InvalidSelectorException;
use webignition\BaseBasilTestCase\BasilTestCaseInterface;
use webignition\BasilModels\Action\ResolvedAction;
use webignition\BasilModels\Assertion\DerivedValueOperationAssertion;
use webignition\BasilParser\ActionParser;
use webignition\BasilParser\AssertionParser;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\NodeSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Statement\StatementFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\StepFactory;
use webignition\BasilPhpUnitResultPrinter\FooModel\ExceptionData\InvalidLocatorExceptionData;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\FooModel\Statement\FailedAssertionStatement;
use webignition\BasilPhpUnitResultPrinter\FooModel\Statement\StatementInterface;
use webignition\BasilPhpUnitResultPrinter\FooModel\Status;
use webignition\BasilPhpUnitResultPrinter\FooModel\Step;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;
use webignition\DomElementIdentifier\ElementIdentifier;
use webignition\SymfonyDomCrawlerNavigator\Exception\InvalidLocatorException;

class StepFactoryTest extends AbstractBaseTest
{
    private StepFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = StepFactory::createFactory();
    }

    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(BasilTestCaseInterface $testCase, Step $expectedStep)
    {
        self::assertEquals($expectedStep, $this->factory->create($testCase));
    }

    public function createDataProvider(): array
    {
        $statementFactory = StatementFactory::createFactory();

        $actionParser = ActionParser::create();
        $assertionParser = AssertionParser::create();

        $clickAction = $actionParser->parse('click $".selector"');
        $unresolvedClickAction = $actionParser->parse('click $page_import_name.elements.selector');
        $resolvedClickAction = new ResolvedAction($unresolvedClickAction, '$".selector"');

        $existsAssertion = $assertionParser->parse('$".selector" exists');
        $derivedExistsAssertion = new DerivedValueOperationAssertion($clickAction, '$".selector"', 'exists');
        $derivedResolvedExistsAssertion = new DerivedValueOperationAssertion(
            $resolvedClickAction,
            '$".selector"',
            'exists'
        );

        $isAssertion = $assertionParser->parse('$".selector" is "value"');
        $includesAssertion = $assertionParser->parse('$page.title includes "expected"');

        $statusPassedLabel = (string) new Status(Status::STATUS_PASSED);
        $statusFailedLabel = (string) new Status(Status::STATUS_FAILED);

        $invalidLocatorElementIdentifier = new ElementIdentifier('a[href=https://example.com]');

        $invalidLocatorException = new InvalidLocatorException(
            $invalidLocatorElementIdentifier,
            \Mockery::mock(InvalidSelectorException::class)
        );

        $nodeSourceFactory = NodeSourceFactory::createFactory();
        $invalidLocatorNodeSource =
            $nodeSourceFactory->create('$"a[href=https://example.com]"') ?? \Mockery::mock(NodeSource::class);

        return [
            'no statements, passed' => [
                'testCase' => $this->createBasilTestCaseFoo([
                    'basilStepName' => 'step name',
                    'status' =>  Status::STATUS_PASSED,
                ]),
                'expectedStep' => new Step('step name', $statusPassedLabel, []),
            ],
            'no statements, failed' => [
                'testCase' => $this->createBasilTestCaseFoo([
                    'basilStepName' => 'step name',
                    'status' =>  Status::STATUS_FAILED,
                ]),
                'expectedStep' => new Step('step name', $statusFailedLabel, []),
            ],
            'single exists assertion, passed' => [
                'testCase' => $this->createBasilTestCaseFoo([
                    'basilStepName' => 'step name',
                    'status' =>  Status::STATUS_PASSED,
                    'handledStatements' => [
                        $existsAssertion,
                    ],
                ]),
                'expectedStep' => new Step(
                    'step name',
                    $statusPassedLabel,
                    [
                        $statementFactory->createForPassedAssertion($existsAssertion),
                    ]
                ),
            ],
            'single derived exists assertion, passed' => [
                'testCase' => $this->createBasilTestCaseFoo([
                    'basilStepName' => 'step name',
                    'status' =>  Status::STATUS_PASSED,
                    'handledStatements' => [
                        $derivedExistsAssertion,
                    ],
                ]),
                'expectedStep' => new Step(
                    'step name',
                    $statusPassedLabel,
                    [
                        $statementFactory->createForPassedAssertion($derivedExistsAssertion),
                    ]
                ),
            ],
            'single derived resolved exists assertion, passed' => [
                'testCase' => $this->createBasilTestCaseFoo([
                    'basilStepName' => 'step name',
                    'status' =>  Status::STATUS_PASSED,
                    'handledStatements' => [
                        $derivedResolvedExistsAssertion,
                    ],
                ]),
                'expectedStep' => new Step(
                    'step name',
                    $statusPassedLabel,
                    [
                        $statementFactory->createForPassedAssertion($derivedResolvedExistsAssertion),
                    ]
                ),
            ],
            'single exists assertion, failed' => [
                'testCase' => $this->createBasilTestCaseFoo([
                    'basilStepName' => 'step name',
                    'status' =>  Status::STATUS_FAILED,
                    'handledStatements' => [
                        $existsAssertion,
                    ],
                ]),
                'expectedStep' => new Step(
                    'step name',
                    $statusFailedLabel,
                    $this->filterStatements([
                        $statementFactory->createForFailedAssertion($existsAssertion, '', ''),
                    ])
                ),
            ],
            'single exists assertion, failed with invalid locator exception' => [
                'testCase' => $this->createBasilTestCaseFoo([
                    'basilStepName' => 'step name',
                    'status' =>  Status::STATUS_FAILED,
                    'handledStatements' => [
                        $existsAssertion,
                    ],
                    'lastException' => $invalidLocatorException,
                ]),
                'expectedStep' => new Step(
                    'step name',
                    $statusFailedLabel,
                    $this->filterStatements([
                        ($statementFactory->createForFailedAssertion(
                            $existsAssertion,
                            '',
                            ''
                        ) ?? \Mockery::mock(FailedAssertionStatement::class))->withExceptionData(
                            new InvalidLocatorExceptionData(
                                'css',
                                'a[href=https://example.com]',
                                $invalidLocatorNodeSource
                            )
                        ),
                    ])
                ),
            ],
            'three assertions, third failed' => [
                'testCase' => $this->createBasilTestCaseFoo([
                    'basilStepName' => 'step name',
                    'status' =>  Status::STATUS_FAILED,
                    'handledStatements' => [
                        $existsAssertion,
                        $isAssertion,
                        $includesAssertion,
                    ],
                    'expectedValue' => 'expected',
                    'examinedValue' => 'actual',
                ]),
                'expectedStep' => new Step(
                    'step name',
                    $statusFailedLabel,
                    $this->filterStatements([
                        $statementFactory->createForPassedAssertion($existsAssertion),
                        $statementFactory->createForPassedAssertion($isAssertion),
                        $statementFactory->createForFailedAssertion(
                            $includesAssertion,
                            'expected',
                            'actual'
                        ),
                    ])
                ),
            ],
            'single click action, passed' => [
                'testCase' => $this->createBasilTestCaseFoo([
                    'basilStepName' => 'step name',
                    'status' =>  Status::STATUS_PASSED,
                    'handledStatements' => [
                        $clickAction,
                    ],
                ]),
                'expectedStep' => new Step(
                    'step name',
                    $statusPassedLabel,
                    [
                        $statementFactory->createForPassedAction($clickAction),
                    ]
                ),
            ],
            'single click action, single exists assertion, passed' => [
                'testCase' => $this->createBasilTestCaseFoo([
                    'basilStepName' => 'step name',
                    'status' =>  Status::STATUS_PASSED,
                    'handledStatements' => [
                        $clickAction,
                        $existsAssertion,
                    ],
                ]),
                'expectedStep' => new Step(
                    'step name',
                    $statusPassedLabel,
                    [
                        $statementFactory->createForPassedAction($clickAction),
                        $statementFactory->createForPassedAssertion($existsAssertion),
                    ]
                ),
            ],
        ];
    }

    /**
     * @param array<mixed> $properties
     *
     * @return BasilTestCaseInterface
     */
    private function createBasilTestCaseFoo(array $properties): BasilTestCaseInterface
    {
        $basilStepName = $properties['basilStepName'] ?? null;
        $status = $properties['status'] ?? null;
        $handledStatements = $properties['handledStatements'] ?? [];
        $expectedValue = $properties['expectedValue'] ?? null;
        $examinedValue = $properties['examinedValue'] ?? null;
        $lastException = $properties['lastException'] ?? null;
        $currentDataSet = $properties['currentDataSet'] ?? null;

        $testCase = \Mockery::mock(BasilTestCaseInterface::class);

        $testCase
            ->shouldReceive('getBasilStepName')
            ->andReturn($basilStepName);

        $testCase
            ->shouldReceive('getStatus')
            ->andReturn($status);

        $testCase
            ->shouldReceive('getHandledStatements')
            ->andReturn($handledStatements);

        $testCase
            ->shouldReceive('getExpectedValue')
            ->andReturn($expectedValue);

        $testCase
            ->shouldReceive('getExaminedValue')
            ->andReturn($examinedValue);

        $testCase
            ->shouldReceive('getLastException')
            ->andReturn($lastException);

        $testCase
            ->shouldReceive('getCurrentDataSet')
            ->andReturn($currentDataSet);

        return $testCase;
    }

    /**
     * @param array<mixed> $statements
     *
     * @return StatementInterface[]
     */
    private function filterStatements(array $statements): array
    {
        return array_filter($statements, function ($item) {
            return $item instanceof StatementInterface;
        });
    }
}
