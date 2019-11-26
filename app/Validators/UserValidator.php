<?php
namespace App\Validators;


use App\Models\User;
use Discuz\Foundation\AbstractValidator;

class UserValidator extends AbstractValidator
{

    protected $user;

    public function setUser(User $user)
    {
        $this->user = $user;
    }


    protected function getRules()
    {
        $rules = [
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
                'max:16',
            ],
        ];

        if($this->user) {
            $rules['password'][] = 'confirmed';
        }

        return $rules;
    }

    protected function getMessages()
    {
        return [
            'username.regex' => '不能有特殊字符',
        ];
    }
}
