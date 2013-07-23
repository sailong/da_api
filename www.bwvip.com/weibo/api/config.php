<?php
/**
 * jishigou api 配置
 * 
 * @author 狐狸<foxis@qq.com>
 * @version $Id: config.php 1049 2012-06-26 06:41:41Z wuliyong $
 */

//记事狗API授权类型，允许 jauth1（最原始版本，不再建议使用） jauth2（jauth1基础上的改良版本） oauth2（通用版本，建议使用）
define('JISHIGOU_API_AUTH_TYPE',	'oauth1');
//记事狗微博服务端的API地址
//define('JISHIGOU_API_SITE_URL',		'http://www.bwvip.com/t2/api.php');
define('JISHIGOU_API_SITE_URL',		'http://www.bwvip.com/t2/api.php');
//记事狗微博分配的APP KEY
define('JISHIGOU_API_APP_KEY',		'4079520736');
//记事狗微博分配的APP SECRET
define('JISHIGOU_API_APP_SECRET',	'0e031730a98fb8ae6e63417bd508bbb3');
//记事狗微博上的用户昵称
//需要将用户昵称的编码格式转换为UTF-8
//define('JISHIGOU_API_USERNAME',		'api');
define('JISHIGOU_API_USERNAME',		'刘熙');
//define('JISHIGOU_API_USERNAME',		'徐玉枭');
//记事狗微博上加密后的密码 
//需要将密码中的用户昵称和密码转换成网站编码一致的字符再进行md5
//define('JISHIGOU_API_PASSWORD',	md5('大正管理员'.md5('dz@bwvip')));
//define('JISHIGOU_API_PASSWORD',	md5('api'.md5('123456')));
//define('JISHIGOU_API_PASSWORD',	md5('api'.md5(iconv('utf-8','gbk','123456'))));
define('JISHIGOU_API_PASSWORD',	md5('刘熙'.md5(iconv('utf-8','gbk','pangpang'))));
//define('JISHIGOU_API_PASSWORD',	md5('徐玉枭'.md5(iconv('utf-8','gbk','123456'))));
//define('JISHIGOU_API_PASSWORD',		md5('dz@bwvip'.md5(iconv('utf-8','gbk','dz@bwvip'))) );

//调试开启，输出请求信息？
define('JISHIGOU_API_DEBUG', 		false);

//oauth2 会使用到的 access_token 值 ，暂存在 _COOKIE 中，实际使用时可以存储在数据库里
//define('JISHIGOU_API_OAUTH2_ACCESS_TOKEN', $_COOKIE['JISHIGOU_API_OAUTH2_ACCESS_TOKEN']);

?>