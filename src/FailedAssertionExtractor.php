<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use PHPUnit\Event\Code\Throwable;
use webignition\BasilModels\Model\Assertion\AssertionInterface;
use webignition\BasilModels\Model\StatementFactory;
use webignition\BasilModels\Model\UnknownEncapsulatedStatementException;

readonly class FailedAssertionExtractor
{
    public function __construct(
        private StatementFactory $statementFactory,
        private JsonExtractor $jsonExtractor,
    ) {}

    public function extract(Throwable $throwable): ?AssertionInterface
    {
        $json = $this->jsonExtractor->extract($throwable);

        try {
            $statement = $this->statementFactory->createFromJson($json);
        } catch (UnknownEncapsulatedStatementException) {
            return null;
        }

        return $statement instanceof AssertionInterface ? $statement : null;
    }
}
