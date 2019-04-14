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
                $data[] = [
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
        $header = \Request::header();
        $admin = session('admin');
        $data = [
            'admin_id'          =>  $admin['id'],
            'ref'               =>  empty($header['referer']) ? ' ' : $header['referer'],
            'url'               =>  $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
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
    public function upImage($file, $dir = 'noname'){
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
        $info = $file->validate([
            'size'  =>  2 * 1024 * 1024,
            'ext'   =>  'jpg,png,gif,ico,bmp,jpeg'
            ])->rule('date')->move($path);
        if($info){
            $ret['phy_url'] = str_replace("\\", "/", $path.$info->getSaveName());
            $ret['url'] = str_replace('./', '/', $ret['phy_url']);
            $ret['code'] = 1;
            $ret['msg'] = '上传成功!';
            $ret['etime'] = date('Y-m-d H:i:s');
        }else{
            // 上传失败获取错误信息
            $ret['msg'] = $file->getError();
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
    public function sendMail($to,$subject,$content){
        
        $data = db('setup')->where('mode','email')->order('id','asc')->select();

        //实例化PHPMailer核心类
        $mail = new \PHPMailer\PHPMailer\PHPMailer();
        //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
        #$mail->SMTPDebug = 1;
        //使用smtp鉴权方式发送邮件
        $mail->isSMTP();
        //smtp需要鉴权 这个必须是true
        $mail->SMTPAuth=true;
        //设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
        $mail->From = $data[0]['value'];
        //链接qq域名邮箱的服务器地址
        $mail->Host = $data[1]['value'];
        //设置使用ssl加密方式登录鉴权
        $mail->SMTPSecure = $data[2]['value'];
        //设置ssl连接smtp服务器的远程服务器端口号，以前的默认是25，但是现在新的好像已经不可用了 可选465或587
        $mail->Port = $data[3]['value'];
        //设置smtp的helo消息头 这个可有可无 内容任意
        $mail->Helo = $data[4]['value'];
        //设置发件人的主机域 可有可无 默认为localhost 内容任意，建议使用你的域名
        $mail->Hostname = $data[5]['value'];
        //设置发送的邮件的编码 可选GB2312 我喜欢utf-8 据说utf8在某些客户端收信下会乱码
        $mail->CharSet = $data[6]['value'];
        //smtp登录的账号 这里填入字符串格式的qq号即可
        $mail->Username = $data[7]['value'];
        //smtp登录的密码 使用生成的授权码 你的最新的授权码
        $mail->Password = $data[8]['value'];
        //设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
        $mail->FromName = $data[9]['value'];
        
        //邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
        $mail->isHTML(true);
        //设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
        $mail->addAddress($to,'测试通知');
        //添加多个收件人 则多次调用方法即可
        // $mail->addAddress('xxx@qq.com','lsgo在线通知');
        //添加该邮件的主题
        $mail->Subject = $subject;
        //添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
        $mail->Body = $content;

        //为该邮件添加附件 该方法也有两个参数 第一个参数为附件存放的目录（相对目录、或绝对目录均可） 第二参数为在邮件附件中该附件的名称
        // $mail->addAttachment('./d.jpg','mm.jpg');
        //同样该方法可以多次调用 上传多个附件
        // $mail->addAttachment('./Jlib-1.1.0.js','Jlib.js');

        $status = $mail->send();

        //简单的判断与提示信息
        if($status) {
            $success = 1;
        }else{
            $success = 2;
        }

        #写email数据库
        $add = [
            'from'  =>  db('setup')->where('name','EmailFrom')->value('value'),
            'to'    =>  $to,
            'subject'   =>  $subject,
            'content'   =>  $content,
            'pubtime'   =>  date('Y-m-d H:i:s'),
            'success'   =>  $success,
            'status'    =>  1,
        ];
        $res = db('email')->insertGetId($add);
        if(!empty($res)){
            $weigh = db('email')->where('id',$res)->update(['weigh' => $res]);
        }
        return $status;
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

        return config();
    }
}