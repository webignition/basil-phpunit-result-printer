<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Generator;

interface GeneratorInterface
{
    /**
     * @param array{"type": string, "payload": array<mixed>} $data
     */
    public function generate(array $data): string;
}
