<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Repositories\LanguageRepository;
use WebAppId\Content\Tests\TestCase;

use Illuminate\Container\Container;

class LanguageTest extends TestCase
{
    private $language;

    private $container;

    public function start()
    {
        $this->container = new Container;
        $this->language = $this->container->make(LanguageRepository::class);
    }

    public function getDummy()
    {
        $objLanguage = new \StdClass;
        $faker = $this->getFaker();
        $objLanguage->code = $faker->word;
        $objLanguage->name = $faker->word;
        $objLanguage->image_id = '1';
        $objLanguage->user_id = '1';
        return $objLanguage;
    }

    public function createLanguage($dummyData)
    {
        return $this->container->call([$this->language,'addLanguage'],['request' => $dummyData]);
    }

    public function setUp()
    {
        parent::setUp();
        $this->start();
    }

    public function testAddLanguage()
    {
        $dummyData = $this->getDummy();
        $result = $this->createLanguage($dummyData);
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
            $this->assertEquals($dummyData->code, $result->code);
            $this->assertEquals($dummyData->name, $result->name);
        }
    }
    
    public function testGetAllLanguage(){
        $result = $this->createLanguage($this->getDummy());
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $resultAllLanguage = $this->container->call([$this->language,'getLanguage']); 
            if(count($resultAllLanguage)>0){
                $this->assertTrue(true);
            }else{
                $this->assertTrue(false);
            }
        }
    }

    public function testGetLanguageByName(){
        $dummyData = $this->getDummy();
        $result = $this->createLanguage($dummyData);
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $resultLanguage = $this->container->call([$this->language,'getLanguageByName'],['name'=>$dummyData->name]); 
            if($resultLanguage==null){
                $this->assertTrue(false);
            }else{
                $this->assertTrue(true);
            }
        }
    }
}
