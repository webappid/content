<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-19
 * Time: 00:01
 */

namespace WebAppId\Content\Services;


use Illuminate\Database\Eloquent\Collection;
use WebAppId\Content\Models\ContentStatus;
use WebAppId\Content\Repositories\ContentStatusRepository;
use WebAppId\DDD\Services\BaseService;

/**
 * Class ContentStatusService
 * @package WebAppId\Content\Services
 */
class ContentStatusService extends BaseService
{
    
    /**
     * @param ContentStatusRepository $contentStatusRepository
     * @return ContentStatus|null
     */
    public function getContentStatus(ContentStatusRepository $contentStatusRepository): ?Collection
    {
        return $this->getContainer()->call([$contentStatusRepository, 'getContentStatus']);
    }
}