<?php

namespace AppBundle\Service;

use \MongoDB;

/**
 * Class DatabaseService
 * @package AppBundle\Service
 */
class DatabaseService
{
    /**
     * @var
     */
    protected $database;


    /**
     * DatabaseService constructor.
     * @param $host
     * @param $port
     * @param $database
     * @param $port2
     * @param $replicaSet
     */
    public function __construct($host, $port, $database, $port2, $replicaSet)
    {
        $mongoClient = new MongoDB\Client("mongodb://$host:$port");
        $this->setDatabase(
            $mongoClient->$database
        );

        //Note:
        //$mongoClient = new MongoDB\Client("mongodb://$host:$port,$host:$port2/?replicaSet=$replicaSet");
        //I could not create replica set for the DB in my local system.
        //If setting up of replica set was successful then I could have setup the change stream DB watch()
        // and invalidate the cache in the redis cache server if there is any change in the mongo db data.

    }

    /**
     * Set database
     * @param MongoDB\Database $database
     */
    public function setDatabase(MongoDB\Database $database)
    {
        $this->database = $database;
    }

    /**
     * Get database
     * @return mixed
     */
    public function getDatabase()
    {
        return $this->database;
    }


}
