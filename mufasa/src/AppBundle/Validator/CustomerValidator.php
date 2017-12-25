<?php

namespace AppBundle\Validator;
use AppBundle\Validator\ConstantlyCool as ConstantlyCool;

/**
 * Class DefaultController
 * @author Deepak soni <deepakdreams.soni@gmail.com>
 * @copyright (c) 2017.
 */

class CustomerValidator
{
    public function customerValidation($dataArr=array()) {       
        if(empty($dataArr['name'])) {
            throw new \InvalidArgumentException('customer name can not empty');
        } else if(empty($dataArr['cnp'])) {
            throw new \InvalidArgumentException('customer CNP can not empty',400);
        } 
           
    }
    
    public function validateDeleteTransaction($transactionId) {       
        if(empty($transactionId)) {
            
            throw new \InvalidArgumentException('Please fill Transaction Id');
        }            
    }
    

    public function transValidation($dataArr=array()) {       
        if (empty($dataArr['customerId'])) {
            throw new InvalidArgumentException('customer id can not empty');
        } else if (empty($dataArr['amount'])) {
            throw new \InvalidArgumentException('customer CNP can not empty', 400);
        } else if (!is_numeric($dataArr['amount'])) {
            throw new \InvalidArgumentException('amount should be an integer value.', 400);
        } else if ($dataArr['amount'] <= 0) {
            throw new \InvalidArgumentException('amount cannot be a negative value or zero.', 400);
        }
    }
    
    public function updateTransValidation($dataArr=array()) {       
        if (empty($dataArr['transactionId'])) {
            throw new InvalidArgumentException('Transaction id can not empty');
        } else if (empty($dataArr['amount'])) {
            throw new \InvalidArgumentException('amount can not empty', 400);
        } else if (!is_numeric($dataArr['amount'])) {
            throw new \InvalidArgumentException('amount should be an integer value.', 400);
        } else if ($dataArr['amount'] <= 0) {
            throw new \InvalidArgumentException('amount cannot be a negative value or zero.', 400);
        }
    }
    
    public function customerParamValidation($customerId,$transactionId) {            
        if(empty($customerId) && empty($transactionId)) {
            throw new \InvalidArgumentException('Please fill either customer id or Transaction Id');
        } 
           
    }
    
    
  public function validatePageNumAndPageSize($pageNum, $pageSize) {
    if (!is_numeric($pageNum)) {
      throw new \InvalidArgumentException('Page Number should be an integer value.', 400);
    } else if ($pageNum <= 0) {
      throw new \InvalidArgumentException('Page Number cannot be a negative value or zero.', 400);
    } else if (!is_numeric($pageSize)) {
      throw new \InvalidArgumentException('Page Size should be an integer value.', 400);
    } else if ($pageSize <= 0) {
      throw new \InvalidArgumentException('Page Size cannot be a negative value or zero.', 400);
    } else if ($pageSize > ConstantlyCool::PAGESIZE) {
      throw new \InvalidArgumentException('Page Size cannot be greater than '.ConstantlyCool::PAGESIZE, 400);
    }
  }
    
    
}
