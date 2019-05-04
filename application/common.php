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
  $strs = "QQWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm.*^$&@!#%-+//";
  return substr(str_shuffle($strs), mt_rand(0, strlen($strs) - 11), $len);
}

/* 获取随机字符串2 */
function getLenRand2($len = 10){
  $strs = "QQWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm__";
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

function get_real_ip(){
    $ip=FALSE;
    //客户端IP 或 NONE 
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    }
    //多重代理服务器下的客户端真实IP地址（可能伪造）,如果没有使用代理，此字段为空
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
        if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
        for ($i = 0; $i < count($ips); $i++) {
            if (!eregi ("^(10│172.16│192.168).", $ips[$i])) {
                $ip = $ips[$i];
                break;
            }
        }
    }
    //客户端IP 或 (最后一个)代理服务器 IP 
    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}

function get_client_ip(){
	if ($_SERVER['REMOTE_ADDR']) {
		$cip = $_SERVER['REMOTE_ADDR'];
	} elseif (getenv("REMOTE_ADDR")) {
		$cip = getenv("REMOTE_ADDR");
	} elseif (getenv("HTTP_CLIENT_IP")) {
		$cip = getenv("HTTP_CLIENT_IP");
	} else {
		$cip = "unknown";
	}
	return $cip;
}

function get_online_ip(){
	$ip = '';
	if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}elseif(isset($_SERVER['HTTP_CLIENT_IP'])){
	$ip = $_SERVER['HTTP_CLIENT_IP'];
	}else{
	$ip = $_SERVER['REMOTE_ADDR'];
	}
	$ip_arr = explode(',', $ip);
	return $ip_arr[0];
}

function getRealIp(){
	if(null != request()->header('X-real-ip')) {
		return request()->header('X-real-ip');
	}else{
		return get_client_ip();
	}
}


//获取地理位置
function getGeo($ip='171.43.239.104'){
  $ip = getRealIp();
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
  function getGeo2($ip){
    //return '';
    $ip = getRealIp();
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
  $size = (int)$size;
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
function checkFileExist($md5, $table='attachment'){
  return db($table)->where('md5', $md5)->find();
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
  //nginx 无 $_SERVER['REQUEST_SCHEME']
  $protocol = stripos(strtolower($_SERVER['SERVER_PROTOCOL']),'https')  === false ? 'http' : 'https';
  return $protocol.'://'.$_SERVER['HTTP_HOST'];
}

/* 检测是否含有特殊字符 */
function hasSpecial($str){
  //$regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
  //return preg_replace($regex, "", $str);
  //$preg = "/[ '.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/";
  //$preg = "/[ ',:;*`@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/";
  $preg = "/[':;*`@#$%^&+=)(<>{}]|\/|\\\|\"|\|/";

  if(preg_match($preg, $str)){ 
    return false;
  }else{
    return true;
  }

}

/* api上传文件类型 */
function api_upload_type($type)
{
  switch($type){
    case 1: $table = 'user_attachment'; break;
    case 2: $table = 'file'; break;
    default: $table = 'user_attachment'; break;
  }
  return $table;
}

/* api获取父文件夹 */
function api_parent_folder($id, $user){
  if($id == 0){
    $folder = db('folder')->where(['pid' => 0, 'user_id' => $user['id']])->find();
  }else{
      $folder = db('folder')->where(['id' => $id, 'user_id' => $user['id']])->find();
  }
  return $folder;
}

/* 判断是文件还是文件夹，并返回相应的表名 */
function file_or_folder($type){
  // 1是文件夹 2是文件
  return $type == 1 ? 'folder' : 'file';
}

/* 删除文件的地址 */
function hideTruePlace($fileList){
  foreach($fileList as $k => $v){
    $fileList[$k]['path'] = '';
    $fileList[$k]['net_path'] = '';
  }
  return $fileList;
}

/* 获取无限极目录文件 */
function GetTree($data, $pid = 0){
  $tree = [];
  foreach($data as $k => $v){
      if($v['pid'] == $pid){
          $v['child'] = GetTree($data, $v['id']);
          $tree[] = $v;
          //unset($data[$k]);
      }
  }
  return $tree;
}

/* 根据user和folder_id获取文件夹下的子文件夹 */
function getChildFolder($user, $folder_id){
  return db('folder')->where([
    'user_id' =>  $user['id'],
    'pid' =>  $folder_id,
    'is_deleted'  => 0,
  ])->select();
}

/* 根据user和folder_id获取文件夹下的子文件 */
function getChildFile($user, $folder_id){
  return db('file')->where([
    'user_id' =>  $user['id'],
    'folder_id' =>  $folder_id,
    'is_deleted'  => 0,
  ])->select();
}

/* 根据user和folderIds获取这些文件夹的信息 */
function getFolderInfo($user, $ids){
  return db('folder')->where([
    'user_id' =>  $user['id'],
    'is_deleted'  =>  0
  ])->whereIn('id', $ids)->select();
}

/* 根据user和fileIds获取这些文件的信息 */
function getFileInfo($user, $ids){
  return db('file')->where([
    'user_id' =>  $user['id'],
    'is_deleted'  =>  0
  ])->whereIn('id', $ids)->select();
}

/* 比对两个数组中是否存在同名的元素 */
function checkSameName($arr1, $arr2){
  foreach($arr1 as $a1){
    foreach($arr2 as $a2){
      if($a1['name'] == $a2['name']){
        return $a1['name'];
      }
    }
  }
  return false;
}


/* 递归复制目录文件 */
function TreeCopy($data, $pid, $need, $user){
  $tree['folder'] = [];
  $count_file = 0;
  foreach($data as $arr){
      if($arr['pid'] == $pid){
        $need_child = TreeCopyFolder($arr, $need, $user);
        $count_file +=  TreeCopyFile($arr, $need_child, $user);
        $arr['child'] = TreeCopy($data, $arr['id'], $need_child, $user);
        $tree['folder'][] = $need_child;
        //unset($data[$k]);
      }
  }
  $tree['count_file'] = $count_file;
  return $tree;
}

/* 复制文件夹写库 正在操作的目录，需要的pid目录 */
function TreeCopyFolder($arr, $need, $user){
  $pid_path = $need['pid_path'];
  $path = $need['path'];
  $sql = [
      'pid'   =>  $need['id'],
      'user_id'   =>  $user['id'],
      'name'  =>  $arr['name'],
      'remark_context'    =>  $arr['remark_context'],
      'regtime'   =>  time(),
      'uptime'    =>  time(),
      'pid_path'  =>  $pid_path,
      'path'      =>  $path
  ];
  $need_id = db('folder')->insertGetId($sql);
  $need_child = [
      'id'    =>  $need_id,
      'pid_path'  =>  $pid_path.$need_id.'/',
      'path'      =>  $path.$arr['name'].'/'
  ];
  return $need_child;
}

/* 复制文件[文件夹类的文件] */
function TreeCopyFile($arr, $need, $user){
  //获取当前文件夹中所有的文件
  $files = db('file')->where([
    'user_id' =>  $user['id'],
    'is_deleted' => 0,
    'folder_id' =>  $arr['id']
  ])->select();
  // 遍历所有获取到的文件并进行重新写库
  $count = 0;
  foreach($files as $file){
    //$sql = array_splice($file['id']);
    $sql = [
      'uptime'    =>  time(),
      'regtime'   =>  time(),
      'is_second' =>  1,
      'folder_id' =>  $need['id'],
      'user_path' =>  $need['pid_path'],
      'old_path'  =>  $need['pid_path'],
      'user_id'   =>  $user['id'],
      'share_frequency' =>  0,
      'down_frequency'  =>  0,
      'is_encrypt'  =>  0
    ];
    unset($file['id']);
    $sql = array_merge($file, $sql);
    $res = db('file')->insertGetId($sql);
    $count += 1;
  }  
  return $count;

}
/* 复制文件[单个文件] */
function TreeCopyFile_Only($arr, $need, $user){
  //$sql = array_splice($file['id']);
  $sql = [
    'uptime'    =>  time(),
    'regtime'   =>  time(),
    'is_second' =>  1,
    'folder_id' =>  $need['id'],
    'user_path' =>  $need['pid_path'],
    'old_path'  =>  $need['pid_path'],
    'user_id'   =>  $user['id'],
    'share_frequency' =>  0,
    'down_frequency'  =>  0,
    'is_encrypt'  =>  0
  ];
  unset($arr['id']);
  $sql = array_merge($arr, $sql);
  $res = db('file')->insertGetId($sql);
  return 1;
}


/* 移动 */
/* 获取文件夹新的pid_path和path，以及user_path */
function TreeMoveFolderPath($arr, $need, $id){
  $start = $need['pid_path'];
  $str = $arr['pid_path'];
  $start_name= $need['path'];
  $str_name = $arr['path'];
  $start_arr = explode('/', $start);
  unset($start_arr[count($start_arr)-1]);
  $str_arr = explode('/', $str);
  $start_name_arr = explode('/', $start_name);
  unset($start_name_arr[count($start_name_arr)-1]);
  $str_name_arr = explode('/', $str_name);
  $ind = array_search($id, $str_arr);
  $str_arr = array_splice($str_arr, $ind);
  $str_name_arr = array_splice($str_name_arr, $ind);
  $new_arr = array_merge($start_arr, $str_arr);
  $new_name_arr = array_merge($start_name_arr, $str_name_arr);
  $last = implode('/', $new_arr);
  $last_name = implode('/', $new_name_arr);
  return [
    'id_path' =>  $last,
    'name_path' =>  $last_name
  ];
}

function TreeMoveFilePath($arr, $need, $id){
  $start = $need['pid_path'];
  $str = $arr['user_path'];
  $start_arr = explode('/', $start);
  unset($start_arr[count($start_arr)-1]);
  $str_arr = explode('/', $str);
  $ind = array_search($id, $str_arr);
  $str_arr = array_splice($str_arr, $ind);
  $new_arr = array_merge($start_arr, $str_arr);
  $last = implode('/', $new_arr);

  return [
    'id_path' =>  $last,
  ];
}

/* 下载文件 */

function download($file){
  //url使用相对路径\
  $url = $file['path'];
  //$file = file_get_contents($url);
  //$file_size =  round(strlen($file)/1024/1024, 2).'MB';
  $size = filesize($url);
  $size2=$size-1;//文件总字节数 
  if(isset($_SERVER['HTTP_RANGE'])) {  
    list($a, $range)=explode("=",$_SERVER['HTTP_RANGE']); 
     //if yes, download missing part 
    str_replace($range, "-", $range);//这句干什么的呢。。。。 
    $new_length=$size2-$range;//获取下次下载的长度 
    header("HTTP/1.1 206 Partial Content"); 
    header("Content-Length: $new_length");//输入总长 
    header("Content-Range: bytes $range-$size2/$size"); //Content-Range: bytes 4908618-4988927/4988928   95%的时候 
  } else {//第一次连接 
    header("Content-Range: bytes 0-$size2/$size"); //Content-Range: bytes 0-4988927/4988928 
    header("Content-Length: ".$size);//输出总长 
  } 

  header('Content-Description: File Transfer');
  //header('Content-Range: bytes 0-' . $size . '/' . $file_size);          // 返回内容范围，断点续传
  //header('Content-Type: application/octet-stream');                  // 文件类型强制为二进制
  header("Content-type:".$file['mime']);
  //header("Accept-Ranges:bytes");
  header("Accept-Length:".$size);
  //header('Content-Length:'.$size);
  header('Content-Transfer-Encoding: binary');
  header('Expires: 0');
  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
  header('Pragma: public');
  header("Content-Disposition:attachment;filename=".$file['name']);

  readfile($url);
  exit();
}

//屏蔽数组键值对
function removeKey($list, $need){
    foreach($need as $k => $v){
        unset($list[$v]);
    }
    return $list;
}
//筛选数组键值对
function filterKey($list, $need){
    $arr = [];
    foreach($need as $k => $v){
        $arr[$v] = $list[$v];
    }
    return $arr;
}

