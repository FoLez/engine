<?php

namespace Applications\core\modules_public;


use System\Source\Base\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        $this->view->setTitle( "Главная" );
        $html = $this->view->loadLayout("Main");
        $this->view->globalContainer();
        $this->view->html_main = $html->header(123, 42342, 2534);
        $this->view->render();
        $this->getNews();
    }

    public function getNews()
    {
        $this->DB->query( "SELECT * FROM news" );
        while( $row = $this->DB->get_row() ){
            $title = $row['title'];
            echo $date = $this->view->formatTime( $row['create_date'], "SHORT" );
        }
    }

    public function registerFormAction()
    {
        $this->view->setTitle( "Регистрация" );
        $this->view->globalContainer();
        $html = $this->view->loadLayout("Register");
        $this->view->html_main = $html->container();
        $this->view->render();
    }
}