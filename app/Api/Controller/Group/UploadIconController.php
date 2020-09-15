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
use App\Models\Group;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use League\Flysystem\FileExistsException;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class UploadIconController extends AbstractResourceController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = GroupSerializer::class;

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @var FilesystemInterface
     */
    protected $filesystem;

    /**
     * @param Dispatcher $bus
     * @param Filesystem $filesystem
     */
    public function __construct(Dispatcher $bus, Filesystem $filesystem)
    {
        $this->bus = $bus;
        $this->filesystem = $filesystem->disk('public');
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return Group|mixed
     * @throws FileExistsException
     * @throws FileNotFoundException
     * @throws PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->assertAdmin($request->getAttribute('actor'));

        $file = Arr::get($request->getUploadedFiles(), 'icon');

        $manager = new ImageManager;

        $encodedImage = $manager->make($file->getStream())->heighten(60, function ($constraint) {
            $constraint->upsize();
        })->encode('png');

        /** @var Group $group */
        $group = Group::query()->findOrFail(Arr::get($request->getQueryParams(), 'id'));

        if ($group->icon && $this->filesystem->has($group->icon)) {
            $this->filesystem->delete($group->icon);
        }

        $uploadName = 'groups/group-' . $group->id . '-' . Str::lower(Str::random(8)) . '.png';

        $this->filesystem->write($uploadName, $encodedImage);

        $group->icon = $uploadName;

        $group->save();

        return $group;
    }
}
