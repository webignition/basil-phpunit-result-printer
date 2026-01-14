<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit;

use webignition\BasilModels\Model\Assertion\DerivedValueOperationAssertion;
use webignition\BasilModels\Parser\AssertionParser;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\ResultPrinter;
use webignition\BasilPhpUnitResultPrinter\Tests\Services\FixtureLoader;

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
        ];
    }

    private static function createDerivedIsRegExpAssertion(string $assertionSource): DerivedValueOperationAssertion
    {
        $assertion = AssertionParser::create()->parse($assertionSource, 0);

        return new DerivedValueOperationAssertion($assertion, (string) $assertion->getValue(), 'is-regexp');
    }
}
