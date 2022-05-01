<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-05
 * Time: 23:12
 */

namespace WebAppId\Content\Requests;

use WebAppId\SmartResponse\Requests\AbstractFormRequest;

class SearchRequest extends AbstractFormRequest
{
    function rules(): array
    {
        return [
            'q' => 'string|nullable|max:255',
            'search' => 'array|nullable|max:255',
        ];
    }
}
