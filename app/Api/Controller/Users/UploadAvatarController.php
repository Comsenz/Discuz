<?php


namespace App\Api\Controller\Users;


use App\Api\Serializer\UserSerializer;
use App\Commands\Users\UploadAvatar;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Validators\AvatarValidator;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tobscure\JsonApi\Document;

class UploadAvatarController extends AbstractResourceController
{


    public $serializer = UserSerializer::class;

    protected $bus;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }


    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $id = Arr::get($request->getQueryParams(), 'id');
        $file = Arr::get($request->getUploadedFiles(), 'avatar');

        return $this->bus->dispatch(new UploadAvatar($actor, $id, $file));

    }

}
