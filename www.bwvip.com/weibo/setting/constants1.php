<?php

/*******************************************************************

 * [JishiGou] (C)2005 - 2099 Cenwor Inc.

 *

 * This is NOT a freeware, use is subject to license terms

 *

 * @Package JishiGou $

 *

 * @Filename constants.php $

 *

 * @Author http://www.jishigou.net $

 *

 * @Date 2011-09-28 20:52:17 1557767523 1806408855 2420 $

 *******************************************************************/





if(true === DEBUG)
{
	error_reporting(E_ERROR);
}
else 
{
	error_reporting(E_ERROR);
}

$config['constants'] = array();

//IN_JISHIGOU
define('IN_JISHIGOU',       true);

//软件的信息
define('SYS_VERSION',		'3.0.3');
define('SYS_PUBLISHED',     'stable');
define('SYS_BUILD',			'build 20120131');

//输出控制
define('GZIP',				(boolean) $config['gzip']);

//数据表前辍
define('TABLE_PREFIX',				$config['db_table_prefix']);

//时区设置
if(function_exists('date_default_timezone_set')) 
{
    $config['timezone'] = ((isset($config['timezone']) && is_numeric($config['timezone'])) ? $config['timezone'] : 8);
    
	@date_default_timezone_set('Etc/GMT'.($config['timezone'] > 0 ? '-' : '+').(abs($config['timezone'])));
}


//Ucenter 设置
if($config['ucenter_enable'])
{

	include(ROOT_PATH . './setting/ucenter.php');

	define('UCENTER' , 			($config['ucenter']['enable'] ? true : false));//标识Ucenter是否已经开启

	if (true === UCENTER)
    {

        define('UCENTER_FACE' , 	($config['ucenter']['face'] ? true : false));//标识Ucenter是否开启调用UC头像

		define('UC_CONNECT', 		$config['ucenter']['uc_connect']);	// 连接 UCenter 的方式: mysql/NULL, 默认为空时为 fscoketopen()

		//数据库相关 (mysql 连接时, 并且没有设置 UC_DBLINK 时, 需要配置以下变量)
		define('UC_DBHOST',		 	$config['ucenter']['uc_db_host']);			// UCenter 数据库主机
		define('UC_DBUSER', 		$config['ucenter']['uc_db_user']);				// UCenter 数据库用户名
		define('UC_DBPW', 			$config['ucenter']['uc_db_password']);					// UCenter 数据库密码
		define('UC_DBNAME', 		$config['ucenter']['uc_db_name']);				// UCenter 数据库名称
		define('UC_DBCHARSET',		str_replace('-','',$config['charset']));				// UCenter 数据库字符集
		define('UC_DBTABLEPRE', 	$config['ucenter']['uc_db_table_prefix']);			// UCenter 数据库表前缀

		//通信相关
		define('UC_KEY', 			$config['ucenter']['uc_key']);				// 与 UCenter 的通信密钥, 要与 UCenter 保持一致
		define('UC_API', 			$config['ucenter']['uc_api']);	// UCenter 的 URL 地址, 在调用头像时依赖此常量
		define('UC_CHARSET', 		$config['charset']);				// UCenter 的字符集
		define('UC_IP', 			$config['ucenter']['uc_ip']);					// UCenter 的 IP, 当 UC_CONNECT 为非 mysql 方式时, 并且当前应用服务器解析域名有问题时, 请设置此值
		define('UC_APPID', 			$config['ucenter']['uc_app_id']);					// 当前应用的 ID
	}
}

//全局时间戳
define('TIMESTAMP', time());

//插件开发者
define('PLUGINDEVELOPER', 1);
?>