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
                'regex:/^[a-z0-9_-]+$/i',
                'min:3',
                'max:15',
                'unique:users'
            ],
            'password' => [
                'required',
                'regex:/^.*(?=.*\d)(?=.*[A-Z])(?=.*[a-z]).*$/',
                'min:6',
                'max:16',
                'confirmed'
            ],
            'password_confirmation' => [
                'required',
                'same:password'
            ]
        ];
    }

    protected function getMessages()
    {
        return [
            'username.regex' => '用户名必须是英文数字下划线',
        ];
    }
}