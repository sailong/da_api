<?php
/*******************************************************************
 * [JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename ajax.php $
 *
 * @Author http://www.jishigou.net $
 *
 * @Date 2011-09-09 10:58:25 337512792 1967955036 2654 $
 *******************************************************************/


error_reporting(E_ERROR);

ini_set("arg_seperator.output", "&amp;");
ini_set("magic_quotes_runtime", 0);


define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());



define('ROOT_PATH',dirname(__FILE__) . '/');

define('RELATIVE_ROOT_PATH','./');
define('IN_JISHIGOU_AJAX',true);

class initialize
{

	
	function init()
	{
		$config=array();

				require ROOT_PATH . 'setting/settings.php';		
		@header('Content-Type: text/html; charset=' . $config['charset']);

				require ROOT_PATH . 'setting/constants.php';
        
        		if ($config['rewrite_enable']) 
		{
			include(ROOT_PATH . 'include/rewrite.php');
		}
		
        		if ($config['extcredits_enable']) 
		{
			include(ROOT_PATH . 'setting/credits.php');
		}
		
				require_once ROOT_PATH . 'include/function/global.func.php';		

				require_once ROOT_PATH . 'modules/ajax/master.mod.php';
		
				require_once ROOT_PATH . 'modules/ajax/' . $this->SetEvent($config['default_module']) . '.mod.php';
		
		@header("Cache-Control: no-cache, must-revalidate"); 		@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
		if($_GET) 
		{
			$_GET		= array_iconv('utf-8',$config['charset'],$_GET);
			$_GET		= jaddslashes($_GET, 1, TRUE);
		}
		if($_POST) 
		{
			$_POST		= array_iconv('utf-8',$config['charset'],$_POST);
			$_POST		= jaddslashes($_POST, 1, TRUE);
		}
	
		$moduleobject = new ModuleObject($config);
		
	}

	
	function SetEvent($default='topic')
	{
		$modss = array(
			'topic'=>1,
			'pm'=>1,
			'member'=>1,
			'sms'=>1,
			'schedule'=>1,
			'face'=>1,
			'reminded'=>1,
			'test'=>1,
			'vote'=>1,
			'app' => 1,
			'qun' => 1,
			'wall' => 1,
			'misc' => 1,
			'longtext' => 1,
			'uploadify' => 1,
			'login' => 1,
			'view' => 1,
			'user' => 1,
			'class' => 1,
			'fenlei' => 1,
			'event' => 1,
		);
		
		$mod = (isset($_POST['mod']) ? $_POST['mod'] : $_GET['mod']);
		
				if(!isset($modss[$mod])) 
		{
			if ($mod)
			{
				$_POST['mod_original'] = $_GET['mod_original'] = $mod;
			}
			
			$mod = ($default ? $default : 'topic');
		}
		
		$_POST['mod'] = $_GET['mod'] = $mod;	
		
		Return $mod;
	}
}
$init=new initialize;
$init->init();


?>