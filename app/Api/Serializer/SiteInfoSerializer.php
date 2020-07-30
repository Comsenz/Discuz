<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Api\Serializer;

use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
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
        // 待审核用户数
        $unapprovedUsers = User::where('status', 2)->count();

        // 待审核主题数
        $unapprovedThreads = Thread::where('is_approved', Thread::UNAPPROVED)
            ->whereNull('deleted_at')->count();

        // 待审核回复数
        $unapprovedPosts = Post::where('is_approved', Post::UNAPPROVED)
            ->whereNull('deleted_at')->where('is_first', false)->count();

        // 待审核提申请现数
        $unapprovedMoneys = UserWalletCash::where('cash_status', UserWalletCash::STATUS_REVIEW)->count();

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
            'unapprovedUsers' => $unapprovedUsers,
            'unapprovedThreads' => $unapprovedThreads,
            'unapprovedPosts' => $unapprovedPosts,
            'unapprovedMoneys' => $unapprovedMoneys,
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
