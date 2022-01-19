<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model;

class Scalar implements SourceBodyInterface
{
    public const TYPE_BROWSER_PROPERTY = 'browser_property';
    public const TYPE_DATA_PARAMETER = 'data_parameter';
    public const TYPE_ENVIRONMENT_PARAMETER = 'environment_parameter';
    public const TYPE_LITERAL = 'literal';
    public const TYPE_PAGE_PROPERTY = 'page_property';

    public function __construct(
        private string $type,
        private string $value
    ) {
    }

    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        return [
            'type' => $this->type,
            'value' => $this->value,
        ];
    }
}
