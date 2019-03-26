<?php
namespace app\admin\controller;

use app\common\BaseAdmin;

class Index extends BaseAdmin
{
    public function index()
    {
        echo \Env::get('root_path').'<br/>';
        return $this->test();
    }
}