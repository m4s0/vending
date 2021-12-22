<?php

declare(strict_types=1);

namespace Core\Infrastructure\Command;

use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream;
use Prooph\EventStore\StreamName;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadDefaultValuesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('vending-machine:load')
            ->setDescription('This command creates the default coins and items.')
            ->setHelp('It creates the default data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $coinService = $this->getContainer()->get('core.service.coin_service');
        $coinService->load();
        $output->writeln('<info>Default coins was created successfully.</info>');

        $itemService = $this->getContainer()->get('core.service.item_service');
        $itemService->load();
        $output->writeln('<info>Default items was created successfully.</info>');
    }
}
