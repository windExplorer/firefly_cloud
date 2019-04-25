<?php
namespace app\common\behavior;

use think\Controller;
use think\Db;
use thnk\facade\APP;


class BaseApi extends Controller 
{
    public $Token = '';
    public $Header = '';
    public $User = '';
    public $Username = '';

    public function initialize(){
        $this->Header = request()->header();
        $this->Token = $this->Header['token'];
        $this->Username = $this->Header['username'];
    }

    public function Restful($data = '', $code = 1, $msg = '请求成功'){
        return json([
            'data'  =>  $data,
            'code'  =>  $code,
            'msg'   =>  $msg,
            'time'  =>  time(),
            'datetime'  =>  date('Y-m-d H:i:s'),
            'token' =>  $this->Token
        ]);
    }

    /* 检测token */
    public function checkToken(){
        $data = db('user')->where(['token' => $this->Token, 'username' => $this->Username])->find();
        $this->User = $data;
        return $data;
    }

    /**
     * XSS过滤
     * @param string $dirty_html 需要过滤的字符串
     * @return string $clean_html 过滤后的字符串
     */
    public function RemoveXss($dirty_arr = [])
    {
        if(!empty($dirty_arr)){
            $config = \HTMLPurifier_Config::createDefault();
            //配置
            $purifier = new \HTMLPurifier($config);
            foreach($dirty_arr as $k => $v){
                $dirty_arr[$k] = $purifier->purify($v);
            }
            return $dirty_arr;
        }
        
        return $clean_html;
    }

    /**
     * 查询数据库
     *  limit 0:查记录集   1:查一个  其他:根据页码查
     */
    public function Retrieve($table, $where = '', $limit = 1, $page = 0, $order = 'id asc')
    {
        $db = db($table)->where($where)->order($order);
        if($limit == 0)
            return $db->select();
        else if($limit == 1)
            return $db->find();
        else
            return $db->page($page, $limit)->select();
            
    }

    /**
     * 新增数据
     * 返回id
     */
    public function Create($table, $data)
    {
        return db($table)->strict(false)->insertGetId($data);
    }

    /**
     * 写用户日志表
     * user_log_type 日志类型[-1:注册,0:增,1:删,2:改,3:查,4:登录,5:退出]
     */
    public function Addlog($table, $info, $user_log_type)
    {
        //nginx 无 $_SERVER['REQUEST_SCHEME']
        $protocol = stripos(strtolower($_SERVER['SERVER_PROTOCOL']),'https')  === false ? 'http' : 'https';
        $header = $this->Header;
        $user = $this->User;
        $data = [
            'user_id'           =>  $user['id'],
            'ref'               =>  empty($header['referer']) ? ' ' : $header['referer'],
            'url'               =>  $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
            'table'             =>  $table,
            'info'              =>  $info,
            'ip'                =>  getIP(),
            'location'          =>  getGeo2(),
            'user_agent'        =>  $header['user-agent'],
            'user_log_type'    =>   $user_log_type,
            'status'            =>  1,
            'is_deleted'        =>  0,
            'regtime'           =>  time(),
            'uptime'            =>  time()
        ];
        return $this->Create('admin_log', $data);
    }

    /**
     * 更新数据 
     * 
      */
      public function Update($table, $id, $data)
      {
          $id = (array)$id;
          foreach($id as $k => $v){
            $row = db($table)->where('id', $v)->strict(false)->update($data);
          }
          return $row;
  
      }
}