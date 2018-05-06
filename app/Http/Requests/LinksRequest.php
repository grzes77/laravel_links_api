<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LinksRequest extends FormRequest
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


    public function rules()
    {
        return [
            'url_short' => 'unique:links,url_short'
        ];
    }

    public function messages()
    {
        return [
            'url_short.unique' => 'istnieje taki link',

        ];
    }
}
