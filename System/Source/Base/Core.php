<?php
namespace System\Source\Base;


class Core
{
    public $DB;

    public $model;

    public function __construct()
    {
        $this->model = new Model();
    }
}