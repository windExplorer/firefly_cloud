<?php
    namespace app\api\controller;

    use app\common\behavior\BaseApi;

    class File extends BaseApi{

        public function checkmd5()
        {
            $res = input('post.');
            switch($res['type']){
                case 1: $table = 'user_attachment'; break;
                case 2: $table = 'file'; break;
            }
            $user = $this->checkToken();
            if(empty($user)){
                return $this->Restful(false, 0, '令牌错误，请重新登录');
            }

            $flag = checkFileExist($res['md5'], $table);
            if(empty($flag)){
                return $this->Restful(true, -1, '查无此文件，可以进行上传');
            }else{
                return $this->Restful($flag, 1, '有文件，可以秒传');
            }

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
