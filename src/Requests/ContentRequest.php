<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Requests;

use WebAppId\DDD\Requests\AbstractFormRequest;

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
            'title.unique' => 'Content title should unique',
            'description.required' => 'Content description required',
            'content.required' => 'Content should required'
        ];
    }

    /**
     * @return array
     */
    function rules(): array
    {
        return [
            'title' => 'required|string|max:191|unique:contents,title',
            'description' => 'required|string|max:191',
            'content' => 'required|string'
        ];
    }
}
