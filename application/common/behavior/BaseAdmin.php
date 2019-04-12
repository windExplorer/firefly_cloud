<?php
namespace app\common\behavior;

use think\Controller;
use think\Db;
use thnk\facade\APP;


class BaseAdmin extends Controller 
{

    public $pjax = 0;
    public $title = '萤火云管理系统';
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
     * 新增数据
     * 返回id
     */
    public function Create($table, $data)
    {
        return db($table)->insertGetId($data);
    }

    /**
     * 更新数据 
     * $data包含主键
      */
    public function Update($table, $id, $data)
    {
        $id = (array)$id;
        foreach($id as $k => $v){
            $row = db($table)->where('id', $v)->update($data);
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
            if(in_array($column, config('dbrule.need_enum'))){
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
        return empty(Db::query("select count(*) from information_schema.columns where table_name = ? and column_name = ?", [config('database.prefix').$table, $field])) ? false : true;
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