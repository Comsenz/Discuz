<?php


namespace App\Validators;


use Discuz\Foundation\AbstractValidator;

class GroupValidator extends AbstractValidator
{

    protected function getRules()
    {
        return [
            'name' => ['required']
        ];
    }

    protected function getMessages()
    {
        return [
            'name.required' => '名称不能为空'
        ];
    }
}
