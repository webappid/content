<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 04/11/18
 * Time: 04.06
 */

namespace WebAppId\Content\Tests\Unit\Repositories;


use WebAppId\Content\Models\CategoryStatus;
use WebAppId\Content\Repositories\CategoryStatusRepository;
use WebAppId\Content\Services\Params\AddCategoryStatusParam;
use WebAppId\Content\Tests\TestCase;

class CategoryStatusTest extends TestCase
{
    private $categoryStatusRepository;
    
    private function categoryStatusRepository(): CategoryStatusRepository
    {
        if ($this->categoryStatusRepository == null) {
            $this->categoryStatusRepository = $this->getContainer()->make(CategoryStatusRepository::class);
        }
        return $this->categoryStatusRepository;
    }
    
    public function getDummy(): AddCategoryStatusParam
    {
        $objCategoryStatus = new AddCategoryStatusParam();
        $objCategoryStatus->setName($this->getFaker()->name);
        return $objCategoryStatus;
    }
    
    public function createDummy($dummy): ?CategoryStatus
    {
        return $this->getContainer()->call([$this->categoryStatusRepository(), 'addCategoryStatus'], ['addCategoryStatusParam' => $dummy]);
    }
    
    public function testAddCategoryStatus(): void
    {
        $dummy = $this->getDummy();
        $result = $this->createDummy($dummy);
        if ($result == null) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
            $this->assertEquals($dummy->getName(), $result->name);
        }
    }
    
}