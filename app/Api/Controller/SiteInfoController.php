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

namespace App\Api\Controller;

use App\Api\Serializer\SiteInfoSerializer;
use App\Models\Category;
use App\Models\Order;
use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\Application;
use Discuz\Foundation\Support\Decomposer;
use Discuz\Qcloud\QcloudTrait;
use Exception;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class SiteInfoController extends AbstractResourceController
{
    use AssertPermissionTrait;
    use QcloudTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = SiteInfoSerializer::class;

    /**
     * @var Application
     */
    protected $app;

    protected $settings;

    /**
     * @param Application $app
     * @param SettingsRepository $settings
     */
    public function __construct(Application $app, SettingsRepository $settings)
    {
        $this->app = $app;
        $this->settings = $settings;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->assertCan($request->getAttribute('actor'), 'viewSiteInfo');

        $decomposer = new Decomposer($this->app, $request);

        $port = $request->getUri()->getPort();
        $siteUrl = $request->getUri()->getScheme() . '://' . $request->getUri()->getHost().(in_array($port, [80, 443, null]) ? '' : ':'.$port);

        $data = [
            'url' => $siteUrl,
            'site_id' => $this->settings->get('site_id'),
            'site_name' => $this->settings->get('site_name'),
            'threads' => Thread::count(),
            'posts' => Post::count(),
            'users' => User::count(),
            'orders' => Order::count(),
            'categories' => serialize(Category::all()->toArray())
        ];

        try {
            $this->report($data)->then(function (ResponseInterface $response) {
                $data = json_decode($response->getBody()->getContents(), true);
                $this->settings->set('site_id', Arr::get($data, 'site_id'));
                $this->settings->set('site_secret', Arr::get($data, 'site_secret'));
            })->wait();
        } catch (Exception $e) {
        }

        return $decomposer->getSiteinfo();
    }
}
