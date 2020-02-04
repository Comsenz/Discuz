<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate;

use Discuz\Foundation\Application;
use Discuz\Notifications\Messages\DatabaseMessage;

class GroupMessage extends DatabaseMessage
{
    protected $translator;

    public function __construct(Application $app)
    {
        $this->translator = $app->make('translator');
    }

    protected function getTitle()
    {
        return $this->translator->get('core.group_change');
    }

    protected function getContent($data)
    {
        $oldGroup = $data['old'];
        $newGroup = $data['new'];

        return $this->translator->get(
            'core.group_change_detail',
            [
                'user' => $this->notifiable->username,
                'oldgroup' => $oldGroup->pluck('name')->join('、'),
                'newgroup' => $newGroup->pluck('name')->join('、')
            ]
        );
    }
}
