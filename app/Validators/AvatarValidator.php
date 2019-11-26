<?php


namespace App\Validators;


use Discuz\Foundation\AbstractValidator;

class AvatarValidator extends AbstractValidator
{

    /**
     * @return array
     */
    protected function getRules()
    {
        return [
            'avatar' => [
                'required',
                'mimes:jpeg,png,bmp,gif',
                'max:5120'
            ]
        ];
    }
}
