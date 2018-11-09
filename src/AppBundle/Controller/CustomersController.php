<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class CustomersController
 * @package AppBundle\Controller
 */
class CustomersController extends Controller
{

    /**
     * @Route("/customers/")
     * @Method("GET")
     */
    public function getAction()
    {
        $cacheServerStatus = $this->get('cache_service')->getServerStatus();

        if($cacheServerStatus == 'Running') {
            //Read the cache element name from config parameters
            $cacheElementName = $this->container->getParameter('cache_element_customers') ?? 'customers';

            //Retrieving elements from cache server
            $cachedCustomers = $this->get('sorted_set_cache_service')->get(array('key' => $cacheElementName, 'min' => 0, 'max' => -1));
        }

        if(!empty($cachedCustomers)) {
            $customers = array_map(function ($element) { return unserialize($element); }, $cachedCustomers);
        }else{
            //Hit the database to get customers data
            $customers = $this->get('customer_service')->getCustomers();

            //Save customers to cache for quick retrieval
            if(!empty($customers)){
                $formattedCustomerDetails = array();
                foreach($customers as $customer){
                    //Prepare data to add to cache server
                    $customerDetails = (array) $customer;
                    $idArray = (array) $customer['_id'];
                    $id = $idArray['oid'];

                    $customerArray['name'] = $customerDetails['name'];
                    $customerArray['age'] = $customerDetails['age'];
                    $customerArray['id'] = $id;

                    //Format customers array for json response
                    $formattedCustomerDetails[] = $customerArray;

                    if($cacheServerStatus == 'Running') {
                        //Request to add each customer details to the cache server
                        $this->get('sorted_set_cache_service')->set(array('key' => $cacheElementName, 'score' => 1, 'value' => serialize($customerArray)));
                    }
                }
                $customers = $formattedCustomerDetails;
            }


        }

        if(!empty($customers)) {
            return new JsonResponse($customers,200);
        }else{
            return new JsonResponse(array(), 204);
        }
    }

    /**
     * @Route("/customers/")
     * @Method("POST")
     */
    public function postAction(Request $request)
    {
        $customers = json_decode($request->getContent());

        if(!empty($customers)) {

            //Read the cache element name from config parameters
            $cacheElementName = $this->container->getParameter('cache_element_customers') ?? 'customers';

            $cacheServerStatus = $this->get('cache_service')->getServerStatus();

            foreach ($customers as $customer) {

                //Request to add customer to database and return last inserted id
                $lastInsertedId = $this->get('customer_service')->addCustomer($customer);

                if (!empty($lastInsertedId) && $cacheServerStatus == 'Running') {
                    //Prepare data to add to cache server
                    $customerArray = (array)$customer;
                    $customerArray['id'] = $lastInsertedId;

                    //Request to add each customer details to the cache server
                    $this->get('sorted_set_cache_service')->set(array('key' => $cacheElementName, 'score' => 1, 'value' => serialize($customerArray)) );
                }
            }
        }else{
            return new JsonResponse(array('status' => 'No donuts for you'), 400);
        }

        return new JsonResponse(array('status' => 'Customers successfully created'),200);
    }

    /**
     * @Route("/customers/")
     * @Method("DELETE")
     */
    public function deleteAction()
    {
        //Request to delete all the customers in database
        $this->get('customer_service')->deleteCustomers();

        $cacheServerStatus = $this->get('cache_service')->getServerStatus();

        if($cacheServerStatus == 'Running') {
            //Read the cache element name from config parameters
            $cacheElementName = $this->container->getParameter('cache_element_customers') ?? 'customers';

            //Request to clear the cache server
            $this->get('sorted_set_cache_service')->del(array('keys' => array($cacheElementName)));
        }

        return new JsonResponse(array('status' => 'Customers successfully deleted'),200);
    }
}
