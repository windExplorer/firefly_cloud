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
                $this->Token = sha1($res['username'].time());
                $now = time();
                //当前连续登录天数-最多连续登录天数-总登录天数
                # 1.查询今日有没有登录，若登陆了就不用进行其他操作
                $today = db('user_log')->whereTime('regtime', 'today')->where('user_log_type', 4)->where('user_id', $res['id'])->find();
                if(!empty($today)){
                    $data = [
                        'token' => $this->Token,
                        'uptime' => $now
                    ];
                }else{
                    $login_total_day = $res['login_total_day'] + 1;
                    # 2.查询昨日有没有登录，如果登陆了，就在login_day+1 ,如果没有就重置登录天数=1
                    $yesterday = db('user_log')->whereTime('regtime', 'yesterday')->where('user_log_type', 4)->where('user_id', $res['id'])->find();
                    if(!empty($yesterday)){
                        $login_day = $res['login_day'] + 1;
                    }else{
                        $login_day = 1;
                    }
                    # 3.判断$login_day是否比login_max_day大
                    if($login_day > $res['login_max_day']){
                        $login_max_day = $login_day;
                    }else{
                        $login_max_day = $res['login_max_day'];
                    }
                    $data = [
                        'login_day' => $login_day,
                        'login_max_day' =>  $login_max_day,
                        'login_total_day'   =>  $login_total_day,
                        'token' => $this->Token,
                        'uptime' => $now
                    ];
                    $res['login_day'] = $login_day;
                    $res['login_max_day'] = $login_max_day;
                    $res['login_total_day'] = $login_total_day;

                }                
                $this->Update($table, $res['id'], $data);
                $res['token'] = $this->Token;
                $res['uptime'] = $now;
                $this->User = $res;
                $this->Addlog($table, '登录成功', 4);
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

            # 为用户增加一个根目录文件夹

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
                if(strlen($res['username']) < 1 || strlen($res['username']) > 24){
                    return $this->Restful(false, 0, '用户名字符数在1-24之间(中文等占3个，字母数字等占1个),且不能有空格!');
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
                return $this->Restful(false, 0, '注册通道已被强制关闭，请与站长联系!');
            }
            ## 6.
            if($free < (int)sysConf('user_default_space') * 2){
                set_sysConf('create_invite_code', 'false');
            }
            ## 7.
            $salt = getLenRand(5);
            $pwd = sha1($res['password'].$salt);
            $sql = [
                'username'          =>  $res['username'],
                'password'          =>  $pwd,
                'salt'              =>  $salt,
                'email'             =>  $res['email'],
                'reg_invite_code'   =>  $res['reg_invite_code'],
                'nickname'          =>  $res['username'],
                'avatar'            =>  '/static/source/img/userHead.jpeg',
                'total_size'        =>  sysConf('user_default_space'),
                'born'              =>  date('Y-m-d'),
                'regtime'           =>  time(),
                'uptime'            =>  time()
            ];
            ## 8.
            if(sysConf('create_invite_code') == 'true' || (int)sysConf('create_invite_code') == 1){
                $sql['invite_code'] = getLenRand(8);
            }

            ## 9.创建
            $userId = $this->Create($table, $sql);
            if(empty($userId)){
                return $this->Restful(false, 0, '注册失败，原因未知，请尝试与站长联系!');
            }

            ## 10.写权重
            $weigh = $this->Update($table, $userId, ['weigh' => $userId, 'uptime' => time()]);

            ## 11.为用户增加一条根目录
            $this->Create('folder', [
                'pid'               =>  0,
                'user_id'           =>  $userId,
                'name'              =>  '首页',
                'path'              =>  '',
                'pid_path'          =>  '',
                'remark_context'    =>  '注册时系统为用户创建的根目录，无法删除',
                'regtime'           =>  time(),
                'uptime'            =>  time()
            ]);
            
            ## 12.写注册日志
            $this->User = [
                'id'    =>  $userId
            ];
            $this->Addlog($table, '('.$res['username'].')注册成功', -1);
            
            ## 13.给注册邮箱发送欢迎消息
            $subject = '【'.sysConf('website_group_name').'】注册成功!';
            $content = '<div style="width:500px;height:500px;background:lightcoral;margin:auto;padding: 30px;text-align: center;margin-top: 50px;margin-bottom:150px;border-radius:4px;">
            <style>a{color: lightskyblue;transition: .5s;opacity: 1;margin-right: 20px;}a:hover{opacity: .7;}</style>
            <h1 style="font-size: 28px;margin-top: 150px;">
                <p style="font-weight: normal;font-size: 18px;color:#333;">亲爱的 <i style="color:lightskyblue;">'.$res['username'].'</i> , 您已于 '.date('Y年m月d日 H时i分s秒').' 正式成为本站会员。</p>
                <a href="'.request()->header()['origin'].'" target="_blank" style="text-decoration: none;font-style: italic;font-size: 40px;">'.sysConf('website_group_name').'</a>热烈欢迎您的加入!
            </h1></div>';
            $this->sendMail($res['email'], $subject, $content, $res['username'], '欢迎加入'.sysConf('website_group_name'), $text = '发送注册欢迎消息');

            ## 14.返回注册成功消息
            return $this->Restful(true, 1, '注册成功,将跳转至登录页面!');
            
        }

        /* 找回密码 */
        public function resetpwd()
        {
            $table = 'user';
            $res = input('post.');

            ## 1.
            if(!empty($res['username'])){
                $check = $this->Retrieve($table, ['username' => $res['username']]);
                if(empty($check)){
                    return $this->Restful(false, 0, '用户名不存在!');
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
            if(!empty($res['password2'])){
                if($res['password'] !== $res['password2']){
                    return $this->Restful(false, 0, '两次密码不一致!');
                }
            }else{
                return $this->Restful(false, 0, '请输入密码(再一次)!');
            }
            ## 4.
            if(!empty($res['vcode'])){
                if($res['vcode'] !== $check['vcode']){
                    return $this->Restful(false, 0, '验证码错误!');
                }
            }else{
                return $this->Restful(false, 0, '请输入验证码!');
            }
            ## 5.
            $salt = getLenRand(5);
            $pwd = sha1($res['password'].$salt);
            $this->User = $check;
            $flag = $this->Update($table, $check['id'], ['password' => $pwd, 'salt' => $salt, 'vcode' => '', 'uptime' => time()]);
            if($flag){
                $this->Addlog($table, '('.$res['username'].')找回密码成功', 2);
                return $this->Restful(true, 1, '找回密码成功!');
            }else{
                return $this->Restful(false, 0, '找回密码失败, 请与站长联系!');
            }

        }

        /**
         *  获取验证码 
         *  email: 邮箱
         *  type: 1.找回密码  2.修改邮箱
         * 
         * */
        public function getvcode()
        {
            $table = 'user';
            $res = input('post.');
            switch($res['type']){
                case 1: $subject_text = '找回密码'; $content_href = request()->header()['origin'].'/resetpwd'; $content_href_text = '重置密码链接'; $logtext = '找回密码'; break;
                case 2: $subject_text = '修改邮箱'; $content_href = 'javascript:;'; $content_href_text = '修改邮箱'; $logtext = '修改邮箱'; break;
                default: $subject_text = '其他'; $content_href = 'javascript:;'; $content_href_text = '其他'; break;
            }
            if(!empty($res['email'])){
                if(!filter_var($res['email'], FILTER_VALIDATE_EMAIL)){
                    return $this->Restful(false, 0, '不是一个合法的邮箱地址!');
                }
                $check = $this->Retrieve($table, ['email' => $res['email']]);
                if(empty($check)){
                    return $this->Restful(false, 0, '邮箱不存在!');
                }
            }else{
                return $this->Restful(false, 0, '请输入邮箱!');
            }

            $res = $check;
            $this->User = $check;

            # 发送邮件
            $vcode = getLenRand(6);
            $subject = '【'.sysConf('website_group_name').'】'.$subject_text.'!';;
            $content = '<style>
            .content {
                width: 500px;
                height: 500px;
                background: lightcoral;
                margin: auto;
                padding: 30px;
                text-align: left;
                line-height: 40px;
                margin-top: 50px;
                margin-bottom: 150px;
                border-radius: 4px;
                font-weight: bold;
                letter-spacing: 2px;
                font-family: "Helvetica Neue",Helvetica,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","微软雅黑",Arial,sans-serif;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
                color: #2c3e50;
            }
            .box{
                margin-top: 100px;
            }
        
            a {
                transition: .5s;
                opacity: 1;
                margin-right: 20px;
                text-decoration: none;
                font-style: italic;
            }
        
            a:hover {
                opacity: .7;
            }
            .important{
                font-weight: bolder;
                color: lightblue;
                font-size: 30px;
                margin-left: 10px;
            }
            .important-1{
                font-size: 20px;
            }
        </style>
        <div class="content">
            <div class="box">
                <p><a href="'.$content_href.'" target="_blank" class="important important-1">'.$content_href_text.'</a></p>
                <p> (该验证码为一次性，无时间限制，但重复获取将被替代)!</p>
                <p>亲爱的 <i class="important">'.$check['username'].'</i> </p>
                <p>验证码为: <i class="important">'.$vcode.'</i> </p>
            </div>
        </div>';
            $context = '用户名：'.$check['username']. '   验证码：'.$vcode.'   (验证码一次性且长期有效，但重复获取将被替代!)';
            $send_flag = $this->sendMail($res['email'], $subject, $content, $check['username'], $context, '发送'.$logtext.'验证码');
            if(!$send_flag){
                return $this->Restful(false, 0, '验证码发送失败，请联系站长！');
            }

            #将验证码写入表中
            $this->Update($table, $check['id'], ['vcode' => $vcode, 'uptime' => time()]);

            return $this->Restful(true, 1, '验证码已发送至您的邮箱，请注意查收，也请注意查看垃圾箱!');

        }

        /* 修改密码 */
        public function changepwd()
        {
            $table = 'user';
            $res = input('post.');
            $res = $this->removeXSS($res);
            foreach($res as $k => $v){
                $res[$k] = str_replace(' ','',$res[$k]);
            }
            $user = $this->checkToken();
            if(empty($user)){
                return $this->Restful(false, 0, '令牌错误，请重新登录');
            }
            if($res['password1'] == $res['password2']){
                return $this->Restful(false, 0, '新密码没有改动'); 
            }
            if(sha1($res['password1'].$user['salt']) !== $user['password']){
                return $this->Restful(false, 0, '原密码不正确');                
            }
            if($res['password2'] !== $res['password3']){
                return $this->Restful(false, 0, '两次新密码不一致');
            }
            if(mb_strlen($res['password2']) < 5 || mb_strlen($res['password2']) > 16){
                return $this->Restful(false, 0, '密码字符数在5-16之间,且不能有空格!');
            }
            #
            $salt = getLenRand(5);
            $pwd = sha1($res['password2'].$salt);
            $flag = $this->Update($table, $user['id'], ['password' => $pwd, 'salt' => $salt, 'uptime' => time()]);
            if($flag){
                $this->Addlog($table, '('.$user['username'].')修改密码成功', 2);
                return $this->Restful(true, 1, '修改密码成功!');
            }else{
                return $this->Restful(false, 0, '修改密码失败, 请与站长联系!');
            }
        }

        /* 修改邮箱 */
        public function changeemail()
        {
            $table = 'user';
            $res = input('post.');
            $res = $this->removeXSS($res);
            foreach($res as $k => $v){
                $res[$k] = str_replace(' ','',$res[$k]);
            }
            $user = $this->checkToken();

            if(empty($user)){
                return $this->Restful(false, 0, '令牌错误，请重新登录');
            }
            if(!empty($res['vcode'])){
                if($res['vcode'] !== $user['vcode']){
                    return $this->Restful(false, 0, '验证码错误!');
                }
            }else{
                return $this->Restful(false, 0, '请输入验证码!');
            }

            if($res['email'] == $user['email']){
                return $this->Restful(false, 0, '邮箱没有变化!');
            }
            if($res['email'] !== $res['email2']){
                return $this->Restful(false, 0, '两次新邮箱不一致!');
            }
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
            $flag = $this->Update($table, $user['id'], ['email' => $res['email'], 'vcode' => '', 'uptime' => time()]);
            if($flag){
                $this->Addlog($table, '('.$user['username'].')修改邮箱成功', 2);
                return $this->Restful(true, 1, '修改邮箱成功!');
            }else{
                return $this->Restful(false, 0, '修改邮箱失败, 请与站长联系!');
            }

        }

        public function changebase()
        {
            $table = 'user';
            $res = input('post.');
            $res = $this->removeXSS($res);
            foreach($res as $k => $v){
                $res[$k] = str_replace(' ','',$res[$k]);
            }
            $user = $this->checkToken();

            if(empty($user)){
                return $this->Restful(false, 0, '令牌错误，请重新登录');
            }
            # 1.判断昵称是否为空
            if(empty($res['nickname'])){
                return $this->Restful(false, 0, '请输入昵称');
            }
            if(strlen($res['nickname']) < 1 || strlen($res['nickname']) > 24){
                return $this->Restful(false, 0, '昵称字符数在1-24之间(中文等占3个，字母数字等占1个),且不能有空格!');
            }

            if($res['nickname'] == $user['nickname'] && 
                $res['born'] == $user['born'] && 
                $res['gender'] == $user['gender'] && 
                $res['sign_context'] == $user['sign_context'] && 
                $res['description_context'] == $user['description_context']
            ){
                return $this->Restful(false, 0, '没有改动!');
            }

            # 2.其他暂时不需要判断
            $sql = [
                'nickname'              =>  $res['nickname'],
                'born'                  =>  $res['born'],
                'gender'                =>  $res['gender'],
                'sign_context'          =>  $res['sign_context'],
                'description_context'   =>  $res['description_context'],
                'uptime'                =>  time()
            ];
            $flag = $this->Update($table, $user['id'], $sql);
            if($flag){
                $this->Addlog($table, '('.$user['username'].')修改基本资料成功', 2);
                return $this->Restful(true, 1, '修改基本资料成功!');
            }else{
                return $this->Restful(false, 0, '修改基本资料失败!');
            }
        }

        /* 上传头像 */
        public function upavatar(){
            $user = $this->checkToken();
            if(empty($user)){
                return $this->Restful(false, 0, '令牌错误，请重新登录');
            }
            $file = request()->file('file');
            $res = input('post.');
            $table = api_upload_type($res['type']);
            $size = 1 * 1024 * 1024;
            $ext = 'jpg,png,gif,jpeg,bmp';
            $ret = $this->upFile($file, $table,  $res['type'], '', $size, $ext);
            $this->Addlog($table, $ret['msg'], 7);
            if($ret['code'] == 0){
                return $this->Restful(false, 0, $ret['msg']);
            }
            # 写更新数据库
            $flag = $this->Update('user', $user['id'],['avatar' => $ret['url'], 'uptime' => time()]);
            if(empty($flag)){
                return $this->Restful(false, 0, '更新头像失败!');
                $this->Addlog('user', '('.$user['username'].')修改头像失败', 2);
            }
            $this->Addlog('user', '('.$user['username'].')修改头像成功', 2);
            return $this->Restful($ret, 1, '更新头像成功!');
        }

    }
