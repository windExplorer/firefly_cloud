<?php
namespace app\install\controller;

use think\Controller;

class Index extends Controller
{
    /**
     * 1.install.lock是否存在
     * 2.数据库是否存在
     * 3.系统管理员是否存在 
     */
    protected $file = '';
    protected $db = '';
    protected $admin = '';
    protected $flag = '';
    protected $model = '';
    protected $pjax = 0;

    protected function initialize()
    {
        $this->pjax = request()->isPjax() ? 1 : 0;
        $this->model = model('index');
        if(file_exists('../install.lock')){
            $this->file = 1;
        } else {
            $this->file = 0;
        }
        $dbInfo = $this->model->index();
        if(!$dbInfo[0]){
            if('err' === $dbInfo[1]){
                $this->db = 0;
                $this->admin = 0;
            }
            if('emp' === $dbInfo[1]){
                $this->db = 1;
                $this->admin = 0;
            }
        } else {
            $this->db = 1;
            $this->admin = 1;
            
        }

        //判定
        if(1 == $this->file && 1 == $this->db && 1 == $this->admin){
            //全部正常(无需安装)
            $this->flag = 2;
        }
        if(0 == $this->file && 1 == $this->db && 1 == $this->admin){
            //没有安装锁文件+数据库连接正常+系统管理员存在(需新建安装锁文件)
            $this->flag = 1;
        }
        if(0 == $this->file && 1 == $this->db && 0 == $this->admin){
            //没有安装锁文件+数据库正常+没有系统管理员(需新建安装锁文件+新增系统管理员)
            $this->flag = 0;
        }
        if(0 == $this->file && 0 == $this->db && 0 == $this->admin){
            //没有安装锁文件+数据库连接异常+系统管理员未知(检测数据库配置+然后重试)
            $this->flag = -1;
        }
        if(1 == $this->file && 0 == $this->db && 0 == $this->admin){
            //有安装锁文件+数据库连接异常+系统管理员未知(检测数据库配置+然后重试)
            $this->flag = -2;
        }
        if(1 == $this->file && 1 == $this->db && 0 == $this->admin){
            //有安装锁文件+数据库连接正常+没有系统管理员(需要新增系统管理员)
            $this->flag = -3;
        }

    }

    public function index()
    {
        $this->assign([
            'flag'  =>  $this->flag
        ]);
        return view();
    }

    public function confirm()
    {
        $this->assign([
            'title' => '安装检测',
            'file'  =>  $this->file,
            'db'    =>  $this->db,
            'admin' =>  $this->admin,
            'flag'  =>  $this->flag,
            'pjax'  =>  $this->pjax
        ]);
        return view();
    }

    
    public function upfile()
    {
        $file = $this->model->create_file();
        if(empty($file))
            return $this->error($file);
        else
            return $this->success('文件写入成功!');
    }

    public function upadmin()
    {
        $admin = $this->model->create_admin();
        if(empty($admin))
            return $this->error($admin);
        else{
            return $this->success('用户名：admin  密码：'.$admin['password'].'  (密码可以在install.lock文件中查看)');
        }
            
    }

    public function upfile_admin(){
        $file = $this->model->create_file();
        if(empty($file))
            return $this->error($file);
        else
            return $this->success('文件写入成功!');
    }

}