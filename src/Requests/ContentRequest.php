<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\Content\Requests;

use WebAppId\SmartResponse\Requests\AbstractFormRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 10/05/2020
 * Time: 15.29
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
