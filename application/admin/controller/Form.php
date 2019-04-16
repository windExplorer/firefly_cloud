<?php
namespace app\admin\controller;

use app\common\behavior\BaseAdmin;

class Form extends BaseAdmin
{
  //Result   //data  code  msg

  /* 数据表格 */
  public function data_table()
  {
    $get = input('get.');
    $db = db($get['table']);
    if(input('?get.data')){
      $data = $get['data'];
      if(!empty($data['regtime'])){
        $t = TurnTime($data['regtime']);
        $db = $db->whereTime('regtime', 'between', [$t[0], $t[1]]);
      }
      if(!empty($data['uptime'])){
        $t = TurnTime($get['data']['uptime']);
        $db = $db->whereTime('uptime', 'between', [$t[0], $t[1]]);
      }
      unset($data['regtime']);
      unset($data['uptime']);
      if($get['blur'] == 0){
        $db = $db->where($data);
      }else{
        $db = $db->where(BlurSearch($data));
      }
    }
    $res = $db->where('is_deleted', 0)->page($get['page'], $get['limit'])->order('id desc')->select();
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
      $this->Addlog($post['table'], '修改[id:'.$post['id'].']的状态失败', 2);
      return $this->Result(false, 0, '状态修改失败');
    } else {
      $this->Addlog($post['table'], '修改[id:'.$post['id'].']的状态成功', 2);
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
    $pid_dom = '';
    $user_id = '';
    $admin_id = '';
    $data = [];
    $default = [];

    if($this->CheckTableField($post['table'], 'user_id')){
      $user_id = $this->Retrieve('user', $this->where, 0);
    }
    if($this->CheckTableField($post['table'], 'admin_id')){
      $admin_id = $this->Retrieve('admin', $this->where, 0);
    }

    if($post['event'] == 'add'){
      if($this->CheckTableField($post['table'], 'pid')){
        $pid_dom = $this->GetChildren($post['table'], '<option>', '</option>')['dom'];
      }
      $default['weigh'] = $this->Retrieve($post['table'], '', 1, 0, 'id desc')['id'] + 1;
    }else{
      $this->where = [
        'is_deleted'  => 0,
        'id'          =>  $post['id'],  
      ];
      $data = $this->Retrieve($post['table'], $this->where);
      if($post['event'] == 'edit'){
        if($this->CheckTableField($post['table'], 'pid')){
          $pid_dom = $this->GetChildren($post['table'], '<option>', '</option>', $data['pid'])['dom'];
        }
      }
    }
    $this->assign([
      'table' =>  $post['table'],
      'cols'  =>  $cols,
      'data'  =>  $data,
      'event' =>  $post['event'],
      'pid_dom'  =>  $pid_dom,
      'user_id' =>  $user_id,
      'admin_id'  =>  $admin_id,
      'default' =>  $default
    ]);
    return view();
  }

  /* icon */
  public function icon(){
    return view();
  }

  /* 新增数据 */
  public function event_add(){
    $msg = '';
    $post = input('post.');
    if($post['table'] == 'email'){
      $data = $post['data'];
      $user = $this->Retrieve('user', ['id' => $data['user_id']], 1);
      $nick = $user['nickname'];
      $send = $this->sendMail($data['to'], $data['subject'], $data['content'], $nick, $data['context'], $data['email_files'], $type = $data['email_type']);
      if($send){
        $data['is_success'] = 1;
        $msg = '[邮件发送成功!]';
      }else{
        $data['is_success'] = 0;
        $msg = "[邮件发送失败，请查看错误日志]";
      }
    }

    if(isset($post['data']['regtime'])){
      $post['data']['regtime'] = strtotime($post['data']['regtime']);
    }
    if(isset($post['data']['uptime'])){
      $post['data']['uptime'] = strtotime($post['data']['uptime']);
    }
    if(!empty($post['data']['password'])){
      $salt = getLenRand(5);
      $post['data']['password'] = sha1($post['data']['password'].$salt);
      $post['data']['salt'] = $salt;
    }
    //检测唯一性
    $check_unique = $this->SearchUnique($post['table'], $post['data'] , 0);
    if($check_unique['code'] == 0){
      return $this->Result(false, 0, '添加数据失败,'.$check_unique['msg']);
    }
    $ret = $this->Create($post['table'], $post['data']);
    if(empty($ret)){
      $this->Addlog($post['table'], '添加数据失败', 0);
      return $this->Result($ret, 0, '添加数据失败'.$msg);
    }else{
      $this->Addlog($post['table'], '添加[id:'.$ret.']数据项成功', 0);
      return $this->Result($ret, 1, '添加数据成功'.$msg);
    }
  }

  /* 编辑数据 */
  public function event_edit(){
    $msg = '';
    $post = input('post.');
    unset($post['data']['regtime']); //不进行regtime写库
    $post['data']['uptime'] = time(); //进行uptime记录

    if(!empty($post['data']['password'])){
      if(mb_strlen($post['data']['password'], 'utf-8') <= 16){
        $salt = getLenRand(5);
        $post['data']['password'] = sha1($post['data']['password'].$salt);
        $post['data']['salt'] = $salt;
      }
    }
    
    //检测是否修改了
    $check_edit = $this->SearchEdit($post['table'], $post['data']);
    if(!$check_edit){
      return $this->Result(false, 0, '没有数据被修改');
    }

    //重发
    /* if($post['table'] == 'email'){
      $data = $post['data'];
      $user = $this->Retrieve('user', ['id' => $data['user_id']], 1);
      $nick = $user['nickname'];
      $send = $this->sendMail($data['to'], $data['subject'], $data['content'], $nick, $data['context'], $data['email_files'], $type = $data['email_type']);
      if($send){
        $data['is_success'] = 1;
        $msg = '[邮件发送成功!]';
      }else{
        $data['is_success'] = 0;
        $msg = "[邮件发送失败，请查看错误日志]";
      }
    } */

    //检测唯一性
    $check_unique = $this->SearchUnique($post['table'], $post['data'] , 0);
    if($check_unique['code'] == 0){
      return $this->Result(false, 0, '修改数据失败,'.$check_unique['msg']);
    }
    $ret = $this->Update($post['table'], $post['data']['id'], $post['data']);
    if(empty($ret)){
      $this->Addlog($post['table'], '修改[id:'.$post['data']['id'].']数据项失败', 2);
      return $this->Result($ret, 0, '修改数据失败'.$msg);
    }else{
      $this->Addlog($post['table'], '修改[id:'.$post['data']['id'].']数据项成功', 2);
      return $this->Result($ret, 1, '修改数据成功'.$msg);
    }
  }

  /* 检测文件是否已经存在 */
  public function check_file_exist(){
    $md5 = input('get.md5');
    $ret = checkFileExist($md5);
    if(!empty($ret)){
      return $this->Result($ret, 1, '文件存在');
    }else{
      return $this->Result($ret, 0, '文件不存在');
    }
  }

  /* 上传图片 */
  public function upload(){
    $file = request()->file('file');
    $table = input('table');
    $ret = $this->upImage($file, $table);
    //$this->Addlog($table, $ret['msg'], 0);
    return $ret;
  }

  /* 选择图片 */
  public function select_image()
  {
    $table = 'attachment';
    if(input('?get.first')){
      $page = input('get.page');
      $flag = 1;
    }else{
      $page = 1;
      $flag = 0;
    }
    $limit = 5;
    $where = [['mime', 'like', 'image%']];
    $data = $this->Retrieve($table, $where, $limit, $page, 'id desc');
    $total = getDbCount($table, $where);

    $this->assign([
      'data'  =>  $data,
      'flag'  =>  $flag,
      'total' =>  $total,
      'limit' =>  $limit,
    ]);
    return view();
  }

    /* 选择文件 */
    public function select_file()
    {
      $table = 'attachment';
      if(input('?get.first')){
        $page = input('get.page');
        $flag = 1;
      }else{
        $page = 1;
        $flag = 0;
      }
      $limit = 5;
      $where = '';
      $data = $this->Retrieve($table, $where, $limit, $page, 'id desc');
      $total = getDbCount($table, $where);
  
      $this->assign([
        'data'  =>  $data,
        'flag'  =>  $flag,
        'total' =>  $total,
        'limit' =>  $limit,
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
      $data = $this->Update($table, $sql['id'], $sql);
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