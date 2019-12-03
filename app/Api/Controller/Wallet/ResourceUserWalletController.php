<?php
/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ResourceUserWalletController.php xxx 2019-10-22 17:20:00 zhouzhou $
 */

namespace App\Api\Controller\Wallet;

use App\Settings\SettingsRepository;
use Illuminate\Contracts\Bus\Dispatcher;
use App\Api\Serializer\UserWalletSerializer;
use App\Commands\Wallet\ResourceUserWallet;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

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
     * @var SettingsRepository
     */
    protected $setting;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus, SettingsRepository $setting)
    {
        $this->bus = $bus;
        $this->setting = $setting;
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
        $return_data =  $this->bus->dispatch(
            new ResourceUserWallet($user_id, $actor)
        );
        //$cash_tax_ratio = $this->setting->tag('cash_tax_ratio');
        $return_data->cash_tax_ratio = 0.01;//税率
        return $return_data;
    }
}
