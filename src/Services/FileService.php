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
use WebAppId\Content\Services\Params\AddFileParam;

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
     * @param FileRepository $file
     * @return \Illuminate\Http\Response
     * @throws \Gumlet\ImageResizeException
     */
    public function index(string $name, FileRepository $file)
    {
        return $this->loadFile($name, '0', $file);
    }
    
    private function saveFile(string $path, $file, $upload,
                              MimeTypeRepository $mimeTypeRepository,
                              FileRepository $fileRepository,
                              AddFileParam $addFileParam)
    {
        $user_id = Auth::id() == null ? session('user_id') : Auth::id();
        
        $path = str_replace('../', '', $path);
        $filename = $file->store($path);
        $fileData = explode('/', $filename);
    
        $resultMimeType = $this->container->call([$mimeTypeRepository, 'getMimeByName'], ['name' => $file->getMimeType()]);
    
        if ($upload->description == null) {
            $upload->description = '';
        }
        if ($upload->alt == null) {
            $upload->alt = '';
        }
        $addFileParam->setName($fileData[1]);
        $addFileParam->setDescription($upload->description);
        $addFileParam->setAlt($upload->alt);
        $addFileParam->setPath($path);
        $addFileParam->setMimeTypeId($resultMimeType[0]->id);
        $addFileParam->setUserId($user_id);
        $addFileParam->setOwnerId($user_id);
    
        return $this->container->call([$fileRepository, 'addFile'], ['addFileParam' => $addFileParam]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @param $path
     * @param $upload
     * @param MimeTypeRepository $mimeTypeRepository
     * @param FileRepository $fileRepository
     * @return array
     */
    public function store(string $path,
                          $upload,
                          MimeTypeRepository $mimeTypeRepository,
                          FileRepository $fileRepository,
                          AddFileParam $addFileParam)
    {
        $result = array();
        if ($upload->photos == (Array)$upload->photos) {
            for ($i = 0; $i < count($upload->photos); $i++) {
                $result[$i] = $this->saveFile($path, $upload->photos[$i], $upload, $mimeTypeRepository, $fileRepository, $addFileParam);
            }
        } else {
            $result[0] = $this->saveFile($path, $upload->photos, $upload, $mimeTypeRepository, $fileRepository, $addFileParam);
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
        $fileData = $this->container->call([$file, 'getFileByName'], ['name' => $name]);
        if ($fileData != null && file_exists($path . $fileData->path . '/' . $fileData->name)) {
            $imageName = $fileData->name;
            $path .= $fileData->path;
            $mimeType = $fileData->mime->name;
        } else {
            $imageName = 'default.jpg';
            $mimeType = 'image/png';
            $path .= 'default';
        }
    
        if ($mimeType != 'image/svg+xml') {
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
        } else {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($path . '/' . $imageName) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($path . '/' . $imageName));
            readfile($path . '/' . $imageName);
            exit;
        }
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