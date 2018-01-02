<?php

namespace AppBundle\DAO;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Customer as Customer;
use AppBundle\Entity\Transaction as Transaction;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

/**
 * Class DefaultController
 * @author Deepak soni <deepakdreams.soni@gmail.com>
 * @copyright (c) 2017.
 */
class CustomerDao extends Controller {
    private $em;
    public function __construct() {  
       $this->em = \AppBundle\Registry\Registry::getInstance();
    }

    public function generateCode($digit = 8) {
        return str_replace('.', 0, substr(rand() . microtime(), 0, $digit));
    }

    public function createCustomer($dataArr = array()) {
        $dateTime = date("Y-m-d H:i:s");
        $customerModel = new Customer;
        $customerModel->setName($dataArr['name']);
        $customerModel->setCnp($dataArr['cnp']);
        $customerModel->setActionBy($dataArr['actionedBy']);
        $customerModel->setCreated($dateTime);
        $customerModel->setUpdated($dateTime);
        $this->em->persist($customerModel);
        $this->em->flush();
        return $customerModel;
    }

    public function addTransaction($dataArr = array()) {
        $dateTime = date("Y-m-d H:i:s");
        if(($dataArr['customerId'])) {            
            $customerDetails = $this->getCustomer($dataArr['customerId']);           
            $customerId=  isset($customerDetails[0])?$customerDetails[0]->getId():null;            
            if (!isset($customerId) || empty($customerId)) {
              throw(new \InvalidArgumentException("No Such customer Exists With This customer id :".$dataArr['customerId'], 409));
            }
        }  
        $transactionModel = new Transaction();
        $transactionModel->setTransId($this->generateCode(8));
        $transactionModel->setCustId($customerDetails[0]);
        $transactionModel->setAmount($dataArr['amount']);
        $transactionModel->setCreated($dateTime);
        
        $this->em->persist($transactionModel);
        $this->em->flush();
        return $transactionModel;
    }

    public function updateTransaction($transactionModel) {
        $dateTime = date("Y-m-d H:i:s");       
        $transactionModel->setCreated($dateTime);        
        $this->em->persist($transactionModel);
        $this->em->flush();
        return $transactionModel;
    }
    
    public function getCustomer($customerId) {        
        return $queryBuilder = $this->em->createQueryBuilder()
                        ->select('svdm')
                        ->from("\AppBundle\Entity\Customer", "svdm")
                        ->where("svdm.id = :customerId")
                        ->setParameter('customerId', $customerId)
                        ->getQuery()->getResult();

        //return $returnResult = $em->getRepository('\AppBundle\Entity\Customer')->findOneBy(array('id'=>$customerId));
    }

    public function getTransaction($customerId=null, $transactionId=null) {
        return $queryBuilder = $this->em->createQueryBuilder()
                        ->select("svdm")
                        ->from("AppBundle\Entity\Transaction", "svdm")
                        ->join("svdm.custId", "fvm")
                        ->where("fvm.id = :customerId")
                        ->orwhere("svdm.transId = :transactionId")
                        ->setParameter('customerId', $customerId)
                        ->setParameter('transactionId', $transactionId)
                        ->getQuery()->getResult();
    }

    public function getTransactionByFilter($customerId, $amount, $date, $offset, $limit) {
        $queryBuilder = $this->em->createQueryBuilder()
                ->select(array("svdm.transId", "svdm.amount", "svdm.created"))
                ->from("AppBundle\Entity\Transaction", "svdm")
                ->join("svdm.custId", "fvm");


        if (!empty($customerId)) {
            $queryBuilder->andwhere("fvm.id = :customerId")->setParameter('customerId', $customerId);
        }

        if (!empty($amount)) {
            $queryBuilder->andwhere("svdm.amount = :amount")->setParameter('amount', $amount);
        }

        if (!empty($date)) {
            $queryBuilder->andwhere("date(svdm.date) = :date")->setParameter('date', $date);
        }
        $skip = ($offset - 1) * $limit;
        $queryBuilder->setMaxResults($limit);
        $queryBuilder->setFirstResult($skip);
        // echo $queryBuilder->getQuery()->getSQL();exit;
        $result = $queryBuilder->getQuery()->getResult();

        return $result;
    }

    public function getTransactionByFilterCount($customerId, $amount, $date) {
        $queryBuilder = $this->em->createQueryBuilder()
                ->select(array("svdm.transId", "svdm.amount", "svdm.created"))
                ->from("AppBundle\Entity\Transaction", "svdm")
                ->join("svdm.custId", "fvm");

        if (!empty($customerId)) {
            $queryBuilder->andwhere("fvm.id = :customerId")->setParameter('customerId', $customerId);
        }

        if (!empty($amount)) {
            $queryBuilder->andwhere("svdm.amount = :amount")->setParameter('amount', $amount);
        }

        if (!empty($date)) {
            $queryBuilder->andwhere("svdm.date = :date")->setParameter('amount', $amount);
        }
        $result = $queryBuilder->getQuery()->getResult();

        return $result;
    }
    
    

    public function deleteTransaction($transactionId) {         
    $result = $this->getTransaction(null,$transactionId);    
    if(!empty($result)) {      
      foreach ($result as $result) { 
      $qa = $this->em->remove($result);
      $em->flush(); 
      }
      return true;
    } else {
      return false;  
    }
   
  }
  
  
    public function getSumTransaction($date) {
        $queryBuilder = $this->em->createQueryBuilder()
                ->select("sum(tr.amount)")
                ->from("AppBundle\Entity\Transaction", "tr")
                ->where("tr.created <= :date")->setParameter('date', $date);
        $result = $queryBuilder->getQuery()->getSingleScalarResult();
        return $result;
    }
    
     public function addSummary($summaryModel) {
        $dateTime = date("Y-m-d H:i:s");
        $summaryModel->setCreated($dateTime);
        $this->em->persist($summaryModel);
        $this->em->flush();
        return $summaryModel;
    }
}
