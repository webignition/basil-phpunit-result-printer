<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model;

use webignition\BasilPhpUnitResultPrinter\Model\Identifier\Identifier;

class Node implements SourceBodyInterface
{
    public const TYPE_ELEMENT = 'element';
    public const TYPE_ATTRIBUTE = 'attribute';

    public function __construct(
        private string $type,
        private Identifier $identifier
    ) {}

    public static function fromIdentifier(Identifier $identifier): self
    {
        return new Node(
            $identifier->isAttribute() ? self::TYPE_ATTRIBUTE : self::TYPE_ELEMENT,
            $identifier
        );
    }

    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        return [
            'type' => $this->type,
            'identifier' => $this->identifier->getData(),
        ];
    }
}
