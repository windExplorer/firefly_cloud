<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');

Route::get('installing', 'install/index/index');

Route::get('installC', 'install/index/confirm');

Route::get('login', 'admin/login/index');

Route::get('downmyfile', 'api/file/downmyfile');

Route::get('download/:id/:encrypt/:link', 'api/share/download');


return [

];
