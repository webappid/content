<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-19
 * Time: 00:01
 */

namespace WebAppId\Content\Services;


use Illuminate\Container\Container;
use WebAppId\Content\Models\ContentStatus;
use WebAppId\Content\Repositories\ContentStatusRepository;

/**
 * Class ContentStatusService
 * @package WebAppId\Content\Services
 */
class ContentStatusService
{
    
    private $container;
    
    /**
     * ContentStatusService constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    /**
     * @param ContentStatusRepository $contentStatusRepository
     * @return ContentStatus|null
     */
    public function getContentStatus(ContentStatusRepository $contentStatusRepository): ?object
    {
        return $this->container->call([$contentStatusRepository, 'getContentStatus']);
    }
}