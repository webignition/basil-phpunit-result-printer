<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Factory;

use PHPUnit\Framework\Attributes\DataProvider;
use webignition\BasilModels\Model\Assertion\Assertion;
use webignition\BasilModels\Model\Assertion\AssertionInterface;
use webignition\BasilModels\Parser\AssertionParser;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\AssertionFailureSummaryFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\NodeSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Source\ScalarSourceFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\ValueFactory;
use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\AssertionFailureSummaryInterface;
use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\Comparison;
use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\Existence;
use webignition\BasilPhpUnitResultPrinter\Model\AssertionFailureSummary\IsRegExp;
use webignition\BasilPhpUnitResultPrinter\Model\Source\NodeSource;
use webignition\BasilPhpUnitResultPrinter\Model\Source\ScalarSource;
use webignition\BasilPhpUnitResultPrinter\Model\Value;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTestCase;

class AssertionFailureSummaryFactoryTest extends AbstractBaseTestCase
{
    private AssertionFailureSummaryFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = AssertionFailureSummaryFactory::createFactory();
    }

    #[DataProvider('createSuccessDataProvider')]
    public function testCreateSuccess(
        AssertionInterface $assertion,
        string $expectedValue,
        string $actualValue,
        AssertionFailureSummaryInterface $expectedSummary
    ): void {
        self::assertEquals($expectedSummary, $this->factory->create($assertion, $expectedValue, $actualValue));
    }

    /**
     * @return array<mixed>
     */
    public static function createSuccessDataProvider(): array
    {
        $assertionParser = AssertionParser::create();

        $nodeSourceFactory = NodeSourceFactory::createFactory();
        $scalarSourceFactory = ScalarSourceFactory::createFactory();
        $valueFactory = ValueFactory::createFactory();

        return [
            'exists, node' => [
                'assertion' => $assertionParser->parse('$".identifier" exists', 0),
                'expectedValue' => '',
                'actualValue' => '',
                'expectedSummary' => new Existence(
                    'exists',
                    $nodeSourceFactory->create('$".identifier"') ?? \Mockery::mock(NodeSource::class)
                ),
            ],
            'not-exists, node' => [
                'assertion' => $assertionParser->parse('$".identifier" not-exists', 0),
                'expectedValue' => '',
                'actualValue' => '',
                'expectedSummary' => new Existence(
                    'not-exists',
                    $nodeSourceFactory->create('$".identifier"') ?? \Mockery::mock(NodeSource::class)
                ),
            ],
            'is-regexp, node' => [
                'assertion' => $assertionParser->parse('$".identifier" is-regexp', 0),
                'expectedValue' => '',
                'actualValue' => 'invalid regexp',
                'expectedSummary' => new IsRegExp(
                    'invalid regexp',
                    $nodeSourceFactory->create('$".identifier"') ?? \Mockery::mock(NodeSource::class)
                ),
            ],
            'is-regexp, scalar' => [
                'assertion' => $assertionParser->parse('$page.title is-regexp', 0),
                'expectedValue' => '',
                'actualValue' => 'invalid regexp',
                'expectedSummary' => new IsRegExp(
                    'invalid regexp',
                    $scalarSourceFactory->create('$page.title') ?? \Mockery::mock(ScalarSource::class)
                ),
            ],
            'is, element node identifier, scalar value' => [
                'assertion' => $assertionParser->parse('$".identifier" is "expected"', 0),
                'expectedValue' => 'expected',
                'actualValue' => 'identifier element node value',
                'expectedSummary' => new Comparison(
                    'is',
                    $valueFactory->create(
                        'expected',
                        '"expected"'
                    ) ?? \Mockery::mock(Value::class),
                    $valueFactory->create(
                        'identifier element node value',
                        '$".identifier"'
                    ) ?? \Mockery::mock(Value::class)
                ),
            ],
            'is, element node identifier, element node value' => [
                'assertion' => $assertionParser->parse('$".identifier" is $".value"', 0),
                'expectedValue' => 'value element node value',
                'actualValue' => 'identifier element node value',
                'expectedSummary' => new Comparison(
                    'is',
                    $valueFactory->create(
                        'value element node value',
                        '$".value"'
                    ) ?? \Mockery::mock(Value::class),
                    $valueFactory->create(
                        'identifier element node value',
                        '$".identifier"'
                    ) ?? \Mockery::mock(Value::class)
                ),
            ],
            'is, scalar identifier, element node value' => [
                'assertion' => $assertionParser->parse('"expected" is $".value"', 0),
                'expectedValue' => 'value element node value',
                'actualValue' => 'expected',
                'expectedSummary' => new Comparison(
                    'is',
                    $valueFactory->create(
                        'value element node value',
                        '$".value"'
                    ) ?? \Mockery::mock(Value::class),
                    $valueFactory->create(
                        'expected',
                        '"expected"'
                    ) ?? \Mockery::mock(Value::class)
                ),
            ],
            'is, scalar identifier, scalar value' => [
                'assertion' => $assertionParser->parse('"actual" is "expected"', 0),
                'expectedValue' => 'expected',
                'actualValue' => 'actual',
                'expectedSummary' => new Comparison(
                    'is',
                    $valueFactory->create(
                        'expected',
                        '"expected"'
                    ) ?? \Mockery::mock(Value::class),
                    $valueFactory->create(
                        'actual',
                        '"actual"'
                    ) ?? \Mockery::mock(Value::class)
                ),
            ],
            'is, attribute node identifier, scalar value' => [
                'assertion' => $assertionParser->parse('$".identifier".attribute_name is "expected"', 0),
                'expectedValue' => 'expected',
                'actualValue' => 'identifier attribute node value',
                'expectedSummary' => new Comparison(
                    'is',
                    $valueFactory->create(
                        'expected',
                        '"expected"'
                    ) ?? \Mockery::mock(Value::class),
                    $valueFactory->create(
                        'identifier attribute node value',
                        '$".identifier".attribute_name'
                    ) ?? \Mockery::mock(Value::class)
                ),
            ],
            'is, element node identifier, attribute node value' => [
                'assertion' => $assertionParser->parse('$".identifier" is $".value".attribute_name', 0),
                'expectedValue' => 'value attribute node value',
                'actualValue' => 'identifier element node value',
                'expectedSummary' => new Comparison(
                    'is',
                    $valueFactory->create(
                        'value attribute node value',
                        '$".value".attribute_name'
                    ) ?? \Mockery::mock(Value::class),
                    $valueFactory->create(
                        'identifier element node value',
                        '$".identifier"'
                    ) ?? \Mockery::mock(Value::class)
                ),
            ],
        ];
    }

    #[DataProvider('createFailureDataProvider')]
    public function testCreateFailure(AssertionInterface $assertion): void
    {
        self::assertNull($this->factory->create($assertion, '', ''));
    }

    /**
     * @return array<mixed>
     */
    public static function createFailureDataProvider(): array
    {
        return [
            'exists, invalid source' => [
                'assertion' => new Assertion(
                    'invalid exists',
                    0,
                    'invalid',
                    'exists'
                ),
            ],
            'is-regexp, invalid source' => [
                'assertion' => new Assertion(
                    'invalid exists',
                    0,
                    'invalid',
                    'is-regexp'
                ),
            ],
            'is, invalid identifier' => [
                'assertion' => new Assertion(
                    'invalid is "value"',
                    0,
                    'invalid',
                    'is',
                    '"value"'
                ),
            ],
            'is, invalid value' => [
                'assertion' => new Assertion(
                    '$".identifier" is invalid',
                    0,
                    '$".identifier"',
                    'is',
                    'invalid'
                ),
            ],
        ];
    }
}
