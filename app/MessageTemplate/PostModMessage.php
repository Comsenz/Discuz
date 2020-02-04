<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate;

use Discuz\Foundation\Application;
use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Support\Str;

class PostModMessage extends DatabaseMessage
{
    protected $translator;

    public function __construct(Application $app)
    {
        $this->translator = $app->make('translator');
    }

    protected function getTitle()
    {
        return $this->translator->get('core.post_mod');
    }

    protected function getContent($data)
    {
        return $this->translator->get('core.post_order_change_detail', [
            'user' => $this->notifiable->username,
            'message' => Str::words($data['message'], 10)
        ]);
    }
}
