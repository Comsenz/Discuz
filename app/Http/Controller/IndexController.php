<?php

namespace App\Http\Controller;

use Discuz\Web\Controller\AbstractWebController;
use Illuminate\View\Factory;
use Psr\Http\Message\ServerRequestInterface;

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
