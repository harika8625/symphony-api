<?php
/**
 * Created by PhpStorm.
 * User: Harika PC
 * Date: 11/8/2018
 * Time: 7:29 PM
 */

namespace AppBundle\Service;


use AppBundle\Model\CachingInterface;

class SetCacheService extends CacheService implements CachingInterface
{
    public function get(array $parameters)
    {
        return $this->predis->smembers($parameters['key']);
    }
    public function set(array $parameters)
    {
        $this->predis->sadd($parameters['key'], $parameters['value']);
    }

    public function del(array $parameters)
    {
        $this->predis->del($parameters['keys']);
    }
}