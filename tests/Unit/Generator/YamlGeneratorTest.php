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
                'documentSource' => $this->createDocumentSource('empty-document', []),
                'expectedString' =>
                    '---' . "\n" .
                    'type: empty-document' . "\n" .
                    '...' . "\n",
            ],
            'single-level document' => [
                'documentSource' => $this->createDocumentSource(
                    'single-level-document',
                    [
                        'level1key1' => 'level1value1',
                    ]
                ),
                'expectedString' =>
                    '---' . "\n" .
                    'type: single-level-document' . "\n" .
                    'level1key1: level1value1' . "\n" .
                    '...' . "\n",
            ],
            'two-level document' => [
                'documentSource' => $this->createDocumentSource(
                    'two-level-document',
                    [
                        'level1key1' => 'level1value1',
                        'level1key2' => [
                            'level2key1' => 'level2value1',
                        ],

                    ]
                ),
                'expectedString' =>
                    '---' . "\n" .
                    'type: two-level-document' . "\n" .
                    'level1key1: level1value1' . "\n" .
                    'level1key2:' . "\n" .
                    '  level2key1: level2value1' . "\n" .
                    '...' . "\n",
            ],
            'three-level document' => [
                'documentSource' => $this->createDocumentSource(
                    'three-level-document',
                    [
                        'level1key1' => 'level1value1',
                        'level1key2' => [
                            'level2key1' => 'level2value1',
                            'level2key2' => [
                                'level3key1' => 'level3value1',
                            ],
                        ],

                    ]
                ),
                'expectedString' =>
                    '---' . "\n" .
                    'type: three-level-document' . "\n" .
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
    private function createDocumentSource(string $type, array $data): DocumentSourceInterface
    {
        $documentSource = \Mockery::mock(DocumentSourceInterface::class);

        $documentSource
            ->shouldReceive('getType')
            ->andReturn($type);

        $documentSource
            ->shouldReceive('getData')
            ->andReturn($data);

        return $documentSource;
    }
}
