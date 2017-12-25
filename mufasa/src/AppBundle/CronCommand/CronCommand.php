<?php

namespace AppBundle\CronCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use AppBundle\DAO\CronDao as CronDao;
use AppBundle\Entity\Summary as Summary;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManager;

class CronCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName("Hash:Hash")
                ->setDescription("Hashes a given string using Bcrypt.")
                ->addArgument('Password', InputArgument::REQUIRED, 'What do you wish to hash)');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $date=date('Y-m-d');        
        $previousDay=date('Y-m-d',  strtotime( $date . ' -1 day' ));
        $result = 'Done';
        $doctrine = $this->getContainer();
        $summary=$cronDao->getSumTransaction($previousDay);
        
        print_r($summary);exit;
        $summary=new Summary();
        
        $output->writeln('Cron status: ' .'today :'.$date.' Previous Day :'. $previousDay);
    }

}
