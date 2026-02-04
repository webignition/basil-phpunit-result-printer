<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\AssertionFailure;

readonly class ExceptionFactory
{
    /**
     * @param array<mixed> $data
     */
    public function create(array $data): ?Exception
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

        return new Exception($class, $code, $message);
    }
}
