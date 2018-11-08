<?php
/**
 * Created by PhpStorm.
 * User: Harika PC
 * Date: 11/9/2018
 * Time: 12:39 AM
 */

namespace AppBundle\Model;


interface CachingInterface
{
    function get(array $parameters);
    function set(array $parameters);
    function del(array $parameters);

}