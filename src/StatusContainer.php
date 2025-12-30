<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use webignition\BasilPhpUnitResultPrinter\Model\Status;

class StatusContainer implements \Stringable
{
    private Status $status;

    public function __construct()
    {
        $this->status = new Status(Status::STATUS_UNKNOWN);
    }

    public function __toString(): string
    {
        return (string) $this->status;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }
}
