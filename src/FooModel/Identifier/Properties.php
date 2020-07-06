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
    private ?string $attribute = null;
    private ?self $parent = null;

    public function __construct(string $type, string $locator, int $position)
    {
        $this->type = $type;
        $this->locator = $locator;
        $this->position = $position;
    }

    public function withAttribute(string $attribute): self
    {
        $new = clone $this;
        $new->attribute = $attribute;

        return $new;
    }

    public function withParent(self $parent): self
    {
        $new = clone $this;
        $new->parent = $parent;

        return $new;
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

        if (null !== $this->attribute) {
            $data['attribute'] = $this->attribute;
        }

        if ($this->parent instanceof self) {
            $data['parent'] = $this->parent->getData();
        }

        return $data;
    }
}
