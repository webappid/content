<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Requests;

use WebAppId\SmartResponse\Requests\AbstractFormRequest;

/**
 * Class ContentRequest
 * @package WebAppId\Content\Requests
 */
class ContentRequest extends AbstractFormRequest
{
    
    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Content title required',
            'title.unique' => 'Content title should unique'
        ];
    }

    /**
     * @return array
     */
    function rules(): array
    {
        return [
            'title' => 'required|string|max:191',
            'description' => 'string|max:191',
            'content' => 'required|string',
            'keyword' => 'string',
            'status_id' => 'numeric',
            'categories' => 'array',
            'default_image' => 'int',
            'galleries' => 'array'
        ];
    }
}
