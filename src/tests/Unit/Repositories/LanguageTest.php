<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Models\Language;
use WebAppId\Content\Repositories\LanguageRepository;
use WebAppId\Content\Services\AddLanguageParam;
use WebAppId\Content\Tests\TestCase;

class LanguageTest extends TestCase
{
    private $languageRepository;
    
    
    public function languageRepository(): LanguageRepository
    {
        if ($this->languageRepository == null) {
            $this->languageRepository = $this->getContainer()->make(LanguageRepository::class);
        }
        return $this->languageRepository;
    }
    
    public function getDummy(): AddLanguageParam
    {
        $objLanguage = new AddLanguageParam();
        $objLanguage->setCode($this->getFaker()->word);
        $objLanguage->setName($this->getFaker()->word);
        $objLanguage->setImageId(1);
        $objLanguage->setUserId(1);
        return $objLanguage;
    }
    
    public function createLanguage($dummyData): ?Language
    {
        return $this->getContainer()->call([$this->languageRepository(), 'addLanguage'], ['addLanguageParam' => $dummyData]);
    }
    
    public function setUp(): void
    {
        parent::setUp();
    }
    
    public function testAddLanguage(): void
    {
        $dummyData = $this->getDummy();
        $result = $this->createLanguage($dummyData);
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
            $this->assertEquals($dummyData->getCode(), $result->code);
            $this->assertEquals($dummyData->getName(), $result->name);
        }
    }
    
    public function testGetAllLanguage(): void
    {
        $result = $this->createLanguage($this->getDummy());
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $resultAllLanguage = $this->getContainer()->call([$this->languageRepository(), 'getLanguage']);
            if (count($resultAllLanguage) > 0) {
                $this->assertTrue(true);
            } else {
                $this->assertTrue(false);
            }
        }
    }
    
    public function testGetLanguageByName(): void
    {
        $dummyData = $this->getDummy();
        $result = $this->createLanguage($dummyData);
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $resultLanguage = $this->getContainer()->call([$this->languageRepository(), 'getLanguageByName'], ['name' => $dummyData->getName()]);
            if ($resultLanguage == null) {
                $this->assertTrue(false);
            } else {
                $this->assertTrue(true);
            }
        }
    }
}
