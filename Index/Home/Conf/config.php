<?php
return array(
	//数据库配置
	'DB_HOST' => '127.0.0.1',	//数据库服务器地址
	'DB_USER' => 'root',	//数据库连接用户名
	'DB_PWD' => '',	//数据库连接密码
	'DB_NAME' => 'weibo', //使用数据库名称
	'DB_PREFIX' => 'hd_',	//数据库表前缀

	'DEFAULT_THEME' => 'default',	//默认主题模版
	'URL_MODEL' => 1,	//默认URL模式使用PATHINFO
	'DB_TYPE' => 'mysql', // 数据库类型
 	'DB_PORT' => 3306, // 端口

 	//用于异位或加密的KEY
 	'ENCTYPTION_KEY' => 'www.houdunwang.com',
 	//自动登录保存时间
 	'AUTO_LOGIN_TIME' => time() + 3600 * 24 * 7, //一个星期

 	'UPLOAD_MAX_SIZE' => 2000000, //设置图片最大上传大小为2M
 	'UPLOAD_PATH' => './Uploads/', //文件上传保存路径
 	'UPLOAD_EXTS' => array('jpg','jpeg','gif','png'), //允许文件上传的后缀

 	//URL路由配置
 	'URL_ROUTER_ON' => true,	//开启路由功能
 	'URL_ROUTE_RULES' => array( //定义路由规则
 		':id\d' => 'User/index',
 		),
);
?>