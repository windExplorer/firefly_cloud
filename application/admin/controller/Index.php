<?php
namespace app\admin\controller;

use app\common\behavior\BaseAdmin;

class Index extends BaseAdmin
{

    protected $pjax = 0;
    /**
     * 初始化 
     * 1.检测是否安装程序
     */
    protected function initialize()
    {
        $this->checkInstall();
        $this->checkLogin();
        if(request()->isPjax())
            $this->pjax = 1;

        $this->assign('pjax', $this->pjax);
    }
 

    public function index()
    {

        return view();
    }

    public function index2()
    {

        return view();
    }

}