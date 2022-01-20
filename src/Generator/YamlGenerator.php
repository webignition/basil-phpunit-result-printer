<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Generator;

use webignition\YamlDocumentGenerator\YamlGenerator as EncapsulatedYamlGenerator;

class YamlGenerator extends EncapsulatedYamlGenerator implements GeneratorInterface
{
    public function __construct()
    {
        $this->setInlineDepth(parent::DEFAULT_INLINE_DEPTH + 1);
    }
}
