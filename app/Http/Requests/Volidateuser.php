<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Volidateuser extends FormRequest
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
            'id' => 'required|unique:users',
            'code' => 'required',
            'pwd' => 'required',
            'pwd' =>array('regex:/^[\da-zA-Z]{6,18}$/'),
        ];
    }
    public function message()
    {
        return [
            'id.required' => '账号不能为空',
            'id.unique' => '账号已存在',
            'code.required' => '验证码不能为空',
            'pwd.required' => '密码不能为空',
            'pwd.regex' => '密码为6-18位数字或字母组成',
        ];
    }
}
