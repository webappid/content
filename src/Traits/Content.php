<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\Content\Traits;

use Illuminate\Container\Container;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use WebAppId\Content\Repositories\TimeZoneRepository;
use WebAppId\Content\Services\Requests\ContentServiceRequest;
use WebAppId\DDD\Tools\Lazy;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 10/05/2020
 * Time: 05.43
 * Class Content
 * @package WebAppId\Content\Traits
 * Use this trait to cleanup the data first before send to the Service class
 */
trait Content
{
    /**
     * @param Container $container
     * @param array $contentRequest
     * @param ContentServiceRequest $contentServiceRequest
     * @param TimeZoneRepository $timeZoneRepository
     * @return object|ContentServiceRequest
     */
    public function transformContent(Container $container,
                                     array $contentRequest,
                                     ContentServiceRequest $contentServiceRequest,
                                     TimeZoneRepository $timeZoneRepository)
    {

        if (session('timezone') == null) {
            $zone = "Asia/Jakarta";
        } else {
            $zone = session('timezone');
        }

        $timeZoneData = $container->call([$timeZoneRepository, 'getByName'], ['name' => $zone]);
        $contentRequest['code'] = Str::slug($contentRequest['title']);
        $contentRequest['og_title'] = $contentRequest['title'] . ' - ' . env('APP_NAME');
        $contentRequest['keyword'] = isset($contentRequest['keyword']) ? $contentRequest['keyword'] : $contentRequest['title'];
        $contentRequest['og_description'] = $contentRequest['description'];
        $contentRequest['default_image'] = isset($contentRequest['default_image']) ? $contentRequest['default_image'] : 1;
        $contentRequest['status_id'] = isset($contentRequest['status_id']) ? (int)$contentRequest['status_id'] : 1;
        $contentRequest['language_id'] = isset($contentRequest['language_id']) ? $contentRequest['language_id'] : 1;
        $contentRequest['publish_date'] = isset($contentRequest['publish_date']) ? $contentRequest['publish_date'] : Carbon::now('UTC');
        $contentRequest['additional_info'] = isset($contentRequest['additional_info']) ? $contentRequest['additional_info'] : "";
        $contentRequest['time_zone_id'] = isset($timeZoneData) ? $timeZoneData->id : 271;
        $contentRequest['parent_id'] = isset($contentRequest['parent_id']) ? $contentRequest['parent_id'] : 0;
        $contentRequest['galleries'] = isset($contentRequest['galleries']) ? $contentRequest['galleries'] : [];
        $contentRequest['keyword'] = isset($contentRequest['keyword']) ? $contentRequest['keyword'] : "";

        try {
            $contentServiceRequest = Lazy::copyFromArray($contentRequest, $contentServiceRequest);
        } catch (\Exception $e) {
            report($e);
        }

        return $contentServiceRequest;
    }
}