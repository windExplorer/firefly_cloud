<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


/**
 * 获取10位随机字符串
 */
function getLenRand($len = 10){
  $strs = "QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm.*^$&@!#%-+/";
  return substr(str_shuffle($strs), mt_rand(0, strlen($strs) - 11), $len);
}

/**
 * 获取32位唯一字符串
 */
function getOnlyStr(){
  return md5(uniqid(microtime(true),true));
}

//获取ip
function getIP(){
  static $realip;
  if (isset($_SERVER)){
      if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
          $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
      } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
          $realip = $_SERVER["HTTP_CLIENT_IP"];
      } else {
          $realip = $_SERVER["REMOTE_ADDR"];
      }
  } else {
      if (getenv("HTTP_X_FORWARDED_FOR")){
          $realip = getenv("HTTP_X_FORWARDED_FOR");
      } else if (getenv("HTTP_CLIENT_IP")) {
          $realip = getenv("HTTP_CLIENT_IP");
      } else {
          $realip = getenv("REMOTE_ADDR");
      }
  } 
  return $realip;
}

//获取地理位置
function getGeo($ip='171.43.239.104'){
  $ip = getIp();
  //ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; GreenBrowser)');
  ini_set('user_agent', \Request::header('user_agent'));
  header($_SERVER['SERVER_PROTOCOL'].'charset=utf-8'); //h2需要很严格的header
  $url="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
  //$addr=json_decode(file_get_contents($url));
  //由于抓取的可能会有乱码，特来转码，这里没有用iconv转，用的新方法(我觉得的)
  //读入当前页面中的内容到一个字符串 put相反，将一个字符串写入文件
  $addr=json_decode(mb_convert_encoding(@file_get_contents($url),'UTF-8','UTF-8,GBK,GB2312,BIG5'),true);
  //$addr = json_decode(file_get_contents($url),true);
  //生成一个对象addr，有两个类 code 和 data,如果code=1,就没有成功;
  if(!empty($addr) || $addr['code'] == 0){
    $ad = $addr['data'];
    return $ad['country'].'-'.$ad['area'].'-'.$ad['region'].'-'.$ad['city'].'-'.$ad['isp'];   
  } else {
    return '';
  }    
}

/**
 * 第二种获取未知信息方法
  */
  function getGeo2($ip = '171.43.239.104'){
    //$ip = getIP();
    ini_set('user_agent', \Request::header('user_agent'));
    header($_SERVER['SERVER_PROTOCOL'].'charset=utf-8'); //h2需要很严格的header
    $url = 'http://whois.pconline.com.cn/ipJson.jsp?ip='.$ip;
    $addr = @file_get_contents($url);
    if($addr){
        $addr = mb_convert_encoding($addr,'UTF-8','UTF-8,GBK,GB2312,BIG5');
        $addr = json_decode(explode(');',explode('IPCallBack(',$addr)[1])[0],true);
        $addr['isp'] = explode(' ',$addr['addr'])[1];
        return '中国--'.$addr['pro'].'-'.$addr['city'].'-'.$addr['isp'];
    }else{
        return '';
    }
    
}

/**
 *  时间转化 regtime uptime
 * */
function TurnTime($time, $tag = '~'){
  //$time = preg_replace('# #','', $time);
  $t = explode($tag, $time);
  /* foreach($t as $k => $v){
    $t[$k] = strtotime($v);
  } */
  return $t;
}

/**
 * 模糊搜索
  */
function BlurSearch($data){
  $ret = [];
  foreach($data as $k => $v){
    $ret[] = [$k, 'like', '%'.$v.'%'];
  }
  return $ret;
}

/* 创建文件目录 */
function adddir($path){
  if(!file_exists($path) || !is_writable($path)){
      if(!mkdir($path, 0777, true)){
          return false;
      }else{
          return true;
      }
  }else{
      return true;
  } 
}


/**
 * 获取文件大小
 * Returns a human readable filesize
 */
function HumanReadableFilesize($size) {
  $mod = 1024; 
  //$units = explode(' ','B KB MB GB TB PB');
  $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
  for ($i = 0; $size > $mod; $i++) {
      $size /= $mod;
  }
  return round($size, 2) . ' ' . $units[$i];
}

/**
 * 获取system_config配置
 * 
  */
function sysConf($name){
  return db('system_config')->where('name', $name)->find()['value_context'];
}

/**
 * 修改system_config配置
  */
function set_sysConf($name, $value){
  return db('system_config')->where('name', $name)->update(['value_context' => $value]);
}

/* 获取表字段 */
function getTableColumn($table){
  $res = Db::query("select * from information_schema.columns where table_schema = ?  and table_name = ?", [config('database.database'), config('database.prefix').$table] );
  $ret = [];
  for($i = 0; $i < count($res); $i ++){
    $ret[] = $res[$i]['COLUMN_NAME'];
  }
  return $ret;
}

/* 检测上传的文件是否存在 */
function checkFileExist($md5){
  return db('attachment')->where('md5', $md5)->find();
}

/* 获取数据库总数量 */
function getDbCount($table, $where){
  return db($table)->where($where)->count();
}

/* 删除单个文件 */
function unlinkFile($path, $info = ''){
  if(file_exists($path)){
    if(!empty($info)){
      unset($info);
    }
    unlink($path);
  }
}

/* 获取域名 */
function getDomain()
{
  return request()->server('REQUEST_SCHEME').'://'.request()->host();
}