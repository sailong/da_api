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
 * @Date 2011-09-21 14:57:40 1269302148 1137239728 4261 $
 *******************************************************************/


error_reporting(E_ERROR);
ini_set("arg_seperator.output", "&amp;");
ini_set("magic_quotes_runtime", 0);

$time_start = microtime_float();

define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());

define('ROOT_PATH',dirname(__FILE__) . '/');
define('RELATIVE_ROOT_PATH','./');
 

class initialize
{

	
	function init()
	{
		$config=array();

				require(ROOT_PATH . 'setting/settings.php');
        		@header('Content-Type: text/html; charset=' . $config['charset']);
                @header('P3P: CP="CAO PSA OUR"'); 
		 
		
				require(ROOT_PATH . 'setting/constants.php');
		
			 
				require_once(ROOT_PATH . 'include/function/global.func.php');
		
				require_once(ROOT_PATH . 'modules/master.mod.php');
 

		require_once(ROOT_PATH . 'modules/topicweibo.mod.php');
		//require_once(ROOT_PATH . 'modules/topic.mod.php');
 
		$moduleobject = new ModuleObject($config);
		 
	}

	 
}
 
$init=new initialize;
$init->init();
unset($init);
ob_end_flush();

 
function microtime_float()
{
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}

?>