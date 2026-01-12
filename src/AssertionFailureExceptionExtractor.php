<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

readonly class AssertionFailureExceptionExtractor
{
    /**
     * @param array<mixed> $data
     */
    public function extract(array $data): ?AssertionFailureException
    {
        $class = $data['class'] ?? '';
        $class = is_string($class) ? $class : '';
        $class = trim($class);

        if ('' === $class) {
            return null;
        }

        $code = $data['code'] ?? 0;
        $code = is_int($code) ? $code : 0;

        $message = $data['message'] ?? '';
        $message = is_string($message) ? $message : '';
        $message = trim($message);

        if ('' === $message) {
            return null;
        }

        return new AssertionFailureException($class, $code, $message);
    }
}
