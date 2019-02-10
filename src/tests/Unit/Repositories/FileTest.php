<?php

namespace WebAppId\Content\Tests\Unit\Repositories;

use WebAppId\Content\Models\File;
use WebAppId\Content\Repositories\FileRepository;
use WebAppId\Content\Services\Params\AddFileParam;
use WebAppId\Content\Tests\TestCase;

class FileTest extends TestCase
{
    private $fileRepository;
    
    private function fileRepository(): FileRepository
    {
        if ($this->fileRepository == null) {
            $this->fileRepository = $this->getContainer()->make(FileRepository::class);
        }
        
        return $this->fileRepository;
    }
    
    public function createDummy(): AddFileParam
    {
        $addFileParam = new AddFileParam();
        $addFileParam->setName($this->getFaker()->word);
        $addFileParam->setDescription($this->getFaker()->text($maxNbChars = 200));
        $addFileParam->setAlt($this->getFaker()->word);
        $addFileParam->setPath('');
        $addFileParam->setMimeTypeId($this->getFaker()->numberBetween(1, 61));
        $addFileParam->setOwnerId(1);
        $addFileParam->setUserId(1);
        return $addFileParam;
    }
    
    public function createFile($dummyFile): ?File
    {
        $result = $this->getContainer()->call([$this->fileRepository(), 'addFile'], ['addFileParam' => $dummyFile]);
        if ($result == false) {
            $this->assertTrue(false);
            return null;
        } else {
            return $result;
        }
    }
    
    public function setUp(): void
    {
        parent::setUp();
    }
    
    public function testAddFile(): void
    {
        $dummyFile = $this->createDummy();
        $result = $this->createFile($dummyFile);
        if ($result != false) {
            $this->assertEquals($dummyFile->getName(), $result->name);
            $this->assertEquals($dummyFile->getDescription(), $result->description);
            $this->assertEquals($dummyFile->getAlt(), $result->alt);
            $this->assertEquals($dummyFile->getPath(), $result->path);
            $this->assertEquals($dummyFile->getMimeTypeId(), $result->mime_type_id);
        }
    }
    
    public function testGetFileByName(): void
    {
        $dummyFile = $this->createDummy();
        $result = $this->createFile($dummyFile);
        if ($result != false) {
            $resultFind = $this->getContainer()->call([$this->fileRepository(), 'getFileByName'], ['name' => $dummyFile->getName()]);
            if ($resultFind == null) {
                $this->assertTrue(false);
            } else {
                $this->assertEquals($dummyFile->getName(), $resultFind->name);
                $this->assertEquals($dummyFile->getDescription(), $resultFind->description);
                $this->assertEquals($dummyFile->getAlt(), $resultFind->alt);
                $this->assertEquals($dummyFile->getPath(), $resultFind->path);
                $this->assertEquals($dummyFile->getMimeTypeId(), $resultFind->mime_type_id);
            }
        }
    }
    
    public function testGetFileMimeTypeName(): void
    {
        $dummyFile = $this->createDummy();
        $result = $this->createFile($dummyFile);
        if ($result != false) {
            $resultFind = $this->getContainer()->call([$this->fileRepository(), 'getFileByName'], ['name' => $dummyFile->getName()]);
            if ($resultFind == null) {
                $this->assertTrue(false);
            } else {
                $this->assertTrue(true);
            }
        }
    }
}
