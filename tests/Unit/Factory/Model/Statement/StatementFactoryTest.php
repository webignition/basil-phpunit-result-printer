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
    public function createForPassedActionDataProvider(): array
    {
        $transformationFactory = new TransformationFactory();

        $actionParser = ActionParser::create();

        $clickAction = $actionParser->parse('click $".selector"');

        $unresolvedClickAction = new Action(
            'click $page_import_name.elements.selector',
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
    public function createForFailedActionDataProvider(): array
    {
        $transformationFactory = new TransformationFactory();

        $actionParser = ActionParser::create();

        $clickAction = $actionParser->parse('click $".selector"');

        $unresolvedClickAction = new Action(
            'click $page_import_name.elements.selector',
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
    public function createForPassedAssertionDataProvider(): array
    {
        $transformationFactory = new TransformationFactory();

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
    ): void {
        self::assertEquals(
            $expectedStatement,
            $this->factory->createForFailedAssertion($assertion, $expectedValue, $actualValue)
        );
    }

    /**
     * @return array<mixed>
     */
    public function createForFailedAssertionDataProvider(): array
    {
        $transformationFactory = new TransformationFactory();
        $assertionFailureSummaryFactory = AssertionFailureSummaryFactory::createFactory();

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
        $isRegExpAssertion = $assertionParser->parse('"literal" is-regexp');

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
                'assertion' => $derivedExistsAssertion,
                'expectedValue' => '',
                'actualValue' => '',
                'expectedStatement' => new FailedAssertionStatement(
                    '$".selector" exists',
                    $existenceFailureSummary,
                    $transformationFactory->createTransformations($derivedExistsAssertion)
                ),
            ],
            'derived, resolved exists assertion' => [
                'assertion' => $derivedResolvedExistsAssertion,
                'expectedValue' => '',
                'actualValue' => '',
                'expectedStatement' => new FailedAssertionStatement(
                    '$".selector" exists',
                    $existenceFailureSummary,
                    $transformationFactory->createTransformations($derivedResolvedExistsAssertion)
                ),
            ],
            'is assertion' => [
                'assertion' => $isAssertion,
                'expectedValue' => 'value',
                'actualValue' => 'selector value',
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
                'assertion' => $isRegExpAssertion,
                'expectedValue' => '',
                'actualValue' => 'literal',
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
}
