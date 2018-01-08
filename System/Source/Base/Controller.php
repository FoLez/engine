<?php
namespace System\Source\Base;

use System\Source\Classes\Mysql\DB;
use System\Source\Classes\output\View;

abstract class Controller
{
    public $routes;
    public $app_data;
    public $DB;
    public $cfg;

    public function __construct()
    {
        $config = require root_path . "/conf_global.php";

        $this->cfg = $config;

        $this->DB = new DB( $this->cfg['db_config']['db_user'], $this->cfg['db_config']['db_pass'], $this->cfg['db_config']['db_name'], $this->cfg['db_config']['db_host'] );

        $this->view = new View();
    }

    public function setAppDatas( $routes, $apps )
    {
        $this->routes = $routes;

        $this->app_data = $apps;
        $this->view->setAppData( $routes, $apps );
    }
}