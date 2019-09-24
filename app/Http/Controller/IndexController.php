<?php

namespace App\Http\Controller;

use App\Models\User;
use Discuz\Web\Controller\AbstractWebController;
use Illuminate\View\Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;

class IndexController extends AbstractWebController
{

    /**
     * @param ServerRequestInterface $request
     * @param Factory $view
     * @return \Illuminate\Contracts\View\View|Factory
     */
    public function render(ServerRequestInterface $request, Factory $view)
    {

        $view = $view->make('app');
        $view->with('title', 'discuss');

        return $view;

    }
}
