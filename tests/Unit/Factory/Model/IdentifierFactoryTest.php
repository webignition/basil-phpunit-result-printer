<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Factory;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Identifier\IdentifierFactory;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class IdentifierFactoryTest extends AbstractBaseTest
{
    private IdentifierFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = IdentifierFactory::createFactory();
    }

    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $source, ?Identifier $expectedProperties)
    {
        self::assertEquals($expectedProperties, $this->factory->create($source));
    }

    public function createDataProvider(): array
    {
        return [
            'empty' => [
                'source' => '',
                'expectedIdentifier' => null,
            ],
            'non-empty' => [
                'source' => '$".selector"',
                'expectedIdentifier' => new Identifier(
                    '$".selector"',
                    new Properties(
                        Properties::TYPE_CSS,
                        '.selector',
                        1
                    )
                ),
            ],
        ];
    }
}
