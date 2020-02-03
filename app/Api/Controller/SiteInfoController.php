<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller;

use App\Api\Serializer\SiteInfoSerializer;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\Application;
use Discuz\Foundation\Support\Decomposer;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class SiteInfoController extends AbstractResourceController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = SiteInfoSerializer::class;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->assertCan($request->getAttribute('actor'), 'viewSiteInfo');

        $decomposer = new Decomposer($this->app, $request);

        return $decomposer->getSiteinfo();
    }
}
