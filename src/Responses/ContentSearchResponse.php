<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-10
 * Time: 21:04
 */

namespace WebAppId\Content\Responses;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class ContentSearchResponse
 * @package WebAppId\Content\Responses
 */
class ContentSearchResponse extends AbstractDataTableResponse
{
    /**
     * @var Collection
     */
    public $data;
    
    /**
     * @return Collection
     */
    public function getData(): ?Collection
    {
        return $this->data;
    }

    /**
     * @param Collection $data
     */
    public function setData(Collection $data): void
    {
        $this->data = $data;
    }

}