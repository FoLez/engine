<?php

namespace Applications\news\modules_public;


use System\Source\Base\Controller;

class NewsController extends Controller
{
    public function listAction()
    {
        $this->view->router = $this->routes;
        $this->view->fetchMenu( $this->routes['app'], $this->app_data );
        $this->view->setTitle( "Главная" );
        $html = $this->view->loadLayout("Main");
        $this->view->globalContainer();
        $this->view->html_main = $html->header(123, 42342, 2534);
        $this->view->render();
    }
}