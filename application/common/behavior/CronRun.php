<?php
    namespace app\common\behavior;

    use think\Exception;
    use think\Response;
    use think\Db;
    
    class CronRun
    {
        public function run(){
            //跨域解决
            $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';    
            //设置允许的域名     
            $allow_origin = [    
                'http://www.ainstall.com',
                'http://www.aindex.com',
                'http://www.aadmin.com',
                'http://www.afile.com',

            ];    
                
            if(in_array($origin, $allow_origin)){    
                header('Access-Control-Allow-Origin:'.$origin);    
                //header('Access-Control-Allow-Methods:POST');    
                header('Access-Control-Allow-Headers:x-requested-with,content-type');
                header('Access-Control-Allow-Credentials: true');
            }

        }
    }