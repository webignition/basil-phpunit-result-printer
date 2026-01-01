<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use PHPUnit\Event\Code\Throwable;

readonly class FailedAssertionExtractor
{
    public function extract(Throwable $throwable): ?FailedAssertion
    {
        $assertionFailureMessage = $throwable->message();
        $finalBracePosition = (int) strrpos($assertionFailureMessage, '}');
        $json = substr($assertionFailureMessage, 0, $finalBracePosition + 1);

        $data = json_decode($json, true);
        $data = is_array($data) ? $data : [];

        $statement = $data['statement'] ?? null;
        $statement = is_string($statement) ? $statement : null;
        $statement = '' === $statement ? null : $statement;

        if (null === $statement) {
            return null;
        }

        return new FailedAssertion($statement);
    }
}
