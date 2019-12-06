<?php

namespace App\Emoji;

use Discuz\Foundation\AbstractServiceProvider;

class EmojiServiceProvider extends AbstractServiceProvider
{
    /**
     * 引导服务.
     *
     * @return void
     */
    public function boot()
    {
        // 生成表情解析器
        if (! file_exists(__DIR__ . '/Bundle.php') || ! file_exists(__DIR__ . '/Renderer.php')) {
            $configurator = new EmojiBundleConfigurator;

            $configurator->saveBundle();
        }
    }
}
