<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:book:find',
    description: 'Test command',
)]
class BookFindCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::REQUIRED, 'Argument description')
            ->addArgument('arg2', InputArgument::OPTIONAL|InputArgument::IS_ARRAY, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NEGATABLE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');
        $arg2 = $input->getArgument('arg2');

        if ($arg1) {
            $io->note(sprintf('You passed an argument 1: %s', $arg1));
        }

        if ($arg2) {
            $io->note(sprintf('You passed an argument 2: %s', implode(', ', $arg2)));
        }

        if (null !== $opt = $input->getOption('option')) {
            $io->note(sprintf('You passed an option: %s', (string) $opt));
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
