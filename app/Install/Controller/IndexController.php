<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Install\Controller;

use App\Settings\SettingsRepository;
use Discuz\Web\Controller\AbstractWebController;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\View\Factory;
use Psr\Http\Message\ServerRequestInterface;

class IndexController extends AbstractWebController
{
    public function render(ServerRequestInterface $request, Factory $view)
    {
        $url = $this->app->make(UrlGenerator::class);

        $viewPath = 'install.problems';
        $problems = $this->preRequireSite();
        if (!$problems->count()) {
            $viewPath = 'install.install';
        }

        $data = [
            'title' => 'Discuz Q 安装程序',
            'problems' => $problems,
            'logo' => 'data:image/png;base64,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'
        ];

        return $view->make($viewPath)->with($data);
    }

    private function preRequireSite()
    {
        $problems = collect();
        $defaultPhpVersion = '7.2.0';
        $phpversion = version_compare(PHP_VERSION, $defaultPhpVersion, '>=');
        if (!$phpversion) {
            $problems->push(['message' => 'PHP 版本必须大于'.$defaultPhpVersion]);
        }

        $extension = collect([
            'dom',
            'gd',
            'json',
            'mbstring',
            'openssl',
            'pdo_mysql',
            'fileinfo'
        ])->reject(function ($extension) {
            return extension_loaded($extension);
        })->map(function ($extension) {
            return [
                'message' => "PHP扩展 '$extension' 未安装.",
            ];
        });

        $problems = $problems->merge($extension);

        $storageDirWritable = is_writable(base_path('storage'));
        $cacheDirWritable = is_writable(storage_path('cache'));
        $configDirWritable = is_writable($this->app->configPath());

        if (!$storageDirWritable) {
            $problems->push(['message' => 'storage 目录不可写']);
        }

        if (!$cacheDirWritable) {
            $problems->push(['message' => 'storage/cache 目录不可写']);
        }

        if (!$configDirWritable) {
            $problems->push(['message' => 'config 目录不可写']);
        }

        if ($this->app->isInstall()) {
            $url = $this->app->make(UrlGenerator::class);
            $setting = $this->app->make(SettingsRepository::class);
            $problems->push(['message' => '您已安装过站点，请直接访问 <a href="'.$url->to('/').'">'.$setting->get('site_name').'</a>']);
        }

        return $problems;
    }
}
