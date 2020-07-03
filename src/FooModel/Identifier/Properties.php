<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\FooModel\Identifier;

class Properties
{
    public const TYPE_CSS = 'css';
    public const TYPE_XPATH = 'xpath';

    private string $type;
    private string $locator;
    private int $position;
    private ?self $parent;

    public function __construct(string $type, string $locator, int $position, ?self $parent = null)
    {
        $this->type = $type;
        $this->locator = $locator;
        $this->position = $position;
        $this->parent = $parent;
    }

    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        $data = [
            'type' => $this->type,
            'locator' => $this->locator,
            'position' => $this->position,
        ];

        if ($this->parent instanceof self) {
            $data['parent'] = $this->parent->getData();
        }

        return $data;
    }
}
