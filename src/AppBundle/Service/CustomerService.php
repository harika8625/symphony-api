<?php
/**
 * Created by PhpStorm.
 * User: Harika PC
 * Date: 11/7/2018
 * Time: 1:17 PM
 */

namespace AppBundle\Service;
use AppBundle\Service\DatabaseService as DatabaseService;

class CustomerService
{
    /** @var DatabaseService */
    private $databaseObj;
    private $database;

    public function __construct(DatabaseService $databaseObj)
    {
        $this->databaseObj = $databaseObj;
        $this->database = $this->databaseObj->getDatabase();
    }

    public function getCustomers(){
        $customers = $this->database->customers->find();
        if(!empty($customers)){
            $customersArray = iterator_to_array($customers);
            $customers = $customersArray;
        }
        return $customers;
    }
    public function addCustomer($customer){
        $insertResult =  $this->database->customers->insertOne($customer);
        $lastInsertedId = (array) $insertResult->getInsertedId();
        return $lastInsertedId['oid'];
    }
    public function deleteCustomers(){
        $this->database->customers->drop();
    }
}