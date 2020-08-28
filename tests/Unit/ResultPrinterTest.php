<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit;

use Facebook\WebDriver\Exception\InvalidSelectorException;
use webignition\BasilModels\Action\ResolvedAction;
use webignition\BasilModels\Assertion\DerivedValueOperationAssertion;
use webignition\BasilModels\Test\Configuration;
use webignition\BasilParser\ActionParser;
use webignition\BasilParser\AssertionParser;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\ResultPrinter;
use webignition\BasilPhpUnitResultPrinter\Tests\Services\BasilTestCaseFactory;
use webignition\BasilPhpUnitResultPrinter\Tests\Services\FixtureLoader;
use webignition\DomElementIdentifier\ElementIdentifier;
use webignition\SymfonyDomCrawlerNavigator\Exception\InvalidLocatorException;

class ResultPrinterTest extends AbstractBaseTest
{
    /**
     * @dataProvider passedDataProvider
     * @dataProvider failedExistsAssertionDataProvider
     * @dataProvider failedExceptionDataProvider
     * @dataProvider failedIsAssertionDataProvider
     * @dataProvider failedIsRegExpDataProvider
     * @dataProvider failedNoStatementsDataProvider
     *
     * @param string[] $testPaths
     * @param array<array<mixed>> $testPropertiesCollection
     * @param string $expectedOutput
     */
    public function testPrinterOutput(
        array $testPaths,
        array $testPropertiesCollection,
        string $expectedOutput
    ) {
        $tests = BasilTestCaseFactory::createCollection($testPaths, $testPropertiesCollection);

        $outResource = fopen('php://memory', 'w+');

        if (is_resource($outResource)) {
            $printer = new ResultPrinter($outResource);

            foreach ($tests as $test) {
                $printer->startTest($test);
                $printer->endTest($test, 0.1);
            }

            rewind($outResource);
            $outContent = stream_get_contents($outResource);
            fclose($outResource);

            $this->assertSame($expectedOutput, $outContent);
        } else {
            $this->fail('Failed to open resource "php://memory" for reading and writing');
        }
    }

    public function passedDataProvider(): array
    {
        $actionParser = ActionParser::create();
        $assertionParser = AssertionParser::create();

        return [
            'passed, single test containing resolved and derived statements' => [
                'testPaths' => [
                    'test.yml'
                ],
                'testPropertiesCollection' => [
                    [
                        'basilTestConfiguration' => new Configuration('chrome', 'http://example.com'),
                        'basilStepName' => 'verify page is open',
                        'status' => Status::STATUS_PASSED,
                        'handledStatements' => [
                            $assertionParser->parse('$page.url is "http://example.com/"'),
                            $assertionParser->parse('$page.title is "Example Domain"'),
                        ],
                    ],
                    [
                        'basilStepName' => 'passing actions and assertions',
                        'status' => Status::STATUS_PASSED,
                        'handledStatements' => [
                            new DerivedValueOperationAssertion(
                                new ResolvedAction(
                                    $actionParser->parse('click $page_import_name.elements.selector'),
                                    '$".button"'
                                ),
                                '$".button"',
                                'exists'
                            ),
                            new ResolvedAction(
                                $actionParser->parse('click $page_import_name.elements.selector'),
                                '$".button"'
                            ),
                            $actionParser->parse('set $".form" >> $".input" to "literal value"'),
                            $assertionParser->parse('$".button".data-clicked is "1"'),
                            $assertionParser->parse('$".form" >> $".input" is "literal value"'),
                        ],
                    ],
                ],
                'expectedOutput' => FixtureLoader::load('/ResultPrinter/passed-single-test.yaml'),
            ],
            'passed, multiple tests' => [
                'testPaths' => [
                    'test1.yml',
                    'test2.yml',
                ],
                'testPropertiesCollection' => [
                    [
                        'basilTestConfiguration' => new Configuration('chrome', 'http://example.com'),
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_PASSED,
                        'handledStatements' => [
                            $assertionParser->parse('$page.url is "http://example.com/"'),
                            $assertionParser->parse('$page.title is "Example Domain"'),
                        ],
                    ],
                    [
                        'basilTestConfiguration' => new Configuration('chrome', 'http://example.com'),
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_PASSED,
                        'handledStatements' => [
                            $actionParser->parse('click $".button"'),
                            $actionParser->parse('set $".form" >> $".input" to "literal value"'),
                            $assertionParser->parse('$".button".data-clicked is "1"'),
                            $assertionParser->parse('$".form" >> $".input" is "literal value"'),
                        ],
                    ],
                ],
                'expectedOutput' => FixtureLoader::load('/ResultPrinter/passed-multiple-tests.yaml'),
            ],
        ];
    }

    public function failedExistsAssertionDataProvider(): array
    {
        $actionParser = ActionParser::create();
        $assertionParser = AssertionParser::create();

        return [
            'failed, element exists assertion' => [
                'testPaths' => [
                    'test.yml'
                ],
                'testPropertiesCollection' => [
                    [
                        'basilTestConfiguration' => new Configuration('chrome', 'http://example.com'),
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$".selector" exists'),
                        ],
                    ],
                ],
                'expectedOutput' => FixtureLoader::load('/ResultPrinter/failed-exists-assertion-element.yaml'),
            ],
            'failed, derived element exists assertion' => [
                'testPaths' => [
                    'test.yml'
                ],
                'testPropertiesCollection' => [
                    [
                        'basilTestConfiguration' => new Configuration('chrome', 'http://example.com'),
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            new DerivedValueOperationAssertion(
                                new ResolvedAction(
                                    $actionParser->parse('click $page_import_name.elements.selector'),
                                    '$".selector"'
                                ),
                                '$".selector"',
                                'exists'
                            ),
                        ],
                    ],
                ],
                'expectedOutput' => FixtureLoader::load('/ResultPrinter/failed-derived-exists-assertion-element.yaml'),
            ],
            'failed, descendant element exists assertion' => [
                'testPaths' => [
                    'test.yml'
                ],
                'testPropertiesCollection' => [
                    [
                        'basilTestConfiguration' => new Configuration('chrome', 'http://example.com'),
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$"form":3 >> $"input":2 exists'),
                        ],
                    ],
                ],
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinter/failed-exists-assertion-descendant-element.yaml'
                ),
            ],
            'failed, descendant css/xpath element exists assertion' => [
                'testPaths' => [
                    'test.yml'
                ],
                'testPropertiesCollection' => [
                    [
                        'basilTestConfiguration' => new Configuration('chrome', 'http://example.com'),
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$"form" >> $"/input" exists'),
                        ],
                    ],
                ],
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinter/failed-exists-assertion-descendant-css-xpath-element.yaml'
                ),
            ],
            'failed, attribute exists assertion' => [
                'testPaths' => [
                    'test.yml'
                ],
                'testPropertiesCollection' => [
                    [
                        'basilTestConfiguration' => new Configuration('chrome', 'http://example.com'),
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$".selector".attribute_name exists'),
                        ],
                    ],
                ],
                'expectedOutput' => FixtureLoader::load('/ResultPrinter/failed-exists-assertion-attribute.yaml'),
            ],
        ];
    }

    public function failedExceptionDataProvider(): array
    {
        $assertionParser = AssertionParser::create();

        return [
            'failed, invalid locator exception' => [
                'testPaths' => [
                    'test.yml'
                ],
                'testPropertiesCollection' => [
                    [
                        'basilTestConfiguration' => new Configuration('chrome', 'http://example.com'),
                        'basilStepName' => 'step with invalid locator exception',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$"a[href=https://example.com/]" exists'),
                        ],
                        'lastException' => new InvalidLocatorException(
                            new ElementIdentifier('a[href=https://example.com/]'),
                            \Mockery::mock(InvalidSelectorException::class)
                        ),
                    ],
                ],
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinter/failed-exception-invalid-locator-exception.yaml'
                ),
            ],
            'failed, unknown exception' => [
                'testPaths' => [
                    'test.yml'
                ],
                'testPropertiesCollection' => [
                    [
                        'basilTestConfiguration' => new Configuration('chrome', 'http://example.com'),
                        'basilStepName' => 'step with unknown exception',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$"a[href=https://example.com/]" exists'),
                        ],
                        'lastException' => new \LogicException('Invalid logic')
                    ],
                ],
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinter/failed-exception-unknown-exception.yaml'
                ),
            ],
        ];
    }

    public function failedIsAssertionDataProvider(): array
    {
        $assertionParser = AssertionParser::create();

        return [
            'failed, is assertion, scalar is scalar, browser property is literal' => [
                'testPaths' => [
                    'test.yml'
                ],
                'testPropertiesCollection' => [
                    [
                        'basilTestConfiguration' => new Configuration('chrome', 'http://example.com'),
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$browser.size is "literal value"'),
                        ],
                        'expectedValue' => 'literal value',
                        'examinedValue' => '1024x768',
                    ],
                ],
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinter/failed-is-assertion-browser-property-is-literal.yaml'
                ),
            ],
            'failed, is assertion, scalar is scalar, page property is data parameter' => [
                'testPaths' => [
                    'test.yml'
                ],
                'testPropertiesCollection' => [
                    [
                        'basilTestConfiguration' => new Configuration('chrome', 'http://example.com'),
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$page.title is $data.expected_title'),
                        ],
                        'expectedValue' => 'expected title value',
                        'examinedValue' => 'Example Domain',
                        'dataSet' => [
                            'expected_url' => 'expected title value'
                        ],
                    ],
                ],
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinter/failed-is-assertion-browser-property-is-data-parameter.yaml'
                ),
            ],
            'failed, is assertion, scalar is scalar, page property is environment parameter' => [
                'testPaths' => [
                    'test.yml'
                ],
                'testPropertiesCollection' => [
                    [
                        'basilTestConfiguration' => new Configuration('chrome', 'http://example.com'),
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$page.title is $env.PAGE_TITLE'),
                        ],
                        'expectedValue' => 'expected title value',
                        'examinedValue' => 'Example Domain',
                    ],
                ],
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinter/failed-is-assertion-browser-property-is-environment-parameter.yaml'
                ),
            ],
            'failed, is assertion, node is scalar' => [
                'testPaths' => [
                    'test.yml'
                ],
                'testPropertiesCollection' => [
                    [
                        'basilTestConfiguration' => new Configuration('chrome', 'http://example.com'),
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$".selector" is "expected value"'),
                        ],
                        'expectedValue' => 'expected value',
                        'examinedValue' => 'actual value',
                    ],
                ],
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinter/failed-is-assertion-node-is-scalar.yaml'
                ),
            ],
            'failed, is assertion, scalar is node' => [
                'testPaths' => [
                    'test.yml'
                ],
                'testPropertiesCollection' => [
                    [
                        'basilTestConfiguration' => new Configuration('chrome', 'http://example.com'),
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$page.title is $".selector"'),
                        ],
                        'expectedValue' => 'expected value',
                        'examinedValue' => 'actual value',
                    ],
                ],
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinter/failed-is-assertion-scalar-is-node.yaml'
                ),
            ],
            'failed, is assertion, node is node' => [
                'testPaths' => [
                    'test.yml'
                ],
                'testPropertiesCollection' => [
                    [
                        'basilTestConfiguration' => new Configuration('chrome', 'http://example.com'),
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$".actual" is $".expected"'),
                        ],
                        'expectedValue' => 'expected value',
                        'examinedValue' => 'actual value',
                    ],
                ],
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinter/failed-is-assertion-node-is-node.yaml'
                ),
            ],
        ];
    }

    public function failedIsRegExpDataProvider(): array
    {
        return [
            'failed, attribute is-regexp assertion' => [
                'testPaths' => [
                    'test.yml'
                ],
                'testPropertiesCollection' => [
                    [
                        'basilTestConfiguration' => new Configuration('chrome', 'http://example.com'),
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $this->createDerivedIsRegExpAssertion('$page.title matches $".selector".attribute_name'),
                        ],
                        'examinedValue' => 'not a regexp',
                    ],
                ],
                'expectedOutput' => FixtureLoader::load('/ResultPrinter/failed-is-regexp-assertion-attribute.yaml'),
            ],
            'failed, element is-regexp assertion' => [
                'testPaths' => [
                    'test.yml'
                ],
                'testPropertiesCollection' => [
                    [
                        'basilTestConfiguration' => new Configuration('chrome', 'http://example.com'),
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $this->createDerivedIsRegExpAssertion('$page.title matches $".selector"'),
                        ],
                        'examinedValue' => 'not a regexp',
                    ],
                ],
                'expectedOutput' => FixtureLoader::load('/ResultPrinter/failed-is-regexp-assertion-element.yaml'),
            ],
            'failed, scalar is-regexp assertion' => [
                'testPaths' => [
                    'test.yml'
                ],
                'testPropertiesCollection' => [
                    [
                        'basilTestConfiguration' => new Configuration('chrome', 'http://example.com'),
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $this->createDerivedIsRegExpAssertion('$page.title matches "not a regexp"'),
                        ],
                        'examinedValue' => 'not a regexp',
                    ],
                ],
                'expectedOutput' => FixtureLoader::load('/ResultPrinter/failed-is-regexp-assertion-scalar.yaml'),
            ],
        ];
    }

    public function failedNoStatementsDataProvider(): array
    {
        return [
            'failed, no statements' => [
                'testPaths' => [
                    'test.yml'
                ],
                'testPropertiesCollection' => [
                    [
                        'basilTestConfiguration' => new Configuration('chrome', 'http://example.com'),
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [],
                    ],
                ],
                'expectedOutput' => FixtureLoader::load('/ResultPrinter/failed-no-statements.yaml'),
            ],
        ];
    }

    private function createDerivedIsRegExpAssertion(string $assertionSource): DerivedValueOperationAssertion
    {
        $assertion = AssertionParser::create()->parse($assertionSource);

        return new DerivedValueOperationAssertion($assertion, (string) $assertion->getValue(), 'is-regexp');
    }
}
