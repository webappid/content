<?php

namespace WebAppId\Content\Presenters;

use WebAppId\Content\Repositories\FileRepository;
use WebAppId\Content\Repositories\MimeTypeRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Container\Container;

use \Gumlet\ImageResize;

class FilePresenter
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

    private function saveFile($path, $file, $upload, $mimeTypePresenter, $fileRepository){
        $user_id = Auth::id()==null?session('user_id'):Auth::id();

        $path = str_replace('../','',$path);
        $filename = $file->store($path);
        $fileData = explode('/',$filename);

        $resultMimeType = $this->container->call([$mimeTypePresenter,'getMimeByName'],['name' => $file->getMimeType()]);
        
        $objFile = new \StdClass;
        $objFile->name          = $fileData[1];
        $objFile->description   = $upload->description;
        $objFile->alt           = $upload->alt;
        $objFile->path          = $path;
        $objFile->mime_type_id  = $resultMimeType[0]->id;
        $objFile->user_id       = $user_id;
        $objFile->owner_id      = $user_id;

        $status = $this->container->call([$fileRepository,'addFile'],['request' => $objFile]);
        return $status;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store($path, $upload, MimeTypeRepository $mimeTypePresenter, FileRepository $fileRepository)
    {
        $status = array();
        if($upload->photos == (Array)$upload->photos){
            for ($i=0; $i < count($upload->photos); $i++) { 
                $status[$i] = $this->saveFile($path, $upload->photos[$i], $upload, $mimeTypePresenter, $fileRepository);
            }
        }else{
            $status[0] = $this->saveFile($path, $upload->photos, $upload, $mimeTypePresenter, $fileRepository);
        }
        
        if(count($status)>0){
            $result['code'] = '201';
            $result['message'] = 'Upload File Success';
            $result['data']['images'] = $status;
            return $result;
        }else{
            $result['code'] = '406';
            $result['message'] = 'Save Data Failed';
            $result['data']['images'] = $status;
            return $result;
        }
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