<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter;

readonly class StatementMessageParser
{
    /**
     * @return array{'data': array<mixed>, 'message': string}
     */
    public function parse(string $content): array
    {
        $lines = explode("\n", $content);

        $jsonLines = [];
        $messageLines = [];
        $isMessageLine = false;

        foreach ($lines as $line) {
            if (str_starts_with($line, 'Failed asserting that')) {
                $isMessageLine = true;
            }

            if ($isMessageLine) {
                $messageLines[] = $line;
            } else {
                $jsonLines[] = $line;
            }
        }

        $data = json_decode(implode("\n", $jsonLines), true);
        $data = is_array($data) ? $data : [];

        return [
            'data' => $data,
            'message' => implode("\n", $messageLines),
        ];
    }
}
