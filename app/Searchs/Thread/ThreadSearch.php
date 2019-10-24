<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ThreadSearch.php xxx 2019-10-23 10:19:00 LiuDongdong $
 */

namespace App\Searchs\Thread;

use Discuz\Search\AbstractSearch;

class ThreadSearch extends AbstractSearch
{
    /**
     * 可被包括的关联方法.
     * 返回数据需要关联的数据，多个关联用","间隔，"-"会把后面的单词首字母大写拼接到前面的单词.
     *
     * @var array
     */
    public $includes = [
        'user',
        'firstPost',
    ];



    /**
     * 默认显示多少条数据.
     *
     * @var int
     */
    public $defaultLimit = 20;

    /**
     * 可被排序的字段。
     *
     * @var array
     */
    public $sort = [
        'id',
        'updated_at',
    ];

    /**
     * 默认的排序字段。
     *
     * @var array
     */
    public $defaultSort = [
        'updated_at' => 'desc'
    ];
}
