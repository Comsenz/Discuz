<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Payment;

use App\Api\Serializer\PaymentSerializer;
use App\Settings\SettingsRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
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
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        return unserialize($this->settings->get('payments', 'default', []));
    }
}
