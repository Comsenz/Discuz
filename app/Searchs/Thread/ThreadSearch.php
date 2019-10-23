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
        'posts',
        'posts.user'
    ];
}
