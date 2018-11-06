<?php

namespace WebAppId\Content\Tests\Feature;

use WebAppId\Content\Repositories\CategoryRepository;
use WebAppId\Content\Tests\TestCase;
use WebAppId\Content\Tests\Unit\Repositories\ContentTest;
use Illuminate\Container\Container;
use Illuminate\Http\UploadedFile;

class ContentFeatureTest extends TestCase
{
    
    private $contentDummy;
    private $categoryData;
    private $contentTest;
    
    private $container;
    
    private function createContentDummy()
    {
        $category = $this->container->make(CategoryRepository::class);
        $this->categoryData = $this->container->call([$category, 'getOne'], ['id' => 1]);
        
        $this->contentDummy = $this->contentTest->getDummy();
        $this->contentDummy->category_id = $this->categoryData->id;
    }
    
    public function setUp()
    {
        parent::setUp();
        $this->contentTest = new ContentTest();
        $this->contentTest->setup();
        $this->container = new Container;
        $this->createContentDummy();
        
    }
    
    public function testAddContentOnly()
    {
        $response = $this->withSession(['timezone' => 'Asia/Jakarta'])->post($this->prefix_route . '/content/store', (Array)$this->contentDummy);
        $this->assertEquals(200, $response->status());
    }
    
    public function testAddContentChild()
    {
        $originalContent = $this->contentDummy;
        
        $this->createContentDummy();
        $childContent = $this->contentDummy;
        
        $response = $this->withSession(['timezone' => 'Asia/Jakarta'])->post($this->prefix_route . '/content/store', (Array)$originalContent);
        
        $content = json_decode($response->baseResponse->getContent(), true);
        $childContent->parent_id = $content["content"]["id"];
        
        $response = $this->withSession(['timezone' => 'Asia/Jakarta'])->post($this->prefix_route . '/content/store', (Array)$childContent);
        
        $this->assertEquals(200, $response->status());
    }
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetContent()
    {
        $response = $this->withSession(['timezone' => 'Asia/Jakarta'])->post($this->prefix_route . '/content/store', (Array)$this->contentDummy);
        
        $response = $this->get($this->prefix_route . '/content?category=' . $this->categoryData->name);
        $this->assertEquals(200, $response->status());
    }
    
    public function testGetEditContent()
    {
        $resultContent = $this->withSession(['timezone' => 'Asia/Jakarta'])->post($this->prefix_route . '/content/store', (Array)$this->contentDummy);
        $content = json_decode($resultContent->baseResponse->getContent(), true);
        $response = $this->get($this->prefix_route . '/content/edit/' . $content['content']['code']);
        $this->assertEquals(200, $response->status());
    }
    
    public function testUpdateContent()
    {
        $originalContent = $this->contentDummy;
        $this->createContentDummy();
        $resultContent = $this->withSession(['timezone' => 'Asia/Jakarta'])->post($this->prefix_route . '/content/store', (Array)$originalContent);
        
        if ($resultContent->status() != 200) {
            dd($resultContent);
        }
        
        $content = json_decode($resultContent->baseResponse->getContent(), true);
        
        $response = $this->get($this->prefix_route . '/content/edit/' . $content['content']['code']);
        
        $content = json_decode($response->baseResponse->getContent(), true);
        
        $content = (Object)$content['content'];
        
        $this->contentDummy->code = $content->code;
        
        $response = $this->get($this->prefix_route . '/content/edit/' . $content->code);
        
        $response = $this->post($this->prefix_route . '/content/update/' . $content->code, (Array)$this->contentDummy);
        
        $this->assertEquals(200, $response->status());
    }
    
    public function testDeleteContent()
    {
        $resultContent = $this->withSession(['timezone' => 'Asia/Jakarta'])->post($this->prefix_route . '/content/store', (Array)$this->contentDummy);
        if ($resultContent->status() != 200) {
            dd($resultContent->status);
        }
        
        $content = json_decode($resultContent->baseResponse->getContent(), true);
        $response = $this->withSession(['timezone' => 'Asia/Jakarta'])->get($this->prefix_route . '/content/delete/' . $content['content']['code']);
        $this->assertEquals(200, $response->status());
    }
    
    public function testGetDetailContent()
    {
        $resultContent = $this->withSession(['timezone' => 'Asia/Jakarta'])->post($this->prefix_route . '/content/store', (Array)$this->contentDummy);
        $content = json_decode($resultContent->baseResponse->getContent(), true);
        
        $this->createContentDummy();
        $childContent = $this->contentDummy;
        $childContent->parent_id = $content["content"]["id"];
        
        $response = $this->withSession(['timezone' => 'Asia/Jakarta'])->post($this->prefix_route . '/content/store', (Array)$childContent);
        
        $file = array('photos' => UploadedFile::fake()->image('file.png', 600, 600), 'name' => 'file.png', 'content_id' => $content['content']['id']);
        $response = $this->withSession(['timezone' => 'Asia/Jakarta'])->post($this->prefix_route . '/content/gallery/tmp', $file);
        
        $response = $this->get($this->prefix_route . '/content/detail/' . $content['content']['code']);
        $content = json_decode($response->baseResponse->getContent(), true);
        
        $this->assertEquals(200, $response->status());
    }
}
