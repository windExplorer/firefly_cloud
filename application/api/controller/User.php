<?php
    namespace app\api\controller;

    use app\common\behavior\BaseApi;

    class User extends BaseApi{

        public function getuser(){
            $data = input();

            //return json($info);
            //return json($data);

            $data = db('user')->select();
            return $this->Restful($data, 1);
        }

        public function login()
        {
            $table = 'user';
            $res = input('post.');
            $res = $this->removeXSS($res);
            $info = $this->Retrieve($table, ['username' => $res['username']]);
            $res['password'] = sha1($res['password'].$info['salt']); 
            $res = $this->Retrieve($table, $res);
            if(!empty($res)){
                $this->User = $res;
                $this->Addlog($table, '登录成功', 4);
                $this->Token = sha1($res['username'].time());
                return $this->Restful($res, 1,'登录成功');
            }else{
                return $this->Restful($res, 0,'登录失败');
            }
            
        }
      
        public function logout()
        {
          $this->Addlog($this->table, '退出成功', 5);
          session(null);
          //data  code  msg
          return $this->Result(true, 1, '退出成功');
        }
    }
