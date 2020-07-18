<?php

declare(strict_types=1);

namespace Symplify\ChangelogLinker\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\ChangelogLinker\ChangelogLinker;
use Symplify\ChangelogLinker\FileSystem\ChangelogFileSystem;
use Symplify\PackageBuilder\Console\Command\CommandNaming;
use Symplify\PackageBuilder\Console\ShellCode;

final class LinkCommand extends Command
{
    /**
     * @var ChangelogLinker
     */
    private $changelogLinker;

    /**
     * @var ChangelogFileSystem
     */
    private $changelogFileSystem;

    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    public function __construct(
        ChangelogLinker $changelogLinker,
        ChangelogFileSystem $changelogFileSystem,
        SymfonyStyle $symfonyStyle
    ) {
        parent::__construct();

        $this->changelogLinker = $changelogLinker;
        $this->changelogFileSystem = $changelogFileSystem;
        $this->symfonyStyle = $symfonyStyle;
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Adds links to CHANGELOG.md');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $changelogContent = $this->changelogFileSystem->readChangelog();

        $processedChangelogContent = $this->changelogLinker->processContentWithLinkAppends($changelogContent);

        $this->changelogFileSystem->storeChangelog($processedChangelogContent);

        $this->symfonyStyle->success('Changelog PRs, links, users and versions are now linked!');

        return ShellCode::SUCCESS;
    }
}
