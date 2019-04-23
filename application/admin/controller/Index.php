<?php
namespace app\admin\controller;

use app\common\behavior\BaseAdmin;

class Index extends BaseAdmin
{

    public function index()
    {
        // 快捷方式 8个
        // 用户统计
        // 管理员统计
        // 分享统计
        // 文件统计
        // 邮件统计
        // 签到统计-暂未开启
        // 积分排行
        // 最近活跃用户
        // 最近分享
        // 最近评论

        //固定参数
        $this->assign([
            'menu_title'  =>  '数据整理',
            //'menu_icon'   =>  'layui-icon layui-icon-layouts',
        ]);


        //附加参数
        $this->assign([
            'user_total'    =>  [
                'count' =>  db('user')->count(),
                'forbidden' =>  db('user')->where('status', 0)->count(),
                'login'     =>  count(db('user_log')->distinct(true)->field('user_id')->where('user_log_type', 4)->whereTime('regtime', date('Y-m-d'))->select()),
                'register'  =>  count(db('user_log')->distinct(true)->field('user_id')->where('user_log_type', 5)->whereTime('regtime', date('Y-m-d'))->select())
            ],
            'admin_total'   =>  [
                'count'     =>  db('admin')->count(),
                'login'     =>  count(db('admin_log')->distinct(true)->field('admin_id')->where('admin_log_type', 4)->whereTime('regtime', date('Y-m-d'))->select()),
                'today'     =>  db('admin_log')->whereTime('regtime', date('Y-m-d'))->count(),
                'all'       =>  db('admin_log')->count(),
            ],

        ]);
        
        return view();
    }

    public function index2()
    {
        // php版本
        // 服务器版本(apahce/nginx)
        // thinkphp版本
        // 服务器操作系统
        // 后台系统版本
        // mysql版本
        // 上传限制大小
        // POST大小
        // 系统磁盘总空间/剩余空间
        // 


        //dump(ini_get_all());die;
        //dump(phpinfo('System'));
        //dump(php_uname());
        //phpinfo(INFO_GENERAL);die;
        //$this->test();die;

        //固定参数
        $this->assign([
            'menu_title'  =>  '系统环境',
            //'menu_icon'   =>  'layui-icon layui-icon-layouts',
        ]);

        //附加参数
        $this->assign([
            'mysql_version'     =>  \Db::query("select version()")[0]['version()'],
            'disk_user_total'   =>  db('user')->sum('total_size'),
            'disk_user_free'    =>  db('user')->sum('use_size'),
        ]);

        // 环境信息
        return view();
    }

    /**
     * 
      */


}