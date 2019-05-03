<?php
	namespace app\api\controller;

	use app\common\behavior\BaseApi;

	class Dynamic extends BaseApi{

        //获取最新动态100条
        public function get_new_share()
        {
            $data = db('share')->where([
                'status'    =>  1,
                'is_deleted'    =>  0,
                'is_open'   =>  1
            ])->order('uptime', 'desc')->limit(100)->select();

            foreach($data as $k => $v){
                $data[$k]['user'] = db('user')->where('id', $data[$k]['user_id'])->find();
                $key = ['id', 'nickname', 'gender', 'sign_context', 'born', 'avatar', 'score', 'level'];
                $data[$k]['user'] = filterKey($data[$k]['user'], $key);
                $data[$k]['user']['avatar'] = getDomain().$data[$k]['user']['avatar'];
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
            
            return $this->Restful($data, 1, '最新分享');

        }

	}