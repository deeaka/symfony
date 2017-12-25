<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Customer")
 */
class Customer
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
   * @var string
   *
   * @ORM\Column(name="name", type="string", nullable=false)
   */
  private $name;
  
  /**
   * @var string
   *
   * @ORM\Column(name="cnp", type="string", nullable=false)
   */
  private $cnp;
  
  /**
   * @var string
   *
   * @ORM\Column(name="actionBy", type="integer", nullable=false)
   */
  private $actionBy;

   /**
   * @var string
   *
   * @ORM\Column(name="created")
   */
  private $created;
  
  /**
   * @var string
   *
   * @ORM\Column(name="updated")
   */
  private $updated;
  
  function getId() {
      return $this->id;
  }

  function getName() {
      return $this->name;
  }

  function getCnp() {
      return $this->cnp;
  }

  function getActionBy() {
      return $this->actionBy;
  }

  function getCreated() {
      return $this->created;
  }

  function getUpdated() {
      return $this->updated;
  }

  function setId($id) {
      $this->id = $id;
  }

  function setName($name) {
      $this->name = $name;
  }

  function setCnp($cnp) {
      $this->cnp = $cnp;
  }

  function setActionBy($actionBy) {
      $this->actionBy = $actionBy;
  }

  function setCreated($created) {
      $this->created = $created;
  }

  function setUpdated($updated) {
      $this->updated = $updated;
  }


  
}
