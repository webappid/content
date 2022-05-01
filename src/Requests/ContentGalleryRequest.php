<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Requests;

use WebAppId\SmartResponse\Requests\AbstractFormRequest;

/**
 * Class ContentGalleryRequest
 * @package WebAppId\Content\Requests
 */
class ContentGalleryRequest extends AbstractFormRequest
{
    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'content_id.required' => 'Content Id required'
        ];
    }


    /**
     * @return mixed
     */
    function rules(): array
    {
        return [
            'content_id' => 'required'
        ];
    }
}