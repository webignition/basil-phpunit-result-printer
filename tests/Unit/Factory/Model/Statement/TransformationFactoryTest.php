<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Factory\Model\Statement;

use webignition\BasilModels\Model\Action\Action;
use webignition\BasilModels\Model\Action\ResolvedAction;
use webignition\BasilModels\Model\Assertion\Assertion;
use webignition\BasilModels\Model\Assertion\DerivedValueOperationAssertion;
use webignition\BasilModels\Model\Assertion\ResolvedAssertion;
use webignition\BasilModels\Model\StatementInterface;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Statement\TransformationFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Statement\Transformation;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

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
     * @param Transformation[] $expectedTransformations
     */
    public function testCreateTransformations(StatementInterface $statement, array $expectedTransformations): void
    {
        self::assertEquals($expectedTransformations, $this->factory->createTransformations($statement));
    }

    /**
     * @return array<mixed>
     */
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
