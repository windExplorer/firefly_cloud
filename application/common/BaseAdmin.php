<?php
namespace app\common;

use think\Controller;
use think\Db;

class BaseAdmin extends Controller 
{
    public function test()
    {
        return 123;
    }
}