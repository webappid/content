<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Repositories\ContentRepository;
use WebAppId\Content\Tests\TestCase;

use Illuminate\Container\Container;

class ContentTest extends TestCase
{

    private $content;

    private $container;

    public function setUp()
    {
        $this->container = new Container;
        $this->content = $this->container->make(ContentRepository::class);
        parent::setUp();
    }

    public function getDummy()
    {
        $objContent = new \StdClass;
        $faker = $this->getFaker();
        $objContent->title = $faker->word;
        $objContent->code = $faker->word;
        $objContent->description = $faker->text($maxNbChars = 190);
        $objContent->keyword = $faker->word;
        $objContent->og_title = $faker->word;
        $objContent->og_description = $faker->text;
        $objContent->default_image = '1';
        $objContent->status_id = '1';
        $objContent->language_id = '1';
        $objContent->publish_date = $faker->date($format = 'Y-m-d', $max = 'now');
        $objContent->additional_info = $faker->text;
        $objContent->content = $faker->text;
        $objContent->time_zone_id = '1';
        $objContent->owner_id = '1';
        $objContent->user_id = '1';
        $objContent->creator_id = '1';

        return $objContent;
    }

    public function createContent($dummy)
    {
        return $this->container->call([$this->content,'addContent'],['data'=>$dummy]);
    }

    /**
     * unit test method for add content
     *
     * @return void
     */
    public function testAddContent()
    {
        $result = $this->createContent($this->getDummy());
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
    }

    public function testBulkAddContentCount()
    {
        for ($n = 0; $n < 10; $n++) {
            $dummyData          = $this->getDummy();
            $dummyData->keyword = 'bulk';

            $this->container->call([$this->content,'addContent'],['data'=>$dummyData]);
        }

        $this->assertEquals(10, 
        $this->container->call([$this->content,'getContentByKeywordCount'],['keyword'=>$dummyData->keyword]));
    }

    public function testGetContentByCode()
    {
        $objContent = $this->getDummy();
        $result = $this->createContent($objContent);
        if (!$result) {
            $this->assertTrue(false);
        } else {
            $result = $this->container->call([$this->content,'getContentByCode'], ["code"=>$result->code , "request"=>$result]);
            if ($result != null) {
                $this->assertTrue(true);
                $this->assertEquals($objContent->title,$result->title);
                $this->assertEquals($objContent->code,$result->code);
                $this->assertEquals($objContent->description,$result->description);
                $this->assertEquals($objContent->keyword,$result->keyword);
                $this->assertEquals($objContent->og_title,$result->og_title);
                $this->assertEquals($objContent->og_description,$result->og_description);
                $this->assertEquals($objContent->default_image,$result->default_image);
                $this->assertEquals($objContent->status_id,$result->status_id);
                $this->assertEquals($objContent->language_id,$result->language_id);
                $this->assertEquals($objContent->publish_date,$result->publish_date);
                $this->assertEquals($objContent->additional_info,$result->additional_info);
                $this->assertEquals($objContent->content,$result->content);
                $this->assertEquals($objContent->time_zone_id,$result->time_zone_id);
                $this->assertEquals($objContent->owner_id,$result->owner_id);
                $this->assertEquals($objContent->user_id,$result->user_id);
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
            $result = $this->container->call([$this->content,'updateContentByCode'],['request'=>$objContent, 'code' => $result->code]);
            if ($result) {
                $this->assertEquals($objContent->title,$result->title);
                $this->assertEquals($objContent->code,$result->code);
                $this->assertEquals($objContent->description,$result->description);
                $this->assertEquals($objContent->keyword,$result->keyword);
                $this->assertEquals($objContent->og_title,$result->og_title);
                $this->assertEquals($objContent->og_description,$result->og_description);
                $this->assertEquals($objContent->default_image,$result->default_image);
                $this->assertEquals($objContent->status_id,$result->status_id);
                $this->assertEquals($objContent->language_id,$result->language_id);
                $this->assertEquals($objContent->publish_date,$result->publish_date);
                $this->assertEquals($objContent->additional_info,$result->additional_info);
                $this->assertEquals($objContent->content,$result->content);
                $this->assertEquals($objContent->time_zone_id,$result->time_zone_id);
                $this->assertEquals($objContent->owner_id,$result->owner_id);
                $this->assertEquals($objContent->user_id,$result->user_id);
                $this->assertTrue(true);
            } else {
                $this->asserTrue(false);
            }
        }
    }

    public function getContent(ContentRepository $content){
        return $content;
    }
}