<?php


namespace App\Api\Controller;


use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Contracts\Setting\SettingsRepository;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class DeleteLogoController extends AbstractResourceController
{

    protected $settings;

    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Get the data to be serialized and assigned to the response document.
     *
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
//        $this->settings->set('logo');
    }
}
