<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-10
 * Time: 20:52
 */

namespace WebAppId\Content\Responses;


use WebAppId\Content\Models\Content;

class ContentResponse extends AbstractResponse
{
    /**
     * @var Content
     */
    private $content;
    /**
     * @var mixed
     */
    private $child;
    /**
     * @var object
     */
    private $gallery;
    /**
     * @var array
     */
    private $categories;

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
     * @return mixed
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * @param $child
     */
    public function setChild($child): void
    {
        $this->child = $child;
    }

    /**
     * @return object
     */
    public function getGallery(): object
    {
        return $this->gallery;
    }

    /**
     * @param object $gallery
     */
    public function setGallery(object $gallery): void
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