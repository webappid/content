<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Repositories\LanguageRepository;
use WebAppId\Content\Tests\TestCase;

class LanguageTest extends TestCase
{
    private $languageRepository;


    public function start()
    {
        $this->languageRepository = $this->getContainer()->make(LanguageRepository::class);
    }

    public function getDummy()
    {
        $objLanguage = new \StdClass;
    
        $objLanguage->code = $this->getFaker()->word;
        $objLanguage->name = $this->getFaker()->word;
        $objLanguage->image_id = '1';
        $objLanguage->user_id = '1';
        return $objLanguage;
    }

    public function createLanguage($dummyData)
    {
        return $this->getContainer()->call([$this->languageRepository, 'addLanguage'], ['request' => $dummyData]);
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
            $resultAllLanguage = $this->getContainer()->call([$this->languageRepository, 'getLanguage']);
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
            $resultLanguage = $this->getContainer()->call([$this->languageRepository, 'getLanguageByName'], ['name' => $dummyData->name]);
            if($resultLanguage==null){
                $this->assertTrue(false);
            }else{
                $this->assertTrue(true);
            }
        }
    }
}
