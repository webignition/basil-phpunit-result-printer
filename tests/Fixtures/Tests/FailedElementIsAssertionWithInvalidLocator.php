<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Fixtures\Tests;

use Facebook\WebDriver\Exception\InvalidSelectorException;
use webignition\BaseBasilTestCase\Attribute\Statements;
use webignition\BaseBasilTestCase\Attribute\StepName;
use webignition\DomElementIdentifier\ElementIdentifier;
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
        try {
            $elementIdentifier = new ElementIdentifier('$".selector"');
            $invalidSelectorException = new InvalidSelectorException('message', null);

            throw new InvalidLocatorException(
                $elementIdentifier,
                $invalidSelectorException,
            );
        } catch (InvalidLocatorException $exception) {
            $locator = $exception->getElementIdentifier()->getLocator();
            $type = $exception->getElementIdentifier()->isCssSelector() ? 'css' : 'xpath';
            $this->fail('{
                "statement": {
                    "statement-type": "assertion",
                    "source": "$\".selector\" exists",
                    "index": 0,
                    "identifier": "$\".selector\"",
                    "operator": "exists"
                },
                "reason": "locator-invalid",
                "exception": {
                    "class": "' . addcslashes($exception::class, '"\\') . '",
                    "code": ' . $exception->getCode() . ',
                    "message": "' . addcslashes($exception->getMessage(), '"\\') . '"
                },
                "context": {
                    "locator": "' . addcslashes($locator, '"\\') . '",
                    "type": "' . addcslashes($type, '"\\') . '"
                }
            }');
        }
    }
}
