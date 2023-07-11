<?php
return array(
	'URL_MODEL'					=>	1,
	'DB_TYPE'					=>	'mysql',
	'DB_HOST'					=>	'localhost',
	'DB_NAME'					=>	'presale',
	'DB_USER'					=>	'root',
	'DB_PWD'					=>	'root密码',
	'DB_PORT'					=>	'3306',
	'DB_PREFIX'					=>	'tb_',
	// 'APP_AUTOLOAD_PATH'			=>	'@.TagLib',
	'SESSION_AUTO_START'		=>	true,
	'TMPL_ACTION_ERROR'			=>	'Public:success',
	'TMPL_ACTION_SUCCESS'		=>	'Public:success',
	'VAR_PAGE'                  =>'p',
	'PAGE_SIZE'                 => 10,
	
	//网站设置，设置完后清除缓存，刷新即可
	'APP_DUBEG'=>false,
	'SHOW_PAGE_TRACE'=>false,
    'SESSION_AUTO_START'=>true,
	'HTTP_CACHE_CONTROL' => 'private,no-transform',
	'siteloginonly' => '1' ,  //网站开启是否唯一性登录，当第二个登录会抢走。 1 开启 为空或其他都为关闭。
	'sitesessionouttime' => '3600',      //定义SESSION过期时间，秒为单位。
	'userpage' => '16'  ,    //用户定义分页。
	'sitehelpurl' => 'https://www.processon.com/view/link/63203ac60e3e7420767e05c7' ,       //使用说明。
	'nologin' => 0 ,    //禁止员工登录

);
