<?php

namespace AppBundle\Entity;
use AppBundle\Entity\Customer as Customer;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Summary")
 */
class Summary
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
   * @var integer
   *
   * @ORM\Column(name="total_ammount", type="float", nullable=false)
   */
  private $amount;
  
   /**
   * @var string
   *
   * @ORM\Column(name="sumary_date")
   */
  private $created;
  
  function getId() {
      return $this->id;
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

  function setAmount($amount) {
      $this->amount = $amount;
  }

  function setCreated($created) {
      $this->created = $created;
  }
  
}
