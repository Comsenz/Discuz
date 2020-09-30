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

use App\Api\Serializer\DialogSerializer;
use App\Models\User;
use App\Repositories\DialogRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\NotAuthenticatedException;
use Discuz\Http\UrlGenerator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Exception\InvalidParameterException;

class ListDialogController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = DialogSerializer::class;

    /**
     * @var DialogRepository
     */
    protected $dialog;

    /**
     * @var UrlGenerator
     */
    protected $url;

    protected $tablePrefix;

    /**
     * @var int|null
     */
    public $dialogCount;

    public $sortFields = [
        'dialogMessageId',
        'createdAt',
    ];

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = ['sender','recipient','dialogMessage','sender.groups','recipient.groups'];

    /* The relationships that are included by default.
     *
     * @var array
     */
    public $include = [];

    /**
     * @param DialogRepository $dialog
     * @param UrlGenerator $url
     */
    public function __construct(DialogRepository $dialog, UrlGenerator $url)
    {
        $this->dialog = $dialog;
        $this->url = $url;
        $this->tablePrefix = config('database.connections.mysql.prefix');
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

        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $include = $this->extractInclude($request);
        $sort = $this->extractSort($request);

        $dialogs = $this->search($actor, $sort, $limit, $offset);

        $document->addPaginationLinks(
            $this->url->route('dialog.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->dialogCount
        );

        $dialogs->loadMissing($include);

        $document->setMeta([
            'total' => $this->dialogCount,
            'pageCount' => ceil($this->dialogCount / $limit),
        ]);

        return $dialogs;
    }

    /**
     * @param User $actor
     * @param null $limit
     * @param int $offset
     * @param $sort
     * @return Collection
     */
    public function search(User $actor, $sort, $limit = null, $offset = 0)
    {
        $query = $this->dialog->query();

        $query->distinct('dialog.id')
            ->select('dialog.*')
            ->join(
                'dialog_message',
                'dialog.id',
                '=',
                'dialog_message.dialog_id'
            )
            ->where(function ($query) use ($actor) {
                $query->where('dialog.sender_user_id', $actor->id)
                    ->whereRaw($this->tablePrefix. 'dialog_message.`created_at` > IFNULL( ' .$this->tablePrefix. 'dialog.`sender_deleted_at`, 0 )');
            })
            ->orWhere(function ($query) use ($actor) {
                $query->where('dialog.recipient_user_id', $actor->id)
                    ->whereRaw($this->tablePrefix. 'dialog_message.`created_at` > IFNULL( ' .$this->tablePrefix. 'dialog.`recipient_deleted_at`, 0 )');
            });

        $this->dialogCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit);

        foreach ((array) $sort as $field => $order) {
            $query->orderBy(Str::snake($field), $order);
        }
        return $query->get();
    }
}
