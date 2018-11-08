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
    public $predis;
    public $serverStatus;
    public function __construct($host, $port, $prefix)
    {

        try {
            $this->predis = new Predis\Client(array('host' => $host, 'port' => $port, 'prefix' => $prefix));
            $this->getServerInfo();
        } catch (Predis\Connection\ConnectionException $exception){
            $this->getServerInfo();
            //echo $exception->getMessage();
        }
    }

    public function getServerInfo(){
        $serverInfo = $this->predis->info();
        if(!empty($serverInfo)){
            $this->setServerStatus('Running');
        }else{
            $this->setServerStatus('Down');
        }
        return $serverInfo;
    }
    public function setServerStatus($status){
        $this->serverStatus = $status;
    }
    public function getServerStatus(){
        return $this->serverStatus;
    }
}
