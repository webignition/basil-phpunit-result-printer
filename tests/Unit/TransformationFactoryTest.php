<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit;

use webignition\BasilModels\Action\Action;
use webignition\BasilModels\Action\ResolvedAction;
use webignition\BasilModels\Assertion\Assertion;
use webignition\BasilModels\Assertion\DerivedValueOperationAssertion;
use webignition\BasilModels\Assertion\ResolvedAssertion;
use webignition\BasilModels\StatementInterface;
use webignition\BasilPhpUnitResultPrinter\FooModel\Statement\Transformation;
use webignition\BasilPhpUnitResultPrinter\TransformationFactory;

class TransformationFactoryTest extends AbstractBaseTest
{
    private TransformationFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new TransformationFactory();
    }

    /**
     * @dataProvider createTransformationsDataProvider
     *
     * @param StatementInterface $statement
     * @param Transformation[] $expectedTransformations
     */
    public function testCreateTransformations(StatementInterface $statement, array $expectedTransformations)
    {
        self::assertEquals($expectedTransformations, $this->factory->createTransformations($statement));
    }

    public function createTransformationsDataProvider(): array
    {
        $clickAction = new Action(
            'click $".selector"',
            'click',
            '$".selector'
        );

        $existsAssertion = new Assertion(
            '$".selector" exists',
            '$".selector"',
            'exists'
        );

        $unresolvedIsAssertion = new Assertion(
            '$page_import_name.elements.selector is "value"',
            '$page_import_name.elements.selector',
            'is',
            '"value"'
        );

        $unresolvedClickAction = new Action(
            'click $page_import_name.elements.selector',
            'click',
            '$page_import_name.elements.selector',
            '$page_import_name.elements.selector'
        );

        $resolvedClickAction = new ResolvedAction($unresolvedClickAction, '$".selector"');

        return [
            'action, non-derived' => [
                'statement' => $clickAction,
                'expectedTransformations' => [],
            ],
            'assertion, non-derived' => [
                'statement' => $existsAssertion,
                'expectedTransformations' => [],
            ],
            'exists assertion, derived' => [
                'statement' => new DerivedValueOperationAssertion($clickAction, '$".selector"', 'exists'),
                'expectedTransformations' => [
                    new Transformation(
                        Transformation::TYPE_DERIVATION,
                        'click $".selector"'
                    ),
                ],
            ],
            'is assertion, resolved' => [
                'statement' => new ResolvedAssertion($unresolvedIsAssertion, '$".selector"', '"value"'),
                'expectedTransformations' => [
                    new Transformation(
                        Transformation::TYPE_RESOLUTION,
                        '$page_import_name.elements.selector is "value"'
                    ),
                ],
            ],
            'action, resolved' => [
                'statement' => $resolvedClickAction,
                'expectedTransformations' => [
                    new Transformation(
                        Transformation::TYPE_RESOLUTION,
                        'click $page_import_name.elements.selector'
                    ),
                ],
            ],
            'assertion, derived from resolved' => [
                'statement' => new DerivedValueOperationAssertion($resolvedClickAction, '$".selector"', 'exists'),
                'expectedTransformations' => [
                    new Transformation(
                        Transformation::TYPE_DERIVATION,
                        'click $".selector"'
                    ),
                    new Transformation(
                        Transformation::TYPE_RESOLUTION,
                        'click $page_import_name.elements.selector'
                    ),
                ],
            ],
        ];
    }
}
