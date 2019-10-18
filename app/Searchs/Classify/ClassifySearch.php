<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: ClassifySearch.php 28830 2019-10-14 15:43 chenkeke $
 */

namespace App\Searchs\Classify;

use Discuz\Search\AbstractSearch;

class ClassifySearch extends AbstractSearch
{
    /**
     * 可被包括的关联方法.
     * 返回数据需要关联的数据，多个关联用","间隔，"-"会把后面的单词首字母大写拼接到前面的单词.
     *
     * @var User
     */
    public $includes = [
        'firstPost',
        'last-post',
        'user'
    ];

    /**
     * 每页多少条数据.
     * $offset!=0时，请求参数为：page[number]=当前页数，会自动计算需要跳过多少条数据.
     * $offset=0时，请求参数为：page[offset]=跳过的条数.
     *
     * @var int
     */
    public $offset = 10;

    /**
     * 默认显示多少条数据.
     *
     * @var int
     */
    public $defaultLimit = 10;

    /**
     * 可被排序的字段.
     *
     * @var string
     */
    public $sort = [
        'id',
        'name'
    ];

    /**
     * 默认的排序字段.
     *
     * @var string
     */
    public $defaultSort = [
        'id'=>'desc'
    ];

}