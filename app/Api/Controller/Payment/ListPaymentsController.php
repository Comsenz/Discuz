<?php


namespace App\Api\Controller\Payment;


use App\Api\Serializer\PaymentSerializer;
use App\Models\Setting;
use App\Settings\SettingsRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Contracts\Search\Searcher;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListPaymentsController extends AbstractListController
{
    use AssertPermissionTrait;

    public $serializer = PaymentSerializer::class;

    protected $settings;

    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;
    }


    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed|null
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        return unserialize($this->settings->get('payments', 'default', []));
    }
}
