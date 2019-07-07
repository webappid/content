<?php


namespace WebAppId\Content\Responses\Categories;


use WebAppId\Content\Models\Category;
use WebAppId\DDD\Responses\AbstractResponse;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 2019-06-07
 * Time: 13:16
 * Class SearchResponse
 * @package WebAppId\Content\Responses\Categories
 */
class SearchResponse extends AbstractResponse
{
    /**
     * @var Category|null
     */
    public $category;

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }
}