<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Thread;

use App\Events\Thread\Created;
use App\Events\Thread\Saving;
use App\Models\Thread;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Validation\Factory as Validator;
use Illuminate\Support\Arr;

class ThreadVideoListener
{
    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @param Validator $validator
     * @param SettingsRepository $settings
     */
    public function __construct(Validator $validator, SettingsRepository $settings)
    {
        $this->validator = $validator;
        $this->settings = $settings;
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(Saving::class, [$this, 'whenThreadSaving']);
        $events->listen(Created::class, SaveVideoToDatabase::class);
    }

    /**
     * @param Saving $event
     */
    public function whenThreadSaving(Saving $event)
    {
        if (Arr::get($event->data, 'attributes.type') == Thread::TYPE_OF_VIDEO) {
            $this->validator->make(
                [
                    'switch' => (bool) $this->settings->get('qcloud_vod', 'qcloud'),
                    'file_id' => Arr::get($event->data, 'attributes.file_id', ''),
                    'file_name' => Arr::get($event->data, 'attributes.file_id', ''),
                ],
                [
                    'switch' => function ($attribute, $value, $fail) {
                        $value ?: $fail(trans('validation.qcloud_vod'));
                    },
                    'file_id' => 'required',
                    'file_name' => 'required',
                ]
            )->validate();
        }
    }
}
