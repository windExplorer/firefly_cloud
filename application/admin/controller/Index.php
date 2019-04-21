<?php
namespace app\admin\controller;

use app\common\behavior\BaseAdmin;

class Index extends BaseAdmin
{

    public function index()
    {
        // 快捷方式 8个
        // 用户统计
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
            

        ]);
        
        return view();
    }

    public function index2()
    {

        // 环境信息
        return view();
    }

    /**
     * 
      */


}