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
            $list['file'] = hideTruePlace($list['file']);

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
                    'file' => hideTruePlace(db('file')->where(['user_id'  =>  $user['id'], 'folder_id' => $folder['id'], 'is_deleted' => 0, 'status' => 1])->order('id desc')->select()),
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
                'is_second'    =>  1, //是否秒传
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
                    'file' => hideTruePlace(db('file')->where(['user_id'  =>  $user['id'], 'folder_id' => $folder['id'], 'is_deleted' => 0, 'status' => 1])->order('id desc')->select()),
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
                $this->Addlog('up_down', '('.$user['username'].')删除上传记录失败', 1);
                return $this->Restful(false, 0, '删除失败!');
            }else{
                $this->Addlog('up_down', '('.$user['username'].')删除上传记录成功，删除了'.$flag.'条数据。ids为['.implode(',', $res['ids']).']', 1);
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
            $this->Addlog($table, '('.$user['username'].')编辑文件/文件夹成功，id为['.$res['id'].']', 2);
            return $this->Restful($ret, 1, '编辑成功!');

            
        }

        /* 修改与之有关的子文件夹的path */
        public function changeAllChildrenPath($folder, $user, $name){
            $child = db('folder')->where('user_id', $user['id'])->where('pid_path', 'like', '%/'.$folder['id'].'/%')->select();
            $flag = 0;
            foreach($child as $k => $v){
                $pid_path = explode('/', $child[$k]['pid_path']);
                $path = explode('/', $child[$k]['path']);
                $p = array_search($folder['id'], $pid_path);
                $path[$p] = $name;
                $path = implode('/', $path);
                $flag += db('folder')->where('id', $child[$k]['id'])->update(['path' => $path, 'uptime' => time()]);
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
            //(硬删除)实际上删除的时候还需要删除磁盘物理文件，删除的时候判断是否有其他用户使用了这个文件，通过md5判断，如果有就不删除物理文件，如果没有，就直接把物理文件删了
            $flag = [
                'folder' => 0,
                'folder_success' => [],
                'file'  => 0,
                'file_success'  =>  [],
            ];
            $size = 0;
            if(!empty($res['folder'])){
                $del_folder = db('folder')->where(['user_id' => $user['id'], 'is_deleted' => 0])->where('id', 'in', $res['folder'])->select();
                foreach($del_folder as $k0){
                    //删除自己
                    $up = db('folder')->where('id', $k0['id'])->update(['is_deleted' => 1, 'uptime' => time()]);
                    if(!empty($up)){
                        $flag['folder'] += $up;
                        $flag['folder_success'][] = $k0['id'];
                        //删除自己的所有子文件夹
                        $up = db('folder')->where('user_id', $user['id'])->where('pid_path', 'like', '%/'.$k0['id'].'/%')->update(['is_deleted' =>  1, 'uptime' => time()]);
                        $flag['folder'] += $up;
                        //获取自己所有的子文件
                        $child = db('file')->where('user_id', $user['id'])->where('user_path', 'like', '%/'.$k0['id'].'/%')->where('is_deleted', 0)->select();
                        if(!empty($child)){
                            $ids = [];
                            foreach($child as $k){
                                $ids[] = $k['id'];
                                $flag['file_success'][] = $k['id'];
                                if($k['is_second'] == 0){
                                    $size += (int)$k['size'];
                                }
                            }
                            //删除自己所有的子文件
                            $up = db('file')->where('id', 'in', $ids)->update(['is_deleted' => 1, 'uptime' => time()]);
                            $flag['file'] += $up;
                        }
                    }  
                    
                }
            }

            // 删除当前id的文件
            if(!empty($res['file'])){
                $child = db('file')->where(['user_id' => $user['id']])->where('id', 'in', $res['file'])->where('is_deleted', 0)->select();
                if(!empty($child)){
                    $ids = [];
                    foreach($child as $k){
                        $ids[] = $k['id'];
                        $flag['file_success'][] = $k['id'];
                        if($k['is_second'] == 0){
                            $size += (int)$k['size'];
                        }
                    }
                    //删除自己所有的子文件
                    $up = db('file')->where('id', 'in', $ids)->update(['is_deleted' => 1, 'uptime' => time()]);
                    $flag['file'] += $up;
                }
            }

            
            //减少空间
            $last_size = $size > (int)$user['use_size'] ? (int)$user['use_size'] : $size;
                
            db('user')->where('id', $user['id'])->setDec('use_size', $last_size);
            
        
            // a:folder_id b.数据库返回的记录 th为当前目录详情，list分为该目录中的folder和file
            $folder = api_parent_folder($res['folder_id'], $user);
            $data = [
                'userInfo' => $this->checkToken(),
                'delInfo' => $flag,
                'size'  =>  $size,
                'home_nav' => [
                    'a' =>  $folder['id'],
                    'b' => [
                        'th'    =>  $folder,
                        'list'  =>  [
                            'folder' => db('folder')->where(['user_id'  =>  $user['id'], 'pid' => $folder['id'], 'is_deleted' => 0, 'status' => 1])->order('id desc')->select(),
                            'file' => hideTruePlace(db('file')->where(['user_id'  =>  $user['id'], 'folder_id' => $folder['id'], 'is_deleted' => 0, 'status' => 1])->order('id desc')->select()),
                        ],
                    ],
                ],

            ];

            if(empty($flag['folder']) && empty($flag['file'])){
                return $this->Restful(false, 0, '删除失败，没有文件/文件夹被删除');
            }

            //写删除日志
            $this->Addlog('user', '('.$user['username'].')删除文件/文件夹成功, 共释放'.HumanReadableFilesize($size).', 删除的文件夹有ids['.implode(',', $flag['folder_success']).'], 删除的文件有ids['.implode(',', $flag['file_success']).']', 12);

            return $this->Restful($data, 1, '删除成功，共删除'.count($flag['folder_success']).'个文件夹，'.count($flag['file_success']).'个文件。共释放'.HumanReadableFilesize($size).' 空间');

        }

        /* 获取文件夹无限极菜单 */
        public function getFolderMenu(){
            //$res = input('post.');
            $user = $this->checkToken();
            if(empty($user)){
                return $this->Restful(false, 0, '令牌错误，请重新登录');
            }
            $data = db('folder')->where([
                'user_id'       =>  $user['id'],
                'status'        => 1,
                'is_deleted'    =>  0
            ])->order('regtime', 'desc')->select();
            $list = GetTree($data);
            //$list[0]['disabled'] = true;
            return $this->Restful($list, 1, '我的文件夹菜单');
        }

        /* 复制文件/文件夹 */
        public function  copy()
        {
            $res = input('post.');
            $user = $this->checkToken();
            if(empty($user)){
                return $this->Restful(false, 0, '令牌错误，请重新登录');
            }
            //获取目标文件夹的文件夹和文件
            $folder = [
                'folder'    =>  getChildFolder($user, $res['to_key']),
                'file'    =>  getChildFile($user, $res['to_key']),
                'th'    =>  db('folder')->where('id', $res['to_key'])->find()
            ];

            $need_file = [];
            $need_folder = [];

            //获取需要复制的文件夹的信息
            if(!empty($res['folder'])){
                $need_folder = getFolderInfo($user, $res['folder']);
                //比对与目标文件夹中子文件夹是否有同名的
                $same = checkSameName($folder['folder'], $need_folder);
                if($same){
                    return $this->Restful($same, 0, '目标文件夹中已存在名称为: ['.$same.']的文件夹！');
                }
            }

            //获取需要复制的文件信息
            if(!empty($res['file'])){
                $need_file = getFileInfo($user, $res['file']);
                //比对与目标文件夹中子文件是否有同名的
                $same = checkSameName($folder['file'], $need_file);
                if($same){
                    return $this->Restful($same, 0, '目标文件夹中已存在名称为: ['.$same.']的文件！');
                }
            }

            //获取该目标文件夹下的所有子文件夹与文件
            $child['folder'] = [];
            $count_file = 0;
            $data = db('folder')->where(['user_id'=> $user['id'], 'is_deleted' => 0])->select();
            $need_child = [
                'id'        =>  $folder['th']['id'],
                'pid_path'  =>  $folder['th']['pid_path'].$folder['th']['id'].'/',
                'path'      =>  $folder['th']['path'].$folder['th']['name'].'/'
            ];
            foreach($need_folder as $arr){
                // 复制当前文件夹
                $need = TreeCopyFolder($arr, $need_child, $user);
                $child['folder'][] = $need;
                //复制子文件
                $count_file += TreeCopyFile($arr, $need, $user);
                //复制子文件夹
                $child_folder = TreeCopy($data, $arr['id'], $need, $user); 
                $child['folder'][] = $child_folder['folder'];
                $count_file += $child_folder['count_file'];
            }
            
            //return $this->Restful($child, 1, '');

            //复制文件
            foreach($need_file as $arr){
                $count_file += TreeCopyFile_Only($arr, $need_child, $user);
            }
            $child['count_file'] = $count_file;

            //写日志
            $this->Addlog('user', '('.$user['username'].')复制文件/文件夹成功, 共复制了'.count($child['folder']).'个文件夹，'.$child['count_file'].'个文件。目标文件夹为'.$res['to_key'], 9);
            return $this->Restful($child, 1, '复制文件/文件夹成功,共复制了'.count($child['folder']).'个文件夹，'.$child['count_file'].'个文件');
        }


        /* 移动文件/文件夹 */
        public function  move()
        {
            $res = input('post.');
            $user = $this->checkToken();
            if(empty($user)){
                return $this->Restful(false, 0, '令牌错误，请重新登录');
            }
            //获取目标文件夹的文件夹和文件
            $folder = [
                'folder'    =>  getChildFolder($user, $res['to_key']),
                'file'    =>  getChildFile($user, $res['to_key']),
                'th'    =>  db('folder')->where('id', $res['to_key'])->find()
            ];

            $need_file = [];
            $need_folder = [];

            //获取需要复制的文件夹的信息
            if(!empty($res['folder'])){
                $need_folder = getFolderInfo($user, $res['folder']);
                //比对与目标文件夹中子文件夹是否有同名的
                $same = checkSameName($folder['folder'], $need_folder);
                if($same){
                    return $this->Restful($same, 0, '目标文件夹中已存在名称为: ['.$same.']的文件夹！');
                }
            }

            //获取需要复制的文件信息
            if(!empty($res['file'])){
                $need_file = getFileInfo($user, $res['file']);
                //比对与目标文件夹中子文件是否有同名的
                $same = checkSameName($folder['file'], $need_file);
                if($same){
                    return $this->Restful($same, 0, '目标文件夹中已存在名称为: ['.$same.']的文件！');
                }
            }

            //获取该目标文件夹下的所有子文件夹与文件
            $child['folder'] = [];
            $count_file = 0;
            $data = db('folder')->where(['user_id'=> $user['id'], 'is_deleted' => 0])->select();
            $need_child = [
                'id'        =>  $folder['th']['id'],
                'pid_path'  =>  $folder['th']['pid_path'].$folder['th']['id'].'/',
                'path'      =>  $folder['th']['path'].$folder['th']['name'].'/'
            ];
            foreach($need_folder as $arr){
                // 移动当前文件夹
                $need = TreeMoveFolder($arr, $need_child, $user);
                $child['folder'][] = $need;
                //移动子文件
                $count_file += TreeMoveFile($arr, $need, $user);
                //移动子文件夹
                $child_folder = TreeMove($data, $arr['id'], $need, $user); 
                $child['folder'][] = $child_folder['folder'];
                $count_file += $child_folder['count_file'];
            }
            
            //return $this->Restful($child, 1, '');

            //复制文件
            foreach($need_file as $arr){
                $count_file += TreeMoveFile_Only($arr, $need_child, $user);
            }
            $child['count_file'] = $count_file;

            //写日志
            $this->Addlog('user', '('.$user['username'].')移动文件/文件夹成功, 共移动了'.count($child['folder']).'个文件夹，'.$child['count_file'].'个文件。目标文件夹为'.$res['to_key'], 10);
            return $this->Restful($child, 1, '移动文件/文件夹成功,共复制了'.count($child['folder']).'个文件夹，'.$child['count_file'].'个文件');
           
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
