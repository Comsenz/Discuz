<?php
namespace App\Validators;


use Discuz\Foundation\AbstractValidator;

class UserValidator extends AbstractValidator
{


    protected function getRules()
    {
        return [
            'username' => [
                'required',
                'regex:/^[A-Za-z0-9_\x{4e00}-\x{9fa5}]+$/u',
                'min:3',
                'max:15',
                'unique:users'
            ],
            'password' => [
                'required',
                'regex:/^.*(?=.*\d)(?=.*[A-Z])(?=.*[a-z]).*$/',
                'min:6',
                'max:16'
            ],
            'loginusername' => [
                'required',
                'exists:users,username'
            ],
            'loginpwd' => [
                'required'
            ],
            'mobile' => [
                'unique:users,mobile'
            ]
        ];
    }

    protected function getMessages()
    {
        return [
            'username.regex' => '不能有特殊字符',
        ];
    }
}