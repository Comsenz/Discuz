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

class ResourceUserWalletController extends AbstractResourceController
{
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
        // TODO: User $actor 用户模型
        $actor = $request->getAttribute('actor');

        //用户ID
        $user_id = Arr::get($request->getQueryParams(), 'user_id');
        $return_data = $this->wallet->findWalletOrFail($user_id, $actor);
        //$cash_tax_ratio = $this->setting->tag('cash_tax_ratio');
        $return_data->cash_tax_ratio = 0.01;//税率
        return $return_data;
    }
}
