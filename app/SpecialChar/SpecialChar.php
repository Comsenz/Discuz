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

namespace App\SpecialChar;

use Discuz\SpecialChar\SpecialCharServer;
use HTMLPurifier;
use HTMLPurifier_Config;
use Discuz\Foundation\Application;

/**
 * Class SpecialChar
 * @package App\SpecialChar
 */
class SpecialChar implements SpecialCharServer
{
    /**
     * @var
     */
    public $app;

    /**
     * @var
     */
    public $config;

    /**
     * @var string 允许使用的HTML标签
     * @ps: 逗号分隔
     */
    public $html = '';

    /**
     * @var string 允许使用的CSS属性
     * @ps: 逗号分隔
     */
    public $css = '';

    /**
     * SpecialChar constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * 返回过滤后的字符串
     *
     * @param $string
     * @return mixed
     */
    public function purify($string)
    {
        $this->filtration();

        // 使用配置生成过滤用的对象
        $purifier = new HTMLPurifier($this->config);

        // 过滤字符串
        $cleanHtml = $purifier->purify($string);

        return $cleanHtml;
    }

    /**
     * 设置过滤
     */
    public function filtration()
    {
        $this->setConfig();

        $this->config->set('HTML.Allowed', $this->html);

        $this->config->set('CSS.AllowedProperties', $this->css);

        // 清除空标签
        $this->config->set('AutoFormat.RemoveEmpty', true);

        $this->config->set('Cache.SerializerPath', storage_path('cache'));
    }

    public function setConfig()
    {
        // 生成配置对象
        $this->config = HTMLPurifier_Config::createDefault();

        // 设置字符集
        $this->config->set('Core.Encoding', 'UTF-8');
    }

    /**
     * 扩展设置
     *
     * @param $html
     */
    public function setHtmlLabel($html)
    {
        $this->html = $html;
    }

    /**
     * 扩展设置
     *
     * @param $css
     */
    public function setCSSLabel($css)
    {
        $this->css = $css;
    }
}
