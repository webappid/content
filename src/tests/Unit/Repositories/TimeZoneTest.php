<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-10
 * Time: 21:22
 */

namespace WebAppId\Content\Tests\Unit\Repositories;


use WebAppId\Content\Models\TimeZone;
use WebAppId\Content\Repositories\TimeZoneRepository;
use WebAppId\Content\Services\Params\AddTimeZoneParam;
use WebAppId\Content\Tests\TestCase;

class TimeZoneTest extends TestCase
{
    
    private $timeZoneRepository;
    
    private function timeZoneRepository(): TimeZoneRepository
    {
        if ($this->timeZoneRepository == null) {
            $this->timeZoneRepository = new TimeZoneRepository();
        }
        return $this->timeZoneRepository;
    }
    
    public function createDummy(): AddTimeZoneParam
    {
        $addTimeZoneParam = new AddTimeZoneParam();
        $addTimeZoneParam->setUserId(1);
        $addTimeZoneParam->setCode($this->getFaker()->text($maxNbChars = 10));
        $addTimeZoneParam->setName($this->getFaker()->text($maxNbChars = 20));
        $addTimeZoneParam->setMinute($this->getFaker()->numberBetween(-8, 8));
        return $addTimeZoneParam;
    }
    
    public function storeTimeZone(AddTimeZoneParam $addTimeZoneParam): ?TimeZone
    {
        return $this->getContainer()->call([$this->timeZoneRepository(), 'addTimeZone'], ['addTimeZoneParam' => $addTimeZoneParam]);
    }
    
    public function testAddTimeZone(): TimeZone
    {
        $dummy = $this->createDummy();
        $timeZone = $this->storeTimeZone($dummy);
        self::assertNotEquals(null, $timeZone);
        self::assertEquals($dummy->getName(), $timeZone->name);
        self::assertEquals($dummy->getUserId(), $timeZone->user_id);
        self::assertEquals($dummy->getMinute(), $timeZone->minute);
        self::assertEquals($dummy->getCode(), $timeZone->code);
        
        return $timeZone;
    }
    
    public function testGetOneTimeZoneByName(): void
    {
        $timeZone = $this->testAddTimeZone();
        $result = $this->getContainer()->call([$this->timeZoneRepository(), 'getOneTimeZoneByName'], ['name' => $timeZone->name]);
        
        self::assertNotEquals(null, $result);
    }
    
    public function testGetTimeZoneByName(): void
    {
        $timeZone = $this->testAddTimeZone();
        $result = $this->getContainer()->call([$this->timeZoneRepository(), 'getTimeZoneByName'], ['name' => $timeZone->name]);
        
        self::assertNotEquals(null, $result);
    }
    
    public function testTimeZoneById(): void
    {
        $timeZone = $this->testAddTimeZone();
        $result = $this->getContainer()->call([$this->timeZoneRepository(), 'getTimeZoneById'], ['id' => $timeZone->id]);
        
        self::assertNotEquals(null, $result);
    }
}