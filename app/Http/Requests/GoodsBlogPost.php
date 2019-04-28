<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoodsBlogPost extends FormRequest
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
        $id=request()->id;

        return [
            'uname'=>[
                'required',
                'unique:goods,uname,'.$id,
                'max:30',
                'min:3'
                ]
        ];
    }

    public function messages()
    {
        return[
            'uname.required'=>'用户名不能为空',
            'uname.unique'=>'用户名存在',
            'uname.max'=>'用户名不能超过30字符',
            'uname.min'=>'用户名不得少于3字符'
        ];
    }



}
