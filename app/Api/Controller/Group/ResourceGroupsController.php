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

namespace App\Api\Controller\Group;

use App\Api\Serializer\GroupSerializer;
use App\Repositories\GroupRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Exception\InvalidParameterException;

class ResourceGroupsController extends AbstractResourceController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = GroupSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = [
        'permission',
        'categoryPermissions',
    ];

    /**
     * {@inheritdoc}
     *
     * @throws InvalidParameterException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $inputs = $request->getQueryParams();
        $include = $this->extractInclude($request);

        $query = GroupRepository::query();

        if (in_array('permission', $include)) {
            // 是否包含分类权限
            if (in_array('categoryPermissions', $include)) {
                $query->with(['permission']);
            } else {
                $query->with(['permission' => function ($query) {
                    $query->where('permission', 'not like', 'category%')
                        ->where('permission', 'not like', 'switch.%');
                }]);
            }
        }

        return $query->where('id', $inputs['id'])->first();
    }
}
