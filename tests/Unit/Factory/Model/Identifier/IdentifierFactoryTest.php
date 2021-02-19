<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Factory\Identifier;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Identifier\IdentifierFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Identifier\PropertiesFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\Model\Identifier\Properties;
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
    public function testCreate(string $source, ?Identifier $expectedIdentifier): void
    {
        self::assertEquals($expectedIdentifier, $this->factory->create($source));
    }

    /**
     * @return array[]
     */
    public function createDataProvider(): array
    {
        $propertiesFactory = PropertiesFactory::createFactory();

        return [
            'empty' => [
                'source' => '',
                'expectedIdentifier' => null,
            ],
            'non-empty' => [
                'source' => '$".selector"',
                'expectedIdentifier' => new Identifier(
                    '$".selector"',
                    $propertiesFactory->create('$".selector"') ?? \Mockery::mock(Properties::class)
                ),
            ],
        ];
    }
}
