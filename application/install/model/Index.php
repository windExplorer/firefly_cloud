<?php
namespace app\install\model;

use think\Model;

class Index extends Model 
{
    protected $table = 'admin';
    /**
     * 1.检测数据库admin表中是否存在权限为0的记录 
     */
    public function index()
    {   
        try{
            $data = db('admin')->where('role', 0)->count();
            if(!empty($data)){
                return $this->mSuccess($data);
            } else {
                return $this->mEmpty();
            }
        } catch(\Exception $e) {
            return $this->mError($e->getMessage());
        }
    }

    public function create_file($type = 2)
    {
        if($type == 2)
            $content = "Repair on ".date('Y-m-d H:i:s')."\r\n";
        else
            $content = "Create on ".date('Y-m-d H:i:s')."\r\n";
        $file = file_put_contents('../install.lock', $content, FILE_APPEND | LOCK_EX);
        return $file;
    }

    public function create_admin()
    {
        $ret = [];
        $salt = getLenRand(5);
        $password = getLenRand(10);
        $insertData = [
            'username'      =>  'admin',
            'nickname'      =>  '系统管理员',
            'password'      =>  sha1($password.$salt),
            'salt'          =>  $salt,
            'avatar'        =>  '/static/source/img/admin.jpeg',
            'gender'        =>  0,
            'born'          =>  date('Y-m-d'),
            'token'         =>  getOnlyStr(),
            'login_fail'    =>  0,
            'role'          =>  0,
            'status'        =>  1,
            'is_deleted'    =>  0,
            'regtime'       =>  time(),
            'uptime'        =>  time()
        ];
        $insertId = db($this->table)->insertGetId($insertData);
        if(!empty($insertId)){
            $ret['db'] = $insertId;
            $ret['password'] = $password;
            $content = "[ADMIN - PASSWORD]\r\nadmin\r\n".$password."\r\n";
            $file = file_put_contents('../install.lock', $content, FILE_APPEND | LOCK_EX);
            $ret['file'] = $file;
        }   
        return $ret;
    }

    /**
     * 成功信息 
     */
    protected function mSuccess($data = ''){
        return [true, 'suc', $data];
    }
    
    /**
     * 错误信息 
     */
    protected function mError($data = ''){
        return [false, 'err', $data];
    }
    
    /**
     * 空信息 
     */
    protected function mEmpty($data = ''){
        return [false, 'emp', $data];
    }
    
}