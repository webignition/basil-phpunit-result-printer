<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model;

use PHPUnit\Runner\BaseTestRunner;

class Status
{
    public const LABEL_PASSED = 'passed';
    public const LABEL_FAILED = 'failed';
    public const LABEL_UNKNOWN = 'unknown';

    public const STATUS_PASSED = BaseTestRunner::STATUS_PASSED;
    public const STATUS_FAILED = BaseTestRunner::STATUS_FAILURE;

    private const MAP = [
        self::STATUS_PASSED => self::LABEL_PASSED,
        self::STATUS_FAILED => self::LABEL_FAILED,
    ];

    private int $status;

    public function __construct(int $status)
    {
        $this->status = $status;
    }

    public function __toString(): string
    {
        return self::MAP[$this->status] ?? self::LABEL_UNKNOWN;
    }
}
