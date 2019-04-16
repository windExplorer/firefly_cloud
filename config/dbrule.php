<?php
  /**
   * 系统规则
   * 数据库某些字段配置
   */
  
  return [
    //管理员日志类型
    'admin_log' =>  [
      ['code' => 0, 'name' => '增'],
      ['code' => 1, 'name' => '删'],
      ['code' => 2, 'name' => '改'],
      ['code' => 3, 'name' => '查'],
      ['code' => 4, 'name' => '登录'],
      ['code' => 5, 'name' => '退出'],
      ['code' => 6, 'name' => '邮箱'],
    ],
    //菜单类型
    'menu_type' => [
      ['code' => 0, 'name' => '非菜单'],
      ['code' => 1, 'name' => '链接'],
      ['code' => 2, 'name' => '图片'],
      ['code' => 3, 'name' => '文字'],
      ['code' => 4, 'name' => '图标'],
      ['code' => 5, 'name' => '自定义'],
    ],
    //状态
    'status' => [
        ['code' => 0, 'name' => '隐藏'],
        ['code' => 1, 'name' => '显示'],
    ],
    //删除状态
    'is_deleted' => [
        ['code' => 0, 'name' => '未删除'],
        ['code' => 1, 'name' => '已删除'],
    ],
    //性别
    'gender' => [
        ['code' => 0, 'name' => '保密'],
        ['code' => 1, 'name' => '女'],
        ['code' => 2, 'name' => '男'],
    ],
    //所有需要枚举的字段
    //数据库建表该类型字段应如一下格式建立      日志类型[0:增,1:删,2:改,3:查,4:登录,5:退出]
    'need_enum' => [
      'status', 'is_deleted', 'type', 'menu_type', 'gender', 'admin_log', 'user_log', 'email_type', 'is_encrypt', 'share_type', 'allow_comment',
      'show_location', 'is_system', 'is_read', 'config_type', 'up_type', 'user_log_type', 'role', 'admin_log_type', 'is_success'
    ],
    //所有需要检测唯一性的字段
    'column_unique' => [
      'username', 'name', 'nickname'
    ],
  ];