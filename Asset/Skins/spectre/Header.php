<?php
namespace Asset\Skins\spectre;

use System\Source\Classes\output\View;

class Header extends View
{
    public function wrapper()
    {
        $out = <<<HTML
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
{$this->getCss()}    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
    <title><%title%></title>
</head>
<body>
    <nav class="blue darken-4">
        <div class="nav-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col s12">
                        <a href="/" class="brand-logo">Engine</a>
                        <!-- activate side-bav in mobile view -->
                        <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
                        <ul class="right hide-on-med-and-down">
<%MENU%>
HTML;
        if ( isset( $_COOKIE['uid'] ) AND $_COOKIE['uid'] > 0 ){
            $member = $_COOKIE['uid'];
        } else {
            $member = <<<HTML
                    <!-- Dropdown Trigger -->
                            <li>
                                <a href="#login" class="modal-trigger">Войти</a>
                            </li>
                            <li>
                                <a href="/register" class="modal-trigger">Зарегистрироваться</a>
                            </li>
HTML;

        }
        $out .= <<<HTML
        {$member}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- Modal Structure -->
    <div id="login" class="modal">
        <div class="modal-content">
            <h4>Modal Header <i class="modal-action modal-close material-icons right">close</i></h4>
            <p>A bunch of text</p>
        </div>
        <div class="modal-footer">
        </div>
    </div>
    <%CONTENT%>
    <script type="text/javascript" src="{$this->getUrl()}/Asset/Skins/spectre/js/materialize.js"></script>
    <script type="text/javascript" src="{$this->getUrl()}/Asset/Skins/spectre/js/main.js"></script>
    <script>
        $(document).ready( function() {
            $('.modal').modal();
        });
    </script>
</body>
</html>
HTML;
        return $out;
    }
}