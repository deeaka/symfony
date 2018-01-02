<?php

namespace AppBundle\CronCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use AppBundle\CronCommand\CronService as CronService;
use AppBundle\Entity\Summary as Summary;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManager;

class CronCommand extends ContainerAwareCommand {

    protected function configure() {
                $this->setName("cron:Hash")
                ->setDescription("Hashes a given string using Bcrypt.")
                ->addArgument('Password', InputArgument::REQUIRED, 'What do you wish to hash)');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        try {
            $date = date('Y-m-d');
            $previousDay = date('Y-m-d', strtotime($date . ' -1 day'));
            $result = 'Done';
            $logger = $this->getContainer()->get('logger');
            $em = $this->getContainer()->get('doctrine.orm.entity_manager');
            \AppBundle\Registry\Registry::setInstance($em);
            $cronService = new CronService;
            $cronService->getSumAmount($date);
        } catch (\Exception $e) {
            $result = 'Fail';
            $output->writeln('Cron execute status: ' . 'today :' . $date . ' Previous Day :' . $previousDay . " status failed".$e->getMessage());
        }
        $output->writeln('Cron status: ' . 'today :' . $date . ' Previous Day :' . $previousDay.'----'.$result);
    }

}
