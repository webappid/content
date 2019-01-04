<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Repositories\ContentRepository;
use WebAppId\Content\Tests\TestCase;

class ContentTest extends TestCase
{
    
    private $contentRepository;
    
    
    public function setUp()
    {
        $this->contentRepository = $this->getContainer()->make(ContentRepository::class);
        parent::setUp();
    }
    
    public function getDummy()
    {
        $objContent = new \StdClass;
        $objContent->title = $this->getFaker()->word;
        $objContent->code = $this->getFaker()->uuid;
        $objContent->description = $this->getFaker()->text($maxNbChars = 190);
        $objContent->keyword = $this->getFaker()->word;
        $objContent->og_title = $this->getFaker()->word;
        $objContent->og_description = $this->getFaker()->text;
        $objContent->default_image = '1';
        $objContent->status_id = '1';
        $objContent->language_id = '1';
        $objContent->publish_date = $this->getFaker()->date($format = 'Y-m-d', $max = 'now');
        $objContent->additional_info = $this->getFaker()->text;
        $objContent->content = $this->getFaker()->text;
        $objContent->time_zone_id = '1';
        $objContent->owner_id = '1';
        $objContent->user_id = '1';
        $objContent->creator_id = '1';
        
        return $objContent;
    }
    
    public function createContent($dummy)
    {
        return $this->getContainer()->call([$this->contentRepository, 'addContent'], ['data' => $dummy]);
    }
    
    public function testAddContent()
    {
        $result = $this->createContent($this->getDummy());
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
            return $result;
        }
    }
    
    public function testBulkAddContentCount()
    {
        for ($n = 0; $n < 10; $n++) {
            $dummyData = $this->getDummy();
            $dummyData->keyword = 'bulk';
    
            $this->getContainer()->call([$this->contentRepository, 'addContent'], ['data' => $dummyData]);
        }
    
        $this->assertEquals(10, $this->getContainer()->call([$this->contentRepository, 'getContentByKeywordCount'], ['keyword' => $dummyData->keyword]));
    }
    
    public function testGetContentByCode()
    {
        $objContent = $this->getDummy();
        $result = $this->createContent($objContent);
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $result = $this->getContainer()->call([$this->contentRepository, 'getContentByCode'], ["code" => $result->code, "request" => $result]);
            if ($result != null) {
                $this->assertTrue(true);
                $this->assertEquals($objContent->title, $result->title);
                $this->assertEquals($objContent->code, $result->code);
                $this->assertEquals($objContent->description, $result->description);
                $this->assertEquals($objContent->keyword, $result->keyword);
                $this->assertEquals($objContent->og_title, $result->og_title);
                $this->assertEquals($objContent->og_description, $result->og_description);
                $this->assertEquals($objContent->default_image, $result->default_image);
                $this->assertEquals($objContent->status_id, $result->status_id);
                $this->assertEquals($objContent->language_id, $result->language_id);
                $this->assertEquals($objContent->publish_date, $result->publish_date);
                $this->assertEquals($objContent->additional_info, $result->additional_info);
                $this->assertEquals($objContent->content, $result->content);
                $this->assertEquals($objContent->time_zone_id, $result->time_zone_id);
                $this->assertEquals($objContent->owner_id, $result->owner_id);
                $this->assertEquals($objContent->user_id, $result->user_id);
            } else {
                $this->asserTrue(false);
            }
            
        }
    }
    
    public function testUpdateContentByCode()
    {
        $objContent = $this->getDummy();
        $result = $this->createContent($objContent);
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $objContent = $this->getDummy();
            $result = $this->getContainer()->call([$this->contentRepository, 'updateContentByCode'], ['request' => $objContent, 'code' => $result->code]);
            if ($result) {
                $this->assertEquals($objContent->title, $result->title);
                $this->assertEquals($objContent->code, $result->code);
                $this->assertEquals($objContent->description, $result->description);
                $this->assertEquals($objContent->keyword, $result->keyword);
                $this->assertEquals($objContent->og_title, $result->og_title);
                $this->assertEquals($objContent->og_description, $result->og_description);
                $this->assertEquals($objContent->default_image, $result->default_image);
                $this->assertEquals($objContent->status_id, $result->status_id);
                $this->assertEquals($objContent->language_id, $result->language_id);
                $this->assertEquals($objContent->publish_date, $result->publish_date);
                $this->assertEquals($objContent->additional_info, $result->additional_info);
                $this->assertEquals($objContent->content, $result->content);
                $this->assertEquals($objContent->time_zone_id, $result->time_zone_id);
                $this->assertEquals($objContent->owner_id, $result->owner_id);
                $this->assertEquals($objContent->user_id, $result->user_id);
                $this->assertTrue(true);
            } else {
                $this->assertTrue(false);
            }
        }
    }
    
    public function testUpdateContentStatusByCode()
    {
        $result = $this->testAddContent();
        if ($result == null) {
            $this->assertTrue(false);
        } else {
            $status_id = $this->getFaker()->numberBetween(1, 4);
            $resultUpdateStatus = $this->getContainer()->call([$this->contentRepository, 'updateContentStatusByCode'], ['code' => $result->code, 'status_id' => $status_id]);
            if ($resultUpdateStatus == null) {
                $this->assertTrue(false);
            } else {
                $this->assertTrue(true);
                self::assertEquals($status_id, $resultUpdateStatus->status_id);
            }
        }
    }
    
}