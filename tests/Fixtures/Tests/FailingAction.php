<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;

class FailingAction extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        [
            'type' => 'action',
            'statement' => 'click $".selector"',
        ],
        [
            'type' => 'assertion',
            'statement' => 'assertion statement one for step one',
        ],
    ])]
    public function testStep1(): void
    {
        try {
            // click $".selector"
            throw new \RuntimeException('Runtime exception executing action');
        } catch (\Throwable $exception) {
            self::fail('{
                "statement": {
                    "statement": "click $\".selector\"",
                    "type": "action"
                },
                "reason": "action-failed",
                "exception": {
                    "class": "' . addcslashes($exception::class, "'\\") . '",
                    "code": ' . $exception->getCode() . ',
                    "message": "' . addcslashes($exception->getMessage(), "'\\") . '"
                }
            }');
        }

        self::assertTrue(
            true,
            (string) json_encode([
                'statement' => 'assertion statement one for step one',
                'type' => 'assertion',
            ])
        );
    }
}
