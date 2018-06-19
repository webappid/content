<?php

namespace WebAppId\Content\Tests\Unit\Models;

use WebAppId\Content\Models\Language;
use WebAppId\Content\Tests\TestCase;

class LanguageTest extends TestCase
{
    private $language;

    private $objLanguage;

    public function start()
    {
        $this->language = new Language;
        $this->objLanguage = new \StdClass;
    }

    public function createDummy()
    {
        $this->objLanguage->code = $this->faker->word;
        $this->objLanguage->name = $this->faker->word;
        $this->objLanguage->image_id = '1';
        $this->objLanguage->user_id = '1';
    }

    public function createLanguage()
    {
        $this->createDummy();
        return $this->language->addLanguage($this->objLanguage);
    }

    public function setUp()
    {
        parent::setUp();
        $this->start();
    }

    public function testAddLanguage()
    {
        $result = $this->createLanguage();
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
            $this->assertEquals($this->objLanguage->code, $result->code);
            $this->assertEquals($this->objLanguage->name, $result->name);
        }
    }
    
    public function testGetAllLanguage(){
        $result = $this->createLanguage();
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $resultAllLanguage = $this->language->getLanguage(); 
            if(count($resultAllLanguage)>0){
                $this->assertTrue(true);
            }else{
                $this->assertTrue(false);
            }
        }
    }

    public function testGetLanguageByName(){
        $result = $this->createLanguage();
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $resultLanguage = $this->language->getLanguageByName($this->objLanguage->name); 
            if($resultLanguage==null){
                $this->assertTrue(false);
            }else{
                $this->assertTrue(true);
            }
        }
    }
}
