<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE);

define( "root_path", dirname( __FILE__ ) );
define( "apps_dir", root_path."/Applications" );

spl_autoload_register( function ( $class ) {
    $path = str_replace( "\\", "/", $class ).".php";

    require $path;
});

require root_path."/Bootstrap.php";

$init = new Bootstrap();

$init->run();