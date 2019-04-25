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
            $table = 'user';
            $user = $this->checkToken();
            if(!empty($user)){
                $this->Update($table, $user['id'], ['token' => '', 'uptime' => time()]);
                $this->Addlog($table, '退出成功', 5);
                return $this->Restful(true, 1, '退出成功');
            }else{
                return $this->Restful(false, 0, '退出失败，查无此用户');
            }
        
            
        }

        public function register(){
            $table = 'user';
            $res = input('post.');
            $res = $this->removeXSS($res);
            foreach($res as $k => $v){
                $res[$k] = str_replace(' ','',$res[$k]);
            }
            # 注册检测 
            # 0.检测是否开放注册
            # 1.检测用户名是否存在并且是否合法  
            # 2.检测密码是否合法 
            # 3.检测邮箱是否存在 
            # 4.检测邀请码是否存在或已被使用(第一个用户的邀请码请在配置表中设置(user_first_invite_code))
            # 5.根据用户默认分配值检测系统磁盘空间是否能够分配给该用户 (user_default_space)
            # 6.分配完成后判断系统是否能够进行下一个用户注册，如果不足默认分配的空间，就修改系统参数表，停止注册 open_register,停止邀请码发放 create_invite_code
            # 7.数据组装
            # 8.判断是否能够发放邀请码

            ## 0.

            if(sysConf('open_register') == 'false' || sysConf('open_register') == '0'){
                return $this->Restful(false, 0, '注册未开放!');
            }
            ## 1.
            if(!empty($res['username'])){
                $check = $this->Retrieve($table, ['username' => $res['username']]);
                if(!empty($check)){
                    return $this->Restful(false, 0, '用户名已存在!');
                }
                if(mb_strlen($res['username']) < 4 || mb_strlen($res['username']) > 12){
                    return $this->Restful(false, 0, '用户名字符数在4-12之间,且不能有空格!');
                }
                if(!preg_match("/^[A-Za-z0-9]+$/", $res['username'])){
                    // return $this->Restful(false, 0, '用户名不能是特殊字符!');
                }
            }else{
                return $this->Restful(false, 0, '请输入用户名!');
            }
            ## 2.
            if(!empty($res['password'])){
                if(mb_strlen($res['password']) < 5 || mb_strlen($res['password']) > 16){
                    return $this->Restful(false, 0, '密码字符数在5-16之间,且不能有空格!');
                }
            }else{
                return $this->Restful(false, 0, '请输入密码!');
            }
            ## 3.
            if(!empty($res['email'])){
                $check = $this->Retrieve($table, ['email' => $res['email']]);
                if(!empty($check)){
                    return $this->Restful(false, 0, '邮箱已存在!');
                }
                if(!filter_var($res['email'], FILTER_VALIDATE_EMAIL)){
                    return $this->Restful(false, 0, '不是一个合法的邮箱地址!');
                }
            }else{
                return $this->Restful(false, 0, '请输入邮箱!');
            }

            ## 4.
            if(!empty($res['reg_invite_code'])){
                $check = $this->Retrieve($table, ['reg_invite_code' => $res['reg_invite_code']]);
                if(!empty($check)){
                    return $this->Restful(false, 0, '邀请码已被使用!');                    
                }
                $check = $this->Retrieve($table, ['invite_code' => $res['reg_invite_code']]);
                if(empty($check)){
                    $default_invite_code = explode(',', sysConf('user_first_invite_code'));
                    if(!in_array($res['reg_invite_code'], $default_invite_code))
                        return $this->Restful(false, 0, '邀请码不存在!');                    
                }
            }else{
                return $this->Restful(false, 0, '请输入邀请码!');
            }

            ## 5. 剩余空间 = 磁盘空闲空间+用户已使用空间-用户已分配空间 
            $free = disk_free_space('/') + db('user')->sum('use_size') - db('user')->sum('total_size');
            if($free < (int)sysConf('user_default_space')){
                set_sysConf('open_register', 'false');
                return $this->Restful(false, 0, '注册已强制关闭，请与站长联系!');
            }
            ## 6.
            if($free < (int)sysConf('user_default_space') * 2){
                set_sysConf('create_invite_code', 'false');
            }
            ## 7.
            $salt = getLenRand(5);
            $pwd = sha1($pwd.$salt);
            $sql = [
                'username' => $res['username'],
                'password' => $pwd,
                
            ];
            ## 8.
            if(sysConf('create_invite_code') == 'true' || (int)sysConf('create_invite_code') == 1){
                $sql['invite_code'] = getLenRand(8);
            }

            
            

            
        }
    }
