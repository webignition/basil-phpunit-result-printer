<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\Statement;

use webignition\BasilPhpUnitResultPrinter\Model\ExceptionData\ExceptionDataInterface;
use webignition\BasilRunnerDocuments\DocumentInterface;

interface StatementInterface extends DocumentInterface
{
    public function withExceptionData(ExceptionDataInterface $exceptionData): StatementInterface;
}
