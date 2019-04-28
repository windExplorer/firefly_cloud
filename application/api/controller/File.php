<?php
    namespace app\api\controller;

    use app\common\behavior\BaseApi;

    class File extends BaseApi{

        public function checkmd5()
        {
            $res = input('post.');
            $table = api_upload_type($res['type']);
            $user = $this->checkToken();
            if(empty($user)){
                return $this->Restful(false, 0, '令牌错误，请重新登录');
            }

            $flag = checkFileExist($res['md5'], $table);
            if(empty($flag)){
                return $this->Restful(true, -1, '查无此文件，可以进行上传');
            }else{
                if($res['type'] == 1)
                    return $this->Restful($flag, 1, '有文件，可以秒传');
                else if($res['type'] == 2){
                    return $this->saveFile($user, $res, $flag);
                }
            }

        }

        /* 获取文件列表，根据folder_id */
        public function get_file_list()
        {
            $res = input('post.');
            $table1 = 'folder';
            $table2 = 'file'; 
            $user = $this->checkToken();
            if(empty($user)){
                return $this->Restful(false, 0, '令牌错误，请重新登录');
            }
            $folder = api_parent_folder($res['folder_id'], $user);
            if(empty($folder)){
                return $this->Restful(false, 0, '没有找到根目录，请与管理员联系!');
            }else{
                $res['folder_id'] = $folder['id'];
            }
            
            $fid = $res['folder_id'];
            $list['folder'] = db($table1)->where([
                'user_id' => $user['id'],  
                'pid'   =>  $fid,
                'status'    =>  1,
                'is_deleted'    =>  0
            ])->order('id desc')->select();
            $list['file'] = db($table2)->where([
                'user_id' => $user['id'],
                'folder_id' =>  $fid,
                'status'    =>  1,
                'is_deleted'    =>  0
            ])->order('id desc')->select();

            return $this->Restful(['th' => $folder, 'list' => $list], 1, $folder['name'].' 目录的文件与子目录列表');
        }

        /* 新建文件夹 */
        public function create_folder()
        {
            $res = input('post.');
            $table = 'folder'; 
            $user = $this->checkToken();
            if(empty($user)){
                return $this->Restful(false, 0, '令牌错误，请重新登录');
            }
            $res = $this->removeXSS($res);
            foreach($res as $k => $v){
                $res[$k] = str_replace(' ','',$res[$k]);
            }
            if(empty($res['name'])){
                return $this->Restful(false, 0, '请输入文件夹名称');
            }

            $folder = api_parent_folder($res['folder_id'], $user);
            if(empty($folder)){
                return $this->Restful(false, 0, '父文件夹不存在');
            }
            $checkSameName = db($table)->where(['name' => $res['name'], 'pid' => $folder['id'], 'user_id' => $user['id'], 'is_deleted' => 0])->find();
            if(!empty($checkSameName)){
                return $this->Restful(false, 0, '文件夹已存在');
            }

            if(!hasSpecial($res['name'])){
                return $this->Restful(false, 0, '名称不能存在特殊字符');
            }

            $sql = [
                'pid'               =>  $folder['id'],
                'user_id'           =>  $user['id'],
                'name'              =>  $res['name'],
                'path'              =>  $folder['path'].$folder['name'].'/',
                'pid_path'          =>  $folder['pid_path'].$folder['id'].'/',
                'remark_context'    =>  $res['remark_context'],
                'regtime'           =>  time(),
                'uptime'            =>  time()
            ];
            $flag = $this->Create($table, $sql);
            if(empty($flag)){
                $this->Addlog($table, '('.$user['username'].')创建文件夹失败', 1);
                return $this->Restful(false, 0, '创建文件夹失败，请与管理员联系!');
            }else{
                $this->Addlog($table, '('.$user['username'].')创建文件夹成功', 1);
                return $this->Restful(true, 1, '创建文件成功!');
            }
        }

        public function get_file_allow()
        {
            $user = $this->checkToken();
            if(empty($user)){
                return $this->Restful(false, 0, '令牌错误，请重新登录');
            }
            return $this->Restful(['allow_ext' => sysConf('upload_ext'), 'allow_size' => sysConf('upload_maxsize')], 1, '允许上传的文件信息');
        }
        
        /* 上传文件 */
        public function up_file(){
            $user = $this->checkToken();
            if(empty($user)){
                return $this->Restful(false, 0, '令牌错误，请重新登录');
            }
            $file = request()->file('file');
            $res = input('post.');
            $table = api_upload_type($res['type']);
            //上传前检测
            if($res['size'] + $user['use_size'] > $user['total_size']){
                return $this->Restful(false, 0, '空间剩余不足!');
            }

            $folder = api_parent_folder($res['folder_id'], $user);
            if(empty($folder)){
                return $this->Restful(false, 0, '父文件夹不存在');
            }

            $step1 = db('file')->where(['user_id' => $user['id'], 'folder_id' => $folder['id'], 'name' => $res['name'], 'is_deleted' => 0])->find();
            if(!empty($step1)){
                return $this->Restful(false, 0, '存在同名文件');
            }

            $ret = $this->upFile($file, $table, $res['type'], ['folder' => $folder]);
            $this->Addlog($table, $ret['msg'], 7);
            if($ret['code'] == 0){
                return $this->Restful(false, 0, $ret['msg']);
            }
            # 写更新数据库
            $flag = $this->Update('user', $user['id'], ['use_size' => $user['use_size'] + $ret['size'], 'uptime' => time()]);
            if(empty($flag)){
                return $this->Restful(false, 0, '上传文件失败!');
                $this->Addlog('user', '('.$user['username'].')上传文件失败', 2);
            }
            $this->Addlog('user', '('.$user['username'].')上传文件成功', 2);
            return $this->Restful($ret, 1, '上传文件成功!');
        }

        public function saveFile($user, $res, $file){
            $folder = api_parent_folder($res['folder_id'], $user);
            if(empty($folder)){
                return $this->Restful(false, 0, '父文件夹不存在');
            }
            #1.检测该目录中是否存在同名文件
            $step1 = db('file')->where(['user_id' => $user['id'], 'folder_id' => $folder['id'], 'name' => $res['name'], 'is_deleted' => 0])->find();
            if(!empty($step1)){
                return $this->Restful(false, 0, '存在同名文件');
            }

            #2.写入并写日志且输出
            $data = [
                'folder_id' =>  $folder['id'],
                'user_id'   =>  $user['id'],
                'name'      =>  $res['name'],
                'save_name' =>  $file['save_name'],
                'mime'      =>  $file['mime'],
                'ext'       =>  $file['ext'],
                'size'      =>  $file['size'],
                'path'      =>  $file['path'],
                'net_path'  =>  $file['net_path'],
                'md5'       =>  $file['md5'],
                'sha1'      =>  $file['sha1'],
                'user_path' =>  $folder['pid_path'].$folder['id'].'/',
                'old_path'  =>  $folder['pid_path'].$folder['id'].'/',
                'status'    =>  1,
                'is_deleted'    =>  0,
                'regtime'   =>  time(),
                'uptime'    =>  time()
            ];
            $step2 = $this->Create('file', $data);
            if(empty($step2)){
                return $this->Restful(false, 0, '上传文件失败!');
                $this->Addlog('user', '('.$user['username'].')上传文件失败', 2);
            }
            $this->Addlog('user', '('.$user['username'].')上传文件成功', 2);
            return $this->Restful(true, 1, '上传文件成功!');


            
        }


        /* wangEditor上传图片 */
        public function wangeditor_image(){
            $files = request()->file('file');
            $table = input('table');
            $ret['errno'] = 0;
            foreach($files as $file){
            $res = $this->upImage($file, $table);
            $ret['data'][] = $res['url'];
            $this->Addlog($table, $res['msg'], 7);
            }
            return json($ret);
        }

        

    }
