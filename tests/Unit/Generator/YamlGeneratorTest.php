<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Generator;

use webignition\BasilPhpUnitResultPrinter\Generator\GeneratorInterface;
use webignition\BasilPhpUnitResultPrinter\Generator\YamlGenerator;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class YamlGeneratorTest extends AbstractBaseTest
{
    public function testImplementsGeneratorInterface()
    {
        $generator = new YamlGenerator();
        self::assertInstanceOf(GeneratorInterface::class, $generator);
    }
}
