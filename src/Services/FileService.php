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
use WebAppId\Content\Repositories\Requests\FileRepositoryRequest;
use WebAppId\Content\Repositories\Requests\MimeTypeRepositoryRequest;
use WebAppId\Content\Services\Contracts\FileServiceContract;
use WebAppId\Content\Tools\ImageResize;
use WebAppId\Content\Tools\SmartReadFile;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 25/04/20
 * Time: 14.31
 * Class FileService
 * @package WebAppId\Content\Services
 */
class FileService implements FileServiceContract
{

    /**
     * @inheritDoc
     */
    public function index(string $name, FileRepository $file, SmartReadFile $smartReadFile, bool $download = false)
    {
        return $this->loadFile($name, '0', $file, $smartReadFile, $download);
    }

    /**
     * @param string $path
     * @param $file
     * @param $upload
     * @param MimeTypeRepositoryRequest $mimeTypeRepositoryRequest
     * @param MimeTypeRepository $mimeTypeRepository
     * @param FileRepositoryRequest $fileRepositoryRequest
     * @param FileRepository $fileRepository
     * @return mixed
     */
    private function saveFile(string $path,
                              $file,
                              $upload,
                              MimeTypeRepositoryRequest $mimeTypeRepositoryRequest,
                              MimeTypeRepository $mimeTypeRepository,
                              FileRepositoryRequest $fileRepositoryRequest,
                              FileRepository $fileRepository)
    {
        $user_id = Auth::id() == null ? session('user_id') : Auth::id();

        $path = str_replace('../', '', $path);
        $filename = $file->store($path);
        $fileData = explode('/', $filename);

        $resultMimeType = app()->call([$mimeTypeRepository, 'getByName'], ['name' => $file->getMimeType()]);

        if ($resultMimeType == null) {
            $mimeTypeRepositoryRequest->name = $file->getMimeType();
            $mimeTypeRepositoryRequest->user_id = $user_id;
            $resultMimeType = app()->call([$mimeTypeRepository, 'store'], compact('mimeTypeRepositoryRequest'));
        }

        if ($upload->description == null) {
            $upload->description = '';
        }
        if ($upload->alt == null) {
            $upload->alt = '';
        }

        $fileRepositoryRequest->name = $fileData[1];
        $fileRepositoryRequest->description = $upload->description;
        $fileRepositoryRequest->alt = $upload->alt;
        $fileRepositoryRequest->path = $path;
        $fileRepositoryRequest->mime_type_id = $resultMimeType->id;
        $fileRepositoryRequest->user_id = $user_id;
        $fileRepositoryRequest->creator_id = $user_id;
        $fileRepositoryRequest->owner_id = $user_id;

        return app()->call([$fileRepository, 'store'], compact('fileRepositoryRequest'));
    }

    /**
     * @inheritDoc
     */
    public function store(string $path,
                          $upload,
                          MimeTypeRepositoryRequest $mimeTypeRepositoryRequest,
                          MimeTypeRepository $mimeTypeRepository,
                          FileRepositoryRequest $fileRepositoryRequest,
                          FileRepository $fileRepository)
    {
        $result[0] = $this->saveFile($path, $upload->upload_file, $upload, $mimeTypeRepositoryRequest, $mimeTypeRepository, $fileRepositoryRequest, $fileRepository);
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function storeMulti(string $path,
                               $upload,
                               MimeTypeRepositoryRequest $mimeTypeRepositoryRequest,
                               MimeTypeRepository $mimeTypeRepository,
                               FileRepositoryRequest $fileRepositoryRequest,
                               FileRepository $fileRepository)
    {
        for ($i = 0; $i < count($upload->upload_files); $i++) {
            $result[$i] = $this->saveFile($path, $upload->upload_file, $upload, $mimeTypeRepositoryRequest, $mimeTypeRepository, $fileRepositoryRequest, $fileRepository);
        }
    }


    /**
     * @param $name
     * @param $size
     * @param FileRepository $fileRepository
     * @param SmartReadFile $smartReadFile
     * @param bool $download
     * @return ResponseFactory|Response|void
     * @throws ImageResizeException
     */
    private function loadFile($name, $size, FileRepository $fileRepository, SmartReadFile $smartReadFile, bool $download = false)
    {
        $path = '';
        $fileData = app()->call([$fileRepository, 'getByName'], ['name' => $name]);

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

            return response()->stream(function () use ($image, $mimeType) {
                echo $image->output();
            }, 200,
                [
                    "Content-Type" => $mimeType,
                    'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                ]
            );
        } else {
            $smartReadFile->getFile($path . '/' . $imageName, $imageName);
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function get($name, $size, FileRepository $file, SmartReadFile $smartReadFile)
    {
        return $this->loadFile($name, $size, $file, $smartReadFile, false);
    }
}
