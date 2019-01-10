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

class UnusedReservationsCountCommand extends Command
{
    protected function configure()
    {
        $this->setName('unused-reservations')
            ->addArgument('instances', InputArgument::REQUIRED, 'Instance list json file')
            ->addArgument('reservations', InputArgument::REQUIRED, 'Reservation list json file')
            ->setDescription('Prints unused reservations count');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(
            (new Report(
                json_decode(file_get_contents($input->getArgument('instances')), true),
                json_decode(file_get_contents($input->getArgument('reservations')), true),
                []
            ))->getUnusedReservations()
        );
    }
}
