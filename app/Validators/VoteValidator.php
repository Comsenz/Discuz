<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Validators;

use Discuz\Foundation\AbstractValidator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class VoteValidator extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    protected function getRules()
    {
        return [
            'thread_id'     => 'filled|integer',
            'type'          => ['required|integer',Rule::in(['0', '1'])],
            'name'          => 'required|max:80',
            'start_at'      => 'filled|date',
            'end_at'        => 'required|date',
            'content'       => [
                'required|array|min:2',
                function ($attribute, $value, $fail) {
                    foreach ($value as $item) {
                        if (Str::length($item) > 450) {
                            $fail(trans('validation.lt.numeric', ['value'=>450]));
                        }
                    }
                }],
        ];
    }

    protected function getMessages()
    {
        return [];
    }
}
