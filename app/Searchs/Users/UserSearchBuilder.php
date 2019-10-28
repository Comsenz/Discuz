<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: ClassifySearchBuilderilder.php 28830 2019-10-15 14:34 chenkeke $
 */

namespace App\Searchs\users;

use Discuz\Search\AbstractSearchBuilder;
use Discuz\Api\Events\SearchModelField;

class userSearchBuilder extends AbstractSearchBuilder
{
    /**
     * 定义查询条件的方法
     *
     * @param $actor
     * @param $query
     * @param $content
     * @return null
     */
    public function name($actor, $query, $content)
    {
        $query->where('username', 'like', '%'.$content.'%');

        $this->event->dispatch(
            new SearchModelField($actor, $query, 'name', $content)
        );
    }
    public function mobile($actor, $query, $content)
    {
        $query->where('mobile', 'like', $content.'%');

        $this->event->dispatch(
            new SearchModelField($actor, $query, 'mobile', $content)
        );
    }
    public function unionid($actor, $query, $content)
    {
        $query->where('unionid', $content);

        $this->event->dispatch(
            new SearchModelField($actor, $query, 'unionid', $content)
        );
    }
    public function status($actor, $query, $content)
    {
        $query->where('status', $content);

        $this->event->dispatch(
            new SearchModelField($actor, $query, 'status', $content)
        );
    }
    public function nickname($actor, $query, $content)
    {
        $query->with(['userWechats' => function ($q) use ($content) {
            $q->where('nickname','like' ,'%'.$content.'%');
        }]);

        $this->event->dispatch(
            new SearchModelField($actor, $query, 'nickname', $content)
        );
    }
//    public function is_wx($actor, $query, $content)
//    {
//        $query->with(['userWechats' => function ($q)  {
//            $q->where('userWechats','!=', null);
//        }]);
//        $this->event->dispatch(
//            new SearchModelField($actor, $query, 'nickname', $content)
//        );
//    }

    /**
     * 定义关联模型的方法
     *
     * @param $actor
     * @param $query
     * @return null
     */

}