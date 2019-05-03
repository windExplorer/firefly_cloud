<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        //return '<h1>Hello world! - 来自于frp内网穿透</h1>';
        //return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
   
        /* $start = '4/11/16/17/18/';
        $str = '4/11/20/21/';
        $start_name= '首页/abc/123/456/789/';
        $str_name = '首页/abc/————16/123/';
        $start_arr = explode('/', $start);
        unset($start_arr[count($start_arr)-1]);
        //$start_arr = array_splice($start_arr, count($start_arr)-1);
        $str_arr = explode('/', $str);
        $start_name_arr = explode('/', $start_name);
        unset($start_name_arr[count($start_name_arr)-1]);
        //$start_name_arr = array_splice($start_name_arr, count($start_name_arr)-1);
        $str_name_arr = explode('/', $str_name);
        //$ind = strpos($str, '/20/');
        //$last = substr($str, $ind+1);
        $ind = array_search('20', $str_arr);
        echo $ind;
        //unset($str_arr[$ind]);
        $str_arr = array_splice($str_arr, $ind);
        $str_name_arr = array_splice($str_name_arr, $ind);
        $new_arr = array_merge($start_arr, $str_arr);
        $new_name_arr = array_merge($start_name_arr, $str_name_arr);
        dump($new_arr);
        dump($new_name_arr);
        
        //die;
        $last = implode('/', $new_arr);
        $last_name = implode('/', $new_name_arr);
        dump($last);
        dump($last_name); */
        //echo $start.$last;

        //dump(TreeMoveFilePath(['user_path' => '4/9/55/109/110/111/112/'], ['pid_path' => '4/11/16/17/18/'], 109));
        dump(strtotime('+1 day'));
        dump(date('Y-m-d H:i:s', strtotime('+ 1 day')));
        dump(mt_rand(20, 25));
        dump(substr(md5(getLenRand2(6).uniqid().getLenRand2(4)), 0, mt_rand(25,30)));
        dump(substr(sha1(getLenRand2(6).uniqid().getLenRand2(4)), 0, mt_rand(20,25)));
        dump(substr(hash('sha256', getLenRand2(6).uniqid().getLenRand2(4)), 0, mt_rand(20,25)));
        dump(substr(md5(getLenRand2(6).uniqid().getLenRand2(4)), 0, mt_rand(20,25)));
        dump(substr(md5(getLenRand2(6).uniqid().getLenRand2(4)), 0, mt_rand(20,25)));

        $data = db('user')->where('id', 1)->value(['username, nickname']);

        dump($data);

        $arr = [5,4,3,2,1];
        //dump(array_splice($arr, 2));
        array_splice($arr, 2);
        dump($arr);
        $arrs = [1,2,3,4,5,6];
        array_splice($arrs,2);
        print_r($arrs);
  


    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
