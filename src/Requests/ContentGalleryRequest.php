<?php

namespace WebAppId\Content\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContentGalleryRequest extends FormRequest{
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
            'content_id' => 'required'
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
            'content_id.required' => 'Content Id required'
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
            
        ];
    }
}