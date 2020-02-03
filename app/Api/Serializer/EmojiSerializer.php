<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use App\Models\Emoji;
use Discuz\Api\Serializer\AbstractSerializer;
use Illuminate\Contracts\Routing\UrlGenerator;

class EmojiSerializer extends AbstractSerializer
{

    protected $url;

    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     */
    protected $type = 'emoji';

    /**
     * {@inheritdoc}
     *
     * @param Emoji $model
     */
    public function getDefaultAttributes($model)
    {
        return [
            'category'          => $model->category,
            // 'url'               => $model->url,
            'url'               => $this->url->to('/' . $model->url),
            'code'              => $model->code,
            'order'             => $model->order,
        ];
    }
}
