<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\ExpectedActualValuesParser;

use PHPUnit\Event\Test\Failed;

readonly class HasEqualLengthValuesHandler implements HandlerInterface
{
    public function handle(Failed $event, string $content): ?array
    {
        if (str_starts_with($content, 'Failed asserting that \'')) {
            $content = substr($content, strlen('Failed asserting that \''));
        } else {
            return null;
        }

        if (str_ends_with($content, '\'.')) {
            $content = substr($content, 0, -strlen('\'.'));
        } else {
            return null;
        }

        $contentMiddlePosition = (int) (strlen($content) / 2);
        $leftHalfContent = substr($content, 0, $contentMiddlePosition);
        $rightHalfContent = substr($content, $contentMiddlePosition);

        $middleContent
            = $this->getLeftHandValueSuffix($leftHalfContent) . $this->getRightHandleValuePrefix($rightHalfContent);
        if (
            ' is equal to' !== $middleContent
            && ' is not equal to ' !== $middleContent
        ) {
            return null;
        }

        $leftHalfFinalQuotePosition = (int) strrpos($leftHalfContent, '\'');
        $rightHandFirstQuotePosition = strpos($rightHalfContent, '\'');

        $expectedValue = substr($leftHalfContent, 0, $leftHalfFinalQuotePosition);
        $actualValue = substr($rightHalfContent, $rightHandFirstQuotePosition + 1);

        return [
            'expected' => $expectedValue,
            'actual' => $actualValue,
        ];
    }

    private function getLeftHandValueSuffix(string $leftHalfContent): string
    {
        $leftHalfFinalQuotePosition = (int) strrpos($leftHalfContent, '\'');

        return substr($leftHalfContent, $leftHalfFinalQuotePosition + 1);
    }

    private function getRightHandleValuePrefix(string $rightHalfContent): string
    {
        $rightHandFirstQuotePosition = (int) strpos($rightHalfContent, '\'');

        return substr($rightHalfContent, 0, $rightHandFirstQuotePosition);
    }
}
