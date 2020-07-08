<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Factory;

use webignition\BasilModels\Assertion\Assertion;
use webignition\BasilModels\Assertion\AssertionInterface;
use webignition\BasilParser\AssertionParser;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\AssertionFailureSummaryFactory;
use webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary\AssertionFailureSummaryInterface;
use webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary\Comparison;
use webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary\Existence;
use webignition\BasilPhpUnitResultPrinter\FooModel\AssertionFailureSummary\IsRegExp;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\FooModel\Node;
use webignition\BasilPhpUnitResultPrinter\FooModel\Scalar;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\FooModel\Source\ScalarSource;
use webignition\BasilPhpUnitResultPrinter\FooModel\Value;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class AssertionFailureSummaryFactoryTest extends AbstractBaseTest
{
    private AssertionFailureSummaryFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = AssertionFailureSummaryFactory::createFactory();
    }

    /**
     * @dataProvider createSuccessDataProvider
     */
    public function testCreateSuccess(
        AssertionInterface $assertion,
        string $expectedValue,
        string $actualValue,
        AssertionFailureSummaryInterface $expectedSummary
    ) {
        self::assertEquals($expectedSummary, $this->factory->create($assertion, $expectedValue, $actualValue));
    }

    public function createSuccessDataProvider(): array
    {
        $assertionParser = AssertionParser::create();

        $cssIdentifierElementProperties = new Properties(Properties::TYPE_CSS, '.identifier', 1);
        $cssIdentifierAttributeProperties = $cssIdentifierElementProperties->withAttribute('attribute_name');

        $cssIdentifierElementNode = new Node(
            Node::TYPE_ELEMENT,
            new Identifier('$".identifier"', $cssIdentifierElementProperties)
        );

        $cssIdentifierAttributeNode = new Node(
            Node::TYPE_ATTRIBUTE,
            new Identifier('$".identifier".attribute_name', $cssIdentifierAttributeProperties)
        );

        $cssValueElementProperties = new Properties(Properties::TYPE_CSS, '.value', 1);
        $cssValueAttributeProperties = $cssValueElementProperties->withAttribute('attribute_name');

        $cssValueElementNode = new Node(
            Node::TYPE_ELEMENT,
            new Identifier('$".value"', $cssValueElementProperties)
        );

        $cssValueAttributeNode = new Node(
            Node::TYPE_ATTRIBUTE,
            new Identifier('$".value".attribute_name', $cssValueAttributeProperties)
        );

        return [
            'exists, element node' => [
                'assertion' => $assertionParser->parse('$".identifier" exists'),
                'expectedValue' => '',
                'actualValue' => '',
                'expectedSummary' => new Existence('exists', new NodeSource($cssIdentifierElementNode)),
            ],
            'exists, attribute node' => [
                'assertion' => $assertionParser->parse('$".identifier".attribute_name exists'),
                'expectedValue' => '',
                'actualValue' => '',
                'expectedSummary' => new Existence('exists', new NodeSource($cssIdentifierAttributeNode)),
            ],
            'exists, descendant element node' => [
                'assertion' => $assertionParser->parse('$".parent" >> $".child" exists'),
                'expectedValue' => '',
                'actualValue' => '',
                'expectedSummary' => new Existence(
                    'exists',
                    new NodeSource(
                        new Node(
                            Node::TYPE_ELEMENT,
                            new Identifier(
                                '$".parent" >> $".child"',
                                (new Properties(
                                    Properties::TYPE_CSS,
                                    '.child',
                                    1
                                ))->withParent(new Properties(
                                    Properties::TYPE_CSS,
                                    '.parent',
                                    1
                                ))
                            )
                        )
                    )
                ),
            ],
            'not-exists, element node' => [
                'assertion' => $assertionParser->parse('$".identifier" not-exists'),
                'expectedValue' => '',
                'actualValue' => '',
                'expectedSummary' => new Existence('not-exists', new NodeSource($cssIdentifierElementNode)),
            ],
            'is-regexp, element node' => [
                'assertion' => $assertionParser->parse('$".identifier" is-regexp'),
                'expectedValue' => '',
                'actualValue' => 'invalid regexp',
                'expectedSummary' => new IsRegExp('invalid regexp', new NodeSource($cssIdentifierElementNode)),
            ],
            'is-regexp, attribute node' => [
                'assertion' => $assertionParser->parse('$".identifier".attribute_name is-regexp'),
                'expectedValue' => '',
                'actualValue' => 'invalid regexp',
                'expectedSummary' => new IsRegExp('invalid regexp', new NodeSource($cssIdentifierAttributeNode)),
            ],
            'is-regexp, scalar' => [
                'assertion' => $assertionParser->parse('$page.title is-regexp'),
                'expectedValue' => '',
                'actualValue' => 'invalid regexp',
                'expectedSummary' => new IsRegExp(
                    'invalid regexp',
                    new ScalarSource(
                        new Scalar(
                            Scalar::TYPE_PAGE_PROPERTY,
                            '$page.title'
                        )
                    )
                ),
            ],
            'is, element node identifier, scalar value' => [
                'assertion' => $assertionParser->parse('$".identifier" is "expected"'),
                'expectedValue' => 'expected',
                'actualValue' => 'identifier element node value',
                'expectedSummary' => new Comparison(
                    'is',
                    new Value(
                        'expected',
                        new ScalarSource(
                            new Scalar(
                                Scalar::TYPE_LITERAL,
                                '"expected"'
                            )
                        )
                    ),
                    new Value(
                        'identifier element node value',
                        new NodeSource($cssIdentifierElementNode)
                    )
                ),
            ],
            'is, element node identifier, element node value' => [
                'assertion' => $assertionParser->parse('$".identifier" is $".value"'),
                'expectedValue' => 'value element node value',
                'actualValue' => 'identifier element node value',
                'expectedSummary' => new Comparison(
                    'is',
                    new Value(
                        'value element node value',
                        new NodeSource($cssValueElementNode)
                    ),
                    new Value(
                        'identifier element node value',
                        new NodeSource($cssIdentifierElementNode)
                    )
                ),
            ],
            'is, scalar identifier, element node value' => [
                'assertion' => $assertionParser->parse('"expected" is $".value"'),
                'expectedValue' => 'value element node value',
                'actualValue' => 'expected',
                'expectedSummary' => new Comparison(
                    'is',
                    new Value(
                        'value element node value',
                        new NodeSource($cssValueElementNode)
                    ),
                    new Value(
                        'expected',
                        new ScalarSource(
                            new Scalar(
                                Scalar::TYPE_LITERAL,
                                '"expected"'
                            )
                        )
                    )
                ),
            ],
            'is, scalar identifier, scalar value' => [
                'assertion' => $assertionParser->parse('"actual" is "expected"'),
                'expectedValue' => 'expected',
                'actualValue' => 'actual',
                'expectedSummary' => new Comparison(
                    'is',
                    new Value(
                        'expected',
                        new ScalarSource(
                            new Scalar(
                                Scalar::TYPE_LITERAL,
                                '"expected"'
                            )
                        )
                    ),
                    new Value(
                        'actual',
                        new ScalarSource(
                            new Scalar(
                                Scalar::TYPE_LITERAL,
                                '"actual"'
                            )
                        )
                    )
                ),
            ],
            'is, attribute node identifier, scalar value' => [
                'assertion' => $assertionParser->parse('$".identifier".attribute_name is "expected"'),
                'expectedValue' => 'expected',
                'actualValue' => 'identifier attribute node value',
                'expectedSummary' => new Comparison(
                    'is',
                    new Value(
                        'expected',
                        new ScalarSource(
                            new Scalar(
                                Scalar::TYPE_LITERAL,
                                '"expected"'
                            )
                        )
                    ),
                    new Value(
                        'identifier attribute node value',
                        new NodeSource($cssIdentifierAttributeNode)
                    )
                ),
            ],
            'is, element node identifier, attribute node value' => [
                'assertion' => $assertionParser->parse('$".identifier" is $".value".attribute_name'),
                'expectedValue' => 'value attribute node value',
                'actualValue' => 'identifier element node value',
                'expectedSummary' => new Comparison(
                    'is',
                    new Value(
                        'value attribute node value',
                        new NodeSource($cssValueAttributeNode)
                    ),
                    new Value(
                        'identifier element node value',
                        new NodeSource($cssIdentifierElementNode)
                    )
                ),
            ],
        ];
    }

    /**
     * @dataProvider createFailureDataProvider
     */
    public function testCreateFailure(AssertionInterface $assertion)
    {
        self::assertNull($this->factory->create($assertion, '', ''));
    }

    public function createFailureDataProvider(): array
    {
        return [
            'exists, invalid source' => [
                'assertion' => new Assertion(
                    'invalid exists',
                    'invalid',
                    'exists'
                ),
            ],
            'is-regexp, invalid source' => [
                'assertion' => new Assertion(
                    'invalid exists',
                    'invalid',
                    'is-regexp'
                ),
            ],
            'is, invalid identifier' => [
                'assertion' => new Assertion(
                    'invalid is "value"',
                    'invalid',
                    'is',
                    '"value"'
                ),
            ],
            'is, invalid value' => [
                'assertion' => new Assertion(
                    '$".identifier" is invalid',
                    '$".identifier"',
                    'is',
                    'invalid'
                ),
            ],
        ];
    }
}
