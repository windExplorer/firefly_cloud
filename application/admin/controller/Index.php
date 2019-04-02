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
        if(request()->isPjax())
            $this->pjax = 1;
        else {
            $this->checkInstall();
            $this->checkLogin();
            /* dump($this->getMenu());
            die; */
            $this->assign([
                'admin'     =>  session('admin'),
                'nav'       =>  $this->getMenu(),
                'thUrl'     =>  request()->url()
            ]);
        }

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