<?php
namespace System\Source\Base;

class Router
{
    /**
     * @var string $route
     */
    public $routes;

    /**
     * @var string $params
     */
    public $params;

    public $cfg=[];

    public function  __construct()
    {
        $config = require root_path . "/conf_global.php";

        $this->cfg = $config;

        foreach ( $this->cfg['installed_app'] as $item) {
            $path = apps_dir."/{$item['dir']}/extensions/Uri.php";
            if( file_exists( $path ) ) {
                $arr = require $path;

                foreach ($arr as $key => $value) {
                    $this->add( $key, $value );
                }

            }
        }
    }

    /**
     * @param $route
     * @param $params
     * @return Set param from $this->>routes
     */
    public function add($route, $params )
    {
        $route = $route;

        $this->routes[$route] = $params;
    }

    public function match()
    {
        $url = trim( $_SERVER['REQUEST_URI'], "/" );

        foreach ( $this->routes as $router => $params ) {
            if( preg_match( $params['out'][0], $url, $matches ) ){
                $this->data = $matches;
                $this->params = $params;
                return true;
            }
        }
    }

    public function init()
    {
        if ( $this->match() ) {
            $path = "Applications\\".$this->params['app']."\modules_public\\".ucfirst( $this->params['in']['matches']['controller'] )."Controller";
            if ( class_exists( $path ) ) {
                $action = $this->params['in']['matches']['action']."Action";
                if ( method_exists( $path, $action) ) {

                    $integer = array_search( "%s", $this->params );

                    if ( $integer != '' ) {
                        $this->params[$integer] = sprintf( $this->params[ $integer ], $this->data[1] );
                    }

                    $controller = new $path();
                    $controller->setAppDatas($this->params, $this->routes);
                    $controller->view->data = $this->params;
                    $controller->$action();
                    exit();

                } else {
                    throw new \Exception( "Не найдено действие: <b>{$action}</b>, в клссе <b>{$path}</b>", 404 );
                }
            } else {
                throw new \Exception( "Не найден класс: <b>{$path}</b>" );
            }
        } else {
            http_response_code(404);
            $this->params['controller'] = 404;
            $this->params['action'] = 404;
            throw new \Exception( "Ошибка, страница не существует..." );
        }
    }
}