<?php

namespace WebAppId\Content\Services;

use WebAppId\Content\Repositories\FileRepository;
use WebAppId\Content\Repositories\MimeTypeRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Container\Container;

use \Gumlet\ImageResize;

class FileService
{

    private $container;

    public function __construct(Container $container){
        $this->container = $container;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($name, File $file)
    {
        return $this->loadFile($name, '0', $file);
    }

    private function saveFile($path, $file, $upload, $mimeTypeService, $fileRepository){
        $user_id = Auth::id()==null?session('user_id'):Auth::id();

        $path = str_replace('../','',$path);
        $filename = $file->store($path);
        $fileData = explode('/',$filename);

        $resultMimeType = $this->container->call([$mimeTypeService,'getMimeByName'],['name' => $file->getMimeType()]);
        
        $objFile = new \StdClass;
        $objFile->name          = $fileData[1];
        $objFile->description   = $upload->description;
        $objFile->alt           = $upload->alt;
        $objFile->path          = $path;
        $objFile->mime_type_id  = $resultMimeType[0]->id;
        $objFile->user_id       = $user_id;
        $objFile->owner_id      = $user_id;

        $result = $this->container->call([$fileRepository,'addFile'],['request' => $objFile]);
        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store($path, $upload, MimeTypeRepository $mimeTypeService, FileRepository $fileRepository)
    {
        $result = array();
        if($upload->photos == (Array)$upload->photos){
            for ($i=0; $i < count($upload->photos); $i++) { 
                $result[$i] = $this->saveFile($path, $upload->photos[$i], $upload, $mimeTypeService, $fileRepository);
            }
        }else{
            $result[0] = $this->saveFile($path, $upload->photos, $upload, $mimeTypeService, $fileRepository);
        }

        return $result;
    }

    private function loadFile($name, $size, $file){
        $path = '../storage/app/';
        $fileData = $file->getFileByName($name);
        if(count($fileData) > 0 && file_exists($path.$fileData[0]->path .'/'. $fileData[0]->name)){
            $imageName  = $fileData[0]->name;
            $path      .= $fileData[0]->path;
            $mimeType   = $fileData[0]->mime;
        }else{
            $imageName  = 'default.jpg';
            $mimeType   = 'image/png';
            $path      .= 'default';
        }

        $image = new ImageResize($path .'/'. $imageName);

        if($size!=='0'){
            $sizeData = explode('x', $size);
            if($sizeData[0] == $sizeData[1]){
                $sourceWidth = $image->getSourceWidth();
                $sourceHeight = $image->getSourceHeight();
                if($sourceWidth<$sourceHeight){
                    $image->resizeToWidth($sizeData[0]);
                }else{
                    $image->resizeToHeight($sizeData[1]);
                }
            }else{
                $image->resizeToBestFit($sizeData[0], $sizeData[1]);
            }
        }
        return response($image->output())->header('Cache-Control','max-age=2592000')->header('Content-Type', $mimeType);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Loketics\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function show($name, $size, File $file)
    {
        return $this->loadFile($name, $size, $file);
    }
}