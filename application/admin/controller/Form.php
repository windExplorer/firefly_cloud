<?php
namespace app\admin\controller;

use app\common\behavior\BaseAdmin;

class Form extends BaseAdmin
{
  //Result   //data  code  msg

  /* 数据表格 */
  public function data_table()
  {
    $this->where = ['is_deleted' => 0];
    $get = input('get.');
    $res = $this->Retrieve($get['table'], $this->where, $get['limit'], $get['page'], 'id desc');
    $ret = [
      "code"  =>  0,
      "msg"   =>  '',
      "count" => count($this->Retrieve($get['table'], $this->where, 0)),
      "data"  => $res
    ];
    return $ret;
  }

  /* 数据表格状态 */
  public function dtb_status()
  {
    $post = input('post.');
    $data = [
      'status'  =>  $post['status'],
      'uptime'  =>  time()
    ];
    $res = $this->Update($post['table'], $post['id'], $data);
    if(empty($res)){
      return $this->Result(false, 0, '状态修改失败');
    } else {
      $this->Addlog($post['table'], '状态修改成功', 2);
      return $this->Result(true, 1, '状态修改成功');
    }
    
  }

  /* 查看-编辑-添加 */
  public function data_table_event()
  {
    $post = input('post.');
    if($post['event'] == 'del'){
      $post['id'] = (array)$post['id'];
      $ret = $this->Delete($post['table'], $post['id']);
      if(empty($ret)){
        $this->Addlog($post['table'], '删除[id:'.implode(',', $post['id']).']失败', 1);
        return  $this->Result(false, 0, '删除失败');

      }else{
        $this->Addlog($post['table'], '删除[id:'.implode(',', $post['id']).']成功', 1);
        return  $this->Result($ret, 1, '删除成功');
        
      }
    }
    $cols = $this->GetColumnInfo($post['table']);
    //dump($cols);
    $this->where = [
      'is_deleted'  => 0,
      'id'          =>  $post['id'],  
    ];
    $data = $this->Retrieve($post['table'], $this->where);
    //dump($data);
    $pids = [];
    if($this->CheckTableField($post['table'], 'pid')){
      $pids = $this->GetTree($post['table']);
    }
    $this->assign([
      'cols'  =>  $cols,
      'data'  =>  $data,
      'event' =>  $post['event'],
      'pids'  =>  $pids
    ]);
    return view();
  }

  /* 密码 */
  public function edit_password ()
  {
    if(!request()->isPost()){
      return view('edit_password');
    }
    $res = input('post.');
    $old = sha1($res['old'].session('admin.salt'));
    if ($old !== session('admin.password')) {
      return $this->Result(false, 0, '原密码匹配错误!');
    } else if ($res['new'] !== $res['new2']){
      return $this->Result(false, 0, '两次密码不一致!');
    } else if ($res['old'] === $res['new']){
      return $this->Result(false, 0, '新密码与原密码一致!');
    } else {
      $ret = [];
      $salt = getLenRand(5);
      $password = $res['new'];
      $sql = [
        'id'            =>  session('admin.id'),
        'password'      =>  sha1($password.$salt),
        'salt'          =>  $salt,
        'uptime'        =>  time()
      ];
      $table = 'admin';
      $data = $this->Update($table, $sql);
      if(empty($data)){
        return $this->Result(false, 0, '修改失败!');
      }else{
        $this->Addlog($table, '修改密码成功', 2);
        session(null);
        return $this->Result($data, 1, '修改成功,请重新登录!');
      }
    }
  }


}