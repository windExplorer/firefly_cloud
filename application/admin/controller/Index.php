<?php
namespace app\admin\controller;

use app\common\behavior\BaseAdmin;

class Index extends BaseAdmin
{

    /**
     * 初始化 
     * 1.检测是否安装程序
     */
    protected function initialize()
    {
        $this->checkInstall();
    }
 

    public function index()
    {
        //echo \Env::get('root_path').'<br/>';
        debug('begin');
        dump($this->test(), $echo = true, $label = null);
        debug('end');
        dump(debug('begin', 'end'));

        return view();
    }
}