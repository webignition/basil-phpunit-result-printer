<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit;

use Facebook\WebDriver\Exception\InvalidSelectorException;
use webignition\BasilModels\Model\Action\ResolvedAction;
use webignition\BasilModels\Model\Assertion\DerivedValueOperationAssertion;
use webignition\BasilModels\Parser\ActionParser;
use webignition\BasilModels\Parser\AssertionParser;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\ResultPrinter;
use webignition\BasilPhpUnitResultPrinter\Tests\Services\FixtureLoader;
use webignition\DomElementIdentifier\ElementIdentifier;
use webignition\SymfonyDomCrawlerNavigator\Exception\InvalidLocatorException;

class ResultPrinterTest extends AbstractBaseTestCase
{
    /**
     * dataProvider passedDataProvider
     * dataProvider failedExistsAssertionDataProvider
     * dataProvider failedExceptionDataProvider
     * dataProvider failedIsAssertionDataProvider
     * dataProvider failedIsRegExpDataProvider
     * dataProvider failedNoStatementsDataProvider.
     *
     * @param BasilTestCaseInterface[] $tests
     */
    public function testPrinterOutput(/* array $tests, string $expectedOutput */): void
    {
        self::markTestSkipped('Obsolete. Keeping for reference until feature complete. Remove in #232');

        //        $outResource = fopen('php://memory', 'w+');
        //
        //        if (is_resource($outResource)) {
        //            $printer = new ResultPrinter($outResource);
        //
        //            foreach ($tests as $test) {
        //                $printer->startTest($test);
        //                $printer->endTest($test, 0.1);
        //            }
        //
        //            rewind($outResource);
        //            $outContent = stream_get_contents($outResource);
        //            fclose($outResource);
        //
        //            self::assertSame($expectedOutput, $outContent);
        //        } else {
        //            $this->fail('Failed to open resource "php://memory" for reading and writing');
        //        }
    }

    /**
     * @return array<mixed>
     */
    public static function passedDataProvider(): array
    {
        $actionParser = ActionParser::create();
        $assertionParser = AssertionParser::create();

        return [
            'passed, single test containing resolved and derived statements' => [
                'tests' => [
                    BasilTestCaseFactory::create([
                        'basilTestPath' => 'test.yml',
                        'basilStepName' => 'verify page is open',
                        'status' => Status::STATUS_PASSED,
                        'handledStatements' => [
                            $assertionParser->parse('$page.url is "http://example.com/"', 0),
                            $assertionParser->parse('$page.title is "Example Domain"', 0),
                        ],
                    ]),
                    BasilTestCaseFactory::create([
                        'basilStepName' => 'passing actions and assertions',
                        'status' => Status::STATUS_PASSED,
                        'handledStatements' => [
                            new DerivedValueOperationAssertion(
                                new ResolvedAction(
                                    $actionParser->parse('click $page_import_name.elements.selector', 0),
                                    '$".button"'
                                ),
                                '$".button"',
                                'exists'
                            ),
                            new ResolvedAction(
                                $actionParser->parse('click $page_import_name.elements.selector', 0),
                                '$".button"'
                            ),
                            $actionParser->parse('set $".form" >> $".input" to "literal value"', 0),
                            $assertionParser->parse('$".button".data-clicked is "1"', 0),
                            $assertionParser->parse('$".form" >> $".input" is "literal value"', 0),
                        ],
                    ]),
                ],
                'expectedOutput' => FixtureLoader::load('/ResultPrinter/passed-single-test.yaml', 0),
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function failedExistsAssertionDataProvider(): array
    {
        $actionParser = ActionParser::create();
        $assertionParser = AssertionParser::create();

        return [
            'failed, element exists assertion' => [
                'tests' => [
                    BasilTestCaseFactory::create([
                        'basilTestPath' => 'test.yml',
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$".selector" exists', 0),
                        ],
                    ]),
                ],
                'expectedOutput' => FixtureLoader::load('/ResultPrinter/failed-exists-assertion-element.yaml', 0),
            ],
            'failed, descendant element exists assertion' => [
                'tests' => [
                    BasilTestCaseFactory::create([
                        'basilTestPath' => 'test.yml',
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$"form":3 >> $"input":2 exists', 0),
                        ],
                    ]),
                ],
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinter/failed-exists-assertion-descendant-element.yaml'
                ),
            ],
            'failed, descendant css/xpath element exists assertion' => [
                'tests' => [
                    BasilTestCaseFactory::create([
                        'basilTestPath' => 'test.yml',
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$"form" >> $"/input" exists', 0),
                        ],
                    ]),
                ],
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinter/failed-exists-assertion-descendant-css-xpath-element.yaml'
                ),
            ],
            'failed, attribute exists assertion' => [
                'tests' => [
                    BasilTestCaseFactory::create([
                        'basilTestPath' => 'test.yml',
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$".selector".attribute_name exists', 0),
                        ],
                    ]),
                ],
                'expectedOutput' => FixtureLoader::load('/ResultPrinter/failed-exists-assertion-attribute.yaml', 0),
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function failedExceptionDataProvider(): array
    {
        $assertionParser = AssertionParser::create();

        return [
            'failed, unknown exception' => [
                'tests' => [
                    BasilTestCaseFactory::create([
                        'basilTestPath' => 'test.yml',
                        'basilStepName' => 'step with unknown exception',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$"a[href=https://example.com/]" exists', 0),
                        ],
                        'lastException' => new \LogicException('Invalid logic', 0),
                    ]),
                ],
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinter/failed-exception-unknown-exception.yaml'
                ),
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function failedIsAssertionDataProvider(): array
    {
        $assertionParser = AssertionParser::create();

        return [
            'failed, is assertion, scalar is scalar, browser property is literal' => [
                'tests' => [
                    BasilTestCaseFactory::create([
                        'basilTestPath' => 'test.yml',
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$browser.size is "literal value"', 0),
                        ],
                        'expectedValue' => 'literal value',
                        'examinedValue' => '1024x768',
                    ]),
                ],
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinter/failed-is-assertion-browser-property-is-literal.yaml'
                ),
            ],
            'failed, is assertion, scalar is scalar, page property is data parameter' => [
                'tests' => [
                    BasilTestCaseFactory::create([
                        'basilTestPath' => 'test.yml',
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$page.title is $data.expected_title', 0),
                        ],
                        'expectedValue' => 'expected title value',
                        'examinedValue' => 'Example Domain',
                        'dataSet' => [
                            'expected_url' => 'expected title value',
                        ],
                    ]),
                ],
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinter/failed-is-assertion-browser-property-is-data-parameter.yaml'
                ),
            ],
            'failed, is assertion, scalar is scalar, page property is environment parameter' => [
                'tests' => [
                    BasilTestCaseFactory::create([
                        'basilTestPath' => 'test.yml',
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$page.title is $env.PAGE_TITLE', 0),
                        ],
                        'expectedValue' => 'expected title value',
                        'examinedValue' => 'Example Domain',
                    ]),
                ],
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinter/failed-is-assertion-browser-property-is-environment-parameter.yaml'
                ),
            ],
            'failed, is assertion, node is scalar' => [
                'tests' => [
                    BasilTestCaseFactory::create([
                        'basilTestPath' => 'test.yml',
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$".selector" is "expected value"', 0),
                        ],
                        'expectedValue' => 'expected value',
                        'examinedValue' => 'actual value',
                    ]),
                ],
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinter/failed-is-assertion-node-is-scalar.yaml'
                ),
            ],
            'failed, is assertion, scalar is node' => [
                'tests' => [
                    BasilTestCaseFactory::create([
                        'basilTestPath' => 'test.yml',
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$page.title is $".selector"', 0),
                        ],
                        'expectedValue' => 'expected value',
                        'examinedValue' => 'actual value',
                    ]),
                ],
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinter/failed-is-assertion-scalar-is-node.yaml'
                ),
            ],
            'failed, is assertion, node is node' => [
                'tests' => [
                    BasilTestCaseFactory::create([
                        'basilTestPath' => 'test.yml',
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $assertionParser->parse('$".actual" is $".expected"', 0),
                        ],
                        'expectedValue' => 'expected value',
                        'examinedValue' => 'actual value',
                    ]),
                ],
                'expectedOutput' => FixtureLoader::load(
                    '/ResultPrinter/failed-is-assertion-node-is-node.yaml'
                ),
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function failedIsRegExpDataProvider(): array
    {
        return [
            'failed, attribute is-regexp assertion' => [
                'tests' => [
                    BasilTestCaseFactory::create([
                        'basilTestPath' => 'test.yml',
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            self::createDerivedIsRegExpAssertion('$page.title matches $".selector".attribute_name', 0),
                        ],
                        'examinedValue' => 'not a regexp',
                    ]),
                ],
                'expectedOutput' => FixtureLoader::load('/ResultPrinter/failed-is-regexp-assertion-attribute.yaml', 0),
            ],
            'failed, element is-regexp assertion' => [
                'tests' => [
                    BasilTestCaseFactory::create([
                        'basilTestPath' => 'test.yml',
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            self::createDerivedIsRegExpAssertion('$page.title matches $".selector"', 0),
                        ],
                        'examinedValue' => 'not a regexp',
                    ]),
                ],
                'expectedOutput' => FixtureLoader::load('/ResultPrinter/failed-is-regexp-assertion-element.yaml', 0),
            ],
            'failed, scalar is-regexp assertion' => [
                'tests' => [
                    BasilTestCaseFactory::create([
                        'basilTestPath' => 'test.yml',
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            self::createDerivedIsRegExpAssertion('$page.title matches "not a regexp"', 0),
                        ],
                        'examinedValue' => 'not a regexp',
                    ]),
                ],
                'expectedOutput' => FixtureLoader::load('/ResultPrinter/failed-is-regexp-assertion-scalar.yaml', 0),
            ],
        ];
    }

    /**
     * @return array<mixed>
     */
    public static function failedNoStatementsDataProvider(): array
    {
        return [
            'failed, no statements' => [
                'tests' => [
                    BasilTestCaseFactory::create([
                        'basilTestPath' => 'test.yml',
                        'basilStepName' => 'step name',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [],
                    ]),
                ],
                'expectedOutput' => FixtureLoader::load('/ResultPrinter/failed-no-statements.yaml', 0),
            ],
        ];
    }

    private static function createDerivedIsRegExpAssertion(string $assertionSource): DerivedValueOperationAssertion
    {
        $assertion = AssertionParser::create()->parse($assertionSource, 0);

        return new DerivedValueOperationAssertion($assertion, (string) $assertion->getValue(), 'is-regexp');
    }
}
