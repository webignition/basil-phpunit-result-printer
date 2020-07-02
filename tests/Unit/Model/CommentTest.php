<?php

declare(strict_types=1);

namespace webignition\BasilPhpUnitResultPrinter\Tests\Unit\Model;

use webignition\BasilPhpUnitResultPrinter\Model\Comment;
use webignition\BasilPhpUnitResultPrinter\Tests\Unit\AbstractBaseTest;

class CommentTest extends AbstractBaseTest
{
    /**
     * @dataProvider renderDataProvider
     */
    public function testRender(Comment $comment, string $expectedRenderedString)
    {
        self::assertSame($expectedRenderedString, $comment->render());
    }

    public function renderDataProvider(): array
    {
        return [
            'default' => [
                'comment' => new Comment('content'),
                'expectedRenderedString' => '<comment>content</comment>',
            ],
        ];
    }
}
