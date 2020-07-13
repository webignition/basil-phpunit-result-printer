<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Generator;

use webignition\BasilPhpUnitResultPrinter\Model\DocumentSourceInterface;

interface GeneratorInterface
{
    public function generate(DocumentSourceInterface $documentSource): string;
}
