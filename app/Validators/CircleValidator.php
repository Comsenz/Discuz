<?php


/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CircleValidator.php 28830 2019-10-11 11:10 chenkeke $
 */

namespace App\Validators;


use Discuz\Foundation\AbstractValidator;

class CircleValidator extends AbstractValidator
{
    protected function getRules()
    {
        return [
            'name' => [
                'required',
                'min:1',
                'max:10'
            ],
        ];
    }

    protected function getMessages()
    {
        return [
            'name.required' => '不能为空',
            'name.min' => '最少输入1个字',
            'name.max' => '最多输入10个字'
        ];
    }
}