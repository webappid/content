<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-19
 * Time: 00:01
 */

namespace WebAppId\Content\Services;


use Illuminate\Container\Container;
use WebAppId\Content\Repositories\ContentStatusRepository;

class ContentStatusService
{
    
    private $container;
    
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    public function getContentStatus(ContentStatusRepository $contentStatusRepository)
    {
        return $this->container->call([$contentStatusRepository, "getContentStatus"]);
    }
}