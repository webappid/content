<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Requests;

use WebAppId\DDD\Requests\AbstractFormRequest;

/**
 * Class UploadMultiRequest
 * @package WebAppId\Content\Requests
 */
class UploadMultiRequest extends AbstractFormRequest
{
    
    /**
     * @return array
     */
    function rules(): array
    {
        return [
            'name' => 'required|string|max:191',
            'upload_files' => 'required|array'
        ];
    }
}