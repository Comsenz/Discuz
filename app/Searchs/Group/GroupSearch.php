<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: GroupSearch.php 28830 2019-10-23 16:39 chenkeke $
 */

namespace App\Searchs\Group;


use Discuz\Search\AbstractSearch;

class GroupSearch extends AbstractSearch
{
    /**
     * 可被包括的关联方法.
     * 返回数据需要关联的数据，多个关联用","间隔，"-"会把后面的单词首字母大写拼接到前面的单词.
     *
     * @var User
     */
    public $includes = [
        'groupPermission'
    ];

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