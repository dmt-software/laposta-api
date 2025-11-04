<?php

namespace DMT\Laposta\Api\Console;

use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Services\CustomFieldsGeneratorService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'check:list-fields',
    description: 'Check custom fields entity must be renewed',
    help: 'Check custom fields entity must be renewed'
)]
class CheckCustomFieldsCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('config', InputArgument::REQUIRED, 'file containing (or bootstrap that returns) the configuration')
            ->addOption('list-id', 'l', InputOption::VALUE_REQUIRED, 'the mailing list id');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('----------------------------------------------------------------');
        $output->writeln(' Check custom fields:');

        $config = Config::load($input->getArgument('config'));
        if (!$config instanceof Config) {
            $output->writeln('  > config not loaded');

            return Command::FAILURE;
        }

        if (!$input->getOption('list-id')) {
            $output->writeln('  > no list id provided');

            return Command::FAILURE;
        }
        $output->writeln('  > using list : ' . $input->getOption('list-id'));
        $output->writeln('----------------------------------------------------------------');

        $service = new CustomFieldsGeneratorService($config);

        if (!$service->checkEntity($input->getOption('list-id'))) {
            $output->writeln(' Custom fields class needs to be updated');
            $output->writeln('----------------------------------------------------------------');

            return Command::FAILURE;
        }

        $output->writeln(' Custom fields class is up-to-date');
        $output->writeln('----------------------------------------------------------------');

        return Command::SUCCESS;
    }
}
