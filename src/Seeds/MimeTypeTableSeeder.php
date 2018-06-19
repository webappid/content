<?php

namespace WebAppId\Content\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use WebAppId\Content\Models\MimeType;

class MimeTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_id = '1';

        $data = [
            [
                'name' => 'audio/aac',
            ],
            [
                'name' => 'application/x-abiword',
            ],
            [
                'name' => 'application/octet-stream',
            ],
            [
                'name' => 'video/x-msvideo',
            ],
            [
                'name' => 'application/vnd.amazon.ebook',
            ],
            [
                'name' => 'application/octet-stream',
            ],
            [
                'name' => 'application/x-bzip',
            ],
            [
                'name' => 'application/x-bzip2',
            ],
            [
                'name' => 'application/x-csh',
            ],
            [
                'name' => 'text/css',
            ],
            [
                'name' => 'text/csv',
            ],
            [
                'name' => 'application/msword',
            ],
            [
                'name' => 'application/vnd.ms-fontobject',
            ],
            [
                'name' => 'application/epub+zip',
            ],
            [
                'name' => 'image/gif',
            ],
            [
                'name' => 'text/html',
            ],
            [
                'name' => 'image/x-icon',
            ],
            [
                'name' => 'text/calendar',
            ],
            [
                'name' => 'application/java-archive',
            ],
            [
                'name' => 'image/jpeg',
            ],
            [
                'name' => 'application/javascript',
            ],
            [
                'name' => 'application/json',
            ],
            [
                'name' => 'audio/midi',
            ],
            [
                'name' => 'video/mpeg',
            ],
            [
                'name' => 'application/vnd.apple.installer+xml',
            ],
            [
                'name' => 'application/vnd.oasis.opendocument.presentation',
            ],
            [
                'name' => 'application/vnd.oasis.opendocument.spreadsheet',
            ],
            [
                'name' => 'application/vnd.oasis.opendocument.text',
            ],
            [
                'name' => 'audio/ogg',
            ],
            [
                'name' => 'video/ogg',
            ],
            [
                'name' => 'application/ogg',
            ],
            [
                'name' => 'font/otf',
            ],
            [
                'name' => 'image/png',
            ],
            [
                'name' => 'application/pdf',
            ],
            [
                'name' => 'application/vnd.ms-powerpoint',
            ],
            [
                'name' => 'application/x-rar-compressed',
            ],
            [
                'name' => 'application/rtf',
            ],
            [
                'name' => 'application/x-sh',
            ],
            [
                'name' => 'image/svg+xml',
            ],
            [
                'name' => 'application/x-shockwave-flash',
            ],
            [
                'name' => 'application/x-tar',
            ],
            [
                'name' => 'image/tiff',
            ],
            [
                'name' => 'application/typescript',
            ],
            [
                'name' => 'font/ttf',
            ],
            [
                'name' => 'application/vnd.visio',
            ],
            [
                'name' => 'audio/x-wav',
            ],
            [
                'name' => 'audio/webm',
            ],
            [
                'name' => 'video/webm',
            ],
            [
                'name' => 'image/webp',
            ],
            [
                'name' => 'font/woff',
            ],
            [
                'name' => 'font/woff2',
            ],
            [
                'name' => 'application/xhtml+xml',
            ],
            [
                'name' => 'application/vnd.ms-excel',
            ],
            [
                'name' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ],
            [
                'name' => 'application/xml',
            ],
            [
                'name' => 'application/vnd.mozilla.xul+xml',
            ],
            [
                'name' => 'application/zip',
            ],
            [
                'name' => 'video/3gpp',
            ],
            [
                'name' => 'audio/3gpp',
            ],
            [
                'name' => 'video/3gpp2',
            ],
            [
                'name' => 'audio/3gpp2',
            ],
            [
                'name' => 'application/x-7z-compressed',
            ],
        ];
        DB::beginTransaction();
        $return = true;
        foreach ($data as $key) {
            $request = new \stdClass;
            $request->name = $key['name'];
            $request->user_id = $user_id;

            $mime_type = new MimeType;
            if (count($mime_type->getMimeByName($request->name)) == 0) {
                $result = $mime_type->addMimeType($request);
                if (!$result) {
                    $return = $result;
                    break;
                }
            }
        }

        if ($return) {
            DB::commit();
        } else {
            DB::rollBack();
        }
    }
}
