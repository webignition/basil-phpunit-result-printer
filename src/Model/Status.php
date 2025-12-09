<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model;

class Status implements \Stringable
{
    public const LABEL_PASSED = 'passed';
    public const LABEL_FAILED = 'failed';
    public const LABEL_UNKNOWN = 'unknown';
    public const LABEL_TERMINATED = 'terminated';

    public const STATUS_PASSED = 0;
    public const STATUS_FAILED = 3;
    public const STATUS_TERMINATED = 4;

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
