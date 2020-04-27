<?php


namespace App\Api\Controller\Users;


use App\Api\Serializer\UserSerializer;
use App\Events\DenyUsers\Saved;
use App\Models\DenyUser;
use Carbon\Carbon;
use Discuz\Api\Controller\AbstractCreateController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreateDenyUserController extends AbstractCreateController
{
    use AssertPermissionTrait;

    public $serializer = UserSerializer::class;

    public $include = ['deny'];

    protected $validation;

    protected $events;

    public function __construct(Factory $validation, Dispatcher $events)
    {
        $this->validation = $validation;
        $this->events = $events;
    }

    /**
     * @inheritDoc
     * @throws PermissionDeniedException
     * @throws Exception
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $id = Arr::get($request->getQueryParams(), 'id');

        $this->assertPermission($actor->id);

        if($actor->id == $id) {
            throw new Exception('deny_self');
        }

        $denyUser = DenyUser::where('user_id', $actor->id)
                        ->where('deny_user_id', $id)
                        ->first();

        $denyUser = $denyUser ?? new DenyUser();
        $denyUser->user_id = $actor->id;
        $denyUser->deny_user_id = $id;


        $validation = $this->validation->make(
            $denyUser->getAttributes(),
            [
                'user_id' => 'required',
                'deny_user_id' => 'required'
            ]
        );

        $validation->failed();

        $denyUser->save();

        $this->events->dispatch(new Saved($denyUser, $actor));

        return $actor;
    }
}
