<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Models\Content;
use WebAppId\Content\Repositories\ContentRepository;
use WebAppId\Content\Services\Params\AddContentParam;
use WebAppId\Content\Tests\TestCase;

class ContentTest extends TestCase
{
    
    private $contentRepository;
    
    private function contentRepository(): ContentRepository
    {
        return $this->contentRepository = $this->getContainer()->make(ContentRepository::class);
    }
    
    public function setUp(): void
    {
        parent::setUp();
    }
    
    public function getDummy(): AddContentParam
    {
        $objContent = new AddContentParam();
        $objContent->setTitle($this->getFaker()->word);
        $objContent->setCode($this->getFaker()->uuid);
        $objContent->setDescription($this->getFaker()->text($maxNbChars = 190));
        $objContent->setKeyword($this->getFaker()->word);
        $objContent->setOgTitle($this->getFaker()->word);
        $objContent->setOgDescription($this->getFaker()->text);
        $objContent->setDefaultImage(1);
        $objContent->setStatusId(1);
        $objContent->setLanguageId(1);
        $objContent->setPublishDate($this->getFaker()->date($format = 'Y-m-d', $max = 'now'));
        $objContent->setAdditionalInfo($this->getFaker()->text);
        $objContent->setContent($this->getFaker()->text);
        $objContent->setTimeZoneId(1);
        $objContent->setOwnerId(1);
        $objContent->setUserId(1);
        $objContent->setCreatorId(1);
        
        return $objContent;
    }
    
    public function createContent($dummy): ?Content
    {
        return $this->getContainer()->call([$this->contentRepository(), 'addContent'], ['addContentParam' => $dummy]);
    }
    
    public function testAddContent(): ?Content
    {
        $result = $this->createContent($this->getDummy());
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
            return $result;
        }
    }
    
    public function testBulkAddContentCount(): void
    {
        for ($n = 0; $n < 10; $n++) {
            $dummyData = $this->getDummy();
            $dummyData->setKeyword('bulk');
    
            $this->getContainer()->call([$this->contentRepository(), 'addContent'], ['addContentParam' => $dummyData]);
        }
    
        $this->assertEquals(10, $this->getContainer()->call([$this->contentRepository(), 'getContentByKeywordCount'], ['keyword' => $dummyData->getKeyword()]));
    }
    
    public function testGetContentByCode(): void
    {
        $objContent = $this->getDummy();
        $result = $this->createContent($objContent);
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $result = $this->getContainer()->call([$this->contentRepository(), 'getContentByCode'], ["code" => $result->code, "request" => $result]);
            if ($result != null) {
                $this->assertTrue(true);
                $this->assertEquals($objContent->getTitle(), $result->title);
                $this->assertEquals($objContent->getCode(), $result->code);
                $this->assertEquals($objContent->getDescription(), $result->description);
                $this->assertEquals($objContent->getKeyword(), $result->keyword);
                $this->assertEquals($objContent->getOgTitle(), $result->og_title);
                $this->assertEquals($objContent->getOgDescription(), $result->og_description);
                $this->assertEquals($objContent->getDefaultImage(), $result->default_image);
                $this->assertEquals($objContent->getStatusId(), $result->status_id);
                $this->assertEquals($objContent->getLanguageId(), $result->language_id);
                $this->assertEquals($objContent->getPublishDate(), $result->publish_date);
                $this->assertEquals($objContent->getAdditionalInfo(), $result->additional_info);
                $this->assertEquals($objContent->getContent(), $result->content);
                $this->assertEquals($objContent->getTimeZoneId(), $result->time_zone_id);
                $this->assertEquals($objContent->getOwnerId(), $result->owner_id);
                $this->assertEquals($objContent->getUserId(), $result->user_id);
            } else {
                $this->asserTrue(false);
            }
            
        }
    }
    
    public function testUpdateContentByCode(): void
    {
        $objContent = $this->getDummy();
        $result = $this->createContent($objContent);
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $objContent = $this->getDummy();
            $result = $this->getContainer()->call([$this->contentRepository(), 'updateContentByCode'], ['addContentParam' => $objContent, 'code' => $result->code]);
            if ($result) {
                $this->assertEquals($objContent->getTitle(), $result->title);
                $this->assertEquals($objContent->getCode(), $result->code);
                $this->assertEquals($objContent->getDescription(), $result->description);
                $this->assertEquals($objContent->getKeyword(), $result->keyword);
                $this->assertEquals($objContent->getOgTitle(), $result->og_title);
                $this->assertEquals($objContent->getOgDescription(), $result->og_description);
                $this->assertEquals($objContent->getDefaultImage(), $result->default_image);
                $this->assertEquals($objContent->getStatusId(), $result->status_id);
                $this->assertEquals($objContent->getLanguageId(), $result->language_id);
                $this->assertEquals($objContent->getPublishDate(), $result->publish_date);
                $this->assertEquals($objContent->getAdditionalInfo(), $result->additional_info);
                $this->assertEquals($objContent->getContent(), $result->content);
                $this->assertEquals($objContent->getTimeZoneId(), $result->time_zone_id);
                $this->assertEquals($objContent->getOwnerId(), $result->owner_id);
                $this->assertEquals($objContent->getUserId(), $result->user_id);
                $this->assertTrue(true);
            } else {
                $this->assertTrue(false);
            }
        }
    }
    
    public function testUpdateContentStatusByCode(): void
    {
        $result = $this->testAddContent();
        if ($result == null) {
            $this->assertTrue(false);
        } else {
            $status_id = $this->getFaker()->numberBetween(1, 4);
            $resultUpdateStatus = $this->getContainer()->call([$this->contentRepository(), 'updateContentStatusByCode'], ['code' => $result->code, 'status_id' => $status_id]);
            if ($resultUpdateStatus == null) {
                $this->assertTrue(false);
            } else {
                $this->assertTrue(true);
                self::assertEquals($status_id, $resultUpdateStatus->status_id);
            }
        }
    }
    
}