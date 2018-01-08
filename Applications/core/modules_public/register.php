<?php
namespace Applications\core\modules_public;
use System\Source\Base\Controller;

include_once $_SERVER['DOCUMENT_ROOT']."/System/Source/Base/Controller.php";

class Register extends Controller {
    public function doReg()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ERROR | E_WARNING | E_PARSE);

        $r_pass = $_POST['r_password'];
        $pass   = $_POST['password'];
        $login  = $_POST['login'];
        $mail   = $_POST['email'];

        if ( $r_pass != $pass ) {
            $data['status'] = 'error';
            $data['msg']    = 'Введенные пароли не совпадают';
        }


        function generatePasswordSalt($len=5)
        {
            $salt = '';

            for ( $i = 0; $i < $len; $i++ )
            {
                $num   = mt_rand(33, 126);

                if ( $num == '92' )
                {
                    $num = 93;
                }

                $salt .= chr( $num );
            }

            return $salt;
        }
        $salt = generatePasswordSalt();
        $pass = md5( md5( $salt ) . md5( $pass ) );
        $login = $this->DB->safesql( $login );
        $_time = time();

        $this->DB->query( "INSERT INTO members (name, email, joined, pass_salt, pass_hash) VALUES( '$login', '$mail', '$_time',  '$salt', '$pass')" );

        $data['status'] = "success";
        $return = '<script type="text/javascript">
			setTimeout(
			    function(){
			        window.location.href="http://app.test/news";
			    },
			    11*1000
            );
		</script>';
        $data['msg'] = $return;

        echo json_encode( $data );
    }
}

$test = new Register();
$test->doReg();