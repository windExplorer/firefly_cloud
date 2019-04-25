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
     * user_log_type 日志类型[-1:注册,0:增,1:删,2:改,3:查,4:登录,5:退出,6:邮箱]
     */
    public function Addlog($table, $info, $user_log_type)
    {
        $header = $this->Header;
        $user = $this->User;
        $data = [
            'user_id'           =>  $user['id'],
            'ref'               =>  empty($header['referer']) ? ' ' : $header['referer'],
            'url'               =>  getDomain().$_SERVER['REQUEST_URI'],
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
        return $this->Create('user_log', $data);
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

    /**
     * 发送邮件方法
     * @param string $to：接收者邮箱地址
     * @param string $title：邮件的标题
     * @param string $content：邮件内容
     * @return boolean  true:发送成功 false:发送失败
     */
    public function sendMail($to, $subject, $content, $to_name = 'Hello World', $context = '', $text = 'Message has been sent', $files = '', $type = 0){


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
            $this->Addlog('email', $text, 6);
            # 写邮件表
            $data = [
                'user_id'   => $this->User['id'],
                'admin_id'  =>  0,
                'to'    =>  $to,
                'from'  =>  sysConf('email_url'),
                'subject'   =>  $subject,
                'content'   =>  $content,
                'context'   =>  $context,
                'email_type'    => $type,
                'regtime'   =>  time(),
                'uptime'    =>  time(),
                'email_files'   =>  $files
            ];
            $this->Create('email', $data);
            return true;
        } catch (Exception $e) {
            $this->Addlog('email', `Message could not be sent. Mailer Error: {$mail->ErrorInfo}`, 6);
            return false;
        }

    
    }
}