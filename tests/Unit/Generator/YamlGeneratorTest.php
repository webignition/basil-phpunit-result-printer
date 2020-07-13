<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Generator;

use webignition\BasilPhpUnitResultPrinter\Generator\YamlGenerator;
use webignition\BasilPhpUnitResultPrinter\Model\DocumentSourceInterface;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class YamlGeneratorTest extends AbstractBaseTest
{
    /**
     * @dataProvider generateDataProvider
     */
    public function testGenerate(DocumentSourceInterface $documentSource, string $expectedString)
    {
        $generator = new YamlGenerator();

        $this->assertSame($expectedString, $generator->generate($documentSource));
    }

    public function generateDataProvider(): array
    {
        return [
            'empty document' => [
                'documentSource' => $this->createDocumentSource([]),
                'expectedString' =>
                    '---' . "\n" .
                    '{  }' . "\n" .
                    '...' . "\n",
            ],
            'single-level document' => [
                'documentSource' => $this->createDocumentSource([
                    'level1key1' => 'level1value1',
                ]),
                'expectedString' =>
                    '---' . "\n" .
                    'level1key1: level1value1' . "\n" .
                    '...' . "\n",
            ],
            'two-level document' => [
                'documentSource' => $this->createDocumentSource([
                    'level1key1' => 'level1value1',
                    'level1key2' => [
                        'level2key1' => 'level2value1',
                    ],

                ]),
                'expectedString' =>
                    '---' . "\n" .
                    'level1key1: level1value1' . "\n" .
                    'level1key2:' . "\n" .
                    '  level2key1: level2value1' . "\n" .
                    '...' . "\n",
            ],
            'three-level document' => [
                'documentSource' => $this->createDocumentSource([
                    'level1key1' => 'level1value1',
                    'level1key2' => [
                        'level2key1' => 'level2value1',
                        'level2key2' => [
                            'level3key1' => 'level3value1',
                        ],
                    ],

                ]),
                'expectedString' =>
                    '---' . "\n" .
                    'level1key1: level1value1' . "\n" .
                    'level1key2:' . "\n" .
                    '  level2key1: level2value1' . "\n" .
                    '  level2key2:' . "\n" .
                    '    level3key1: level3value1' . "\n" .
                    '...' . "\n",
            ],
        ];
    }

    /**
     * @param array<mixed> $data
     *
     * @return DocumentSourceInterface
     */
    private function createDocumentSource(array $data): DocumentSourceInterface
    {
        $documentSource = \Mockery::mock(DocumentSourceInterface::class);
        $documentSource
            ->shouldReceive('getData')
            ->andReturn($data);

        return $documentSource;
    }
}
