<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Model;

use webignition\YamlDocumentGenerator\DocumentSourceInterface;

class Exception implements DocumentSourceInterface
{
    private const TYPE = 'exception';

    private ?string $step;
    private string $class;
    private string $message;
    private int $code;

    /**
     * @var array<int, array<string, string|int>>
     */
    private array $trace;

    /**
     * @param string|null $step
     * @param string $class
     * @param string $message
     * @param int $code
     * @param array<int, array<string, string|int>> $trace
     */
    private function __construct(?string $step, string $class, string $message, int $code, array $trace)
    {
        $this->step = $step;
        $this->class = $class;
        $this->message = $message;
        $this->code = $code;
        $this->trace = $trace;
    }

    public static function createFromThrowable(?string $step, \Throwable $throwable): self
    {
        return new Exception(
            $step,
            get_class($throwable),
            $throwable->getMessage(),
            $throwable->getCode(),
            $throwable->getTrace()
        );
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getData(): array
    {
        return [
            'step' => $this->step,
            'class' => $this->class,
            'message' => $this->message,
            'code' => $this->code,
            'trace' => $this->trace,
        ];
    }
}
