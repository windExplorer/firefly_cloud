<?php
namespace app\admin\controller;

use app\common\behavior\BaseAdmin;

class Index extends BaseAdmin
{

    public function index()
    {
        //$ret = $this->GetColumnInfo('menu');
        $ret = $this->GetCategory('menu');
        dump($ret);
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