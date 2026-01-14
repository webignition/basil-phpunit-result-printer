<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Factory\Model\Identifier;

use PHPUnit\Framework\Attributes\DataProvider;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Identifier\IdentifierFactory;
use webignition\BasilPhpUnitResultPrinter\Factory\Model\Identifier\PropertiesFactory;
use webignition\BasilPhpUnitResultPrinter\Model\Identifier\Identifier;
use webignition\BasilPhpUnitResultPrinter\Model\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTestCase;

class IdentifierFactoryTest extends AbstractBaseTestCase
{
    private IdentifierFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = IdentifierFactory::createFactory();
    }

    #[DataProvider('createDataProvider')]
    public function testCreate(string $source, ?Identifier $expectedIdentifier): void
    {
        self::assertEquals($expectedIdentifier, $this->factory->create($source));
    }

    /**
     * @return array<mixed>
     */
    public static function createDataProvider(): array
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
