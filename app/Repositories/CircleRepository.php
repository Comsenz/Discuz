<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      1: CircleRepository.php 28830 2019-09-25 11:45 chenkeke $
 */

namespace App\Repositories;

class CircleRepository
{
    public function getdata($data){
        $data[] = 'ddd';
        return $data;
    }
}