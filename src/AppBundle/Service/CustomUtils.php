<?php
/**
 * Created by PhpStorm.
 * User: Harika PC
 * Date: 11/9/2018
 * Time: 12:09 AM
 */

namespace AppBundle\Service;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class CustomUtils
 * @package AppBundle\Service
 */
class CustomUtils
{
    /**
     * CustomUtils constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $key
     */
    public function getConfigParameters($key)
    {
        $this->container->getParameter($key);
    }


}