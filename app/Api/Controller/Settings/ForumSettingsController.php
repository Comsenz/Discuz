<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Settings;

use App\Api\Serializer\ForumSettingSerializer;
use App\Models\User;
use Discuz\Api\Controller\AbstractResourceController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ForumSettingsController extends AbstractResourceController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = ForumSettingSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = ['users'];

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $data = [];

        if (in_array('users', $this->extractInclude($request))) {
            $data['users'] = User::orderBy('created_at', 'desc')->limit(5)->get(['id', 'username', 'avatar']);
        }

        return ['id' => 1];
    }
}
