<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Factory\Model\Statement;

use PHPUnit\Framework\Attributes\DataProvider;
use webignition\BasilModels\Model\Statement\Action\Action;
use webignition\BasilModels\Model\Statement\Action\ResolvedAction;
use webignition\BasilModels\Model\Statement\Assertion\AssertionInterface;
use webignition\BasilModels\Model\Statement\Assertion\DerivedValueOperationAssertion;
use webignition\BasilModels\Model\Statement\StatementInterface as StatementModelInterface;
use webignition\BasilModels\Parser\ActionParser;
use webignition\BasilModels\Parser\AssertionParser;
use webignition\BasilPhpUnitResultPrinter\Enum\StatementType;
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
use webignition\BasilPhpUnitResultPrinter\Model\Statement\Statement;
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

    #[DataProvider('createForPassedActionDataProvider')]
    #[DataProvider('createForFailedActionDataProvider')]
    #[DataProvider('createForPassedAssertionDataProvider')]
    public function testCreate(
        StatementModelInterface $statement,
        Status $status,
        StatementInterface $expectedStatement
    ): void {
        self::assertEquals($expectedStatement, $this->factory->create($statement, $status));
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
            'action, passed, click action' => [
                'statement' => $clickAction,
                'status' => new Status(Status::STATUS_PASSED),
                'expectedStatement' => new Statement(
                    StatementType::ACTION,
                    'click $".selector"',
                    (string) new Status(Status::STATUS_PASSED)
                ),
            ],
            'action, passed, resolved click action' => [
                'statement' => $resolvedClickAction,
                'status' => new Status(Status::STATUS_PASSED),
                'expectedStatement' => new Statement(
                    StatementType::ACTION,
                    'click $".selector"',
                    (string) new Status(Status::STATUS_PASSED),
                )->withTransformations(
                    $transformationFactory->createTransformations($resolvedClickAction),
                ),
            ],
        ];
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
            'action, failed, click action' => [
                'statement' => $clickAction,
                'status' => new Status(Status::STATUS_FAILED),
                'expectedStatement' => new Statement(
                    StatementType::ACTION,
                    'click $".selector"',
                    (string) new Status(Status::STATUS_FAILED)
                ),
            ],
            'action, failed, resolved click action' => [
                'statement' => $resolvedClickAction,
                'status' => new Status(Status::STATUS_FAILED),
                'expectedStatement' => new Statement(
                    StatementType::ACTION,
                    'click $".selector"',
                    (string) new Status(Status::STATUS_FAILED),
                )->withTransformations(
                    $transformationFactory->createTransformations($resolvedClickAction)
                ),
            ],
        ];
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
            'assertion, passed, exists assertion' => [
                'statement' => $existsAssertion,
                'status' => new Status(Status::STATUS_PASSED),
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" exists',
                    (string) new Status(Status::STATUS_PASSED),
                ),
            ],
            'assertion, passed, derived exists assertion' => [
                'statement' => $derivedExistsAssertion,
                'status' => new Status(Status::STATUS_PASSED),
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" exists',
                    (string) new Status(Status::STATUS_PASSED),
                )->withTransformations(
                    $transformationFactory->createTransformations($derivedExistsAssertion)
                ),
            ],
            'assertion, passed, derived, resolved exists assertion' => [
                'statement' => $derivedResolvedExistsAssertion,
                'status' => new Status(Status::STATUS_PASSED),
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" exists',
                    (string) new Status(Status::STATUS_PASSED),
                )->withTransformations(
                    $transformationFactory->createTransformations($derivedResolvedExistsAssertion)
                ),
            ],
            'assertion, passed, is assertion' => [
                'statement' => $assertionParser->parse('$".selector" is "value"', 0),
                'status' => new Status(Status::STATUS_PASSED),
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" is "value"',
                    (string) new Status(Status::STATUS_PASSED),
                ),
            ],
        ];
    }

    #[DataProvider('createForExpectationFailureDataProvider')]
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
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" exists',
                    (string) new Status(Status::STATUS_FAILED),
                )->withFailureSummary($existenceFailureSummary),
            ],
            'derived exists assertion' => [
                'expectationFailure' => new ExpectationFailure($derivedExistsAssertion, '', ''),
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" exists',
                    (string) new Status(Status::STATUS_FAILED),
                )
                    ->withFailureSummary(
                        $existenceFailureSummary
                    )
                    ->withTransformations(
                        $transformationFactory->createTransformations($derivedExistsAssertion)
                    ),
            ],
            'derived, resolved exists assertion' => [
                'expectationFailure' => new ExpectationFailure($derivedResolvedExistsAssertion, '', ''),
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" exists',
                    (string) new Status(Status::STATUS_FAILED),
                )
                    ->withFailureSummary(
                        $existenceFailureSummary
                    )->withTransformations(
                        $transformationFactory->createTransformations($derivedResolvedExistsAssertion)
                    ),
            ],
            'is assertion' => [
                'expectationFailure' => new ExpectationFailure(
                    $isAssertion,
                    'value',
                    'selector value'
                ),
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" is "value"',
                    (string) new Status(Status::STATUS_FAILED),
                )->withFailureSummary(
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
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '"literal" is-regexp',
                    (string) new Status(Status::STATUS_FAILED),
                )->withFailureSummary(
                    $assertionFailureSummaryFactory->create(
                        $isRegExpAssertion,
                        '',
                        'literal'
                    ) ?? \Mockery::mock(AssertionFailureSummaryInterface::class)
                ),
            ],
        ];
    }

    #[DataProvider('createForAssertionFailureDataProvider')]
    public function testCreateForAssertionFailure(
        AssertionInterface $statement,
        StatementInterface $expectedStatement
    ): void {
        self::assertEquals(
            $expectedStatement,
            $this->factory->createForAssertionFailure($statement)
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
                'statement' => $existsAssertion,
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" exists',
                    (string) new Status(Status::STATUS_FAILED),
                ),
            ],
            'derived exists assertion' => [
                'statement' => $derivedExistsAssertion,
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" exists',
                    (string) new Status(Status::STATUS_FAILED),
                )->withTransformations(
                    $transformationFactory->createTransformations($derivedExistsAssertion)
                ),
            ],
            'derived, resolved exists assertion' => [
                'statement' => $derivedResolvedExistsAssertion,
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" exists',
                    (string) new Status(Status::STATUS_FAILED),
                )->withTransformations(
                    $transformationFactory->createTransformations($derivedResolvedExistsAssertion)
                ),
            ],
            'is assertion' => [
                'statement' => $isAssertion,
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '$".selector" is "value"',
                    (string) new Status(Status::STATUS_FAILED),
                ),
            ],
            'is-regexp assertion' => [
                'statement' => $isRegExpAssertion,
                'expectedStatement' => new Statement(
                    StatementType::ASSERTION,
                    '"literal" is-regexp',
                    (string) new Status(Status::STATUS_FAILED),
                ),
            ],
        ];
    }
}
