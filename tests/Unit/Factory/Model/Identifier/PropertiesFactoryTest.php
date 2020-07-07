<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model\Factory\Identifier;

use webignition\BasilPhpUnitResultPrinter\Factory\Model\Identifier\PropertiesFactory;
use webignition\BasilPhpUnitResultPrinter\FooModel\Identifier\Properties;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class PropertiesFactoryTest extends AbstractBaseTest
{
    private PropertiesFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = PropertiesFactory::createFactory();
    }

    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $source, ?Properties $expectedProperties)
    {
        self::assertEquals($expectedProperties, $this->factory->create($source));
    }

    public function createDataProvider(): array
    {
        return [
            'empty' => [
                'source' => '',
                'expectedProperties' => null,
            ],
            'css element, no parent' => [
                'source' => '$".selector"',
                'expectedProperties' => new Properties(
                    Properties::TYPE_CSS,
                    '.selector',
                    1
                ),
            ],
            'xpath element, no parent' => [
                'source' => '$"//div"',
                'expectedProperties' => new Properties(
                    Properties::TYPE_XPATH,
                    '//div',
                    1
                ),
            ],
            'css attribute, no parent' => [
                'source' => '$".selector".attribute_name',
                'expectedProperties' =>
                    (new Properties(
                        Properties::TYPE_CSS,
                        '.selector',
                        1
                    ))->withAttribute('attribute_name'),
            ],
            'css element, css parent' => [
                'source' => '$".parent" >> $".child"',
                'expectedProperties' =>
                    (new Properties(
                        Properties::TYPE_CSS,
                        '.child',
                        1
                    ))->withParent(
                        new Properties(
                            Properties::TYPE_CSS,
                            '.parent',
                            1
                        )
                    ),
            ],
            'css element, xpath parent' => [
                'source' => '$"//parent" >> $".child"',
                'expectedProperties' =>
                    (new Properties(
                        Properties::TYPE_CSS,
                        '.child',
                        1
                    ))->withParent(
                        new Properties(
                            Properties::TYPE_XPATH,
                            '//parent',
                            1
                        )
                    ),
            ],
            'css grandparent, parent, child' => [
                'source' => '$".grandparent":4 >> $".parent":3 >> $".child":2',
                'expectedProperties' =>
                    (new Properties(
                        Properties::TYPE_CSS,
                        '.child',
                        2
                    ))->withParent(
                        (new Properties(
                            Properties::TYPE_CSS,
                            '.parent',
                            3
                        ))->withParent(
                            new Properties(
                                Properties::TYPE_CSS,
                                '.grandparent',
                                4
                            )
                        )
                    ),
            ],
        ];
    }
}
