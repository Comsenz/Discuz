<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: ClassifySearchBuilderilder.php 28830 2019-10-15 14:34 chenkeke $
 */

namespace App\Searchs\Classify;

use Discuz\Search\AbstractSearchBuilder;
use Discuz\Api\Events\SearchModelField;

class ClassifySearchBuilder extends AbstractSearchBuilder
{

    public function name($actor, $query, $content)
    {
        $query->where('name', 'like', '%'.$content.'%');

        $this->event->dispatch(
            new SearchModelField($actor, $query, 'name', $content)
        );
    }



    public function user($actor, $query)
    {

    }
}