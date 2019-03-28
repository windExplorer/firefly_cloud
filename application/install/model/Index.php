<?php
namespace app\install\model;

use think\Model;

class Index extends Model 
{
    /**
     * 1.检测数据库admin表中是否存在权限为0的记录 
     */
    public function index()
    {   
        try{
            $data = db('admin')->where('role', 0)->count();
            if(!empty($data)){
                return mSuccess($data);
            } else {
                return mEmpty();
            }
        } catch(\Exception $e) {
            return mError($e->getMessage());
        }
    }
}