<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-10
 * Time: 23:02
 */

namespace WebAppId\Content\Tests\Unit\Repositories;


use WebAppId\Content\Models\MimeType;
use WebAppId\Content\Repositories\MimeTypeRepository;
use WebAppId\Content\Services\Params\AddMimeTypeParam;
use WebAppId\Content\Tests\TestCase;

class MimeTypeTest extends TestCase
{
    private $mimeTypeRepository;
    
    private function mimeTypeRepository(): MimeTypeRepository
    {
        if ($this->mimeTypeRepository == null) {
            $this->mimeTypeRepository = new MimeTypeRepository();
        }
        
        return $this->mimeTypeRepository;
    }
    
    public function createDummy()
    {
        $addMimeTypeParam = new AddMimeTypeParam();
        $addMimeTypeParam->setName($this->getFaker()->text($maxNbChars = 20));
        $addMimeTypeParam->setUserId(1);
        return $addMimeTypeParam;
    }
    
    public function createAddMimeType(AddMimeTypeParam $addMimeTypeParam): ?MimeType
    {
        return $this->getContainer()->call([$this->mimeTypeRepository(), 'addMimeType'], ['addMimeTypeParam' => $addMimeTypeParam]);
    }
    
    
    public function testAddMimeType(): ?MimeType
    {
        $dummy = $this->createDummy();
        $result = $this->createAddMimeType($dummy);
        self::assertNotEquals(null, $result);
        return $result;
    }
    
    public function testGetMimeTypeById()
    {
        $mimeType = $this->testAddMimeType();
        $result = $this->getContainer()->call([$this->mimeTypeRepository(), 'getOne'], ['id' => $mimeType->id]);
        self::assertNotEquals(null, $result);
    }
    
    public function testGetMimeTypeByName()
    {
        $mimeType = $this->testAddMimeType();
        $result = $this->getContainer()->call([$this->mimeTypeRepository(), 'getMimeByName'], ['name' => $mimeType->name]);
        self::assertNotEquals(null, $result);
    }
}