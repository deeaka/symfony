<?php

namespace AppBundle\CronCommand;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Psr\Log\LoggerInterface;
use AppBundle\DAO\CustomerDao as CustomerDao;

/**
 * Class CronService
 * @author Deepak soni <deepakdreams.soni@gmail.com>
 * @copyright (c) 2017.
 */
class CronService {

    private $logger;

    public function getSumAmount($date) {
        try {
            $customerDao = new CustomerDao;
            $reposnseData = $customerDao->getSumTransaction($date);
            if (empty($reposnseData)) {
                return false;
            } else {
                $summaryModel = new \AppBundle\Entity\Transaction_Summary();
                $summaryModel->setAmount($reposnseData);
                $pushData = $customerDao->addSummary($summaryModel);
            }
        } catch (\MySQLException $e) {
            //$this->logger->error('getSumAmount:error occured' . $e->getMessage());
            throw new \InvalidArgumentException($e->getMessage(), 500);
        }
        return $pushData;
    }

}
