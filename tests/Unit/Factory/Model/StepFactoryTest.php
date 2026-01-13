<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Factory;

use Facebook\WebDriver\Exception\InvalidSelectorException;
use PHPUnit\Framework\Attributes\DataProvider;
use webignition\BasilModels\Model\Action\ResolvedAction;
use webignition\BasilModels\Model\Assertion\DerivedValueOperationAssertion;
use webignition\BasilModels\Parser\ActionParser;
use webignition\BasilModels\Parser\AssertionParser;
use webignition\BasilPhpUnitResultPrinter\AssertionFailure;
use webignition\BasilPhpUnitResultPrinter\AssertionFailureException;
use webignition\BasilPhpUnitResultPrinter\ExpectationFailure;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\NodeSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Statement\StatementFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\StepFactory;
use webignition\BasilPhpUnitResultPrinter\Model\ExceptionData\InvalidLocatorExceptionData;
use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\StatementInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\State;
use webignition\BasilPhpUnitResultPrinter\StatementCollection;
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
     * @param null|array<mixed> $data
     */
    #[DataProvider('createDataProvider')]
    public function testCreate(
        string $stepName,
        State $state,
        StatementCollection $statements,
        ?array $data,
        Step $expectedStep,
    ): void {
        $step = $this->factory->create($stepName, $state, $statements, $data);

        self::assertEquals($expectedStep, $step);
    }

    /**
     * @return array<mixed>
     */
    public static function createDataProvider(): array
    {
        $statementFactory = StatementFactory::createFactory();

        $actionParser = ActionParser::create();
        $assertionParser = AssertionParser::create();

        $clickAction = $actionParser->parse('click $".selector"', 0);
        $unresolvedClickAction = $actionParser->parse('click $page_import_name.elements.selector', 0);
        $resolvedClickAction = new ResolvedAction($unresolvedClickAction, '$".selector"');

        $existsAssertion = $assertionParser->parse('$".selector" exists', 0);
        $derivedExistsAssertion = new DerivedValueOperationAssertion($clickAction, '$".selector"', 'exists');
        $derivedResolvedExistsAssertion = new DerivedValueOperationAssertion(
            $resolvedClickAction,
            '$".selector"',
            'exists'
        );

        $isAssertion = $assertionParser->parse('$".selector" is "value"', 1);
        $includesAssertion = $assertionParser->parse('$page.title includes "expected"', 2);
        $isAssertionWithData = $assertionParser->parse('$".selector" is $data.expected_value', 0);

        $statusPassedLabel = (string) new Status(Status::STATUS_PASSED);
        $statusFailedLabel = (string) new Status(Status::STATUS_FAILED);

        $invalidLocatorElementIdentifier = new ElementIdentifier('a[href=https://example.com]');

        $invalidLocatorException = new InvalidLocatorException(
            $invalidLocatorElementIdentifier,
            \Mockery::mock(InvalidSelectorException::class)
        );

        $nodeSourceFactory = NodeSourceFactory::createFactory();
        $invalidLocatorNodeSource
            = $nodeSourceFactory->create('$".selector"') ?? \Mockery::mock(NodeSource::class);

        return [
            'no statements, passed' => [
                'stepName' => 'step name',
                'state' => (function () {
                    $state = new State();
                    $state->setStatus(new Status(Status::STATUS_PASSED));

                    return $state;
                })(),
                'statements' => new StatementCollection([]),
                'data' => null,
                'expectedStep' => new Step('step name', $statusPassedLabel, [], null),
            ],
            'no statements, failed' => [
                'stepName' => 'step name',
                'state' => (function () {
                    $state = new State();
                    $state->setStatus(new Status(Status::STATUS_FAILED));

                    return $state;
                })(),
                'statements' => new StatementCollection([]),
                'data' => null,
                'expectedStep' => new Step('step name', $statusFailedLabel, [], null),
            ],
            'single exists assertion, passed' => [
                'stepName' => 'step name',
                'state' => (function () {
                    $state = new State();
                    $state->setStatus(new Status(Status::STATUS_PASSED));

                    return $state;
                })(),
                'statements' => new StatementCollection([
                    $existsAssertion,
                ]),
                'data' => null,
                'expectedStep' => new Step(
                    'step name',
                    $statusPassedLabel,
                    [
                        $statementFactory->createForPassedAssertion($existsAssertion),
                    ]
                ),
            ],
            'single derived exists assertion, passed' => [
                'stepName' => 'step name',
                'state' => (function () {
                    $state = new State();
                    $state->setStatus(new Status(Status::STATUS_PASSED));

                    return $state;
                })(),
                'statements' => new StatementCollection([
                    $derivedExistsAssertion,
                ]),
                'data' => null,
                'expectedStep' => new Step(
                    'step name',
                    $statusPassedLabel,
                    [
                        $statementFactory->createForPassedAssertion($derivedExistsAssertion),
                    ]
                ),
            ],
            'single derived resolved exists assertion, passed' => [
                'stepName' => 'step name',
                'state' => (function () {
                    $state = new State();
                    $state->setStatus(new Status(Status::STATUS_PASSED));

                    return $state;
                })(),
                'statements' => new StatementCollection([
                    $derivedResolvedExistsAssertion,
                ]),
                'data' => null,
                'expectedStep' => new Step(
                    'step name',
                    $statusPassedLabel,
                    [
                        $statementFactory->createForPassedAssertion($derivedResolvedExistsAssertion),
                    ]
                ),
            ],
            'single exists assertion, failed' => [
                'stepName' => 'step name',
                'state' => (function () use ($existsAssertion) {
                    $state = new State();
                    $state->setStatus(new Status(Status::STATUS_FAILED));
                    $state->setExpectationFailure(new ExpectationFailure($existsAssertion, true, false));

                    return $state;
                })(),
                'statements' => (function () use ($existsAssertion) {
                    $collection = new StatementCollection([
                        $existsAssertion,
                    ]);

                    $collection->setFailedStatement($existsAssertion);

                    return $collection;
                })(),
                'data' => null,
                'expectedStep' => new Step(
                    'step name',
                    $statusFailedLabel,
                    self::filterStatements([
                        $statementFactory->createForExpectationFailure(
                            new ExpectationFailure($existsAssertion, true, false),
                        ),
                    ])
                ),
            ],
            'single exists assertion, failed with invalid locator exception' => [
                'stepName' => 'step name',
                'state' => (function () use ($existsAssertion) {
                    $state = new State();
                    $state->setStatus(new Status(Status::STATUS_FAILED));
                    $state->setAssertionFailure(
                        new AssertionFailure(
                            $existsAssertion,
                            'locator-invalid',
                            new AssertionFailureException(
                                InvalidLocatorException::class,
                                0,
                                'locator-invalid',
                            ),
                            [
                                'locator' => '$".selector"',
                                'type' => 'css',
                            ]
                        )
                    );

                    return $state;
                })(),
                'statements' => (function () use ($existsAssertion) {
                    $collection = new StatementCollection([
                        $existsAssertion,
                    ]);

                    $collection->setFailedStatement($existsAssertion);

                    return $collection;
                })(),
                'data' => null,
                'expectedStep' => new Step(
                    'step name',
                    $statusFailedLabel,
                    self::filterStatements([
                        $statementFactory->createForAssertionFailure($existsAssertion)->withExceptionData(
                            new InvalidLocatorExceptionData(
                                'css',
                                '$".selector"',
                                $invalidLocatorNodeSource
                            )
                        ),
                    ])
                ),
            ],
            'three assertions, third failed' => [
                'stepName' => 'step name',
                'state' => (function () use ($includesAssertion) {
                    $state = new State();
                    $state->setStatus(new Status(Status::STATUS_FAILED));
                    $state->setExpectationFailure(new ExpectationFailure(
                        $includesAssertion,
                        true,
                        false
                    ));

                    return $state;
                })(),
                'statements' => (function () use ($existsAssertion, $isAssertion, $includesAssertion) {
                    $collection = new StatementCollection([
                        $existsAssertion,
                        $isAssertion,
                        $includesAssertion,
                    ]);

                    $collection->setFailedStatement($includesAssertion);

                    return $collection;
                })(),
                'data' => null,
                'expectedStep' => new Step(
                    'step name',
                    $statusFailedLabel,
                    self::filterStatements([
                        $statementFactory->createForPassedAssertion($existsAssertion),
                        $statementFactory->createForPassedAssertion($isAssertion),
                        $statementFactory->createForExpectationFailure(
                            new ExpectationFailure(
                                $includesAssertion,
                                true,
                                false
                            ),
                        ),
                    ])
                ),
            ],
            'single click action, passed' => [
                'stepName' => 'step name',
                'state' => (function () {
                    $state = new State();
                    $state->setStatus(new Status(Status::STATUS_PASSED));

                    return $state;
                })(),
                'statements' => new StatementCollection([
                    $clickAction,
                ]),
                'data' => null,
                'expectedStep' => new Step(
                    'step name',
                    $statusPassedLabel,
                    [
                        $statementFactory->create($clickAction, new Status(Status::STATUS_PASSED)),
                    ]
                ),
            ],
            'single click action, single exists assertion, passed' => [
                'stepName' => 'step name',
                'state' => (function () {
                    $state = new State();
                    $state->setStatus(new Status(Status::STATUS_PASSED));

                    return $state;
                })(),
                'statements' => new StatementCollection([
                    $clickAction,
                    $existsAssertion,
                ]),
                'data' => null,
                'expectedStep' => new Step(
                    'step name',
                    $statusPassedLabel,
                    [
                        $statementFactory->create($clickAction, new Status(Status::STATUS_PASSED)),
                        $statementFactory->createForPassedAssertion($existsAssertion),
                    ]
                ),
            ],
            'single is assertion with data, passed' => [
                'stepName' => 'step name',
                'state' => (function () {
                    $state = new State();
                    $state->setStatus(new Status(Status::STATUS_PASSED));

                    return $state;
                })(),
                'statements' => new StatementCollection([
                    $isAssertionWithData,
                ]),
                'data' => [
                    'expected_value' => 'literal value',
                ],
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
