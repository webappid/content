<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Services;

use Gumlet\ImageResizeException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use WebAppId\Content\Repositories\FileRepository;
use WebAppId\Content\Repositories\MimeTypeRepository;
use WebAppId\Content\Services\Params\AddFileParam;
use WebAppId\Content\Services\Params\AddMimeTypeParam;
use WebAppId\Content\Tools\ImageResize;
use WebAppId\Content\Tools\SmartReadFile;
use WebAppId\DDD\Services\BaseService;

/**
 * Class FileService
 * @package WebAppId\Content\Services
 */
class FileService extends BaseService
{

    /**
     * Display a listing of the resource.
     *
     * @param string $name
     * @param bool $download
     * @param FileRepository $file
     * @param SmartReadFile $smartReadFile
     * @return Response
     * @throws ImageResizeException
     */
    public function index(string $name, FileRepository $file, SmartReadFile $smartReadFile, bool $download = false)
    {
        return $this->loadFile($name, '0', $file, $smartReadFile, $download);
    }

    /**
     * @param string $path
     * @param $file
     * @param $upload
     * @param MimeTypeRepository $mimeTypeRepository
     * @param FileRepository $fileRepository
     * @param AddFileParam $addFileParam
     * @return mixed
     */
    private function saveFile(string $path, $file, $upload,
                              MimeTypeRepository $mimeTypeRepository,
                              FileRepository $fileRepository,
                              AddFileParam $addFileParam)
    {
        $user_id = Auth::id() == null ? session('user_id') : Auth::id();

        $path = str_replace('../', '', $path);
        $filename = $file->store($path);
        $fileData = explode('/', $filename);

        $resultMimeType = $this->getContainer()->call([$mimeTypeRepository, 'getMimeByName'], ['name' => $file->getMimeType()]);

        if ($resultMimeType == null) {
            $addMimeTypeParam = new AddMimeTypeParam();
            $addMimeTypeParam->setUserId($user_id);
            $addMimeTypeParam->setName($file->getMimeType());
            $resultMimeType = $this->getContainer()->call([$mimeTypeRepository, 'addMimeType'], ['addMimeTypeParam' => $addMimeTypeParam]);
        }

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
        $addFileParam->setMimeTypeId($resultMimeType->id);
        $addFileParam->setUserId($user_id);
        $addFileParam->setCreatorId($user_id);
        $addFileParam->setOwnerId($user_id);

        return $this->getContainer()->call([$fileRepository, 'addFile'], ['addFileParam' => $addFileParam]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param string $path
     * @param $upload
     * @param MimeTypeRepository $mimeTypeRepository
     * @param FileRepository $fileRepository
     * @param AddFileParam $addFileParam
     * @return array
     */
    public function store(string $path,
                          $upload,
                          MimeTypeRepository $mimeTypeRepository,
                          FileRepository $fileRepository,
                          AddFileParam $addFileParam)
    {
        $result[0] = $this->saveFile($path, $upload->upload_file, $upload, $mimeTypeRepository, $fileRepository, $addFileParam);
        return $result;
    }

    /**
     * @param string $path
     * @param $upload
     * @param MimeTypeRepository $mimeTypeRepository
     * @param FileRepository $fileRepository
     * @param AddFileParam $addFileParam
     */
    public function storeMulti(string $path,
                               $upload,
                               MimeTypeRepository $mimeTypeRepository,
                               FileRepository $fileRepository,
                               AddFileParam $addFileParam)
    {
        for ($i = 0; $i < count($upload->upload_files); $i++) {
            $result[$i] = $this->saveFile($path, $upload->upload_files[$i], $upload, $mimeTypeRepository, $fileRepository, $addFileParam);
        }
    }


    /**
     * @param $name
     * @param $size
     * @param FileRepository $file
     * @param bool $download
     * @param SmartReadFile $smartReadFile
     * @return ResponseFactory|Response|void
     * @throws ImageResizeException
     */
    private function loadFile($name, $size, $file, SmartReadFile $smartReadFile, bool $download = false)
    {
        $path = '';
        $fileData = $this->getContainer()->call([$file, 'getFileByName'], ['name' => $name]);

        if ($fileData != null && Storage::exists($fileData->path . '/' . $fileData->name)) {
            $imageName = $fileData->name;
            $path .= $fileData->path;
            $mimeType = $fileData->mime->name;
        } else {
            $imageName = 'default.jpg';
            $mimeType = 'image/png';
            $path .= 'default';
        }
        $isImage = strpos($mimeType, 'image') !== false;
        if ($isImage && !$download) {
            $image = new ImageResize(storage_path() . '/app/' . $path . '/' . $imageName);

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
            return $smartReadFile->getFile($path . '/' . $imageName, $imageName);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $name
     * @param $size
     * @param FileRepository $file
     * @param SmartReadFile $smartReadFile
     * @return Response
     * @throws ImageResizeException
     */
    public function show($name, $size, FileRepository $file, SmartReadFile $smartReadFile)
    {
        return $this->loadFile($name, $size, $file, $smartReadFile, false);
    }
}
