<?php
/**
 * Created by PhpStorm.
 * User: Harika PC
 * Date: 11/9/2018
 * Time: 12:09 AM
 */

namespace AppBundle\Service;
use Symfony\Component\DependencyInjection\ContainerInterface;


class CustomUtils
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    public function getConfigParameters($key)
    {
        $this->container->getParameter($key);
    }


}