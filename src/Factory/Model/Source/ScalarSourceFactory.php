<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Factory\Model\Source;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\ScalarFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Scalar;
use webignition\BasilPhpUnitResultPrinter\Model\Source\ScalarSource;

class ScalarSourceFactory
{
    public function __construct(
        private ScalarFactory $scalarFactory
    ) {}

    public static function createFactory(): self
    {
        return new ScalarSourceFactory(
            ScalarFactory::createFactory()
        );
    }

    public function create(string $source): ?ScalarSource
    {
        $scalar = $this->scalarFactory->create($source);
        if ($scalar instanceof Scalar) {
            return new ScalarSource($scalar);
        }

        return null;
    }
}
