<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-10
 * Time: 20:52
 */

namespace WebAppId\Content\Responses;


use Illuminate\Database\Eloquent\Collection;
use WebAppId\Content\Models\Content;
use WebAppId\DDD\Responses\AbstractResponse;

class ContentResponse extends AbstractResponse
{
    /**
     * @var Content
     */
    public $content;
    /**
     * @var Collection | null
     */
    public $child;
    /**
     * @var object | null
     */
    public $gallery;
    /**
     * @var array
     */
    public $categories;

    /**
     * @return Content|null
     */
    public function getContent(): ?Content
    {
        return $this->content;
    }

    /**
     * @param Content $content
     */
    public function setContent(Content $content): void
    {
        $this->content = $content;
    }

    /**
     * @return Collection|null
     */
    public function getChild(): ?Collection
    {
        return $this->child;
    }

    /**
     * @param Collection $child
     */
    public function setChild(Collection $child): void
    {
        $this->child = $child;
    }

    /**
     * @return object|null
     */
    public function getGallery(): ?object
    {
        return $this->gallery;
    }

    /**
     * @param object|null $gallery
     */
    public function setGallery(?object $gallery): void
    {
        $this->gallery = $gallery;
    }

    /**
     * @return array|null
     */
    public function getCategories(): ?array
    {
        return $this->categories;
    }

    /**
     * @param array $categories
     */
    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }

}