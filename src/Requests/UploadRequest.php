<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UploadRequest
 * @package WebAppId\Content\Requests
 */
class UploadRequest extends FormRequest{
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
            'name' => 'required',
            'photo' => 'image|mimes:jpeg,bmp,png,jpg|size:40000'
        ];
    }
}