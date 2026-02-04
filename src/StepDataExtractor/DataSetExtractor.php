<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\StepDataExtractor;

readonly class DataSetExtractor
{
    /**
     * @return array<string, bool|int|string>
     */
    public function extract(string $arrayAsString): array
    {
        $lines = explode("\n", $arrayAsString);
        array_shift($lines);
        array_pop($lines);

        $extracted = [];

        foreach ($lines as $line) {
            $extracted = array_merge($extracted, $this->extractFromLine($line));
        }

        return $extracted;
    }

    /**
     * @return array<string, bool|int|string>
     */
    private function extractFromLine(string $line): array
    {
        $line = trim($line);

        $assignmentOperator = ' => ';

        $assignmentOperatorPosition = (int) strpos($line, $assignmentOperator);
        $nameComponent = substr($line, 0, $assignmentOperatorPosition);
        $key = trim($nameComponent, '\' ');

        $valueComponent = substr($line, $assignmentOperatorPosition + strlen($assignmentOperator));
        $valueComponent = rtrim($valueComponent, ',');

        $value = $this->getValueFromString($valueComponent);

        return [
            $key => $value,
        ];
    }

    private function getValueFromString(string $value): bool|int|string
    {
        $singleQuoteTrimmedValue = trim($value, '\'');
        if ($singleQuoteTrimmedValue !== $value) {
            return $singleQuoteTrimmedValue;
        }

        if (ctype_digit($value)) {
            return (int) $value;
        }

        if ('true' === $value) {
            return true;
        }

        if ('false' === $value) {
            return false;
        }

        return $value;
    }
}
