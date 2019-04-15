<?php
namespace app\admin\controller;

use app\common\behavior\BaseAdmin;

class Mail extends BaseAdmin
{

  public function email()
  {
    $table = 'email';
    //固定参数
    $this->assign([
      'table'       =>  $table,
      'cols'        =>  $this->GetColumnInfo($table),
      'menu_title'  =>  '电子邮件',
      'menu_icon'   =>  'layui-icon layui-icon-layouts',
    ]);
    //附加参数
    $this->assign([

      //'menu'        =>  $this->Retrieve($table, '', 0),
      //'treedom'     =>  $this->GetChildren($table, '<option>', '</option>')['dom']
    ]);
    return view();
  }

  public function user()
  {
    $table = 'user';
    //固定参数
    $this->assign([
      'table'       =>  $table,
      'cols'        =>  $this->GetColumnInfo($table),
      'menu_title'  =>  '普通用户',
      'menu_icon'   =>  'layui-icon layui-icon-layouts',
    ]);
    //附加参数
    $this->assign([

      //'menu'        =>  $this->Retrieve($table, '', 0),
      //'treedom'     =>  $this->GetChildren($table, '<option>', '</option>')['dom']
    ]);
    return view();
  }
    
  public function admin_log()
  {
    $table = 'admin_log';
    //固定参数
    $this->assign([
      'table'       =>  $table,
      'cols'        =>  $this->GetColumnInfo($table),
      'menu_title'  =>  '管理员日志',
      'menu_icon'   =>  'layui-icon layui-icon-layouts',
    ]);
    //附加参数
    $this->assign([

      //'menu'        =>  $this->Retrieve($table, '', 0),
      //'treedom'     =>  $this->GetChildren($table, '<option>', '</option>')['dom']
    ]);
    return view();
  }

  public function user_log()
  {
    $table = 'user_log';
    //固定参数
    $this->assign([
      'table'       =>  $table,
      'cols'        =>  $this->GetColumnInfo($table),
      'menu_title'  =>  '用户日志',
      'menu_icon'   =>  'layui-icon layui-icon-layouts',
    ]);
    //附加参数
    $this->assign([

      //'menu'        =>  $this->Retrieve($table, '', 0),
      //'treedom'     =>  $this->GetChildren($table, '<option>', '</option>')['dom']
    ]);
    return view();
  }
    


}