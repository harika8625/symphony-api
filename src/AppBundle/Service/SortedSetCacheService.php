<?php
/**
 * Created by PhpStorm.
 * User: Harika PC
 * Date: 11/8/2018
 * Time: 7:29 PM
 */

namespace AppBundle\Service;


use AppBundle\Model\CachingInterface;

/**
 * Class SortedSetCacheService
 * @package AppBundle\Service
 */
class SortedSetCacheService extends CacheService implements CachingInterface
{
    /**
     * Get data from cache server
     * @param array $parameters
     * @return array|mixed
     */
    public function get(array $parameters)
    {
        return  $this->predis->zrange($parameters['key'], $parameters['min'], $parameters['max']);
    }

    /**
     * Add data to cache server
     * @param array $parameters
     * @return mixed|void
     */
    public function set(array $parameters)
    {
        $this->predis->zadd($parameters['key'], $parameters['score'], $parameters['value']);
    }

    /**
     * Delete data from cache server
     * @param array $parameters
     * @return mixed|void
     */
    public function del(array $parameters)
    {
        $this->predis->del($parameters['keys']);
    }

}