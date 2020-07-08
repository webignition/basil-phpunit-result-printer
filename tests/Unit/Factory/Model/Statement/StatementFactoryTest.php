<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Factory\Model\Statement;

use webignition\BasilModels\Action\Action;
use webignition\BasilModels\Action\ActionInterface;
use webignition\BasilModels\Action\ResolvedAction;
use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilModels\Assertion\DerivedValueOperationAssertion;
use webignition\BasilParser\ActionParser;
use webignition\BasilParser\AssertionParser;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Statement\StatementFactory;
use webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary\Comparison;
use webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary\Existence;
use webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary\IsRegExp;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\FooModel\Node;
use webignition\BasilPhpUnitResultPrinter\FooModel\Scalar;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\ScalarSource;
use webignition\BasilPhpUnitResultPrinter\FooModel\Statement\ActionStatement;
use webignition\BasilPhpUnitResultPrinter\FooModel\Statement\FailedAssertionStatement;
use webignition\BasilPhpUnitResultPrinter\FooModel\Statement\PassedAssertionStatement;
use webignition\BasilPhpUnitResultPrinter\FooModel\Statement\StatementInterface;
use webignition\BasilPhpUnitResultPrinter\FooModel\Statement\Transformation;
use webignition\BasilPhpUnitResultPrinter\FooModel\Status;
use webignition\BasilPhpUnitResultPrinter\FooModel\Value;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class StatementFactoryTest extends AbstractBaseTest
{
    private StatementFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = StatementFactory::createFactory();
    }

    /**
     * @dataProvider createForPassedActionDataProvider
     */
    public function testCreateForPassedAction(ActionInterface $action, StatementInterface $expectedStatement)
    {
        self::assertEquals($expectedStatement, $this->factory->createForPassedAction($action));
    }

    public function createForPassedActionDataProvider(): array
    {
        $actionParser = ActionParser::create();

        $clickAction = $actionParser->parse('click $".selector"');

        $unresolvedClickAction = new Action(
            'click $page_import_name.elements.selector',
            'click',
            '$page_import_name.elements.selector',
            '$page_import_name.elements.selector'
        );

        return [
            'click action' => [
                'action' => $clickAction,
                'expectedStatement' => new ActionStatement(
                    'click $".selector"',
                    (string) new Status(Status::STATUS_PASSED)
                ),
            ],
            'resolved click action' => [
                'action' => new ResolvedAction(
                    $unresolvedClickAction,
                    '$".selector"'
                ),
                'expectedStatement' => new ActionStatement(
                    'click $".selector"',
                    (string) new Status(Status::STATUS_PASSED),
                    [
                        new Transformation(
                            Transformation::TYPE_RESOLUTION,
                            'click $page_import_name.elements.selector'
                        )
                    ]
                ),
            ],
        ];
    }

    /**
     * @dataProvider createForFailedActionDataProvider
     */
    public function testCreateForFailedAction(ActionInterface $action, StatementInterface $expectedStatement)
    {
        self::assertEquals($expectedStatement, $this->factory->createForFailedAction($action));
    }

    public function createForFailedActionDataProvider(): array
    {
        $actionParser = ActionParser::create();

        $clickAction = $actionParser->parse('click $".selector"');

        $unresolvedClickAction = new Action(
            'click $page_import_name.elements.selector',
            'click',
            '$page_import_name.elements.selector',
            '$page_import_name.elements.selector'
        );

        return [
            'click action' => [
                'action' => $clickAction,
                'expectedStatement' => new ActionStatement(
                    'click $".selector"',
                    (string) new Status(Status::STATUS_FAILED)
                ),
            ],
            'resolved click action' => [
                'action' => new ResolvedAction(
                    $unresolvedClickAction,
                    '$".selector"'
                ),
                'expectedStatement' => new ActionStatement(
                    'click $".selector"',
                    (string) new Status(Status::STATUS_FAILED),
                    [
                        new Transformation(
                            Transformation::TYPE_RESOLUTION,
                            'click $page_import_name.elements.selector'
                        )
                    ]
                ),
            ],
        ];
    }

    /**
     * @dataProvider createForPassedAssertionDataProvider
     */
    public function testCreateForPassedAssertion(AssertionInterface $assertion, StatementInterface $expectedStatement)
    {
        self::assertEquals($expectedStatement, $this->factory->createForPassedAssertion($assertion));
    }

    public function createForPassedAssertionDataProvider(): array
    {
        $actionParser = ActionParser::create();
        $assertionParser = AssertionParser::create();

        $clickAction = $actionParser->parse('click $".selector"');
        $unresolvedClickAction = $actionParser->parse('click $page_import_name.elements.selector');
        $resolvedClickAction = new ResolvedAction($unresolvedClickAction, '$".selector"');

        $existsAssertion = $assertionParser->parse('$".selector" exists');

        return [
            'exists assertion' => [
                'assertion' => $existsAssertion,
                'expectedStatement' => new PassedAssertionStatement('$".selector" exists'),
            ],
            'derived exists assertion' => [
                'assertion' => new DerivedValueOperationAssertion($clickAction, '$".selector"', 'exists'),
                'expectedStatement' => new PassedAssertionStatement(
                    '$".selector" exists',
                    [
                        new Transformation(
                            Transformation::TYPE_DERIVATION,
                            'click $".selector"'
                        ),
                    ]
                ),
            ],
            'derived, resolved exists assertion' => [
                'assertion' => new DerivedValueOperationAssertion($resolvedClickAction, '$".selector"', 'exists'),
                'expectedStatement' => new PassedAssertionStatement(
                    '$".selector" exists',
                    [
                        new Transformation(
                            Transformation::TYPE_DERIVATION,
                            'click $".selector"'
                        ),
                        new Transformation(
                            Transformation::TYPE_RESOLUTION,
                            'click $page_import_name.elements.selector'
                        ),
                    ]
                ),
            ],
            'is assertion' => [
                'assertion' => $assertionParser->parse('$".selector" is "value"'),
                'expectedStatement' => new PassedAssertionStatement('$".selector" is "value"'),
            ],
        ];
    }

    /**
     * @dataProvider createForFailedAssertionDataProvider
     */
    public function testCreateForFailedAssertion(
        AssertionInterface $assertion,
        string $expectedValue,
        string $actualValue,
        StatementInterface $expectedStatement
    ) {
        self::assertEquals(
            $expectedStatement,
            $this->factory->createForFailedAssertion($assertion, $expectedValue, $actualValue)
        );
    }

    public function createForFailedAssertionDataProvider(): array
    {
        $actionParser = ActionParser::create();
        $assertionParser = AssertionParser::create();

        $clickAction = $actionParser->parse('click $".selector"');
        $unresolvedClickAction = $actionParser->parse('click $page_import_name.elements.selector');
        $resolvedClickAction = new ResolvedAction($unresolvedClickAction, '$".selector"');

        $existsAssertion = $assertionParser->parse('$".selector" exists');

        $elementNodeSource = new NodeSource(
            new Node(
                Node::TYPE_ELEMENT,
                new Identifier(
                    '$".selector"',
                    new Properties(
                        Properties::TYPE_CSS,
                        '.selector',
                        1
                    )
                )
            )
        );

        $existenceFailureSummary = new Existence('exists', $elementNodeSource);

        return [
            'exists assertion' => [
                'assertion' => $existsAssertion,
                'expectedValue' => '',
                'actualValue' => '',
                'expectedStatement' => new FailedAssertionStatement('$".selector" exists', $existenceFailureSummary),
            ],
            'derived exists assertion' => [
                'assertion' => new DerivedValueOperationAssertion($clickAction, '$".selector"', 'exists'),
                'expectedValue' => '',
                'actualValue' => '',
                'expectedStatement' => new FailedAssertionStatement(
                    '$".selector" exists',
                    $existenceFailureSummary,
                    [
                        new Transformation(
                            Transformation::TYPE_DERIVATION,
                            'click $".selector"'
                        ),
                    ]
                ),
            ],
            'derived, resolved exists assertion' => [
                'assertion' => new DerivedValueOperationAssertion($resolvedClickAction, '$".selector"', 'exists'),
                'expectedValue' => '',
                'actualValue' => '',
                'expectedStatement' => new FailedAssertionStatement(
                    '$".selector" exists',
                    $existenceFailureSummary,
                    [
                        new Transformation(
                            Transformation::TYPE_DERIVATION,
                            'click $".selector"'
                        ),
                        new Transformation(
                            Transformation::TYPE_RESOLUTION,
                            'click $page_import_name.elements.selector'
                        ),
                    ]
                ),
            ],
            'is assertion' => [
                'assertion' => $assertionParser->parse('$".selector" is "value"'),
                'expectedValue' => 'value',
                'actualValue' => 'selector value',
                'expectedStatement' => new FailedAssertionStatement(
                    '$".selector" is "value"',
                    new Comparison(
                        'is',
                        new Value(
                            'value',
                            new ScalarSource(
                                new Scalar(
                                    Scalar::TYPE_LITERAL,
                                    '"value"'
                                )
                            )
                        ),
                        new Value('selector value', $elementNodeSource)
                    )
                ),
            ],
            'is-regexp assertion' => [
                'assertion' => $assertionParser->parse('"literal" is-regexp'),
                'expectedValue' => '',
                'actualValue' => 'literal',
                'expectedStatement' => new FailedAssertionStatement(
                    '"literal" is-regexp',
                    new IsRegExp(
                        'literal',
                        new ScalarSource(
                            new Scalar(
                                Scalar::TYPE_LITERAL,
                                '"literal"'
                            )
                        )
                    )
                ),
            ],
        ];
    }
}
