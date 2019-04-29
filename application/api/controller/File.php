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
                    return $this->saveAvatar($user, $flag);
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

            // 去除文件的真实链接
            foreach($list['file'] as $k => $v){
                $list['file'][$k]['path'] = '';
                $list['file'][$k]['net_path'] = '';
            }

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
                $this->Addlog('file', '('.$user['username'].')上传文件失败', 7);
                return $this->Restful(false, 0, '上传文件失败!');
            }
            $this->Addlog('file', '('.$user['username'].')上传文件成功,文件id为'.$ret['file_id'], 7);
            //写上传日志
            $this->AddUpDown($user, $ret['file_id'], 0);
            //获取该文件夹中的文件信息
            $ret['extend'] = [
                'th' => $folder,
                'list' => [
                    'folder' => db('folder')->where(['user_id'  =>  $user['id'], 'pid' => $folder['id'], 'is_deleted' => 0, 'status' => 1])->order('id desc')->select(),
                    'file' => db('file')->where(['user_id'  =>  $user['id'], 'folder_id' => $folder['id'], 'is_deleted' => 0, 'status' => 1])->order('id desc')->select(),
                ],
                'my_up' =>  $this->get_myup_list($user, 'month'),
            ];
            return $this->Restful($ret, 1, '上传文件成功!');
        }

        /* 保存文件 */
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
                'is_deleted'    =>  1, //是否秒传
                'regtime'   =>  time(),
                'uptime'    =>  time()
            ];
            $step2 = $this->Create('file', $data);
            if(empty($step2)){
                return $this->Restful(false, 0, '上传文件失败!');
                $this->Addlog('file', '('.$user['username'].')上传文件失败', 7);
            }
            $this->Addlog('file', '('.$user['username'].')上传文件成功,文件id为'.$step2, 7);
            /* 写上传日志 */
            $this->AddUpDown($user, $step2, 0);
            $ret['extend'] = [
                'th' => $folder,
                'list' => [
                    'folder' => db('folder')->where(['user_id'  =>  $user['id'], 'pid' => $folder['id'], 'is_deleted' => 0, 'status' => 1])->order('id desc')->select(),
                    'file' => db('file')->where(['user_id'  =>  $user['id'], 'folder_id' => $folder['id'], 'is_deleted' => 0, 'status' => 1])->order('id desc')->select(),
                ],
                'my_up' =>  $this->get_myup_list($user, 'month'),
            ];
            return $this->Restful($ret, 1, '上传文件成功!');
            
        }

        /* 写秒传头像数据 */
        public function saveAvatar($user, $file)
        {
            $flag = $this->Update('user', $user['id'],['avatar' => $file['net_path'], 'uptime' => time()]);
            if(empty($flag)){
                return $this->Restful(false, 0, '更新头像失败!');
                $this->Addlog('user', '('.$user['username'].')修改头像失败', 2);
            }
            $this->Addlog('user', '('.$user['username'].')修改头像成功', 2);
            return $this->Restful($file, 1, '更新头像成功!');
        }

        /* 获取上传记录 */
        public function get_my_up()
        {
            $res = input('post.');
            if(empty($res['type'])){
                $ret['type'] = 'week';
            }
            $user = $this->checkToken();
            if(empty($user)){
                return $this->Restful(false, 0, '令牌错误，请重新登录');
            }
            $list = $this->get_myup_list($user, $res['type']);
            
            return $this->Restful($list, 1, '我的上传记录');
        }

        public function get_myup_list($user, $type='weeek'){
            $list = db('up_down')->where(['user_id' => $user['id'],  'up_type' => 0, 'is_deleted' => 0, 'status' => 1])->whereTime('regtime', $type)->order('id', 'desc')->select();
            if(!empty($list)){
                $ids = [
                    'file' => [],
                    'folder'=> []  
                ];
                $res = [
                    'file' => [],
                    'folder' => []
                ];
                foreach($list as $k => $v){
                    $ids['file'][] = $list[$k]['file_id'];
                }
  
                $res['file'] = db('file')->where('id', 'in', $ids['file'])->column('*', 'id');

                foreach($res['file'] as $k => $v){
                    $ids['folder'][] = $res['file'][$k]['folder_id'];
                }

                $res['folder'] = db('folder')->where('id', 'in', $ids['folder'])->column('*', 'id');

                                
                foreach($list as $k => $v){
                    if(!empty($res['file'][$list[$k]['file_id']])){
                        $list[$k]['file'] = $res['file'][$list[$k]['file_id']];
                        $list[$k]['folder'] = $res['folder'][$list[$k]['file']['folder_id']];
                    } 
                }
            }
            
            return $list;
        }

        public function del_my_up()
        {
            $res = input('post.');
            $user = $this->checkToken();
            if(empty($user)){
                return $this->Restful(false, 0, '令牌错误，请重新登录');
            }
            $flag = db('up_down')->where('user_id', $user['id'])->where('id', 'in' ,$res['ids'])->update(['is_deleted' => 1, 'uptime' => time()]);
            if(empty($flag)){
                $this->Addlog('up_down', '('.$user['username'].')删除文件失败', 1);
                return $this->Restful(false, 0, '删除失败!');
            }else{
                $this->Addlog('up_down', '('.$user['username'].')删除文件成功，删除了'.$flag.'条数据。ids为['.implode(',', $res['ids']).']', 1);
                return $this->Restful(true, 1, '删除成功! 删除了'.$flag.'条数据。');
            }
            
        }


        /* 编辑文件/文件夹 */
        public function edit()
        {
            $res = input('post.');
            $user = $this->checkToken();
            if(empty($user)){
                return $this->Restful(false, 0, '令牌错误，请重新登录');
            }
            $table = file_or_folder($res['type']);
            $res = $this->removeXSS($res);
            foreach($res as $k => $v){
                $res[$k] = str_replace(' ','',$res[$k]);
            }
            if(empty($res['name'])){
                return $this->Restful(false, 0, '请输入文件/文件夹名称');
            }
            if(!hasSpecial($res['name'])){
                return $this->Restful(false, 0, '名称不能存在特殊字符');
            }
            $data = db($table)->where('id', $res['id'])->where('user_id', $user['id'])->where('is_deleted', 0)->where('status', 1)->find();

            # 1.若是文件夹，主要判断文件夹名称是否被修改，如果被修改，与之有关的文件夹都要的path都得被修改，当然，可以做成被动的
            #   主动修改：遍历所有与之有关的文件夹(path like %/old_name/%), 然后替换成新的名字再一次修改记录
            if((int)$res['type'] == 1){
                //文件夹
                $folder = $data;
                if($res['name'] == $folder['name'] && $res['context'] == $folder['remark_context']){
                    return $this->Restful(false, 0, '没有改动');
                }
                if(empty($folder)){
                    return $this->Restful(false, 0, '查无此文件夹');
                }
                if($res['name'] !== $folder['name']){
                    $flag = $this->changeAllChildrenPath($folder, $user, $res['name']);

                }
                $ret = $this->Update($table, $res['id'], [
                    'name'  =>  $res['name'],
                    'remark_context'    =>  $res['context'],
                    'uptime'            =>  time()
                ]);

            }else{
                $file = $data;
                if($res['name'].'.'.$file['ext'] == $file['name'] && $res['context'] == $file['description_context']){
                    return $this->Restful(false, 0, '没有改动!');
                }

                $ret = $this->Update($table, $res['id'], [
                    'name'  =>  $res['name'].'.'.$file['ext'],
                    'description_context'    =>  $res['context'],
                    'uptime'            =>  time()
                ]);
            }

            return $this->Restful($ret, 1, '编辑成功!');

            
        }

        /* 修改与之有关的子文件夹的path */
        public function changeAllChildrenPath($folder, $user, $name){
            $child = db('folder')->where('user_id', $user['id'])->where('path', 'like', '%/'.$folder['name'].'/%')->select();
            $flag = 0;
            foreach($child as $k => $v){
                $pid_path = explode('/', $child[$k]['pid_path']);
                $path = explode('/', $child[$k]['path']);
                $p = array_search($folder['id'], $pid_path);
                $path[$p] = $name;
                $path = implode('/', $path);
                $flag += db('folder')->where('id', $child[$k]['id'])->update(['path' => $path]);
            } 
            return $flag;
        }

        /* 删除文件/文件夹 */
        public function del(){
            $res = input('post.');
            $user = $this->checkToken();
            if(empty($user)){
                return $this->Restful(false, 0, '令牌错误，请重新登录');
            }
            //直接删除（？软删除），不设定回收站-回收站后续再开启，或者后续再加一条系统参数，是否软删除
            //获取该文件夹以及子文件夹下所有的文件夹
            //获取该文件夹以及文件夹下所有文件，并且统计是否为秒传，如果是秒传则不计入空间

            
  
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
