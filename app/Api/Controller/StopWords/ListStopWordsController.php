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

namespace App\Api\Controller\StopWords;

use App\Api\Serializer\StopWordSerializer;
use App\Repositories\StopWordRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Http\UrlGenerator;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListStopWordsController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = StopWordSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = ['user'];

    /**
     * @var StopWordRepository
     */
    protected $stopWords;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @param StopWordRepository $stopWords
     * @param UrlGenerator $url
     */
    public function __construct(StopWordRepository $stopWords, UrlGenerator $url)
    {
        $this->stopWords = $stopWords;
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->assertAdmin($request->getAttribute('actor'));

        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $include = $this->extractInclude($request);
        $keyword = Arr::get($this->extractFilter($request), 'q');

        $query = $this->stopWords
            ->query()
            ->with($include)
            ->when($keyword, function ($query, $keyword) {
                return $query->where('find', 'like', "%$keyword%");
            });

        $stopWordCount = $limit > 0 ? $query->count() : null;

        $stopWords = $query->limit($limit)->offset($offset)->get();

        $document->addPaginationLinks(
            $this->url->route('stop-words.index'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $stopWordCount
        );

        $document->setMeta([
            'total' => $stopWordCount,
            'pageCount' => ceil($stopWordCount / $limit),
        ]);

        return $stopWords;
    }
}
