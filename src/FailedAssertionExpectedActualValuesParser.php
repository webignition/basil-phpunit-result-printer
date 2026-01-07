<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

use PHPUnit\Event\Test\Failed;

readonly class FailedAssertionExpectedActualValuesParser
{
    /**
     * @return array{'expected': string, 'actual': string}
     */
    public function parse(Failed $event, string $content): array
    {
        if ($event->hasComparisonFailure()) {
            return [
                'expected' => 'expected-from-comparison-failure',
                'actual' => 'actual-from-comparison-failure',
            ];
        }

        $expectedValueLength = $this->getLastNumber($content);
        $expectedValueSuffix = ' [ASCII](length: ' . $expectedValueLength . ').';
        $expectedValueSuffixLength = strlen($expectedValueSuffix);

        $content = substr($content, 0, strlen($content) - $expectedValueSuffixLength - 1);
        $expectedValue = substr($content, strlen($content) - $expectedValueLength);

        $content = substr($content, 0, strlen($content) - $expectedValueLength - 1);

        $actualValueLength = $this->getLastNumber($content);
        $actualValueSuffix = ' [ASCII](length: ' . $actualValueLength . ') contains ';
        $actualValueSuffixLength = strlen($actualValueSuffix);

        $content = substr($content, 0, strlen($content) - $actualValueSuffixLength - 1);
        $actualValue = substr($content, strlen($content) - $actualValueLength);

        return [
            'expected' => $expectedValue,
            'actual' => $actualValue,
        ];
    }

    private function getLastNumber(string $message): int
    {
        $state = null;
        $stateCollectingDigits = 'collecting-digits';
        $stateFinished = 'finished';

        $characters = str_split($message);
        $reversedCharacters = array_reverse($characters);

        $digits = [];

        foreach ($reversedCharacters as $character) {
            if ($state === $stateFinished) {
                continue;
            }

            if (ctype_digit($character) && null === $state) {
                $state = $stateCollectingDigits;
            }

            if (!ctype_digit($character) && $state === $stateCollectingDigits) {
                $state = $stateFinished;
            }

            if ($state === $stateCollectingDigits) {
                $digits[] = $character;
            }
        }

        return (int) implode('', array_reverse($digits));
    }
}
