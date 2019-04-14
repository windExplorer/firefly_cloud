<?php
namespace app\admin\controller;

use app\common\behavior\BaseAdmin;

class User extends BaseAdmin
{

  public function admin()
  {
    $table = 'admin';
    //固定参数
    $this->assign([
      'table'       =>  $table,
      'cols'        =>  $this->GetColumnInfo($table),
      'menu_title'  =>  '管理员用户',
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
    
  public function userinfo()
  {
    $table = 'admin';
    //固定参数
    $this->assign([
      'table'       =>  $table,
      'cols'        =>  $this->GetColumnInfo($table),
      'data'        =>  $data = $this->Retrieve($table, ['id' => session('admin.id')]),
      'event'       =>  'edit',
      'menu_title'  =>  '基本资料',
      'menu_icon'   =>  'layui-icon layui-icon-layouts',
    ]);
    //附加参数
    $this->assign([

    ]);
    return view();
  }


    


}