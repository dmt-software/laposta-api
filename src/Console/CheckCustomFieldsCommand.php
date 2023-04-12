<?php

namespace DMT\Laposta\Api\Console;

use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Services\CustomFieldsGeneratorService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CheckCustomFieldsCommand extends Command
{
    protected static $defaultName = 'check:list-fields';
    protected static $defaultDescription = 'Check custom fields entity must be renewed';

    protected function configure(): void
    {
        $this
            ->addArgument('config', InputArgument::REQUIRED, 'file containing (or bootstrap that returns) the configuration')
            ->addOption('list-id', 'l', InputOption::VALUE_REQUIRED, 'the mailing list id')
            ->setHelp(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('----------------------------------------------------------------');
        $output->writeln(' Check custom fields:');

        $bootstrap = $input->getArgument('config');
        if (!is_file($bootstrap) || !is_array($configArray = @include($bootstrap))) {
            $output->writeln('  > config not loaded');

            return Command::FAILURE;
        }
        $config = Config::fromArray($configArray);

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
