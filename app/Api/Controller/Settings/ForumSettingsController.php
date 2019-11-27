<?php


namespace App\Api\Controller\Settings;


use App\Api\Serializer\ForumSettingSerializer;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ForumSettingsController extends AbstractResourceController
{

    public $serializer = ForumSettingSerializer::class;

    protected $settings;

    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        return [
            'default' => Arr::only(Arr::get($this->settings->all(), 'default'), ['siteMode', 'logo'])
        ];
    }
}
