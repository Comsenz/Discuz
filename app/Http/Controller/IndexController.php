<?php

namespace App\Http\Controller;

use Discuz\Foundation\Application;
use Discuz\Web\Controller\AbstractWebController;
use Illuminate\Contracts\Filesystem\Factory as FileFactory;
use Illuminate\View\Factory;
use Psr\Http\Message\ServerRequestInterface;

class IndexController extends AbstractWebController
{
    protected $file;
    protected $validator;

    public function __construct(Application $app, Factory $view, FileFactory $file, \App\Validators\TestValidator $validator)
    {
        parent::__construct($app, $view);
        $this->file = $file;
        $this->validator = $validator;
    }

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
