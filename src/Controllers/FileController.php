<?php

namespace WebAppId\Content\Controllers;

use WebAppId\Content\Models\File;
use WebAppId\Content\Models\MimeType AS MimeTypeModel;
use WebAppId\Content\Controllers\Controller;
use WebAppId\Content\Requests\UploadRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use \Gumlet\ImageResize;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($name, File $file)
    {
        return $this->loadFile($name, '0', $file);
    }

    private function saveFile($path, $file, $upload, $mimeTypeModel){
        $user_id = Auth::id()==null?session('user_id'):Auth::id();

        $path = str_replace('../','',$path);
        $filename = $file->store($path);
        $fileData = explode('/',$filename);

        $resultMimeType = $mimeTypeModel->getMimeByName($file->getMimeType());
        
        $objFile = new \StdClass;
        $objFile->name          = $fileData[1];
        $objFile->description   = $upload->description;
        $objFile->alt           = $upload->alt;
        $objFile->path          = $path;
        $objFile->mime_type_id  = $resultMimeType[0]->id;
        $objFile->user_id       = $user_id;
        $objFile->owner_id      = $user_id;

        $file = new File;
        $status = $file->addFile($objFile);
        return $status;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($path, UploadRequest $upload, MimeTypeModel $mimeTypeModel)
    {
        $status = array();
        if($upload->photos == (Array)$upload->photos){
            for ($i=0; $i < count($upload->photos); $i++) { 
                $status[$i] = $this->saveFile($path, $upload->photos[$i], $upload, $mimeTypeModel);
            }
        }else{
            $status[0] = $this->saveFile($path, $upload->photos, $upload, $mimeTypeModel);
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
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Loketics\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Loketics\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Loketics\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        //
    }
}