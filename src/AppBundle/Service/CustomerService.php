<?php
/**
 * Created by PhpStorm.
 * User: Harika PC
 * Date: 11/7/2018
 * Time: 1:17 PM
 */

namespace AppBundle\Service;
use AppBundle\Service\DatabaseService as DatabaseService;

/**
 * Class CustomerService
 * @package AppBundle\Service
 */
class CustomerService
{
    /** @var DatabaseService */
    private $databaseObj;
    /**
     * @var
     */
    private $database;

    /**
     * CustomerService constructor.
     * @param \AppBundle\Service\DatabaseService $databaseObj
     */
    public function __construct(DatabaseService $databaseObj)
    {
        $this->databaseObj = $databaseObj;
        $this->database = $this->databaseObj->getDatabase();
    }

    /**
     * Get customers for database
     * @return array
     */
    public function getCustomers(){
        $customers = $this->database->customers->find();
        if(!empty($customers)){
            $customersArray = iterator_to_array($customers);
            $customers = $customersArray;
        }
        return $customers;
    }

    /**
     * Add customer to the database
     * @param $customer
     * @return mixed
     */
    public function addCustomer($customer){
        $insertResult =  $this->database->customers->insertOne($customer);
        $lastInsertedId = (array) $insertResult->getInsertedId();
        return $lastInsertedId['oid'];
    }

    /**
     * Delete customers from database
     *
     */
    public function deleteCustomers(){
        $this->database->customers->drop();
    }
}