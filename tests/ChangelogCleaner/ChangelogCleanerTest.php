<?php

declare(strict_types=1);

namespace Symplify\ChangelogLinker\Tests\ChangelogCleaner;

use Iterator;
use Symplify\ChangelogLinker\ChangelogCleaner;
use Symplify\ChangelogLinker\HttpKernel\ChangelogLinkerKernel;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\EasyTesting\StaticFixtureSplitter;
use Symplify\PackageBuilder\Tests\AbstractKernelTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ChangelogCleanerTest extends AbstractKernelTestCase
{
    /**
     * @var ChangelogCleaner
     */
    private $changelogCleaner;

    protected function setUp(): void
    {
        $this->bootKernel(ChangelogLinkerKernel::class);
        $this->changelogCleaner = self::$container->get(ChangelogCleaner::class);
    }

    /**
     * @dataProvider dataProvider()
     */
    public function test(SmartFileInfo $fixtureFile): void
    {
        [$inputContent, $expectedContent] = StaticFixtureSplitter::splitFileInfoToInputAndExpected($fixtureFile);

        $outputContent = $this->changelogCleaner->processContent($inputContent);
        $this->assertSame($expectedContent, $outputContent, $fixtureFile->getRelativeFilePathFromCwd());
    }

    public function dataProvider(): Iterator
    {
        return StaticFixtureFinder::yieldDirectory(__DIR__ . '/Source/fixture', '*.md');
    }
}
