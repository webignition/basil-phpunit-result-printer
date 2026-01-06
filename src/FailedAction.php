<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use webignition\BasilModels\Model\Action\ActionInterface;

readonly class FailedAction
{
    /**
     * @param non-empty-string $reason
     */
    public function __construct(
        public ActionInterface $action,
        public string $reason,
        public FailedActionException $exception,
    ) {}
}
