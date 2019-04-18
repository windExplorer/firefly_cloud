<?php
namespace app\admin\controller;

use app\common\behavior\BaseAdmin;

class Share extends BaseAdmin
{

  public function share()
  {
    $table = 'share';
    //固定参数
    $this->assign([
      'table'       =>  $table,
      'cols'        =>  $this->GetColumnInfo($table),
      'menu_title'  =>  '用户分享',
      'menu_icon'   =>  'layui-icon layui-icon-layouts',
    ]);
    //附加参数
    $this->assign([

      //'menu'        =>  $this->Retrieve($table, '', 0),
      //'treedom'     =>  $this->GetChildren($table, '<option>', '</option>')['dom']
    ]);
    return view();
  }

  public function share_comment()
  {
    $table = 'share_comment';
    //固定参数
    $this->assign([
      'table'       =>  $table,
      'cols'        =>  $this->GetColumnInfo($table),
      'menu_title'  =>  '分享评论',
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