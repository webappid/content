<?php
/**
 * Created by PhpStorm.
 * User: galih
 * Date: 2019-03-04
 * Time: 12:27
 */

namespace WebAppId\Content\Services;


use Illuminate\Container\Container;

class BaseService
{
    private $container;
    
    /**
     * ContentService constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    /**
     * @return Container
     */
    protected function getContainer(): Container
    {
        return $this->container;
    }
}