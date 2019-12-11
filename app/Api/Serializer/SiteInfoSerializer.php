<?php


namespace App\Api\Serializer;



use App\Models\Post;
use App\Models\Thread;
use App\Models\UserWalletCash;
use Discuz\Api\Serializer\AbstractSerializer;
use Discuz\Foundation\Application;

class SiteInfoSerializer extends AbstractSerializer
{

    protected $type = 'siteinfo';

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
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
            'unapprovedThreads' => Thread::where('is_approved', Thread::UNAPPROVED)->count(),
            'unapprovedPosts' => Post::where('is_approved', Post::UNAPPROVED)->count(),
            'unapprovedMoneys' => UserWalletCash::where('cash_status', UserWalletCash::STATUS_REVIEW)->count(),
        ];
    }

    public function getId($model)
    {
        return 1;
    }
}
