<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use App\Models\Post;
use App\Models\Thread;
use App\Models\UserWalletCash;
use Discuz\Api\Serializer\AbstractSerializer;

class SiteInfoSerializer extends AbstractSerializer
{
    protected $type = 'siteinfo';

    /**
     * {@inheritdoc}
     */
    public function getDefaultAttributes($model)
    {
        // 待审核主题数
        $unapprovedThreads = Thread::where('is_approved', Thread::UNAPPROVED)
            ->whereNull('deleted_at')->count();

        // 待审核回复数
        $unapprovedPosts = Post::where('is_approved', Post::UNAPPROVED)
            ->whereNull('deleted_at')->where('is_first', false)->count();

        return [
            'version' => $model['version'],
            'php_version' => $model['php_version'],
            'server_software' => $model['server_software'],
            'server_os' => $model['server_os'],
            'database_connection_name' => $model['database_connection_name'],
            'ssl_installed' => $model['ssl_installed'],
            'cache_driver' => $model['cache_driver'],
            'upload_size' => $model['upload_size'],
            'db' => $model['db'],
            'db_size' => $model['db_size'],
            'timezone' => $model['timezone'],
            'debug_mode' => $model['debug_mode'],
            'storage_dir_writable' => $model['storage_dir_writable'],
            'cache_dir_writable' => $model['cache_dir_writable'],
            'app_size' => $model['app_size'],
            'packages' => $model['packages'],
            'unapprovedThreads' => $unapprovedThreads,
            'unapprovedPosts' => $unapprovedPosts,
            'unapprovedMoneys' => UserWalletCash::where('cash_status', UserWalletCash::STATUS_REVIEW)->count(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getId($model)
    {
        return 1;
    }
}
