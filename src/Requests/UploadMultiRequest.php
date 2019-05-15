<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UploadMultiRequest
 * @package WebAppId\Content\Requests
 */
class UploadMultiRequest extends FormRequest
{
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
            'name' => 'required|string|max:191',
            'upload_files' => 'required|array'
        ];
    }
}