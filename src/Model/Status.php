<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model;

use PHPUnit\Runner\BaseTestRunner;

class Status implements \Stringable
{
    public const LABEL_PASSED = 'passed';
    public const LABEL_FAILED = 'failed';
    public const LABEL_UNKNOWN = 'unknown';
    public const LABEL_TERMINATED = 'terminated';

    public const STATUS_PASSED = BaseTestRunner::STATUS_PASSED;
    public const STATUS_FAILED = BaseTestRunner::STATUS_FAILURE;
    public const STATUS_TERMINATED = BaseTestRunner::STATUS_ERROR;

    private const MAP = [
        self::STATUS_PASSED => self::LABEL_PASSED,
        self::STATUS_FAILED => self::LABEL_FAILED,
        self::STATUS_TERMINATED => self::LABEL_TERMINATED,
    ];

    public function __construct(
        private int $status
    ) {}

    public function __toString(): string
    {
        return self::MAP[$this->status] ?? self::LABEL_UNKNOWN;
    }
}
