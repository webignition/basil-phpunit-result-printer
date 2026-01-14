<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;
use webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests\BasilTestCase;
use webignition\SymfonyDomCrawlerNavigator\Exception\InvalidLocatorException;

class FailedElementIsRegexpAssertion extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        '{
            "statement-type": "assertion",
            "source": "$page.title matches $\".selector\"",
            "index": 0,
            "identifier": "$page.title",
            "value": "$\".selector\".attribute_name",
            "operator": "matches"
        }',
    ])]
    public function testStep1(): void
    {
        $this->assertFalse(
            true,
            '{
                "statement": {
                    "container": {
                        "value": "$\".selector\"",
                        "operator": "is-regexp",
                        "type": "derived-value-operation-assertion"
                    },
                    "statement": {
                        "statement-type": "assertion",
                        "source": "$page.title matches $\".selector\"",
                        "index": 0,
                        "identifier": "$page.title",
                        "value": "$\".selector\"",
                        "operator": "matches"
                    }
                },
                "expected": true,
                "examined": "element value"
            }'
        );
    }
}
