<?php
/**
 * Created by PhpStorm.
 * User: Harika PC
 * Date: 11/9/2018
 * Time: 12:39 AM
 */

namespace AppBundle\Model;


/**
 * Interface CachingInterface
 * @package AppBundle\Model
 */
interface CachingInterface
{
    /**
     * @param array $parameters
     * @return mixed
     */
    function get(array $parameters);

    /**
     * @param array $parameters
     * @return mixed
     */
    function set(array $parameters);

    /**
     * @param array $parameters
     * @return mixed
     */
    function del(array $parameters);

}