<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Requests;

use WebAppId\SmartResponse\Requests\AbstractFormRequest;

/**
 * Class CategoryRequest
 * @package WebAppId\Content\Requests
 */
class CategoryRequest extends AbstractFormRequest
{
    
    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Category name required',
            'name.unique' => 'Category name should unique'
        ];
    }
    
    /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'name' => 'trim|escape'
        ];
    }

    /**
     * @return mixed
     */
    function rules(): array
    {
        return ['name' => 'required|unique:categories|191'];
    }
}