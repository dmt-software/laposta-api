<?php

namespace DMT\Laposta\Api\Console;

use DMT\Laposta\Api\Clients\Fields;
use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Factories\CommandBusFactory;
use DMT\Laposta\Api\Services\CustomFieldsGeneratorService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCustomFieldsCommand extends Command
{
    protected static $defaultName = 'generate:list-fields';
    protected static $defaultDescription = 'Generate custom fields for a list';

    protected function configure(): void
    {
        $this
            ->addArgument('config', InputArgument::REQUIRED, 'file containing (or bootstrap that returns) the configuration')
            ->addOption('list-id', 'l', InputOption::VALUE_REQUIRED, 'the mailing list id')
            ->addOption('class-name', 'c', InputOption::VALUE_REQUIRED, 'the (fully qualified) class name of the entity', 'Name\\Space\\CustomField')
            ->addOption('destination', 'd', InputOption::VALUE_OPTIONAL, 'path to store the generated entity', sys_get_temp_dir())
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'dry run to view generation first')
            ->setHelp(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('----------------------------------------------------------------');
        $output->writeln(' > generate    : list-fields');

        $bootstrap = $input->getArgument('config');
        if (!is_file($bootstrap) || !is_array($configArray = @include($bootstrap))) {
            $output->writeln(' > config not loaded');

            return Command::FAILURE;
        }
        $config = Config::fromArray($configArray);

        $listId = $input->getOption('list-id');
        if (!$listId) {
            $output->writeln(' > no list id provided');

            return Command::FAILURE;
        }
        $output->writeln(' > using list  : ' . $listId);

        $fileName = preg_replace('~^(.*\\\\)([^\\\\]+)$~', '$2', $input->getOption('class-name'));
        $destination = $input->getOption('destination') . sprintf('/%s.php', $fileName);

        try {
            $service = new CustomFieldsGeneratorService($config);
            $service->generateEntity(
                $input->getOption('list-id'),
                $input->getOption('class-name'),
                $input->getOption('dry-run') ? 'php://output' : $destination
            );
        } catch (\Exception $exception) {
            $output->writeln(' > error retrieving fields');

            return Command::FAILURE;
        }

        if (!$input->getOption('dry-run')) {
            $output->writeln(' > destination : ' . $destination);
        }
        $output->writeln('----------------------------------------------------------------');

        return Command::SUCCESS;
    }
}