<?php

namespace WebAppId\Content\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:categories|191'
        ];
    }

    /**
     *  Custom error message.
     *
     * @return array
     */

    public function messages()
    {
        return [
            'name.required' => 'Cateogory name required',
            'name.unique' => 'Cateogory name should unique'
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
}