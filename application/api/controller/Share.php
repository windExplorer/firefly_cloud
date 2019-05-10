<?php
	namespace app\api\controller;

	use app\common\behavior\BaseApi;

	class Share extends BaseApi{

        /*  subject: '', //主题
            content: '', //内容
            is_open: 0, //是否公开发布到动态
            is_encrypt: true, //是否加密
            is_expire: 0, //是否有期限
            is_frequency: 0, //是否有次数
            expire_type: '2', //到期时间类型 1: 1天, 2: 7天, 3: 一个月 ,默认7天
            frequency: 1, //设定次数
            allow_comment: true, //是否允许评论
            show_location: 0, //是否显示地理位置
            custom_location: '' //自定义地理位置 */
		public function share()
		{
			$res = input('post.');
            $user = $this->checkToken();
            if(empty($user)){
                return $this->Restful(false, -100, '令牌错误，请重新登录');
            }
            if(empty($res['file']) && empty($res['folder'])){
                return $this->Restful(false, 0, '没有文件/文件夹被分享');
            }
            $sql = [];
            $pwd = '';
            $expire_day = '';
            $expire_time = '';
            $frequency = '';
            $sql['file_ids'] = implode(',', $res['file']);
            $sql['folder_ids'] = implode(',', $res['folder']);
            $res = $res['share'];
            $res = $this->removeXSS($res);
            $sql['ip'] = getRealIP();
            $sql['location'] = getGeo2();
            if($res['is_open']){
                if(empty($res['subject'])){
                    return $this->Restful(false, 0, '请填写主题(50字内)');
                }
                if(empty($res['content'])){
                    #return $this->Restful(false, 0, '请填写正文(140字内)');
                }
                if($res['allow_comment']){
                    $sql['allow_comment'] = 1;
                }else{
                    $sql['allow_comment'] = 0;
                }
                if($res['show_location']){
                    $sql['show_location'] = 1;
                    if(empty($res['custom_location'])){
                        $sql['custom_location'] = $sql['location'];
                    }else{
                        $sql['custom_location'] = $res['custom_location'];                        
                    }
                }else{
                    $sql['show_location'] = 0;
                }
                $sql['subject'] = substr($res['subject'], 0, 50);
                $sql['content'] = substr($res['content'], 0, 140);
                $sql['is_open'] = 1;
            }else{
                $sql['is_open'] = 0;
            }

            if($res['is_encrypt']){
                //生成密钥
                $sql['share_password'] = getLenRand2(4);
                $pwd = '提取码: '.$sql['share_password'];
                $sql['is_encrypt'] = 1;
            }else{
                $sql['is_encrypt'] = 0;
                $sql['share_password'] = '';
            }

            if($res['is_expire']){
                $sql['is_expire'] = 1;
                switch($res['expire_type']){
                    case '1': $sql['expire_time'] = strtotime('+1 day'); $expire_day = '文件将于1天后过期';  break;
                    case '2': $sql['expire_time'] = strtotime('+7 days'); $expire_day = '文件将于7天后过期'; break;
                    case '3': $sql['expire_time'] = strtotime('+1 month'); $expire_day = '文件将于1月后过期'; break;
                    default: $sql['expire_time'] = strtotime('+1 day'); $expire_day = '文件将于1天后过期'; break;
                }
                $expire_time = date('Y-m-d H:i:s', $sql['expire_time']);
            }else{
                $sql['is_expire'] = 0;
            }

            if($res['is_frequency']){
                $sql['is_frequency'] = 1;
                $sql['frequency'] = (int)$res['frequency'];
                $frequency = '允许'.$sql['frequency'].'次下载';
            }else{
                $sql['is_frequency'] = 0;
            }
            
            $sql['regtime'] = time();
            $sql['uptime'] = time();
            $sql['user_id'] = $user['id'];
            $sql['user_agent'] = $this->Header['user-agent'];
            

            //生成私密链接
            $sql['private_link'] = substr(sha1(getLenRand2(6).uniqid($user['id']).getLenRand2(4)), 0, mt_rand(25,30));

            //写库，写日志，根据类型返回密钥，期限，和下载次数
            $flag = db('share')->insertGetId($sql);
            if(empty($flag)){
                return $this->Restful('', 0, '分享失败');
            }
            $this->Addlog('share', '('.$user['username'].')分享成功,id为['.$flag.']', 11);

            
            $ret = [
                'share_link'    =>  request()->header('origin').'/share/'.$sql['private_link'],
                'share_password' => $sql['share_password'],
                'expire_time'   =>  $expire_time,
                'frequency' =>  $frequency,
                'expire_day'    =>  $expire_day,
            ];
            $ret['copy_link'] = '链接: '.$ret['share_link'];
            $ret['copy_link_pwd'] = $ret['copy_link'].' '.$pwd.' [来自'.$user['nickname'].'的分享]';

            return $this->Restful($ret, 1, '分享成功');

        }
        
        //获取我的分享列表
        public function get_myshare()
        {
            $res = input('post.');
            $user = $this->checkToken();
            if(empty($user)){
                return $this->Restful(false, -100, '令牌错误，请重新登录');
            }
            $data = db('share')->where([
                'user_id'   =>  $user['id'],
                //'status'    =>  1,
                'is_deleted'    =>  0,
            ])->order('id', 'desc')->select();
            foreach($data as $k => $v){
                $file = [];
                $folder = [];
                if(empty($v['folder_ids'])){
                    $file = explode(',', $v['file_ids']);
                    $data[$k]['isfolder'] = 0;
                    $data[$k]['name'] = db('file')->where('id', $file[0])->value('name');
                }else{
                    $folder = explode(',', $v['folder_ids']);
                    $data[$k]['isfolder'] = 1;
                    $data[$k]['name'] = db('folder')->where('id', $folder[0])->value('name');
                }
                if((count($folder) + count($file)) > 1){
                    $data[$k]['name'] .= '等';
                }
                $data[$k]['downUrl'] = request()->header('origin').'/share/'.$data[$k]['private_link'];
            }
            

            return $this->Restful($data, 1, '我的分享列表');

        }

        //取消我的分享[status=0]
        public function hide_myshare()
        {
            $res = input('post.');
            $user = $this->checkToken();
            if(empty($user)){
                return $this->Restful(false, -100, '令牌错误，请重新登录');
            }
            $ids = $res['ids'];
            $flag = db('share')->whereIn('id', $ids)->where([
                'user_id'   =>  $user['id'],
                'is_deleted'    =>  0,
                'status'    =>  1
            ])->update([
                'status'    =>  0,
                'uptime'    =>  time()
            ]);
            if(empty($flag)){
                $this->Addlog('share', '('.$user['username'].')取消分享失败', 13);
                return $this->Restful(false, 0, '取消分享失败!');
            }else{
                $this->Addlog('share', '('.$user['username'].')取消分享成功，取消分享了'.$flag.'条数据。ids为['.implode(',', $res['ids']).']', 13);
                return $this->Restful(true, 1, '取消分享成功! 取消了'.$flag.'条数据。');
            }
        }
        //恢复我的分享[status=1]
        public function show_myshare()
        {
            $res = input('post.');
            $user = $this->checkToken();
            if(empty($user)){
                return $this->Restful(false, -100, '令牌错误，请重新登录');
            }
            $ids = $res['ids'];
            $flag = db('share')->whereIn('id', $ids)->where([
                'user_id'   =>  $user['id'],
                'is_deleted'    =>  0,
                'status'    =>  0
            ])->update([
                'status'    =>  1,
                'uptime'    =>  time()
            ]);
            if(empty($flag)){
                $this->Addlog('share', '('.$user['username'].')恢复分享失败', 11);
                return $this->Restful(false, 0, '恢复分享失败!');
            }else{
                $this->Addlog('share', '('.$user['username'].')恢复分享成功，恢复分享了'.$flag.'条数据。ids为['.implode(',', $res['ids']).']', 11);
                return $this->Restful(true, 1, '恢复分享成功! 恢复了'.$flag.'条数据。');
            }
        }

        //删除我的分享[取消分享并且删除记录，status=0,is_deleted=1]
        public function del_myshare()
        {
            $res = input('post.');
            $user = $this->checkToken();
            if(empty($user)){
                return $this->Restful(false, -100, '令牌错误，请重新登录');
            }
            $ids = $res['ids'];
            $flag = db('share')->whereIn('id', $ids)->where([
                'user_id'   =>  $user['id'],
                'is_deleted'    =>  0,
                'status'    =>  1
            ])->update([
                'status'    =>  0,
                'is_deleted'    =>  1,
                'uptime'    =>  time()
            ]);
            if(empty($flag)){
                $this->Addlog('share', '('.$user['username'].')删除分享失败', 13);
                return $this->Restful(false, 0, '删除分享失败!');
            }else{
                $this->Addlog('share', '('.$user['username'].')删除分享成功，删除了'.$flag.'条数据。ids为['.implode(',', $res['ids']).']', 13);
                return $this->Restful(true, 1, '删除分享成功! 删除了'.$flag.'条数据。');
            }

        }

        //分享详情[获取分享的文件和文件夹] [参数为分享链接参数和提取码, link, encrypt]
        public function get_share()
        {
            $res = input('post.');
            # 1.如果没有获取到私密链接直接返回
            return $this->share_info($res);
        }

        //分享进入文件夹 [link, to, from, encrypt] from = [id,pid_path,path,name],to = [id]
        public function enter_folder(){
            $res = input('post.');
            $check = $this->check_share($res);
            $share = $check['data'];
            //如果文件夹id为0，则表示获取当前的分享首页
            if($res['to']['id'] == 0){
                # 6.获取分享的文件夹列表和文件列表
                $ret['file'] = db('file')->where(['is_deleted'  =>  0, 'status' =>  1])->whereIn('id', explode(',', $share['file_ids']))->order('id', 'desc')->select();
                //删除文件真实链接
                $ret['file'] = hideTruePlace($ret['file']);
                foreach($ret['file'] as $k => $v){
                    $key = ['user_path', 'old_path'];
                    $ret['file'][$k] = removeKey($v, $key);
                }
                $ret['folder'] = db('folder')->where(['is_deleted'  =>  0, 'status' =>  1])->whereIn('id', explode(',', $share['folder_ids']))->order('id', 'desc')->select();
                $ret['to'] = [
                    'id'    =>  0,
                    'path'  =>  ['首页'],
                    'pid_path' => [0],
                    'name'  =>  '首页'
                ];
                $ret['from'] = [];
            }else{
                $folder = db('folder')->where('id', $res['to']['id'])->find();
                

                //$newPath = TreeMoveFolderPath($res['to'], $res['from'], $res['to']['id']);
                //$new_pid_path = $newPath['id_path'];
                //$new_path = $newPath['name_path'];


                $new_pid_path = $res['from']['pid_path'];
                $new_path = $res['from']['path'];

                $ind = array_search($res['to']['id'], $new_pid_path);
                
                if($ind){
                    array_splice($new_pid_path, $ind+1);
                    array_splice($new_path, $ind+1);
                }else{
                    $new_pid_path[] = $res['to']['id'];
                    $new_path[] = $folder['name'];
                }
                

                $ret['to'] = [
                    'id'    =>  $res['to']['id'],
                    'pid_path'  =>  $new_pid_path,
                    'path'  =>  $new_path,
                    'name'  =>  $folder['name']
                ];

                
                //获取文件夹子文件夹信息
                $ret['folder'] = db('folder')->where([
                    'user_id'   =>  $share['user']['id'],
                    'pid' =>  $res['to']['id'],
                    'status'    =>  1,
                    'is_deleted'    =>  0,
                ])->order('id', 'desc')->select();
                //获取文件夹文件信息
                $ret['file'] = db('file')->where([
                    'user_id'   =>  $share['user']['id'],
                    'folder_id' =>  $res['to']['id'],
                    'status'    =>  1,
                    'is_deleted'    =>  0,
                ])->order('id', 'desc')->select();
                $ret['file'] = hideTruePlace($ret['file']);
                foreach($ret['file'] as $k => $v){
                    $key = ['user_path', 'old_path'];
                    $ret['file'][$k] = removeKey($v, $key);
                }
            }
            $ret['list'] = array_merge($ret['folder'], $ret['file']);
            return $this->Restful($ret, 1, '获取成功');
        }


        //下载 需要文件id和分享私密链接以及提取码
        public function download($id, $encrypt, $link)
        {
            //$res = input('get.');
            $res = [
                'link'  =>  $link,
                'file_id'   =>  $id,
                'encrypt'   =>  $encrypt
            ];
            # 1.如果没有获取到私密链接或者文件id直接返回
            if(empty($res['link'])){
                return $this->Restful(false, 0, '下载失败，分享私密链接不正确');
            }
            if(empty($res['file_id'])){
                return $this->Restful(false, 0, '下载失败，文件不存在');
            }
            $check = $this->check_share($res);

            if($check['flag'] != 1){
                return $this->Restful(false, $check['flag'], $check['msg']);
            }
            $file = db('file')->where('id', $res['file_id'])->where(['is_deleted' => 0, 'status'    =>  1])->find();
            if(empty($file)){
                return $this->Restful(false, 0, '下载失败，文件被取消分享或被删除');
            }
            if(!file_exists($file['path'])){
                return $this->Restful(false, 0, '没有找到该文件');
            }
            $share = $check['data'];
            db('file')->where('id', $file['id'])->setInc('down_frequency');
            db('share')->where('id', $share['id'])->setInc('use_frequency');
            download($file);

        }

        //获取分享 参数由$res[link, encrypt]组成
        public function share_info($res){

            # 2.根据链接获取分享
            # 3.判断是否过期或者分享被取消，分享被删除，或者下载次数已达到上限
            # 以上两步整合到一个方法里
            # 4.如果上述的判断有问题，则直接返回信息，需要密码返回码为-1，其他错误为0

            $check = $this->check_share($res);
            if($check['flag'] != 1){
                return $this->Restful(false, $check['flag'], $check['msg']);
            }
            # 5.如果上述的判断没有问题，设置初始值
            $share = $check['data'];
            $ret = [
                'subject'   =>  '',
                'content'   =>  '',
                'comment'   =>  [],
                'location'  =>  '',
                'file'      =>  [],
                'folder'    =>  [],
                'list'      =>  [],
                'to'        => [
                    'id'    =>  0,
                    'path'  =>  ['首页'],
                    'pid_path' => [0],
                    'name'  =>  '首页'
                ],
                'from' =>    [],
                'user'  =>  $share['user'],
                'frequency' =>  $share['frequency'],
                'use_frequency' =>  $share['use_frequency'],
                'regtime'   =>  $share['regtime'],
                'uptime'    =>  $share['uptime'],
                'is_encrypt'    =>  $share['is_encrypt'],
                'is_expire'    =>  $share['is_expire'],
                'is_open'    =>  $share['is_open'],
                'is_frequency'    =>  $share['is_frequency'],
                'expire_time'   =>  $share['expire_time'],
                'downUrl'      =>  getDomain().'/download'
            ];
            # 6.获取分享的文件夹列表和文件列表
            $ret['file'] = db('file')->where(['is_deleted'  =>  0, 'status' =>  1])->whereIn('id', explode(',', $share['file_ids']))->order('id', 'desc')->select();
            //删除文件真实链接
            $ret['file'] = hideTruePlace($ret['file']);
            foreach($ret['file'] as $k => $v){
                $key = ['user_path', 'old_path'];
                $ret['file'][$k] = removeKey($v, $key);
            }
            $ret['folder'] = db('folder')->where(['is_deleted'  =>  0, 'status' =>  1])->whereIn('id', explode(',', $share['folder_ids']))->order('id', 'desc')->select();
            $ret['list'] = array_merge($ret['folder'], $ret['file']);
            # 7.如果发布了动态，则判断是否开启了评论，并获取评论
            # 8.如果该分享开启了评论，获取该分享的评论 
            # 9.获取评论的后遍历，获取用户user_id和replay_id
            # 10.如果该分享允许显示地理位置，则获取显示位置[组装]custom_location
            if($share['is_open'] == 1){
                $ret['subject'] = $share['subject'];
                $ret['content'] = $share['content'];
                if($share['allow_comment'] == 1){
                    $ret['comment'] = $this->getComment($share['id']);
                }
                if($share['show_location'] == 1){
                    $ret['location'] = $share['custom_location'];
                }
            }
            return $this->Restful($ret, 1, '获取成功');
        }

        //判断分享是否有效
        public function check_share($res){
            if(empty($res['link'])){
                return [
                    'flag'  =>  0,
                    'msg'   =>  '分享私密链接不正确'
                ];
            }
            $share = db('share')->where('private_link', $res['link'])->find();
            $user = db('user')->where('id', $share['user_id'])->where(['status' =>  1, 'is_deleted' =>  0])->find();
            if(empty($share)){
                return [
                    'flag'  =>  0,
                    'msg'   =>  '没有找到该分享'
                ];
            }
            if(empty($share)){
                return [
                    'flag'  =>  0,
                    'msg'   =>  '该分享用户已被拉黑或不存在'
                ];
            }
            if($share['is_encrypt'] == 1){
                if(empty($res['encrypt'])){
                    return [
                        'flag'  =>  -1,
                        'msg'   =>  '需要提取码'
                    ];
                }
                if($res['encrypt'] != $share['share_password']){
                    return [
                        'flag'  =>  0,
                        'msg'   =>  '提取码错误'
                    ];
                }
            }
            if($share['is_deleted'] == 1){
                return [
                    'flag'  =>  0,
                    'msg'   =>  '该分享已被删除'
                ];
            }
            if($share['status'] == 0){
                return [
                    'flag'  =>  0,
                    'msg'   =>  '该分享已被取消'
                ];
            }
            if($share['is_frequency'] == 1){
                if($share['use_frequency'] >= $share['frequency']){
                    return [
                        'flag'  =>  0,
                        'msg'   =>  '该分享下载次数已达到上限'
                    ];
                }
            }
            if($share['is_expire'] == 1){
                if($share['expire_time'] < time()){
                    return [
                        'flag'  =>  0,
                        'msg'   =>  '该分享已过期'
                    ];
                }
            }
            

            $key = ['id', 'nickname', 'gender', 'sign_context', 'born', 'avatar', 'score', 'level'];
            $user = filterKey($user, $key);
            $user['avatar'] = getDomain().$user['avatar']; 
            $share['user'] = $user;

            return [
                'flag'  =>  1,
                'data'  =>  $share,
            ];

        }

        //获取评论
        public function getComment($id){
            $data = db('share_comment')->where([
                'share_id'  =>  $id,
                'is_deleted'    =>  0,
                'status'    =>  1,
            ])->order('id', 'asc')->select();
            foreach($data as $k => $v){
                $data[$k]['user_name'] = db('user')->where(['id' => $arr['user_id'], 'status'    =>  1, 'is_deleted' =>  0])->value('nickname');
                if($data[$k]['pid'] != 0){
                    $data[$k]['reply_name'] = db('user')->where(['id' => $arr['reply_id'], 'status'    =>  1, 'is_deleted' =>  0])->value('nickname');
                }
                $key = ['ip', 'location', 'user_agent', 'status', 'is_deleted'];
                $data[$k] = removeKey($data[$k], $key);
            }
            return $data;
        }
		

	}