<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Settings;

use App\Api\Serializer\SettingSerializer;
use App\Models\Setting;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListSettingsController extends AbstractListController
{
    use AssertPermissionTrait;

    public $serializer = SettingSerializer::class;

    protected $settings;

    protected $validation;

    public function __construct(Factory $validation, SettingsRepository $settings)
    {
        $this->settings = $settings;
        $this->validation = $validation;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return array|mixed
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->assertAdmin($request->getAttribute('actor'));

        $filter = $this->extractFilter($request);

        $tag = Arr::get($filter, 'tag', 'default');

        $query = Setting::query()->where('tag', $tag);

        if (Arr::has($filter, 'key')) {
            $query->orWhere('key', Arr::get($filter, 'key'));
        }

        $settings = $query->get();

        return $settings;
    }
}
