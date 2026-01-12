<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Factory\Model\Statement;

use webignition\BasilModels\Model\Action\Action;
use webignition\BasilModels\Model\Action\ActionInterface;
use webignition\BasilModels\Model\Action\ResolvedAction;
use webignition\BasilModels\Model\Assertion\AssertionInterface;
use webignition\BasilModels\Model\Assertion\DerivedValueOperationAssertion;
use webignition\BasilModels\Parser\ActionParser;
use webignition\BasilModels\Parser\AssertionParser;
use webignition\BasilPhpUnitResultPrinter\ExpectationFailure;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\AssertionFailureSummaryFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Statement\StatementFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Statement\TransformationFactory;
use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\AssertionFailureSummaryInterface;
use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\Existence;
use webignition\BasilPhpUnitResultPrinter\Model\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\Model\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\Model\Node;
use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\ActionStatement;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\FailedAssertionStatement;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\PassedAssertionStatement;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\StatementInterface;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTestCase;

class StatementFactoryTest extends AbstractBaseTestCase
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
    public function testCreateForPassedAction(ActionInterface $action, StatementInterface $expectedStatement): void
    {
        self::assertEquals($expectedStatement, $this->factory->createForPassedAction($action));
    }

    /**
     * @return array<mixed>
     */
    public static function createForPassedActionDataProvider(): array
    {
        $transformationFactory = new TransformationFactory();

        $actionParser = ActionParser::create();

        $clickAction = $actionParser->parse('click $".selector"', 0);

        $unresolvedClickAction = new Action(
            'click $page_import_name.elements.selector',
            0,
            'click',
            '$page_import_name.elements.selector',
            '$page_import_name.elements.selector'
        );

        $resolvedClickAction = new ResolvedAction(
            $unresolvedClickAction,
            '$".selector"'
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
                'action' => $resolvedClickAction,
                'expectedStatement' => new ActionStatement(
                    'click $".selector"',
                    (string) new Status(Status::STATUS_PASSED),
                    $transformationFactory->createTransformations($resolvedClickAction),
                ),
            ],
        ];
    }

    /**
     * @dataProvider createForFailedActionDataProvider
     */
    public function testCreateForFailedAction(ActionInterface $action, StatementInterface $expectedStatement): void
    {
        self::assertEquals($expectedStatement, $this->factory->createForFailedAction($action));
    }

    /**
     * @return array<mixed>
     */
    public static function createForFailedActionDataProvider(): array
    {
        $transformationFactory = new TransformationFactory();

        $actionParser = ActionParser::create();

        $clickAction = $actionParser->parse('click $".selector"', 0);

        $unresolvedClickAction = new Action(
            'click $page_import_name.elements.selector',
            0,
            'click',
            '$page_import_name.elements.selector',
            '$page_import_name.elements.selector'
        );

        $resolvedClickAction = new ResolvedAction(
            $unresolvedClickAction,
            '$".selector"'
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
                'action' => $resolvedClickAction,
                'expectedStatement' => new ActionStatement(
                    'click $".selector"',
                    (string) new Status(Status::STATUS_FAILED),
                    $transformationFactory->createTransformations($resolvedClickAction)
                ),
            ],
        ];
    }

    /**
     * @dataProvider createForPassedAssertionDataProvider
     */
    public function testCreateForPassedAssertion(
        AssertionInterface $assertion,
        StatementInterface $expectedStatement
    ): void {
        self::assertEquals($expectedStatement, $this->factory->createForPassedAssertion($assertion));
    }

    /**
     * @return array<mixed>
     */
    public static function createForPassedAssertionDataProvider(): array
    {
        $transformationFactory = new TransformationFactory();

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

        return [
            'exists assertion' => [
                'assertion' => $existsAssertion,
                'expectedStatement' => new PassedAssertionStatement('$".selector" exists'),
            ],
            'derived exists assertion' => [
                'assertion' => $derivedExistsAssertion,
                'expectedStatement' => new PassedAssertionStatement(
                    '$".selector" exists',
                    $transformationFactory->createTransformations($derivedExistsAssertion)
                ),
            ],
            'derived, resolved exists assertion' => [
                'assertion' => $derivedResolvedExistsAssertion,
                'expectedStatement' => new PassedAssertionStatement(
                    '$".selector" exists',
                    $transformationFactory->createTransformations($derivedResolvedExistsAssertion)
                ),
            ],
            'is assertion' => [
                'assertion' => $assertionParser->parse('$".selector" is "value"', 0),
                'expectedStatement' => new PassedAssertionStatement('$".selector" is "value"'),
            ],
        ];
    }

    /**
     * @dataProvider createForExpectationFailureDataProvider
     */
    public function testCreateForExpectationFailure(
        ExpectationFailure $expectationFailure,
        StatementInterface $expectedStatement,
    ): void {
        self::assertEquals(
            $expectedStatement,
            $this->factory->createForExpectationFailure($expectationFailure)
        );
    }

    /**
     * @return array<mixed>
     */
    public static function createForExpectationFailureDataProvider(): array
    {
        $transformationFactory = new TransformationFactory();
        $assertionFailureSummaryFactory = AssertionFailureSummaryFactory::createFactory();

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

        $isAssertion = $assertionParser->parse('$".selector" is "value"', 0);
        $isRegExpAssertion = $assertionParser->parse('"literal" is-regexp', 0);

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
                'expectationFailure' => new ExpectationFailure($existsAssertion, '', ''),
                'expectedStatement' => new FailedAssertionStatement('$".selector" exists', $existenceFailureSummary),
            ],
            'derived exists assertion' => [
                'expectationFailure' => new ExpectationFailure($derivedExistsAssertion, '', ''),
                'expectedStatement' => new FailedAssertionStatement(
                    '$".selector" exists',
                    $existenceFailureSummary,
                    $transformationFactory->createTransformations($derivedExistsAssertion)
                ),
            ],
            'derived, resolved exists assertion' => [
                'expectationFailure' => new ExpectationFailure($derivedResolvedExistsAssertion, '', ''),
                'expectedStatement' => new FailedAssertionStatement(
                    '$".selector" exists',
                    $existenceFailureSummary,
                    $transformationFactory->createTransformations($derivedResolvedExistsAssertion)
                ),
            ],
            'is assertion' => [
                'expectationFailure' => new ExpectationFailure(
                    $isAssertion,
                    'value',
                    'selector value'
                ),
                'expectedStatement' => new FailedAssertionStatement(
                    '$".selector" is "value"',
                    $assertionFailureSummaryFactory->create(
                        $isAssertion,
                        'value',
                        'selector value'
                    ) ?? \Mockery::mock(AssertionFailureSummaryInterface::class)
                ),
            ],
            'is-regexp assertion' => [
                'expectationFailure' => new ExpectationFailure(
                    $isRegExpAssertion,
                    '',
                    'literal'
                ),
                'expectedStatement' => new FailedAssertionStatement(
                    '"literal" is-regexp',
                    $assertionFailureSummaryFactory->create(
                        $isRegExpAssertion,
                        '',
                        'literal'
                    ) ?? \Mockery::mock(AssertionFailureSummaryInterface::class)
                ),
            ],
        ];
    }

    /**
     * @dataProvider createForAssertionFailureDataProvider
     */
    public function testCreateForAssertionFailure(
        AssertionInterface $assertion,
        StatementInterface $expectedStatement
    ): void {
        self::assertEquals(
            $expectedStatement,
            $this->factory->createForAssertionFailure($assertion)
        );
    }

    /**
     * @return array<mixed>
     */
    public static function createForAssertionFailureDataProvider(): array
    {
        $transformationFactory = new TransformationFactory();

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

        $isAssertion = $assertionParser->parse('$".selector" is "value"', 0);
        $isRegExpAssertion = $assertionParser->parse('"literal" is-regexp', 0);

        return [
            'exists assertion' => [
                'assertion' => $existsAssertion,
                'expectedStatement' => new FailedAssertionStatement(
                    '$".selector" exists',
                    null
                ),
            ],
            'derived exists assertion' => [
                'assertion' => $derivedExistsAssertion,
                'expectedStatement' => new FailedAssertionStatement(
                    '$".selector" exists',
                    null,
                    $transformationFactory->createTransformations($derivedExistsAssertion)
                ),
            ],
            'derived, resolved exists assertion' => [
                'assertion' => $derivedResolvedExistsAssertion,
                'expectedStatement' => new FailedAssertionStatement(
                    '$".selector" exists',
                    null,
                    $transformationFactory->createTransformations($derivedResolvedExistsAssertion)
                ),
            ],
            'is assertion' => [
                'assertion' => $isAssertion,
                'expectedStatement' => new FailedAssertionStatement(
                    '$".selector" is "value"',
                    null
                ),
            ],
            'is-regexp assertion' => [
                'assertion' => $isRegExpAssertion,
                'expectedStatement' => new FailedAssertionStatement(
                    '"literal" is-regexp',
                    null
                ),
            ],
        ];
    }
}
