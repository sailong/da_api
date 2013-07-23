<?php if (!defined('IS_IN_XWB_PLUGIN')) {die('access deny!');}?><?php if('UTF-8' != strtoupper($GLOBALS['jsg_sys_config']['charset'])){@header("Content-type: text/html; charset=utf-8");}?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>注册页面</title>
<link type="text/css" rel="stylesheet" href="<?php echo XWB_plugin::getPluginUrl('images/xwb_'. XWB_S_VERSION .'.css');?>" />
<style>


	
	
</style>
</head>

<body>

<div class="bind-setting xwb-plugin">
	<p class="alert-tips">你还没有绑定新浪微博！</p>
    <div class="bing-text">
    	<h4>绑定后，你将获得以下特权：</h4>
        <p>可以使用新浪微博帐号登录 <?php echo XWB_S_TITLE;?>，不用担心忘记密码</p>
        <p>在 <?php echo XWB_S_TITLE;?> 发微博同步发到新浪微博上，吸引更多人关注</p>
        <!-- <p>可使用新浪微博签名</p> -->
    </div>
    <a class="bind-btn" href="<?php echo XWB_plugin::URL('xwbAuth.login');?>" class="mibLoginBtn" target="_top"></a>
    <p class="txtb">还没有新浪微博帐号？<a href="http://t.sina.com.cn/reg.php" class="cp_more" target="_blank">30秒完成免费注册</a></p>
</div>

</body>
</html>
