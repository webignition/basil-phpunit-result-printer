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
     * @return array{'expected': ?string, 'actual': ?string}
     */
    public function parse(Failed $event, string $content): array
    {
        foreach ($this->handlers as $handler) {
            $result = $handler->handle($event, $content);

            if (is_array($result)) {
                return $result;
            }
        }

        return [
            'expected' => null,
            'actual' => null,
        ];
    }
}
