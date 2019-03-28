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
 * 成功信息 
 */
function mSuccess($data = ''){
  return [true, 'suc', $data];
}

/**
 * 错误信息 
 */
function mError($data = ''){
  return [false, 'err', $data];
}

/**
 * 空信息 
 */
function mEmpty($data = ''){
  return [false, 'emp', $data];
}