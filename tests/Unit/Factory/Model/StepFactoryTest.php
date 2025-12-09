<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Factory;

use Facebook\WebDriver\Exception\InvalidSelectorException;
use webignition\BaseBasilTestCase\BasilTestCaseInterface;
use webignition\BasilModels\Model\Action\ResolvedAction;
use webignition\BasilModels\Model\Assertion\DerivedValueOperationAssertion;
use webignition\BasilModels\Model\DataSet\DataSet;
use webignition\BasilModels\Parser\ActionParser;
use webignition\BasilModels\Parser\AssertionParser;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\NodeSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Statement\StatementFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\StepFactory;
use webignition\BasilPhpUnitResultPrinter\Model\ExceptionData\InvalidLocatorExceptionData;
use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\FailedAssertionStatement;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\StatementInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\Tests\Services\BasilTestCaseFactory;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTestCase;
use webignition\BasilRunnerDocuments\Step;
use webignition\DomElementIdentifier\ElementIdentifier;
use webignition\SymfonyDomCrawlerNavigator\Exception\InvalidLocatorException;

class StepFactoryTest extends AbstractBaseTestCase
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
    public function testCreate(BasilTestCaseInterface $testCase, Step $expectedStep): void
    {
        self::assertEquals($expectedStep, $this->factory->create($testCase));
    }

    /**
     * @return array<mixed>
     */
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
        $isAssertionWithData = $assertionParser->parse('$".selector" is $data.expected_value');

        $statusPassedLabel = (string) new Status(Status::STATUS_PASSED);
        $statusFailedLabel = (string) new Status(Status::STATUS_FAILED);

        $invalidLocatorElementIdentifier = new ElementIdentifier('a[href=https://example.com]');

        $invalidLocatorException = new InvalidLocatorException(
            $invalidLocatorElementIdentifier,
            \Mockery::mock(InvalidSelectorException::class)
        );

        $nodeSourceFactory = NodeSourceFactory::createFactory();
        $invalidLocatorNodeSource
            = $nodeSourceFactory->create('$"a[href=https://example.com]"') ?? \Mockery::mock(NodeSource::class);

        return [
            'no statements, passed' => [
                'testCase' => BasilTestCaseFactory::create([
                    'basilStepName' => 'step name',
                    'status' => Status::STATUS_PASSED,
                ]),
                'expectedStep' => new Step('step name', $statusPassedLabel, []),
            ],
            'no statements, failed' => [
                'testCase' => BasilTestCaseFactory::create([
                    'basilStepName' => 'step name',
                    'status' => Status::STATUS_FAILED,
                ]),
                'expectedStep' => new Step('step name', $statusFailedLabel, []),
            ],
            'single exists assertion, passed' => [
                'testCase' => BasilTestCaseFactory::create([
                    'basilStepName' => 'step name',
                    'status' => Status::STATUS_PASSED,
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
                'testCase' => BasilTestCaseFactory::create([
                    'basilStepName' => 'step name',
                    'status' => Status::STATUS_PASSED,
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
                'testCase' => BasilTestCaseFactory::create([
                    'basilStepName' => 'step name',
                    'status' => Status::STATUS_PASSED,
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
                'testCase' => BasilTestCaseFactory::create([
                    'basilStepName' => 'step name',
                    'status' => Status::STATUS_FAILED,
                    'handledStatements' => [
                        $existsAssertion,
                    ],
                ]),
                'expectedStep' => new Step(
                    'step name',
                    $statusFailedLabel,
                    self::filterStatements([
                        $statementFactory->createForFailedAssertion($existsAssertion, '', ''),
                    ])
                ),
            ],
            'single exists assertion, failed with invalid locator exception' => [
                'testCase' => BasilTestCaseFactory::create([
                    'basilStepName' => 'step name',
                    'status' => Status::STATUS_FAILED,
                    'handledStatements' => [
                        $existsAssertion,
                    ],
                    'lastException' => $invalidLocatorException,
                ]),
                'expectedStep' => new Step(
                    'step name',
                    $statusFailedLabel,
                    self::filterStatements([
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
                'testCase' => BasilTestCaseFactory::create([
                    'basilStepName' => 'step name',
                    'status' => Status::STATUS_FAILED,
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
                    self::filterStatements([
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
                'testCase' => BasilTestCaseFactory::create([
                    'basilStepName' => 'step name',
                    'status' => Status::STATUS_PASSED,
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
                'testCase' => BasilTestCaseFactory::create([
                    'basilStepName' => 'step name',
                    'status' => Status::STATUS_PASSED,
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
            'single is assertion with data, passed' => [
                'testCase' => BasilTestCaseFactory::create([
                    'basilStepName' => 'step name',
                    'status' => Status::STATUS_PASSED,
                    'handledStatements' => [
                        $isAssertionWithData,
                    ],
                    'currentDataSet' => new DataSet('data set name', [
                        'expected_value' => 'literal value',
                    ]),
                ]),
                'expectedStep' => new Step(
                    'step name',
                    $statusPassedLabel,
                    [
                        $statementFactory->createForPassedAssertion($isAssertionWithData),
                    ],
                    [
                        'expected_value' => 'literal value',
                    ]
                ),
            ],
        ];
    }

    /**
     * @param array<mixed> $statements
     *
     * @return StatementInterface[]
     */
    private static function filterStatements(array $statements): array
    {
        return array_filter($statements, function ($item) {
            return $item instanceof StatementInterface;
        });
    }
}
