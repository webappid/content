<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-10
 * Time: 20:52
 */

namespace WebAppId\Content\Responses;


class ContentResponse extends AbstractResponse
{
    private $content;
    private $child;
    private $gallery;
    private $categories;
    
    /**
     * @return object|null
     */
    public function getContent(): ?object
    {
        return $this->content;
    }
    
    /**
     * @param object $content
     */
    public function setContent(object $content): void
    {
        $this->content = $content;
    }
    
    /**
     * @return array|null
     */
    public function getChild(): ?array
    {
        return $this->child;
    }
    
    /**
     * @param array $child
     */
    public function setChild(array $child): void
    {
        $this->child = $child;
    }
    
    /**
     * @return array|null
     */
    public function getGallery(): ?array
    {
        return $this->gallery;
    }
    
    /**
     * @param array $gallery
     */
    public function setGallery(array $gallery): void
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