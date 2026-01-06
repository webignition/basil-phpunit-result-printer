<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;
use webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests\BasilTestCase;
use webignition\SymfonyDomCrawlerNavigator\Exception\InvalidLocatorException;

class FailingStringContainsStringAssertion extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        '{
            "statement-type": "assertion",
            "source": "$\".selector\" includes \"value\"",
            "index": 0,
            "identifier": "$\".selector\"",
            "value": "\"value\"",
            "operator": "includes"
        }'
    ])]
    public function testStep1(): void
    {
        $expectedValue = 'expected-value';
        $examinedValue = 'examined-value';
        $this->assertStringContainsString(
            ($expectedValue),
            ($examinedValue),
        '{
                    "statement-type": "assertion",
                    "source": "$\".selector\" includes \"value\"",
                    "index": 0,
                    "identifier": "$\".selector\"",
                    "value": "\"value\"",
                    "operator": "includes"
                }'
        );
    }
}
