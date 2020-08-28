<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model;

use webignition\BasilModels\Test\ConfigurationInterface;

class Test implements DocumentSourceInterface
{
    private const TYPE = 'test';

    private string $path;
    private ConfigurationInterface $configuration;

    public function __construct(string $path, ConfigurationInterface $configuration)
    {
        $this->path = $path;
        $this->configuration = $configuration;
    }

    public function hasPath(string $path): bool
    {
        return $this->path === $path;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getData(): array
    {
        return [
            'path' => $this->path,
            'config' => [
                'browser' => $this->configuration->getBrowser(),
                'url' => $this->configuration->getUrl(),
            ],
        ];
    }
}
