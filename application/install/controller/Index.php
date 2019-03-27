<?php
namespace app\install\controller;

use think\Controller;

class Index extends Controller
{
    protected function initialize()
    {
       /*  $model = model('index');
        if(file_exists('./install.lock')){
            echo '<br>检测到install.lock文件';
        } else {
            echo '<br>未检测到install.lock文件';
        }
        echo '<br>继续检测数据库是否存在超级系统管理员';
        if(!$model->index()){
            echo '<br>数据库中没有最高权限管理员，系统未安装';
        } else {
            echo '<br>数据库中存在最高权限管理员，系统已安装';
            echo '<br><a href="">创建install.lock文件</a>';
        } */

    }

    public function index()
    {
        return '<br>123';
    }

    public function confirm()
    {
        $this->assign([
            //'title' => '程序安装检测',       
        ]);
        return view();
    }
}