<?php

declare(strict_types=1);

namespace Symplify\ChangelogLinker\Tests\ChangeTree\ChangeFactory\Resolver;

use Iterator;
use Symplify\ChangelogLinker\Tests\ChangeTree\ChangeFactory\AbstractChangeFactoryTest;

final class PackageResolverTest extends AbstractChangeFactoryTest
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $message, string $expectedPackage): void
    {
        $change = $this->createChangeForTitle($message);
        $this->assertSame($expectedPackage, $change->getPackage());
    }

    public function provideData(): Iterator
    {
        yield ['Some message', 'Unknown Package'];
        yield ['[A] Some message', 'Aliased'];
        yield ['[CodingStandard] Add feature', 'CodingStandard'];
        yield ['[Skeleton] Deletes unnecessary templates', 'Skeleton'];
        yield ['[coding-standards] Deletes unnecessary templates', 'coding-standards'];
    }
}
