<?php

namespace App\Api\Controller\Settings;

use App\Api\Serializer\SettingTags;
use App\Models\Setting;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class TagsSettingsController extends AbstractListController
{
    public $serializer = SettingTags::class;

    /**
     * @var Dispatcher
     */
    public $bus;

    /**
     * TagsSettingsController constructor.
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * Get the data to be serialized and assigned to the response document.
     *
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $tags = $request->getParsedBody()->get('data', []);

        $settings = collect($tags)->map(function ($tag) {
            return Setting::where('tag', $tag)->get()->toArray();
        })->filter();

        return $settings;
    }
}
