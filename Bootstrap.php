<?php

use System\Source\Base\Router;

class Bootstrap
{
    public function __construct()
    {
    }

    public function run()
    {
        $core = new \System\Source\Base\Core();
        $route = new Router();

        try{
            $route->init();
        } catch ( Exception $e ) {
            die ($e->getMessage());
        }
    }
}