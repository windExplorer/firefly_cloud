<?php
namespace app\common\behavior;

use think\Controller;
use think\Db;
use thnk\facade\APP;


class BaseAdmin extends Controller 
{

    /**
     * admin所有都需要登录才允许进入
     */
    
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
        if(!file_exists('../install.lock')){
            $this->redirect(url('install/index/confirm'));
        }
    }

    /**
     * 检测是否登录
     * 
     */
    public function checkLogin(){
        //检测session('admin')是否存在
        if(!session('?admin')){
            $this->redirect(url('admin/Login/index'));
        }
    }

    /**
     * 权限检测
     */
    public function checkAuth()
    {

    }

    /**
     * XSS过滤
     * @param string $dirty_html 需要过滤的字符串
     * @return string $clean_html 过滤后的字符串
     */
    public function RemoveXss($dirty_arr = [])
    {
        if(!empty($dirty_arr)){
            $config = \HTMLPurifier_Config::createDefault();
            //配置
            $purifier = new \HTMLPurifier($config);
            foreach($dirty_arr as $k => $v){
                $dirty_arr[$k] = $purifier->purify($v);
            }
            return $dirty_arr;
        }
        
        return $clean_html;
    }

    /**
     * 查询数据库
     *  
     */
    public function Retrieve($table, $where = '', $order = 'id asc', $limit = 1, $page = 0)
    {
        if($limit !== 1)
            return db($table)->where($where)->order($order)->limit($limit * $page, $limit)->select();
        else
            return db($table)->where($where)->order($order)->find();
    }

    /**
     * 新增数据
     * 返回id
     */
    public function Create($table, $data)
    {
        return db($table)->insertGetId($data);
    }

    /**
     * 写管理员日志表
     *  
     */
    public function Addlog($table, $info, $admin_log_type)
    {
        $header = \Request::header();
        $admin = session('admin');
        $data = [
            'admin_id'          =>  $admin['id'],
            'ref'               =>  empty($header['referer']) ? ' ' : $header['referer'],
            'url'               =>  $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
            'table'             =>  $table,
            'info'              =>  $info,
            'ip'                =>  getIP(),
            'location'          =>  getGeo2(),
            'user_agent'        =>  $header['user-agent'],
            'admin_log_type'    =>  $admin_log_type,
            'status'            =>  1,
            'is_deleted'        =>  0,
            'regtime'           =>  time(),
            'uptime'            =>  time()
        ];
        return $this->Create('admin_log', $data);
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
            'database'    => 'firefly_cloud',
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