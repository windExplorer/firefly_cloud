<?php
namespace app\admin\controller;

use app\common\behavior\BaseAdmin;

class System extends BaseAdmin
{

    public function menu()
    {
      $table = 'menu';
      if(!request()->isPost()){
        //系统
        $this->assign([
          'status'      =>  config('dbrule.status'),
          'is_deleted'  =>  config('dbrule.is_deleted'),
          'menu_type'   =>  config('dbrule.menu_type'),
          'table'       =>  $table,
        ]);
        //附加参数
        $this->assign([
          'menu_title'  =>  '菜单配置',
          'menu_icon'   =>  'layui-icon layui-icon-layouts',
          'menu'        =>  $this->Retrieve($table, '', 0),
          'treedom'     =>  $this->GetChildren($table, '<option>', '</option>')['dom']
        ]);
        return view();
      }

      //表单提交
      $res = input('post.');
      $data = $this->SearchRetrieve($table, $res);
    }
    public function menu_view()
    {
      $table = 'menu';
      $id = input('post.id');
      $this->assign([
        'data'  =>  $this->Retrieve($table, $this->where, 1)
      ]);
      return view();
    }
    public function menu_edit()
    {
      $table = 'menu';
      $id = input('post.id');
      $this->assign([
        'data'  =>  $this->Retrieve($table, $this->where, 1)
      ]);
      return view();
    }
    public function menu_del()
    {

    }

    


}