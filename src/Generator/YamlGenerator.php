<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Generator;

use Symfony\Component\Yaml\Yaml;
use webignition\BasilPhpUnitResultPrinter\Model\DocumentSourceInterface;

class YamlGenerator implements GeneratorInterface
{
    private const DOCUMENT_START = '---';
    private const DOCUMENT_END = '...';

    private const YAML_DUMP_INLINE_DEPTH = 7;
    private const YAML_DUMP_INDENT_SIZE = 2;

    public function generate(DocumentSourceInterface $documentSource): string
    {
        return
            self::DOCUMENT_START . "\n" .
            trim(Yaml::dump(
                $documentSource->getData(),
                self::YAML_DUMP_INLINE_DEPTH,
                self::YAML_DUMP_INDENT_SIZE
            )) . "\n" .
            self::DOCUMENT_END . "\n";
    }
}
