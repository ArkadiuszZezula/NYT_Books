<?php

namespace App\Command;

use App\Service\NewYorkTimes;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class NytImportBestsellerCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:nyt-import-bestseller';

    /**
     * NytImportBooksCommand constructor.
     * @param string|null $name
     * @param NewYorkTimes $newYorkTimesService
     */
    public function __construct(string $name = null, NewYorkTimes $newYorkTimesService)
    {
        $this->newYorkTimesService = $newYorkTimesService;
        parent::__construct($name);

    }

    protected function configure()
    {
        $this->setDescription('Import New York Times bestsellers books')
            ->setHelp('This command allows you to import books')
            ->addArgument(
                'startPage',
                InputArgument::OPTIONAL,
                'Start page to import books'
            )
            ->addArgument(
                'endPage',
                InputArgument::OPTIONAL,
                'End page to import books'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Start import',
            '============',
            '',
        ]);
        $this->newYorkTimesService->importBestseller(
                $input->getArgument('startPage'),
                $input->getArgument('endPage')
            );
        $output->write(
            'Number of books imported: '
            . $this->newYorkTimesService->getNumberOfImportedBooks()
        );
    }
}