<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Validators;

use App\Censor\Censor;
use App\Censor\CensorNotPassedException;
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
            'type'          => ['required',Rule::in(['0', '1'])],
            'name'          => 'required|max:80',
            'start_at'      => 'filled|date',
            'end_at'        => 'required|date',
            'content'       => [
                'required',
                'array',
                'min:2',
                function ($attribute, $value, $fail) {
                    $censor = app()->make(Censor::class);
                    foreach ($value as $item) {
                        if (Str::length($item) > 450) {
                            $fail(trans('validation.lt.string', ['value'=>450]));
                        }

                        $censor->checkText($item);
                        if ($censor->isMod) {
                            throw new CensorNotPassedException('content_banned');
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
