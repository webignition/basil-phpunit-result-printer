<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\ExpectedActualValuesParser;

use PHPUnit\Event\Test\Failed;

readonly class Parser
{
    /**
     * @param HandlerInterface[] $handlers
     */
    public function __construct(
        private array $handlers,
    ) {}

    /**
     * @return array{'expected': string, 'actual': string}
     */
    public function parse(Failed $event, string $content): array
    {
        foreach ($this->handlers as $handler) {
            $result = $handler->handle($event, $content);

            if (is_array($result)) {
                return $result;
            }
        }

        if (str_starts_with($content, 'Failed asserting that \'')) {
            $content = substr($content, strlen('Failed asserting that \''));
        }

        if (str_ends_with($content, '.')) {
            $content = substr($content, 0, -strlen('.'));
        }

        if (str_ends_with($content, '\'')) {
            $content = substr($content, 0, -strlen('.'));
        }

        $contentMiddlePosition = (int) (strlen($content) / 2);

        $leftHalfContent = substr($content, 0, $contentMiddlePosition);
        $leftHalfFinalQuotePosition = (int) strrpos($leftHalfContent, '\'');

        $rightHalfContent = substr($content, $contentMiddlePosition);
        $rightHandFirstQuotePosition = strpos($rightHalfContent, '\'');

        $expectedValue = substr($leftHalfContent, 0, $leftHalfFinalQuotePosition);
        $actualValue = substr($rightHalfContent, $rightHandFirstQuotePosition + 1);

        return [
            'expected' => $expectedValue,
            'actual' => $actualValue,
        ];
    }
}
