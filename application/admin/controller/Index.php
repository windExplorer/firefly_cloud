<?php
namespace app\admin\controller;

use app\common\behavior\BaseAdmin;

class Index extends BaseAdmin
{

    public function index()
    {
        //$ret = $this->GetColumnInfo('menu');
        $ret = $this->GetChildren('menu');
        $ret = db('menu')->whereTime('uptime', 'between', ['', '']);
        //dump($ret);
        return view();
    }

    public function index2()
    {

        return view();
    }

    /**
     * 
      */


}