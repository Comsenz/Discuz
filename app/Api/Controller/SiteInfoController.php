<?php


namespace App\Api\Controller;


use App\Api\Serializer\SiteInfoSerializer;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Foundation\Application;
use Discuz\Foundation\Support\Decomposer;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class SiteInfoController extends AbstractResourceController
{
    use AssertPermissionTrait;

    public $serializer = SiteInfoSerializer::class;

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return array|mixed
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        //权限验证，只能是能进入后台人员查看.
        $this->assertAdmin($request->getAttribute('actor'));

        //获取相关信息
        $decomposer = new Decomposer($this->app, $request);

        return $decomposer->getSiteinfo();
    }


}
