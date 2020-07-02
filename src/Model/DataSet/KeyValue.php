<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model\DataSet;

use webignition\BasilPhpUnitResultPrinter\Model\Comment;
use webignition\BasilPhpUnitResultPrinter\Model\RenderableInterface;

class KeyValue implements RenderableInterface
{
    private Key $key;
    private Comment $value;

    public function __construct(string $key, string $value)
    {
        $this->key = new Key($key);
        $this->value = new Comment($value);
    }

    public function render(): string
    {
        return sprintf(
            '%s: %s',
            $this->key->render(),
            $this->value->render()
        );
    }
}
