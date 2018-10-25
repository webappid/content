<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Repositories\FileRepository;
use WebAppId\Content\Tests\TestCase;

use Illuminate\Container\Container;

class FileTest extends TestCase
{
    private $objFile;

    private $file;

    private $container;

    public function createDummy()
    {
        $faker = $this->getFaker();
        $this->objFile->name = $faker->word;
        $this->objFile->description = $faker->text($maxNbChars = 200);
        $this->objFile->alt = $faker->word;
        $this->objFile->path = '';
        $this->objFile->mime_type_id = $faker->numberBetween(1, 61);
        $this->objFile->owner_id = '1';
        $this->objFile->user_id = '1';
        return $this->objFile;
    }

    public function createFile()
    {
        $this->createDummy();
        $result = $this->container->call([$this->file,'addFile'],['request' => $this->objFile]);
        if ($result == false) {
            $this->assertTrue(false);
        } else {
            return $result;
        }
    }

    public function setUp()
    {
        parent::setUp();
        $this->container = new Container;
        $this->file = $this->container->make(FileRepository::class);
        $this->objFile = new \StdClass;
    }

    public function testAddFile()
    {
        $result = $this->createFile();
        if ($result != false) {
            $this->assertEquals($this->objFile->name, $result->name);
            $this->assertEquals($this->objFile->description, $result->description);
            $this->assertEquals($this->objFile->alt, $result->alt);
            $this->assertEquals($this->objFile->path, $result->path);
            $this->assertEquals($this->objFile->mime_type_id, $result->mime_type_id);
        }
    }

    public function testGetFileByName()
    {
        $result = $this->createFile();
        if ($result != false) {
            $resultFind = $this->container->call([$this->file,'getFileByName'],['name' => $this->objFile->name]);
            if ($resultFind == null) {
                $this->assertTrue(false);
            } else {
                $this->assertEquals($this->objFile->name, $resultFind->name);
                $this->assertEquals($this->objFile->description, $resultFind->description);
                $this->assertEquals($this->objFile->alt, $resultFind->alt);
                $this->assertEquals($this->objFile->path, $resultFind->path);
                $this->assertEquals($this->objFile->mime_type_id, $resultFind->mime_type_id);
            }
        }
    }

    public function testGetFileMimeTypeName()
    {
        $result = $this->createFile();
        if ($result != false) {
            $resultFind = $this->container->call([$this->file,'getFileByName'],['name' => $this->objFile->name]);
            if ($resultFind == null) {
                $this->assertTrue(false);
            } else {
                $this->assertTrue(true);
            }
        }
    }
}
