<?php
/**
 * Created by PhpStorm.
 * User: folez
 * Date: 07.01.2018
 * Time: 21:52
 */

namespace Asset\Skins\spectre;


class Register
{
    public function container()
    {
        return <<<HTML
    <div class="container">
        <div class="row">
            <form action="/" method="POST" class="col s12">
                <div class="row">
                    <div class="input-field col s12 l6">
                        <input type="text" required name="login" id="login">
                        <label for="login">Логин</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <input type="email" required name="email" id="email">
                        <label for="email">Электронная почта</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 l6">
                        <input type="password" required name="password" id="password">
                        <label for="password">Пароль</label>
                    </div>
                    <div class="input-field col s12 l6">
                        <input type="password" required name="r_password" id="r_password">
                        <label for="r_password">Повторите пароль</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 l4 push-l4">
                        <button onclick="goReg(event);" class="waves-effect blue darken-4 btn width-100">Зарегистрироваться</button>
                    </div>
                </div>
            </form>
            <div class="col s12" id="reg_result"></div>
        </div>
    </div>
HTML;

    }
}