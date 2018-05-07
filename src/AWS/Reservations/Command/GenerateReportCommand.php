<?php

namespace AWS\Reservations\Command;

use AWS\Reservations\InstancesParser;
use AWS\Reservations\Report;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateReportCommand extends Command
{
    protected function configure()
    {
        $this->setName('generate-report')
            ->addArgument('instances', InputArgument::REQUIRED, 'Instance list json file')
            ->addArgument('reservations', InputArgument::REQUIRED, 'Reservation list json file')
            ->setDescription('Generated reserved instances report');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $groupNames = [];
        $instances = json_decode(file_get_contents($input->getArgument('instances')), true);
        $reservations = json_decode(file_get_contents($input->getArgument('reservations')), true);

        $report = (new Report(
            $instances,
            $reservations,
            $groupNames
        ))->generate();

        $this->outputTable($report, $output);
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
}
