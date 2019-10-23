<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ThreadSearchBuilder.php xxx 2019-10-23 10:19:00 LiuDongdong $
 */

namespace App\Searchs\Thread;

use Discuz\Search\AbstractSearchBuilder;

class ThreadSearchBuilder extends AbstractSearchBuilder
{
    // /**
    //  * 定义查询条件的方法
    //  *
    //  * @param $actor
    //  * @param $query
    //  * @param $content
    //  * @return null
    //  */
    // public function name($actor, $query, $content)
    // {
    //     // $query->where('name', 'like', '%'.$content.'%');
    //     //
    //     // $this->event->dispatch(
    //     //     new SearchModelField($actor, $query, 'name', $content)
    //     // );
    // }

    // // /**
    // //  * 定义关联模型的方法
    // //  *
    // //  * @param $actor
    // //  * @param $query
    // //  * @return null
    // //  */
    // public function firstPost($actor, $query)
    // {
    //     // dd(__FUNCTION__);
    //     dd($query->getModel());
    //     // $query->selectRaw('1')
    //     //     ->from('circle_users')
    //     //     ->where('circle_users.user_id', $actor->id)
    //     //     ->whereColumn('circles.id', 'circle_users.circle_id');
    //     $query->where('is_first', true);
    // }


}
