<?php

namespace AppBundle\DAO;
use Doctrine\ORM\EntityManager;

class CronDao
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    
    public function getSumTransaction($date) {
        $queryBuilder = $this->entityManager->createQueryBuilder()
                ->select(array("sum(svdm.amount)"))
                ->from("AppBundle\Entity\Transaction", "svdm")
                ->where("svdm.date = :date")->setParameter('date', $date);
        $result = $queryBuilder->getQuery()->getResult();
        return $result;
    }
  
    
}
