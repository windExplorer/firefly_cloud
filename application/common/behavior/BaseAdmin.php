<?php
namespace app\common\behavior;

use think\Controller;
use think\Db;
use thnk\facade\APP;


class BaseAdmin extends Controller 
{

    public $pjax = 0;
    public $title = 'FireFly';
    public $where = ['is_deleted' => 0];
    

    /**
     * admin所有都需要登录才允许进入
     */
    
    protected $beforeActionList = [
        
    ];

    /**
     * 
     * 初始化 
     * 1.检测是否安装程序
     */
    public function initialize()
    {
        $this->checkInstall();
        if(!strpos(request()->url(), 'login'))
            $this->checkLogin();
        if(request()->isPjax())
            $this->pjax = 1;
        else {
            /* dump($this->getMenu());
            die; */
            $this->assign([
                'title'     =>  $this->title,
                'admin'     =>  session('admin'),
                'nav'       =>  $this->getMenu(),
                'thUrl'     =>  request()->url()
            ]);
        }

        $this->assign('pjax', $this->pjax);
    }

    /**
     * 检测是否安装程序
     */
    public function checkInstall()
    {
        if(!file_exists('../install.lock')){
            $this->redirect(url('install/index/confirm'));
        }
    }

    /**
     * 检测是否登录
     * 
     */
    public function checkLogin(){

        //检测session('admin')是否存在
        if(!session('?admin')){
            $this->redirect(url('admin/Login/index'));
        }
    }

    /**
     * 权限检测
     */
    public function checkAuth()
    {

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
     * 查询-表格
     * 
      */
    public function SearchRetrieve($table, $db)
    {
        
    }

    /**
     * 检测唯一字段
      */
    public function SearchUnique($table, $data, $n = 0)
    {
        $db = db($table);
        if(isset($data['id'])){
            $db = $db->where('id', '<>', $data['id']);
        }
        unset($data['id']);
        foreach($data as $k => $v){
            if(in_array($k, config('dbrule.column_unique'))){
                if(count($db->where($k, $v)->select()) > $n){
                    return [
                        'code'      =>  0,
                        'column'    =>  $k,
                        'msg'       =>  $v.'已存在'
                    ];
                }
            }
        }
        return ['code'  =>  1, 'msg' => '无误'];
    }

    /**
     * 检测是否有改动
     * 
      */
    public function SearchEdit($table, $data){
        $db_data = db($table)->where('id', $data['id'])->find();
        $flag = false;
        foreach($db_data as $k => $v){
            if(stripos($k, 'time') === false){
                if(isset($data[$k])){
                    if($v != $data[$k]){
                        return true;
                    }
                    //echo $k.'=>'.$v.":".$data[$k]."[".$flag."]"."\r\n";
                }  
            }
        }
        return $flag;
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
     * 更新数据 
     * $data包含主键
      */
    public function Update($table, $id, $data)
    {
        $id = (array)$id;
        foreach($id as $k => $v){
            $row = db($table)->where('id', $v)->strict(false)->update($data);
        }
        return $row;

    }

    /**
     * 删除数据
     * 如果表中存在字段is_deleted，则为软删除，否则进行硬删除
      */
    public function Delete($table, $id)
    {
        if($this->CheckTableField($table, 'is_deleted')){
            return $this->Update($table, $id, ['is_deleted' => 1, 'uptime' => time()]);
        } else {
            return db($table)->delete($id);
        }
    }

    /**
     * 获取数据库表字段以及相关信息-并且进行转化
      */
    public function GetColumnInfo($table)
    {
        $res = Db::query("select * from information_schema.columns where table_schema = ?  and table_name = ?", [config('database.database'), config('database.prefix').$table] );
        $data = [];
        foreach($res as $k => $v)
        {
            $column = $v['COLUMN_NAME'];
            $comment = $v['COLUMN_COMMENT'];
            $default = $v['COLUMN_DEFAULT'];
            if(in_array($column, config('dbrule.need_enum')) && stripos($comment, '[') !== false){
                $com = explode('[', $comment);
                $data[$column] = [
                    'name'          =>  $column,
                    'title'         =>  $com[0],
                    'allow_null'    =>  $v['IS_NULLABLE'],
                    'default'       =>  $default,
                ];
                $child = explode(',', explode(']', $com[1])[0]);
                foreach($child as $k => $v){
                    $a = explode(':', $v); 
                    $data[$column]['child'][$a[0]] = $a[1];
               } 
            }else{
                $data[$column] = [
                    'name'          =>  $column,
                    'title'         =>  $comment,
                    'default'       =>  $default,
                    'allow_null'    =>  $v['IS_NULLABLE'],
                    'child'         =>  []
                ];
            }
        }
        return $data;
    }

    /**
     * 判断数据库表中字段是否存在
     * 返回 true false
      */
    public function CheckTableField($table, $field)
    {
        return empty(Db::query("select * from information_schema.columns where table_name = ? and column_name = ?", [config('database.prefix').$table, $field])) ? false : true;
    }

    /**
     * 写管理员日志表
     * admin_log_type 日志类型[0:增,1:删,2:改,3:查,4:登录,5:退出]
     */
    public function Addlog($table, $info, $admin_log_type)
    {
        //nginx 无 $_SERVER['REQUEST_SCHEME']
        //$protocol = stripos(strtolower($_SERVER['SERVER_PROTOCOL']),'https')  === false ? 'http' : 'https';
        $header = \Request::header();
        $admin = session('admin');
        $data = [
            'admin_id'          =>  $admin['id'],
            'ref'               =>  empty($header['referer']) ? ' ' : $header['referer'],
            'url'               =>  getDomain().$_SERVER['REQUEST_URI'],
            'table'             =>  $table,
            'info'              =>  $info,
            'ip'                =>  getIP(),
            'location'          =>  getGeo2(),
            'user_agent'        =>  $header['user-agent'],
            'admin_log_type'    =>  $admin_log_type,
            'status'            =>  1,
            'is_deleted'        =>  0,
            'regtime'           =>  time(),
            'uptime'            =>  time()
        ];
        return $this->Create('admin_log', $data);
    }


    /**
     * 获取导航[一级菜单]
     * 左侧，顶部左，顶部右
     */
    public function getMenu()
    {
        $this->where = ['is_deleted' => 0, 'status' => 1];
        $data = [];
        for($i = 0; $i < 3; $i ++){
            $res = db('menu')->where(['pid' => $i + 1])->where($this->where)->order('weigh', 'asc')->select();
            $data[$i] = $this->getMenu2($res);
        }
        return $data;
    }

    /**
     * 获取导航[2级菜单]
     *  
     */
    public function getMenu2($data)
    {
        $this->where = ['is_deleted' => 0, 'status' => 1];
        if(!empty($data)){
            foreach($data as $k => $v){
                $data[$k]['child'] = db('menu')->where('pid', $v['id'])->where($this->where)->order('weigh', 'asc')->select();
            }
        }
        return $data;
    }

    /**
     * 无限极菜单
     * 
      */
    
    public function GetChildren($table, $dom_s = '<li>', $dom_e = '</li>', $selected = 0){
        $arr = $this->Retrieve($table, ['is_deleted' => 0], 0);
        return [
            'tree'  =>  $this->GetTree($arr, 0),
            'dom'   =>  $this->TreeToDom($arr, 0, 0, $dom_s, $dom_e, $selected)
        ];
    }

    public function GetTree($data, $pid){
        $tree = [];
        foreach($data as $k => $v){
            if($v['pid'] == $pid){
                $v['child'] = $this->GetTree($data, $v['id']);
                $tree[] = $v;
                //unset($data[$k]);
            }
        }
        return $tree;
    }

    /**
     * 生成dom元素
     * 
      */
    public function TreeToDom($data, $pid, $level = 0, $dom_s = '<li>', $dom_e = '</li>', $selected){
        
        $html = '';
        $deep = '';
        for($i = 0; $i < $level; $i ++){
            $deep .= '&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        if($pid == 0){
            $deep = '┎ '.$deep;
        }else{
            $deep .= '┠━ ';
        }
        
        foreach($data as $k => $v){
            if($dom_s == '<option>'){
                if($selected == $v['id'])
                    $dom_s_s = '<option value='.$v['id'].' selected>';
                else
                    $dom_s_s = '<option value='.$v['id'].' >';  
            }else{
                $dom_s_s = $dom_s;
            }
            if($v['pid'] == $pid){ 
                $html .= $dom_s_s.$deep.$v['name'];
                $html .= $this->TreeToDom($data, $v['id'], $level + 1, $dom_s, $dom_e, $selected);
                $html = $html.$dom_e;
            }
            
        }
        //return $html ? '<ul>'.$html.'</ul>' : $html ;
        return $html;
    }

    /**
     * @param array $files 准备上传的文件
     * @param string $dir 上传的目标目录
     * @param array 
     */
    public function upImage($file, $dir = 'noname', $size = '', $ext = ''){
        if(empty($size))
            $size = sysConf('upload_maxsize');
        if(empty($ext))
            $ext = sysConf('upload_ext');

        $ret = [
            'code'      =>  0,
            'phy_url'   =>  '',
            'url'       =>  '',
            'msg'       =>  '',
            'stime'     =>  date('Y-m-d H:i:s'),
            'etime'     =>  ''
        ];
        $path = './uploads/'.$dir.'/';
        //检测目录是否存在
        if(!adddir($path)){
            $ret['msg'] = '创建目录失败，请检测权限后重试!';
            $ret['etime'] = date('Y-m-d H:i:s');
            return $ret;
        }
        if($ext == '*'){
            $info = $file->validate([
                'size'  => $size
                ])->rule('date')->move($path);
        }else{
            $info = $file->validate([
                'size'  => $size,
                'ext'   => $ext
                ])->rule('date')->move($path);
        }
        /* $info = $file->validate([
            'size'  =>  $size,
            'ext'   =>  $ext
            ])->rule('date')->move($path); */
        //dump($info);die;
        if($info){
            $ret['code'] = 1;
            $ret['msg'] = '上传成功!';
            $ret['etime'] = date('Y-m-d H:i:s');
            $md5 = $info->md5();
            $check = checkFileExist($md5, 'attachment');
            $ret['phy_url'] = str_replace('//', '/', str_replace("\\", "/", $info->getPathName()));
            $ret['url'] = str_replace('./', '/', $ret['phy_url']);
            if(empty($check)){
                //写图片数据
                $sha1 = $info->sha1();
                $imgData = [
                    'admin_id'  =>  session('admin.id'),
                    'name'      =>  $info->getInfo('name'),
                    'save_name' =>  $info->getFileName(),
                    'mime'      =>  $info->getInfo('type'),
                    'ext'       =>  $info->getExtension(),
                    'size'      =>  $info->getInfo('size'),
                    'path'      =>  $ret['phy_url'],
                    'net_path'  =>  $ret['url'],
                    'md5'       =>  $md5,
                    'sha1'      =>  $sha1,
                    'status'    =>  1,
                    'is_deleted'    =>  0,
                    'regtime'   =>  time(),
                    'uptime'    =>  time()
                ];
                $this->Create('attachment', $imgData);
            }else{
                if(file_exists($ret['phy_url'])){
                    unset($info);
                    unlink($ret['phy_url']);
                }
                $ret['phy_url'] = $check['path'];
                $ret['url'] = $check['net_path'];

            }
        }else{
            // 上传失败获取错误信息
            $ret['msg'] = '上传失败'.$file->getError();
            $ret['etime'] = date('Y-m-d H:i:s');
        }    
        return $ret;
    }


    /**
     * 发送邮件方法
     * @param string $to：接收者邮箱地址
     * @param string $title：邮件的标题
     * @param string $content：邮件内容
     * @return boolean  true:发送成功 false:发送失败
     */
    public function sendMail($to, $subject, $content, $to_name = 'Hello World', $context = '', $files = '', $type = 0){


        //实例化PHPMailer核心类
        //$mail = new \PHPMailer\PHPMailer\PHPMailer();
        //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
        //$mail->SMTPDebug = 1;

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);;

        try {
            //Server settings
            $mail->SMTPDebug = 0;                                       // Enable verbose debug output  启用详细调试输出 2
            $mail->CharSet   = 'utf-8';                                 // 字符编码
            $mail->isSMTP();                                            // Set mailer to use SMTP  将Mailer设置为使用SMTP
            $mail->Host       = sysConf('email_smtp');                  // Specify main and backup SMTP servers  指定主服务器和备份SMTP服务器
            $mail->SMTPAuth   = (bool)sysConf('email_auth');            // Enable SMTP authentication 启用SMTP身份验证
            $mail->Username   = sysConf('email_username');                   // SMTP username
            $mail->Password   = sysConf('email_password');              // SMTP password
            $mail->SMTPSecure = sysConf('email_secure');                // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = sysConf('email_port');                  // TCP port to connect to

            //Recipients
            $mail->setFrom(sysConf('email_url'), sysConf('email_nick'));
            $mail->addAddress($to, $to_name);     // Add a recipient
            //$mail->addAddress('ellen@example.com');               // Name is optional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            // Attachments
            if(!empty($files)){
                $arr = explode(';', $files);
                foreach($arr as $k => $v){
                    $mail->addAttachment($v);
                }
            }
            
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(!(bool)$type);                                  // Set email format to HTML
            $mail->Subject = $subject;
            if($type == 0)
                $mail->Body    = $content;
            else
                $mail->Body    = $context;
            $mail->AltBody = $context;

            $mail->send();
            $this->Addlog('email', 'Message has been sent', 6);
            return true;
        } catch (Exception $e) {
            $this->Addlog('email', `Message could not be sent. Mailer Error: {$mail->ErrorInfo}`, 6);
            return false;
        }

    
    }



    public function test()
    {
        $aa = Db::connect([
            // 数据库类型
            'type'        => 'mysql',
            // 数据库连接DSN配置
            'dsn'         => '',
            // 服务器地址
            'hostname'    => '127.0.0.1',
            // 数据库名
            'database'    => 'firefly_cloud',
            // 数据库用户名
            'username'    => 'root',
            // 数据库密码
            'password'    => 'shenlu123',
            // 数据库连接端口
            'hostport'    => '',
            // 数据库连接参数
            'params'      => [],
            // 数据库编码默认采用utf8
            'charset'     => 'utf8',
        ])->table('admin')->find();
        dump($aa);
        dump(env('root_path'));
        dump(exec('cd '. env('root_path') .' && php think version'));
        dump(ini_get('upload_max_filesize'));
        dump(ini_get('max_file_uploads'));
        dump(app()->version());
        dump(app()->config());
        dump(env());
        $a = Db::query("select version()");
        dump($a[0]['version()']);
        dump(disk_total_space('/')/1024/1024/1024);     #获取总大小
        dump(disk_free_space('/')/1024/1024/1024);     #获取可用空间

        return config();
    }
}