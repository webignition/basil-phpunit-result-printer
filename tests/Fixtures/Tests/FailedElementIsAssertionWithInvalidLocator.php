<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use Facebook\WebDriver\Exception\InvalidSelectorException;
use SmartAssert\DomIdentifier\ElementIdentifier;
use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;
use webignition\BaseBasilTestCase\Enum\StatementStage;
use webignition\SymfonyDomCrawlerNavigator\Exception\InvalidLocatorException;

class FailedElementIsAssertionWithInvalidLocator extends BasilTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    #[StepName('step one')]
    #[Statements([
        '{
            "statement-type": "assertion",
            "source": "$\".selector\" exists",
            "index": 0,
            "identifier": "$\".selector\"",
            "operator": "exists"            
        }',
    ])]
    public function testStep1(): void
    {
        $statement_0 = '{
            "statement-type": "assertion",
            "source": "$\".selector\" exists",
            "index": 0,
            "identifier": "$\".selector\"",
            "operator": "exists"
        }';

        try {
            $elementIdentifier = new ElementIdentifier('$".selector"');
            $invalidSelectorException = new InvalidSelectorException('message', null);

            throw new InvalidLocatorException(
                $elementIdentifier,
                $invalidSelectorException,
            );
        } catch (\Throwable $exception) {
            $this->fail(
                (string) self::$messageFactory->createFailureMessage(
                    $statement_0,
                    $exception,
                    StatementStage::EXECUTE,
                )
            );
        }
    }
}
