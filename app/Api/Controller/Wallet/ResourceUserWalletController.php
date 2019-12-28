<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Wallet;

use App\Settings\SettingsRepository;
use Illuminate\Contracts\Bus\Dispatcher;
use App\Api\Serializer\UserWalletSerializer;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use App\Repositories\UserWalletRepository;
use Discuz\Auth\AssertPermissionTrait;

class ResourceUserWalletController extends AbstractResourceController
{
    use AssertPermissionTrait;
    /**
     * {@inheritdoc}
     */
    public $serializer = UserWalletSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = [
        'user'
    ];

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @var UserWalletRepository
     */
    public $wallet;

    /**
     * @var SettingsRepository
     */
    protected $setting;

    /**
     * @param Dispatcher $bus
     * @param SettingsRepository $setting
     * @param UserWalletRepository $wallet
     */
    public function __construct(Dispatcher $bus, SettingsRepository $setting, UserWalletRepository $wallet)
    {
        $this->bus = $bus;
        $this->setting = $setting;
        $this->wallet = $wallet;
    }

    /**
     * {@inheritdoc}
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');
        $this->assertRegistered($actor);
        $data = $this->wallet->findOrFail(Arr::get($request->getQueryParams(), 'user_id'), $request->getAttribute('actor'));

        $data->cash_tax_ratio = $this->setting->get('cash_rate', 'cash', 0);

        return $data;
    }
}
