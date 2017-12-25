<?php

namespace AppBundle\DAO;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Customer as Customer;
use AppBundle\Entity\Transaction as Transaction;

/**
 * Class DefaultController
 * @author Deepak soni <deepakdreams.soni@gmail.com>
 * @copyright (c) 2017.
 */
class CustomerDao extends Controller {

    public function generateCode($digit = 8) {
        return str_replace('.', 0, substr(rand() . microtime(), 0, $digit));
    }

    public function createCustomer($em, $dataArr = array()) {
        $dateTime = date("Y-m-d H:i:s");
        $customerModel = new Customer;
        $customerModel->setName($dataArr['name']);
        $customerModel->setCnp($dataArr['cnp']);
        $customerModel->setActionBy($dataArr['actionedBy']);
        $customerModel->setCreated($dateTime);
        $customerModel->setUpdated($dateTime);
        $em->persist($customerModel);
        $em->flush();
        return $customerModel;
    }

    public function addTransaction($em, $dataArr = array()) {
        $dateTime = date("Y-m-d H:i:s");
        if(($dataArr['customerId'])) {            
            $customerDetails = $this->getCustomer($em,$dataArr['customerId']);           
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
        
        $em->persist($transactionModel);
        $em->flush();
        return $transactionModel;
    }

    public function updateTransaction($em, $transactionModel) {
        $dateTime = date("Y-m-d H:i:s");       
        $transactionModel->setCreated($dateTime);        
        $em->persist($transactionModel);
        $em->flush();
        return $transactionModel;
    }
    
    public function getCustomer($em, $customerId) {
        return $queryBuilder = $em->createQueryBuilder()
                        ->select('svdm')
                        ->from("\AppBundle\Entity\Customer", "svdm")
                        ->where("svdm.id = :customerId")
                        ->setParameter('customerId', $customerId)
                        ->getQuery()->getResult();

        //return $returnResult = $em->getRepository('\AppBundle\Entity\Customer')->findOneBy(array('id'=>$customerId));
    }

    public function getTransaction($em, $customerId=null, $transactionId=null) {
        return $queryBuilder = $em->createQueryBuilder()
                        ->select("svdm")
                        ->from("AppBundle\Entity\Transaction", "svdm")
                        ->join("svdm.custId", "fvm")
                        ->where("fvm.id = :customerId")
                        ->orwhere("svdm.transId = :transactionId")
                        ->setParameter('customerId', $customerId)
                        ->setParameter('transactionId', $transactionId)
                        ->getQuery()->getResult();
    }

    public function getTransactionByFilter($em, $customerId, $amount, $date, $offset, $limit) {
        $queryBuilder = $em->createQueryBuilder()
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

    public function getTransactionByFilterCount($em, $customerId, $amount, $date) {
        $queryBuilder = $em->createQueryBuilder()
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
    
    

    public function deleteTransaction($em,$transactionId) {         
    $result = $this->getTransaction($em,null,$transactionId);    
    if(!empty($result)) {      
      foreach ($result as $result) { 
      $qa = $em->remove($result);
      $em->flush(); 
      }
      return true;
    } else {
      return false;  
    }
   
  }
  
  
    public function getSumTransaction($date) {
        $queryBuilder = $em->createQueryBuilder()
                ->select(array("sum(svdm.amount)"))
                ->from("AppBundle\Entity\Transaction", "svdm")
                ->where("svdm.date = :date")->setParameter('date', $date);
        $result = $queryBuilder->getQuery()->getResult();
        return $result;
    }
    
    
}
