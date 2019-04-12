<?php
namespace app\admin\controller;

use app\common\behavior\BaseAdmin;

class System extends BaseAdmin
{

  public function menu()
  {
    $table = 'menu';
    //固定参数
    $this->assign([
      'table'       =>  $table,
      'cols'        =>  $this->GetColumnInfo($table),
      'menu_title'  =>  '菜单配置',
      'menu_icon'   =>  'layui-icon layui-icon-layouts',
    ]);
    //附加参数
    $this->assign([

      //'menu'        =>  $this->Retrieve($table, '', 0),
      //'treedom'     =>  $this->GetChildren($table, '<option>', '</option>')['dom']
    ]);
    return view();
  }

  public function system_config()
  {
    $table = 'system_config';
    //固定参数
    $this->assign([
      'table'       =>  $table,
      'cols'        =>  $this->GetColumnInfo($table),
      'menu_title'  =>  '参数配置',
      'menu_icon'   =>  'layui-icon layui-icon-layouts',
    ]);
    //附加参数
    $this->assign([

    ]);
    return view();
  }
    


    


}