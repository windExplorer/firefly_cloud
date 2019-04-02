<?php
namespace app\admin\controller;

use app\common\behavior\BaseAdmin;

class Login extends BaseAdmin
{

  protected $table = 'admin';
  /**
   * 初始化 
   * 1.检测是否安装程序
   */
  protected function initialize()
  {
      $this->checkInstall();
  }


  public function index()
  {
    $this->assign([
      'title' =>  '登录'
    ]);
    return view();
  }

  public function check()
  {
    $res = input('post.');
    $res = $this->removeXSS($res);
    $info = $this->Retrieve($this->table, ['username' => $res['username']]);
    $res['password'] = sha1($res['password'].$info['salt']); 
    $res = $this->Retrieve($this->table, $res);
    if(!empty($res)){
      session('admin', $res);
      $this->Addlog('admin', '登录成功', 4);
    }
    return $this->Result($res);
  }

  public function logout()
  {
    session(null);
    //data  code  msg
    return $this->Result(true, 1, '退出成功');
  }

}