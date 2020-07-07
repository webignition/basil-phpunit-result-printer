<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Generator;

use Symfony\Component\Yaml\Yaml;
use webignition\BasilPhpUnitResultPrinter\FooModel\DocumentSourceInterface;

class YamlGenerator implements GeneratorInterface
{
    private const DOCUMENT_START = '---';
    private const DOCUMENT_END = '...';

    public function generate(DocumentSourceInterface $documentSource): string
    {
        return
            self::DOCUMENT_START . "\n" .
            trim(Yaml::dump($documentSource->getData(), 3)) . "\n" .
            self::DOCUMENT_END . "\n";
    }
}
