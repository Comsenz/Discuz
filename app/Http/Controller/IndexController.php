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

namespace App\Http\Controller;

use Discuz\Http\DiscuzResponseFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class IndexController implements RequestHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $route = $request->getUri()->getPath();

        if (Str::startsWith($route, '/admin')) {
            $file = 'admin.html';
        } else {
            $isMobile = $this->isMobile($request->getServerParams());
            $file = $isMobile ? 'index.html' : 'pc.html';

            if (Arr::has($request->getQueryParams(), 'from')) {
                $file = 'index.html';
            }

            if (!$isMobile && Str::startsWith($route, '/pages')) {
                $file = Str::replaceFirst("/pages", "/pc-pages", $route) . "/index.html";
            }

            if (!$isMobile && (Str::startsWith($route, '/topic/index') || Str::startsWith($route, '/topic/post'))) {
                $file = Str::replaceFirst("/topic", "/pc-topic", $route) . "/index.html";
            }
        }

        return DiscuzResponseFactory::FileResponse(
            public_path($file)
        );
    }

    /**
     * 判断设备
     *
     * @param $server
     * @return bool
     */
    protected function isMobile($server)
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($server['HTTP_X_WAP_PROFILE'])) {
            return true;
        }

        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($server['HTTP_VIA']) && stristr($server['HTTP_VIA'], 'wap')) {
            return true;
        }

        $user_agent = $server['HTTP_USER_AGENT'];

        // 如果是 Windows PC 微信浏览器，返回 true 直接访问 index.html，不然打开是空白页
        if (stristr($user_agent, 'Windows NT') && stristr($user_agent, 'MicroMessenger')) {
            return true;
        }

        $mobile_agents = [
            'iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi',
            'opera mini', 'ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod',
            'nokia', 'samsung', 'palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma',
            'docomo', 'up.browser', 'up.link', 'blazer', 'helio', 'hosin', 'huawei', 'novarra', 'coolpad',
            'techfaith', 'alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom',
            'bunjalloo', 'maui', 'smartphone', 'iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech',
            'gionee', 'portalmmm', 'jig browser', 'hiptop', 'benq', 'haier', '^lct', '320x320', '240x320',
            '176x220', 'windows phone', 'cect', 'compal', 'ctl', 'lg', 'nec', 'tcl', 'daxian', 'dbtel', 'eastcom',
            'konka', 'kejian', 'lenovo', 'mot', 'soutec', 'sgh', 'sed', 'capitel', 'panasonic', 'sonyericsson',
            'sharp', 'panda', 'zte', 'acer', 'acoon', 'acs-', 'abacho', 'ahong', 'airness', 'anywhereyougo.com',
            'applewebkit/525', 'applewebkit/532', 'asus', 'audio', 'au-mic', 'avantogo', 'becker', 'bilbo',
            'bleu', 'cdm-', 'danger', 'elaine', 'eric', 'etouch', 'fly ', 'fly_', 'fly-', 'go.web', 'goodaccess',
            'gradiente', 'grundig', 'hedy', 'hitachi', 'htc', 'hutchison', 'inno', 'ipad', 'ipaq', 'ipod',
            'jbrowser', 'kddi', 'kgt', 'kwc', 'lg ', 'lg2', 'lg3', 'lg4', 'lg5', 'lg7', 'lg8', 'lg9', 'lg-', 'lge-',
            'lge9', 'maemo', 'mercator', 'meridian', 'micromax', 'mini', 'mitsu', 'mmm', 'mmp', 'mobi', 'mot-',
            'moto', 'nec-', 'newgen', 'nf-browser', 'nintendo', 'nitro', 'nook', 'obigo', 'palm', 'pg-',
            'playstation', 'pocket', 'pt-', 'qc-', 'qtek', 'rover', 'sama', 'samu', 'sanyo', 'sch-', 'scooter',
            'sec-', 'sendo', 'sgh-', 'siemens', 'sie-', 'softbank', 'sprint', 'spv', 'tablet', 'talkabout',
            'tcl-', 'teleca', 'telit', 'tianyu', 'tim-', 'toshiba', 'tsm', 'utec', 'utstar', 'verykool', 'virgin',
            'vk-', 'voda', 'voxtel', 'vx', 'wellco', 'wig browser', 'wii', 'wireless', 'xde', 'pad', 'gt-p1000'
        ];

        $is_mobile = false;
        foreach ($mobile_agents as $device) {
            if (stristr($user_agent, $device)) {
                $is_mobile = true;
                break;
            }
        }
        return $is_mobile;
    }
}
