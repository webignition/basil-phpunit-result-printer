<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit;

use webignition\BaseBasilTestCase\BasilTestCaseInterface;
use webignition\BasilModels\StatementInterface;
use webignition\BasilParser\ActionParser;
use webignition\BasilParser\AssertionParser;
use webignition\BasilPhpUnitResultPrinter\Model\Status;
use webignition\BasilPhpUnitResultPrinter\ResultPrinter;
use webignition\BasilPhpUnitResultPrinter\Tests\Services\BasilTestCaseFactory;

class ResultPrinterTest extends AbstractBaseTest
{
    /**
     * @dataProvider printerOutputDataProvider
     *
     * @param string[] $testPaths
     * @param string[] $stepNames
     * @param int[] $endStatuses
     * @param array<int, StatementInterface[]> $handledStatements
     * @param array<mixed> $expectedValues
     * @param array<mixed> $examinedValues
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

            $this->exercisePrinter($printer, $tests);

            rewind($outResource);
            $outContent = stream_get_contents($outResource);
            fclose($outResource);

            $this->assertSame($expectedOutput, $outContent);
        } else {
            $this->fail('Failed to open resource "php://memory" for reading and writing');
        }
    }

    public function printerOutputDataProvider(): array
    {
        $actionParser = ActionParser::create();
        $assertionParser = AssertionParser::create();

        return [
            'single test' => [
                'testPaths' => [
                    'test.yml'
                ],
                'testPropertiesCollection' => [
                    [
                        'basilStepName' => 'step one',
                        'status' => Status::STATUS_PASSED,
                        'handledStatements' => [
                            $assertionParser->parse('$page.url is "http://example.com/"'),
                        ],
                    ],
                ],
                'expectedOutput' =>
                    '---' . "\n" .
                    'path: test.yml' . "\n" .
                    '...' . "\n" .
                    '---' . "\n" .
                    'name: \'step one\'' . "\n" .
                    'status: passed' . "\n" .
                    'statements:' . "\n" .
                    '  -' . "\n" .
                    '    type: assertion' . "\n" .
                    '    source: \'$page.url is "http://example.com/"\'' . "\n" .
                    '    status: passed' . "\n" .
                    '...' . "\n",
            ],
            'multiple tests' => [
                'testPaths' => [
                    'test1.yml',
                    'test2.yml',
                    'test2.yml',
                    'test3.yml',
                ],
                'testPropertiesCollection' => [
                    [
                        'basilStepName' => 'test one step one',
                        'status' => Status::STATUS_PASSED,
                        'handledStatements' => [
                            $assertionParser->parse('$page.url is "http://example.com/"'),
                            $assertionParser->parse('$page.title is "Hello, World!"'),
                        ],
                    ],
                    [
                        'basilStepName' => 'test two step one',
                        'status' => Status::STATUS_PASSED,
                        'handledStatements' => [
                            $actionParser->parse('click $".successful"'),
                            $assertionParser->parse('$page.url is "http://example.com/successful/"')
                        ],
                    ],
                    [
                        'basilStepName' => 'test two step two',
                        'status' => Status::STATUS_PASSED,
                        'handledStatements' => [
                            $actionParser->parse('click $".back"'),
                            $assertionParser->parse('$page.url is "http://example.com/"'),
                        ],
                    ],
                    [
                        'basilStepName' => 'test three step one',
                        'status' => Status::STATUS_FAILED,
                        'handledStatements' => [
                            $actionParser->parse('click $".new"'),
                            $assertionParser->parse('$page.url is "http://example.com/new/"'),
                        ],
                        'expectedValue' => 'http://example.com/new/',
                        'examinedValue' => 'http://example.com/',
                    ],
                ],
                'expectedOutput' =>
                    '---' . "\n" .
                    'path: test1.yml' . "\n" .
                    '...' . "\n" .
                    '---' . "\n" .
                    'name: \'test one step one\'' . "\n" .
                    'status: passed' . "\n" .
                    'statements:' . "\n" .
                    '  -' . "\n" .
                    '    type: assertion' . "\n" .
                    '    source: \'$page.url is "http://example.com/"\'' . "\n" .
                    '    status: passed' . "\n" .
                    '  -' . "\n" .
                    '    type: assertion' . "\n" .
                    '    source: \'$page.title is "Hello, World!"\'' . "\n" .
                    '    status: passed' . "\n" .
                    '...' . "\n" .
                    '---' . "\n" .
                    'path: test2.yml' . "\n" .
                    '...' . "\n" .
                    '---' . "\n" .
                    'name: \'test two step one\'' . "\n" .
                    'status: passed' . "\n" .
                    'statements:' . "\n" .
                    '  -' . "\n" .
                    '    type: action' . "\n" .
                    '    source: \'click $".successful"\'' . "\n" .
                    '    status: passed' . "\n" .
                    '  -' . "\n" .
                    '    type: assertion' . "\n" .
                    '    source: \'$page.url is "http://example.com/successful/"\'' . "\n" .
                    '    status: passed' . "\n" .
                    '...' . "\n" .
                    '---' . "\n" .
                    'name: \'test two step two\'' . "\n" .
                    'status: passed' . "\n" .
                    'statements:' . "\n" .
                    '  -' . "\n" .
                    '    type: action' . "\n" .
                    '    source: \'click $".back"\'' . "\n" .
                    '    status: passed' . "\n" .
                    '  -' . "\n" .
                    '    type: assertion' . "\n" .
                    '    source: \'$page.url is "http://example.com/"\'' . "\n" .
                    '    status: passed' . "\n" .
                    '...' . "\n" .
                    '---' . "\n" .
                    'path: test3.yml' . "\n" .
                    '...' . "\n" .
                    '---' . "\n" .
                    'name: \'test three step one\'' . "\n" .
                    'status: failed' . "\n" .
                    'statements:' . "\n" .
                    '  -' . "\n" .
                    '    type: action' . "\n" .
                    '    source: \'click $".new"\'' . "\n" .
                    '    status: passed' . "\n" .
                    '  -' . "\n" .
                    '    type: assertion' . "\n" .
                    '    source: \'$page.url is "http://example.com/new/"\'' . "\n" .
                    '    status: failed' . "\n" .
                    '    summary:' . "\n" .
                    '      operator: is' . "\n" .
                    '      expected:' . "\n" .
                    '        value: \'http://example.com/new/\'' . "\n" .
                    '        source:' . "\n" .
                    '          type: scalar' . "\n" .
                    '          body:' . "\n" .
                    '            type: literal' . "\n" .
                    '            value: \'"http://example.com/new/"\'' . "\n" .
                    '      actual:' . "\n" .
                    '        value: \'http://example.com/\'' . "\n" .
                    '        source:' . "\n" .
                    '          type: scalar' . "\n" .
                    '          body:' . "\n" .
                    '            type: page_property' . "\n" .
                    '            value: $page.url' . "\n" .
                    '...' . "\n",
            ],
        ];
    }

    /**
     * @param ResultPrinter $printer
     * @param BasilTestCaseInterface[] $tests
     */
    private function exercisePrinter(ResultPrinter $printer, array $tests): void
    {
        foreach ($tests as $test) {
            $printer->startTest($test);
            $printer->endTest($test, 0.1);
        }
    }
}
