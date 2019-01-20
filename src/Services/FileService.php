<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Services;

use Gumlet\ImageResize;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Auth;
use WebAppId\Content\Repositories\FileRepository;
use WebAppId\Content\Repositories\MimeTypeRepository;

/**
 * Class FileService
 * @package WebAppId\Content\Services
 */
class FileService
{
    
    private $container;
    
    /**
     * FileService constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @param $name
     * @param int $size
     * @param FileRepository $file
     * @return \Illuminate\Http\Response
     * @throws \Gumlet\ImageResizeException
     */
    public function index($name, FileRepository $file, $size = 0)
    {
        return $this->loadFile($name, $size, $file);
    }
    
    private function saveFile($path, $file, $upload, $mimeTypeService, $fileRepository)
    {
        $user_id = Auth::id() == null ? session('user_id') : Auth::id();
        
        $path = str_replace('../', '', $path);
        $filename = $file->store($path);
        $fileData = explode('/', $filename);
        
        $resultMimeType = $this->container->call([$mimeTypeService, 'getMimeByName'], ['name' => $file->getMimeType()]);
        
        $objFile = new \StdClass;
        $objFile->name = $fileData[1];
        $objFile->description = $upload->description;
        $objFile->alt = $upload->alt;
        $objFile->path = $path;
        $objFile->mime_type_id = $resultMimeType[0]->id;
        $objFile->user_id = $user_id;
        $objFile->owner_id = $user_id;
        
        return $this->container->call([$fileRepository, 'addFile'], ['request' => $objFile]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @param $path
     * @param $upload
     * @param MimeTypeRepository $mimeTypeService
     * @param FileRepository $fileRepository
     * @return array
     */
    public function store($path, $upload, MimeTypeRepository $mimeTypeService, FileRepository $fileRepository)
    {
        $result = array();
        if ($upload->photos == (Array)$upload->photos) {
            for ($i = 0; $i < count($upload->photos); $i++) {
                $result[$i] = $this->saveFile($path, $upload->photos[$i], $upload, $mimeTypeService, $fileRepository);
            }
        } else {
            $result[0] = $this->saveFile($path, $upload->photos, $upload, $mimeTypeService, $fileRepository);
        }
        
        return $result;
    }
    
    /**
     * @param $name
     * @param $size
     * @param FileRepository $file
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Gumlet\ImageResizeException
     */
    private function loadFile($name, $size, $file)
    {
        $path = '../storage/app/';
        $fileData = $this->container->call([$file, 'getFileByName'], ['name', $name]);
        if (count($fileData) > 0 && file_exists($path . $fileData[0]->path . '/' . $fileData[0]->name)) {
            $imageName = $fileData[0]->name;
            $path .= $fileData[0]->path;
            $mimeType = $fileData[0]->mime;
        } else {
            $imageName = 'default.jpg';
            $mimeType = 'image/png';
            $path .= 'default';
        }
        
        $image = new ImageResize($path . '/' . $imageName);
        
        if ($size !== '0') {
            $sizeData = explode('x', $size);
            if ($sizeData[0] == $sizeData[1]) {
                $sourceWidth = $image->getSourceWidth();
                $sourceHeight = $image->getSourceHeight();
                if ($sourceWidth < $sourceHeight) {
                    $image->resizeToWidth($sizeData[0]);
                } else {
                    $image->resizeToHeight($sizeData[1]);
                }
            } else {
                $image->resizeToBestFit($sizeData[0], $sizeData[1]);
            }
        }
        return response($image->output())->header('Cache-Control', 'max-age=2592000')->header('Content-Type', $mimeType);
    }
    
    /**
     * Display the specified resource.
     *
     * @param $name
     * @param $size
     * @param FileRepository $file
     * @return \Illuminate\Http\Response
     * @throws \Gumlet\ImageResizeException
     */
    public function show($name, $size, FileRepository $file)
    {
        return $this->loadFile($name, $size, $file);
    }
}