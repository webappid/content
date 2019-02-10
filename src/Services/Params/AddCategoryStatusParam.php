<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-09
 * Time: 11:09
 */

namespace WebAppId\Content\Services\Params;


class AddCategoryStatusParam
{
    private $name;
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    
}