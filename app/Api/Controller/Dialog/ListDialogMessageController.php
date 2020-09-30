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

namespace App\Api\Controller\Dialog;

use App\Api\Serializer\DialogMessageSerializer;
use App\Models\User;
use App\Repositories\DialogMessageRepository;
use App\Repositories\DialogRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\NotAuthenticatedException;
use Discuz\Http\UrlGenerator;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Exception\InvalidParameterException;

class ListDialogMessageController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = DialogMessageSerializer::class;

    /**
     * @var DialogRepository
     */
    protected $dialogs;

    /**
     * @var DialogMessageRepository
     */
    protected $dialogMessage;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var int|null
     */
    public $dialogMessageCount;

    /**
     * @var ValidationFactory
     */
    public $validation;

    /**
     * @var string[]
     */
    public $sortFields = [
        'createdAt',
    ];

    public $include = ['attachment'];

    public $optionalInclude = ['user','user.groups'];

    /**
     * @param DialogRepository $dialogs
     * @param DialogMessageRepository $dialogMessage
     * @param ValidationFactory $validation
     * @param UrlGenerator $url
     */
    public function __construct(DialogRepository $dialogs, DialogMessageRepository $dialogMessage, ValidationFactory $validation, UrlGenerator $url)
    {
        $this->dialogs = $dialogs;
        $this->dialogMessage = $dialogMessage;
        $this->validation = $validation;
        $this->url = $url;
    }

    /**
     * 我的关注
     * {@inheritdoc}
     * @throws InvalidParameterException
     * @throws NotAuthenticatedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $this->assertRegistered($actor);

        $filter = $this->extractFilter($request);
        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $include = $this->extractInclude($request);
        $sort = $this->extractSort($request);

        $this->validation->make(
            ['dialog_id' => Arr::get($filter, 'dialog_id')],
            ['dialog_id' => 'required']
        )->validate();

        //设置登录用户已读
        $dialog = $this->dialogs->findOrFail($filter['dialog_id'], $actor);
        if ($dialog->sender_user_id == $actor->id) {
            $type = 'sender';
        } else {
            $type = 'recipient';
        }
        $dialog->setRead($type);

        $dialogMessages = $this->search($actor, $sort, $filter, $dialog, $limit, $offset);

        $document->addPaginationLinks(
            $this->url->route('dialog.message.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->dialogMessageCount
        );

        $dialogMessages->loadMissing($include);

        $document->setMeta([
            'total' => $this->dialogMessageCount,
            'pageCount' => ceil($this->dialogMessageCount / $limit),
        ]);

        return $dialogMessages;
    }

    /**
     * @param User $actor
     * @param $sort
     * @param array $filter
     * @param $dialog
     * @param null $limit
     * @param int $offset
     * @return Collection
     */
    public function search(User $actor, $sort, $filter, $dialog, $limit = null, $offset = 0)
    {
        $query = $this->dialogMessage->query();

        $query->select('dialog_message.*');
        $query->where('dialog_id', $filter['dialog_id']);

        $query->join(
            'dialog',
            'dialog.id',
            '=',
            'dialog_message.dialog_id'
        )->where(function ($query) use ($actor) {
            $query->where('dialog.sender_user_id', $actor->id);
            $query->orWhere('dialog.recipient_user_id', $actor->id);
        });

        // 按照登陆用户的删除情况过滤数据
        if ($dialog->sender_user_id == $actor->id && $dialog->sender_deleted_at) {
            $query->whereColumn(
                'dialog_message.created_at',
                '>',
                'dialog.sender_deleted_at'
            );
        }
        if ($dialog->recipient_user_id == $actor->id && $dialog->recipient_deleted_at) {
            $query->whereColumn(
                'dialog_message.created_at',
                '>',
                'dialog.recipient_deleted_at'
            );
        }

        $this->dialogMessageCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit);

        foreach ((array) $sort as $field => $order) {
            $query->orderBy(Str::snake($field), $order);
        }
        return $query->get();
    }
}
