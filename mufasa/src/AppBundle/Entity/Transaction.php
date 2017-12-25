<?php

namespace AppBundle\Entity;
use AppBundle\Entity\Customer as Customer;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Transaction")
 */
class Transaction
{
    
  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer", nullable=false)
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="IDENTITY")
   */
  private $id;

  /**
   * One transaction has One customer.
   * @ORM\OneToOne(targetEntity="Customer")
   * @ORM\JoinColumn(name="cust_id", referencedColumnName="id")
   */
  private $custId;
  
  /**
   * @var integer
   *
   * @ORM\Column(name="trans_id", type="integer", nullable=false)
   */
  private $transId;
  
  /**
   * @var integer
   *
   * @ORM\Column(name="tans_amount", type="float", nullable=false)
   */
  private $amount;
  
   /**
   * @var string
   *
   * @ORM\Column(name="tans_date")
   */
  private $created;
  
  function getId() {
      return $this->id;
  }

  function getCustId() {
      return $this->custId;
  }

  function getTransId() {
      return $this->transId;
  }

  function getAmount() {
      return $this->amount;
  }

  function getCreated() {
      return $this->created;
  }

  function setId($id) {
      $this->id = $id;
  }

  function setCustId(Customer $custId) {
      $this->custId = $custId;
  }

  function setTransId($transId) {
      $this->transId = $transId;
  }

  function setAmount($amount) {
      $this->amount = $amount;
  }

  function setCreated($created) {
      $this->created = $created;
  }

}
