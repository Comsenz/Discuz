<?php


namespace App\Api\Controller\Users;


use App\Api\Serializer\UserProfileSerializer;
use App\Api\Serializer\UserSerializer;
use App\Models\Order;
use App\Repositories\UserRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Contracts\Setting\SettingsRepository;
use Exception;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ProfileController extends AbstractResourceController
{

    use AssertPermissionTrait;

    public $serializer = UserSerializer::class;

    public $optionalInclude = ['wechat', 'groups'];

    protected $users;

    protected $settings;

    public function __construct(UserRepository $users, SettingsRepository $settings)
    {
        $this->users = $users;
        $this->settings = $settings;
    }


    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     * @throws \Tobscure\JsonApi\Exception\InvalidParameterException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $id = Arr::get($request->getQueryParams(), 'id');

        $user = $this->users->findOrFail($id, $actor);

        if($actor->id === $user->id) {
            $this->serializer = UserProfileSerializer::class;
        }

        if($this->settings->get('siteMode') === 'pay') {
            $user->payd = false;
            if($order = $user->orders()->where([
                ['type', Order::ORDER_TYPE_REGISTER],
                ['status', Order::ORDER_STATUS_PAID]
            ])->first()) {
                $user->payd = true;
                $user->payTime = $order->updated_at;
            }
        }

        $include = $this->extractInclude($request);

        $user->load($include);

        return $user;
    }
}
