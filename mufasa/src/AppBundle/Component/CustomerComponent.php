<?php

namespace AppBundle\Component;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Psr\Log\LoggerInterface;
use AppBundle\DAO\CustomerDao as CustomerDao;
use AppBundle\Validator\CustomerValidator as CustomerValidator;

/**
 * Class DefaultController
 * @author Deepak soni <deepakdreams.soni@gmail.com>
 * @copyright (c) 2017.
 */

class CustomerComponent
{
    private $logger;
    private $validator;
    
    public function __construct(LoggerInterface $logger) {
      $this->logger=$logger;  
      $this->validator=new CustomerValidator;
      
    }
   
    public function addCustomer($postArray) {        
        $this->logger->info('getCustomerList:inside');
        $this->validator->customerValidation($postArray);
        try {
            $customerDao = new CustomerDao;
            $responseData = $customerDao->createCustomer($postArray);
            $normalizers = new \Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer();
            $responseDataArr = $normalizers->normalize($responseData);            
        } catch (\MySQLException $e) {
              $this->logger->error('indexAction:error occured' . $e->getMessage());
              throw new \InvalidArgumentException($e->getMessage(), 500);
        }       
        return $responseDataArr;
    }
    
    public function addTransaction($postArray) { 
        $responseData=array();
        $this->logger->info('addTransaction:inside');
        $this->validator->transValidation($postArray);
        try {            
            $customerDao = new CustomerDao;      
                      
            $responseApi = $customerDao->addTransaction( $postArray);
            $responseData['transactionId']=$responseApi->getTransId();
            $responseData['amount']=$responseApi->getAmount();
            $responseData['date']=$responseApi->getCreated();                  
        } catch (\MySQLException $e) {
              $this->logger->error('indexAction:error occured' . $e->getMessage());
              throw new \InvalidArgumentException($e->getMessage(), 500);
        }       
        return $responseData;
    }
    
     public function updateTransaction($postArray) { 
        $responseData=array();
        $this->logger->info('addTransaction:inside');
        $this->validator->updateTransValidation($postArray);
        try {
            $customerDao = new CustomerDao;
            if (($postArray['transactionId'])) {
                $transactionDetails = $customerDao->getTransaction( null,$postArray['transactionId']);               
                if (!isset($transactionDetails) || empty($transactionDetails)) {
                    throw(new \InvalidArgumentException("No Such Transaction Id  Exists :" . $postArray['transactionId'], 409));
                }
            }           
            $transactionModel=$transactionDetails[0];            
            $transactionModel->setAmount($postArray['amount']);
            $responseApi = $customerDao->updateTransaction( $transactionModel);
            $responseData['transactionId'] = $responseApi->getTransId();
            $responseData['customerId'] = $responseApi->getCustId()->getId();
            $responseData['amount'] = $responseApi->getAmount();
            $responseData['date'] = $responseApi->getCreated();
        } catch (\MySQLException $e) {
              $this->logger->error('indexAction:error occured' . $e->getMessage());
              throw new \InvalidArgumentException($e->getMessage(), 500);
        }       
        return $responseData;
    }
    
    
    public function getTransaction($customerId,$transactionId) {   
        $this->logger->info('getCustomerList:inside');
        $this->validator->customerParamValidation($customerId,$transactionId);
        $responseDataArr=array();
        try {       
            $customerDao = new CustomerDao();
            //check customer id exist or not            
            if(!empty($customerId)) {
            $customerDetails = $customerDao->getCustomer($customerId);            
            $customerId=  isset($customerDetails[0])?$customerDetails[0]->getId():null;            
            if (!isset($customerId) || empty($customerId)) {
              throw(new \InvalidArgumentException("No Such customer Exists With This customer id :".$customerId, 409));
            }
            }
            $responseApiData = $customerDao->getTransaction($customerId, $transactionId); 
             if(empty($responseApiData)) {
               throw(new \InvalidArgumentException("No Transaction Exists", 409));  
            }            
            foreach ($responseApiData as $responseApi) {
            $responseData['transactionId']=$responseApi->getTransId();
            $responseData['amount']=$responseApi->getAmount();
            $responseData['date']=$responseApi->getCreated();
            $responseDataArr[]=$responseData;
            }            
           
        } catch (\MySQLException $e) {       
              $this->logger->error('indexAction:error occured' . $e->getMessage());
              throw new \InvalidArgumentException($e->getMessage(), 500);
        }       
        return $responseDataArr;
    }
    
    public function getTransactionByFilter($customerId,$amount,$date,$offset,$limit) {        
        $this->logger->info('getCustomerList:inside');
        $this->validator->validatePageNumAndPageSize($offset,$limit);
        try {
            $customerDao = new CustomerDao();
            //check customer id exist or not
            if(!empty($customerId)) {
            $customerDetails = $customerDao->getCustomer($customerId);
            $customerId=  isset($customerDetails[0])?$customerDetails[0]->getId():null;            
            if (!isset($customerId) || empty($customerId)) {
              throw(new \InvalidArgumentException("No Such customer Exists With This customer id :".$dataArr['customerId'], 409));
            }
            }
            
            $responseData = $customerDao->getTransactionByFilterCount($customerId,$amount,$date); 
            $cnt = count($responseData);
            $totalPages = ceil($cnt / $limit);
            if ($offset > $totalPages) {
                throw new \InvalidArgumentException('Records are not present for this Page Number.', 409);
            }

            $responseData = $customerDao->getTransactionByFilter($customerId,$amount,$date,$offset,$limit); 
            if(empty($responseData)) {
               throw(new \InvalidArgumentException("No Transaction Exists", 409));  
            }
        } catch (\MySQLException $e) {                
              $this->logger->error('indexAction:error occured' . $e->getMessage());
              throw new \InvalidArgumentException($e->getMessage(), 500);
        }       
        return $responseData;
    }
    
    
    public function deleteTransaction($transactionId) {        
        $this->logger->info('getCustomerList:inside');
        $this->validator->validateDeleteTransaction($transactionId);
        try {
            $customerDao = new CustomerDao();                    
            $responseData = $customerDao->deleteTransaction($transactionId); 
            if($responseData) {
                $responseData='success';
            } else {
                $responseData='fail';
            }
        } catch (\MySQLException $e) {                
              $this->logger->error('indexAction:error occured' . $e->getMessage());
              throw new \InvalidArgumentException($e->getMessage(), 500);
        }       
        return $responseData;
    }    
    
}
