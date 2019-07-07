<?php
/**
 * Created by PhpStorm.
 * User: galih
 * Date: 2019-03-04
 * Time: 09:20
 */

namespace WebAppId\Content\Services\Params;


class ContentSearchParam
{
    public $q;
    public $category;
    
    /**
     * @return string|null
     */
    public function getQ(): ?string
    {
        return $this->q;
    }
    
    /**
     * @param string $q
     */
    public function setQ(string $q): void
    {
        $this->q = $q;
    }
    
    /**
     * @return string
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }
    
    /**
     * @param string $category
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
    }
    
    
}