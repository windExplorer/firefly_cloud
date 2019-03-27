<?php
namespace app\common\behavior;

use think\Controller;
use think\Db;
use thnk\facade\APP;

class BaseAdmin extends Controller 
{

    protected $beforeActionList = [
        'checkInstall',
    ];

    /**
     * 初始化 
     */
    protected function initialize()
    {

    }

    /**
     * 检测是否安装程序
     */
    public function checkInstall(){
        if(!file_exists('./install.lock')){
            $this->redirect(url('install/index/confirm'));
        }
    }

    /**
     * 权限检测
     */
    public function checkAuth()
    {

    }

    public function test()
    {
        $aa = Db::connect([
            // 数据库类型
            'type'        => 'mysql',
            // 数据库连接DSN配置
            'dsn'         => '',
            // 服务器地址
            'hostname'    => '127.0.0.1',
            // 数据库名
            'database'    => 'book_simple_cloud',
            // 数据库用户名
            'username'    => 'root',
            // 数据库密码
            'password'    => 'shenlu123',
            // 数据库连接端口
            'hostport'    => '',
            // 数据库连接参数
            'params'      => [],
            // 数据库编码默认采用utf8
            'charset'     => 'utf8',
        ])->table('admin')->find();
        dump($aa);
        dump(env('root_path'));
        dump(exec('cd '. env('root_path') .' && php think version'));
        dump(ini_get('upload_max_filesize'));
        dump(ini_get('max_file_uploads'));
        dump(app()->version());
        dump(app()->config());
        dump(env());

        return config();
    }
}