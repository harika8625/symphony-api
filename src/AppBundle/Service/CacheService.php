<?php

namespace AppBundle\Service;

use Predis;

/**
* Here you have to implement a CacheService with the operations above.
* It should contain a failover, which means that if you cannot retrieve
* data you have to hit the Database.
**/
class CacheService
{
    /**
     * @var Predis\Client
     */
    public $predis;
    /**
     * @var
     */
    public $serverStatus;

    /**
     * CacheService constructor.
     * @param $host
     * @param $port
     * @param $prefix
     */
    public function __construct($host, $port, $prefix)
    {

        try {
            $this->predis = new Predis\Client(array('host' => $host, 'port' => $port, 'prefix' => $prefix));
            $this->getServerInfo();
        } catch (Predis\Network\ConnectionException $exception){
            $this->getServerInfo();
            //echo $exception->getMessage();
        }catch (Predis\Connection\ConnectionException $exception){
            $this->getServerInfo();
            //echo $exception->getMessage();
        }

        //Note: unable to catch the connection Exception if the redis server is turned off.
    }

    /**
     * Get server details
     * @return array
     */
    public function getServerInfo(){
        $serverInfo = $this->predis->info();
        if(!empty($serverInfo)){
            $this->setServerStatus('Running');
        }else{
            $this->setServerStatus('Down');
        }
        return $serverInfo;
    }

    /**
     * Set server status
     * @param $status
     */
    public function setServerStatus($status){
        $this->serverStatus = $status;
    }

    /**
     * Get server status
     * @return mixed
     */
    public function getServerStatus(){
        return $this->serverStatus;
    }
}
