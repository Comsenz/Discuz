<?php


namespace App\Api\Controller\Settings;


use App\Api\Serializer\ForumSettingSerializer;
use App\Models\User;
use Discuz\Api\Controller\AbstractResourceController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ForumSettingsController extends AbstractResourceController
{

    public $serializer = ForumSettingSerializer::class;

    public $optionalInclude = ['users'];

    protected function data(ServerRequestInterface $request, Document $document)
    {
        return [
            'users' => User::limit(5)->get()
        ];
    }
}
