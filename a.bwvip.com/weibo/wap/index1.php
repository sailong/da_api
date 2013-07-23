<?php
/*******************************************************************
 * [JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename index.php $ 
 * 
 * @Author http://www.jishigou.net $
 *
 * @Date 2011-09-21 14:57:44 1138114312 194420748 2966 $
 *******************************************************************/



error_reporting(E_ERROR);
ini_set("arg_seperator.output", "&amp;");
ini_set("magic_quotes_runtime", 0);
header('Content-Type: text/html; charset=utf-8');




define('ROOT_PATH',substr(dirname(__FILE__),0,-4) . '/');
define('TEMPLATE_ROOT_PATH', ROOT_PATH . 'wap/');
define('RELATIVE_ROOT_PATH','../');
define('IN_JISHIGOU_WAP',true);

$mobile=$_GET['mod'];
$strmob=YYS($mobile);
if(YYS($mobile)){
	?>
    
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>大正网-提供专业高尔夫新闻，golf教学，球星，视频，赛事，球场预订服务，最纯净高尔夫实名认证互动社区_www.bwvip.com</title>
<meta name="keywords" content="大正网,大正高尔夫,高尔夫球星,高尔夫赛事,高尔夫比赛,高尔夫球场,高尔夫教学,golf教学，高尔夫新闻，高尔夫协会，中国高尔夫，城市挑战赛，golf高尔夫，golf"/>
<meta name="Description" content="专业高尔夫爱好者无广告纯净高尔夫主题互动社区，实名认证，远离广告骚扰。提供最优惠的高尔夫球场预订信息、golf球具订购服务,承办高尔夫比赛（皇冠杯城市挑战赛、英菲尼迪车主赛、电信天翼高尔夫活动等），提供最全面的球场资讯、高尔夫新闻、golf教学。大正高尔夫让您更好的享受高尔夫,享受生活。"/>
<link href="templates/default/css/style1.css" rel="stylesheet" type="text/css" />

</head>
<body>
<div style="width:500px; margin:0 auto;margin-top:300px; ">
<?php echo $mobile.'属于'.$strmob.'手机号码 大正网，专业高尔夫爱好者无广告纯净高尔夫主题互动社区，实名认证，远离广告骚扰。提供最优惠的高尔夫球场预订信息、golf球具订购服务,承办高尔夫比赛（皇冠杯城市挑战赛、英菲尼迪车主赛、电信天翼高尔夫活动等），提供最全面的球场资讯、高尔夫新闻、golf教学。大正高尔夫让您更好的享受高尔夫,享受生活。';

function randomkeys($length)
{
 $pattern='1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ专业高尔夫爱好者无广告纯净高尔夫主题互动社区';
 for($i=0;$i<$length;$i++)
 {
   $key .= $pattern{mt_rand(0,35)};    //生成php随机数
 }
 return $key;
}
 echo randomkeys(10);


?>
</div>
</body>
</html>

<?php 
exit;
}else
{
}


function YYS($mobile){
$cm=array(134,135,136,137,138,139,147,150,151,152,157,158,159,182,187,188); //移动的号码
$cu=array(130,131,132,155,156,185,186); //联通的号码
$cd=array(133,153,180,189); //电信的号码
$mobile=substr($mobile,0,3);

                   //移动：1069yd
                   //联通：1069lt
                   //电信：1069dx
if(in_array($mobile,$cm))
{return '移动'; //如果是移动
}
else if(in_array($mobile,$cu))
{ return '联通'; //如果是联通
}
else if(in_array($mobile,$cd)) 
{return '电信'; //如果是电信
}
else {return false;}
}

//die('系统升级中，请稍候……');
class initialize
{

	
	function init()
	{
		$config=array();

				require(ROOT_PATH . 'setting/settings.php');
		
				if($config['install_lock_time'] < 1) 
		{
			if (!is_file(ROOT_PATH . 'install/install.lock') && is_file(ROOT_PATH . 'install.php')) 
			{
				die("<a href='./install.php'>请点此进行系统的安装</a>");
			}
		}
		
				if ($config['upgrade_lock_time'] > 0) 
		{			
			if(($config['upgrade_lock_time'] + 600 > time()) || (is_file(ROOT_PATH . 'cache/upgrade.lock') && @filemtime(ROOT_PATH . 'cache/upgrade.lock')+600>time())) 
            {
				die('系统升级中，请稍候……');
			}
		}
		
				if ($config['site_closed']) 
		{
			if ('login'!=$_GET['mod'] && $site_enable_msg=file_get_contents('./cache/site_enable.php')) 
			{
				die($site_enable_msg);
			}
		}
		
		if(!$config['wap'])
		{
			include(ROOT_PATH . 'wap/include/error_wap.php');
		}
		
		require ROOT_PATH . 'setting/constants.php';
		
				if($config['robot_enable']) 
		{
			include(ROOT_PATH . 'setting/robot.php');
		}
		
				if ($config['extcredits_enable']) 
		{
			include(ROOT_PATH . 'setting/credits.php');
		}
		
		require_once ROOT_PATH . 'include/function/global.func.php';
		
		require_once ROOT_PATH . 'wap/include/function/wap_global.func.php'; 		
		
		require_once ROOT_PATH . 'wap/modules/master.mod.php';		
		require_once ROOT_PATH . 'wap/modules/' . $this->SetEvent($config['default_module']).'.mod.php';
		if($_GET) 
		{
			$_GET		= jaddslashes($_GET, 1, TRUE);
		}
		if($_POST) 
		{
			$_POST		= jaddslashes($_POST, 1, TRUE);
		}
		$moduleobject=new ModuleObject($config);
		
	}



	function SetEvent($default='topic')
	{
		$modss = array('topic'=>1,'login'=>1,'member'=>1,'tag'=>1,'pm'=>1,);
		
		$mod = (isset($_POST['mod']) ? $_POST['mod'] : $_GET['mod']);
		
				if(!isset($modss[$mod])) 
		{
			if($mod)
			{
				$_POST['mod_original'] = $_GET['mod_original'] = $mod;
			}
			
			$mod = ($default ? $default : 'index');
		}
		
		$_POST['mod'] = $_GET['mod'] = $mod;	
		
		Return $mod;
	}
}
$init=new initialize;
$init->init();

?>