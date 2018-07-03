<?php

namespace AWS\Reservations\Command;

use AWS\Reservations\InstancesParser;
use AWS\Reservations\Report;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateReportCommand extends Command
{
    protected function configure()
    {
        $this->setName('generate-report')
            ->addArgument('instances', InputArgument::REQUIRED, 'Instance list json file')
            ->addArgument('reservations', InputArgument::REQUIRED, 'Reservation list json file')
            ->addOption(
                'groups',
                'g',
                InputOption::VALUE_REQUIRED,
                'Search keywords for grouping instances'
            )->addOption(
                'csv',
                'c',
                InputOption::VALUE_REQUIRED,
                'CSV file for output'
            )->addOption(
                'type',
                't',
                InputOption::VALUE_REQUIRED,
                'Filter for instance type',
                null
            )->addOption(
                'uncovered',
                'u',
                InputOption::VALUE_NONE,
                'Filter for uncovered instances'
            )
            ->setDescription('Generated reserved instances report');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $groupNames = [];
        $instances = json_decode(file_get_contents($input->getArgument('instances')), true);
        $reservations = json_decode(file_get_contents($input->getArgument('reservations')), true);

        if ($input->getOption('groups')) {
            $groupNames = json_decode(file_get_contents($input->getOption('groups')), true);
        }

        $report = (new Report(
            $instances,
            $reservations,
            $groupNames
        ))->generate(
            $input->getOption('type'),
            $input->getOption('uncovered') ? false : null
        );

        if ($input->getOption('csv')) {
            $this->outputCsv($report, $input->getOption('csv'));
        } else {
            $this->outputTable($report, $output);
        }
    }

    /**
     * @param array $report
     * @param OutputInterface $output
     */
    private function outputTable($report, OutputInterface $output)
    {
        $table = new Table($output);
        $table->setHeaders($report['header']);
        $table->setRows($report['body']);
        $table->render();
    }

    /**
     * @param array $report
     * @param string $file
     */
    private function outputCsv($report, $file)
    {
        $out = fopen($file, 'w');

        fputcsv($out, $report['header']);

        foreach ($report['body'] as $line) {
            fputcsv($out, $line);
        }

        fclose($out);
    }
}
