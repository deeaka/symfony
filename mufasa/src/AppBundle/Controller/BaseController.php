<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
/**
 * Class DefaultController
 * @author Deepak soni <deepakdreams.soni@gmail.com>
 * @copyright (c) 2017.
 */
class BaseController extends Controller {    
    public function __construct(EntityManager $em) {      
        \AppBundle\Registry\Registry::setInstance($em);
    }    
}
