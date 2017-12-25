<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;

/**
 * Class DefaultController
 * @author Deepak soni <deepakdreams.soni@gmail.com>
 * @copyright (c) 2017.
 */
class DefaultController extends Controller {

    private $logger;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    /**
     * @Route("/addCustomer", name="addCustomer")
     * @Method({"POST"})
     */
    public function indexAction(Request $request) {
        $responseArray = array('response' => '', 'error' => '');
        $this->logger->info('deepak Soni');
        try {
            $data = $request->getContent();
            $postArray = json_decode($data, true);

            $em = $this->getDoctrine()->getManager();
            $customerComponent = new \AppBundle\Component\CustomerComponent($this->logger);
            $customerList = $customerComponent->addCustomer($em, $postArray);
            $responseArray['response'] = $customerList;
        } catch (\Exception $e) {
            $responseArray['error'] = $e->getMessage();
            $this->logger->error('indexAction:error occured' . $e->getMessage());
        }
        return new \Symfony\Component\HttpFoundation\JsonResponse($responseArray);
    }

    /**
     * @Route("/addTransaction", name="addTransaction")
     * @Method({"POST"})
     */
    public function addTransaction(Request $request) {
        $responseArray = array('response' => '', 'error' => '');
        $this->logger->info('addTransaction');
        try {
            $data = $request->getContent();
            $postArray = json_decode($data, true);
            
            $em = $this->getDoctrine()->getManager();
            $customerComponent = new \AppBundle\Component\CustomerComponent($this->logger);
            $customerList = $customerComponent->addTransaction($em, $postArray);
            $responseArray['response'] = $customerList;
        } catch (\Exception $e) {
            $responseArray['error'] = $e->getMessage();
            $this->logger->error('indexAction:error occured' . $e->getMessage());
        }
        return new \Symfony\Component\HttpFoundation\JsonResponse($responseArray);
    }

    
    /**
     * @Route("/updateTransaction", name="updateTransaction")
     * @Method({"PUT"})
     */
    public function updateTransaction(Request $request) {
        $responseArray = array('response' => '', 'error' => '');
        $this->logger->info('addTransaction');
        try {
            $data = $request->getContent();
            $postArray = json_decode($data, true);
            
            $em = $this->getDoctrine()->getManager();
            $customerComponent = new \AppBundle\Component\CustomerComponent($this->logger);
            $customerList = $customerComponent->updateTransaction($em, $postArray);
            $responseArray['response'] = $customerList;
        } catch (\Exception $e) {
            $responseArray['error'] = $e->getMessage();
            $this->logger->error('indexAction:error occured' . $e->getMessage());
        }
        return new \Symfony\Component\HttpFoundation\JsonResponse($responseArray);
    }
    
    
    /**
     * @Route("/getTransaction", name="")
     * @Method({"GET"})
     */
    public function getTransaction(Request $request) {
        $responseArray = array('response' => '', 'error' => '');
        $this->logger->info('deepak Soni');
        try {
            $customerId = $request->query->get('customerId');
            $transactionId = $request->query->get('transactionId');

            $em = $this->getDoctrine()->getManager();
            $customerComponent = new \AppBundle\Component\CustomerComponent($this->logger);
            $customerList = $customerComponent->getTransaction($em, $customerId, $transactionId);
            $responseArray['response'] = $customerList;
        } catch (\Exception $e) {
            $responseArray['error'] = $e->getMessage();
            $this->logger->error('indexAction:error occured' . $e->getMessage());
        }
        return new \Symfony\Component\HttpFoundation\JsonResponse($responseArray);
    }

    /**
     * @Route("/getTransactionByFilter", name="getTransactionByFilter")
     * @Method({"GET"})
     */
    public function getTransactionByFilter(Request $request) {
        $responseArray = array('response' => '', 'error' => '');
        $this->logger->info('Controller:getTransactionByFilter');
        try {
            $customerId = $request->query->get('customerId');
            $amount = $request->query->get('amount');
            $date = $request->query->get('date');
            $offset = $request->query->get('offset');
            $limit = $request->query->get('limit');

            $em = $this->getDoctrine()->getManager();
            $customerComponent = new \AppBundle\Component\CustomerComponent($this->logger);
            $customerList = $customerComponent->getTransactionByFilter($em, $customerId, $amount, $date, $offset, $limit);
            $responseArray['response'] = $customerList;
        } catch (\Exception $e) {
            $responseArray['error'] = $e->getMessage();
            $this->logger->error('Controller:getTransactionByFilter error occured' . $e->getMessage());
        }
        return new \Symfony\Component\HttpFoundation\JsonResponse($responseArray);
    }

    /**
     * @Route("/deleteTransaction", name="deleteTransaction")
     * @Method({"DELETE"})
     */
    public function deleteTransaction(Request $request) {
        $responseArray = array('response' => '', 'error' => '');
        $this->logger->info('Controller:getTransactionByFilter');
        try {
            
            $transactionId = $request->query->get('transactionId');    
            $em = $this->getDoctrine()->getManager();
            $customerComponent = new \AppBundle\Component\CustomerComponent($this->logger);
            $customerList = $customerComponent->deleteTransaction($em, $transactionId);
            $responseArray['response'] = $customerList;
        } catch (\Exception $e) {
            $responseArray['error'] = $e->getMessage();
            $this->logger->error('Controller:getTransactionByFilter error occured' . $e->getMessage());
        }
        return new \Symfony\Component\HttpFoundation\JsonResponse($responseArray);
    }
    
    /**
     * @Route("/about", name="about")
     */
    public function aboutAction(Request $request) {
        // replace this example code with whatever you need
        return $this->render('mine/index.html.twig');
    }

}
