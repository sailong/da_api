<?php
/*******************************************************************
 * [JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename admin.php $ 
 * 
 * @Author http://www.jishigou.net $
 *
 * @Date 2011-09-21 14:57:39 578578080 724366457 2765 $
 *******************************************************************/


error_reporting(E_ERROR);

ini_set("arg_seperator.output", "&amp;");
ini_set("magic_quotes_runtime", 0);



define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());




define('ROOT_PATH',dirname(__FILE__) . '/');
define('RELATIVE_ROOT_PATH','./');
define('IN_JISHIGOU_ADMIN',true);

define('PLUGIN_DIR',ROOT_PATH . 'plugin');

class initialize
{

	
	function init()
	{
		$config=array();

				require ROOT_PATH . 'setting/settings.php';
		@header('Content-Type: text/html; charset=' . $config['charset']);
		
				require ROOT_PATH . 'setting/constants.php';
		
				if ($config['extcredits_enable']) 
		{
			include(ROOT_PATH . 'setting/credits.php');
		}
		
				require_once ROOT_PATH . 'include/function/global.func.php';

				require_once ROOT_PATH . 'include/function/admincp.func.php';

				require_once ROOT_PATH . 'modules/admin/master.mod.php';
				require_once ROOT_PATH . 'modules/admin/' . $this->SetEvent() . '.mod.php';		
		if($_GET) 
		{
			$_GET		= jaddslashes($_GET, 1, TRUE);
		}
		if($_POST) 
		{
			$_POST		= jaddslashes($_POST, 1, TRUE);
		}
		$moduleobject = new ModuleObject($config);
		
	}

	
	function SetEvent()
	{
		$modss = array ( 
			'cache' => 1, 
			'db' => 1, 
			'imjiqiren' => 1, 
			'income' => 1, 
			'index' => 1, 
			'link' => 1, 
			'login' => 1, 
			'medal' => 1, 
			'media' => 1, 
			'member' => 1, 
			'notice' => 1, 
			'pm' => 1, 
			'report' => 1, 
			'rewrite' => 1, 
			'robot' => 1, 
			'role' => 1, 
			'role_action' => 1, 
			'role_module' => 1, 
			'sessions' => 1, 
			'setting' => 1, 
			'share' => 1, 
			'show' => 1, 
			'sms' => 1, 
			'tag' => 1, 
			'topic' => 1, 
			'ucenter' => 1, 
			'upgrade' => 1, 
			'web_info' => 1, 
			'task' => 1, 
			'user_tag' => 1, 
			'api' => 1,
			'vote' => 1,
			'vipintro'=>1,
			'qun' => 1,
			'recdtopic' => 1,
			'plugin' => 1,
			'plugindesign' => 1,
			'class'=>1,
			'module' => 1,
			'city' =>1,
			'fenlei' => 1,
		    'event' => 1,
			'account' => 1,
		);
		
		$mod = (isset($_POST['mod']) ? $_POST['mod'] : $_GET['mod']);
		
		if(!$mod) 
		{
			$mod = "index";
		}
		
		if(!isset($modss[$mod]))
		{
			include(ROOT_PATH . 'include/error_404.php');
			exit;
		}
		
		$_POST['mod'] = $_GET['mod'] = $mod;
		
		return $mod;
	}
}
$init=new initialize;
$init->init();

?>