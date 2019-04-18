<?php
namespace app\admin\controller;

use app\common\behavior\BaseAdmin;

class File extends BaseAdmin
{

  public function attachment()
  {
    $table = 'attachment';
    //固定参数
    $this->assign([
      'table'       =>  $table,
      'cols'        =>  $this->GetColumnInfo($table),
      'menu_title'  =>  '后台附件',
      'menu_icon'   =>  'layui-icon layui-icon-layouts',
    ]);
    //附加参数
    $this->assign([

      //'menu'        =>  $this->Retrieve($table, '', 0),
      //'treedom'     =>  $this->GetChildren($table, '<option>', '</option>')['dom']
    ]);
    return view();
  }

  public function folder()
  {
    $table = 'folder';
    //固定参数
    $this->assign([
      'table'       =>  $table,
      'cols'        =>  $this->GetColumnInfo($table),
      'menu_title'  =>  '目录管理',
      'menu_icon'   =>  'layui-icon layui-icon-layouts',
    ]);
    //附加参数
    $this->assign([

      //'menu'        =>  $this->Retrieve($table, '', 0),
      //'treedom'     =>  $this->GetChildren($table, '<option>', '</option>')['dom']
    ]);
    return view();
  }
    
  public function file()
  {
    $table = 'file';
    //固定参数
    $this->assign([
      'table'       =>  $table,
      'cols'        =>  $this->GetColumnInfo($table),
      'menu_title'  =>  '用户文件',
      'menu_icon'   =>  'layui-icon layui-icon-layouts',
    ]);
    //附加参数
    $this->assign([

      //'menu'        =>  $this->Retrieve($table, '', 0),
      //'treedom'     =>  $this->GetChildren($table, '<option>', '</option>')['dom']
    ]);
    return view();
  }

  public function up_down()
  {
    $table = 'up_down';
    //固定参数
    $this->assign([
      'table'       =>  $table,
      'cols'        =>  $this->GetColumnInfo($table),
      'menu_title'  =>  '上传下载',
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