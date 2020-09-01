<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Generator;

use webignition\BasilRunnerDocuments\DocumentInterface;

interface GeneratorInterface
{
    public function generate(DocumentInterface $documentSource): string;
}
