<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Repositories;

use App\Models\WechatOffiaccountReply;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class WechatOffiaccountReplyRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the posts table.
     *
     * @return Builder
     */
    public function query()
    {
        return WechatOffiaccountReply::query();
    }

    /**
     * @param $id
     * @return Builder|\Illuminate\Database\Eloquent\Model
     */
    public function findOrFail($id)
    {
        $query = $this->query()->where('id', $id);

        return $query->firstOrFail();
    }

    /**
     * @param $id
     * @return Builder|\Illuminate\Database\Eloquent\Model
     */
    public function findWellDelete($id)
    {
        $reply = $this->findOrFail($id);

        $reply->delete();

        return $reply;
    }
}
