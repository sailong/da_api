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

 $tmod=$_GET['mod']=='getuinfo'?'getuinfo' : $_GET['mod'];
  
if($tmod=='getuinfo')
{
$uid=$_GET['uid'];
}
$config = array();
require('../setting/settings.php');

@header('Content-Type: text/html; charset=' . $config['charset']);

require('../setting/constants.php');
require_once('../include/function/global.func.php');


$db_charset = strtolower(str_replace('-','',$config['charset']));
if(!defined('JSG_DB_CHARSET')) define("JSG_DB_CHARSET",$db_charset);
$db_prefix = $config['db_table_prefix'];

  global $db;
    
	$db = new upgrade_dbstuff;
	$db->connect($config['db_host'], $config['db_user'],$config['db_pass'],$config['db_name']);
	 
$uid=$_GET['uid'];
 

 $query = $db->query("select `username` , `nickname` from {$db_prefix}members where uid='$uid' limit 0 ,1");	
	while (false != ($row = $db->fetch_array($query)))
{	
 $username= $row['username']; 
 $_GET['mod']=$username;
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
			
class upgrade_dbstuff {
	var $querynum = 0;
	var $link;
	function connect($dbhost, $dbuser, $dbpw, $dbname = '', $pconnect = 0, $halt = TRUE) {
		if($pconnect) {
			if(!$this->link = @mysql_pconnect($dbhost, $dbuser, $dbpw)) {
				$halt && $this->halt('Can not connect to MySQL server');
			}
		} else {
			if(!$this->link = @mysql_connect($dbhost, $dbuser, $dbpw, 1)) {
				$halt && $this->halt('Can not connect to MySQL server');
			}
		}

		if($this->version() > '4.1') {


			@mysql_query("SET character_set_connection=".JSG_DB_CHARSET.", character_set_results=".JSG_DB_CHARSET.", character_set_client=binary", $this->link);

			if($this->version() > '5.0.1') {
				@mysql_query("SET sql_mode=''", $this->link);
			}
		}

		if($dbname) {
			@mysql_select_db($dbname, $this->link);
		}

	}

	function select_db($dbname) {
		return mysql_select_db($dbname, $this->link);
	}

	function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return mysql_fetch_array($query, $result_type);
	}
    
    function fetch_first($sql,$type = '')
    {
        $query = $this->query($sql,$type);
        
        if($query)
        {
            return $this->fetch_array($query);
        }
        else
        {
            return false;
        }
    }

	function query($sql, $type = '') {
		global $debug, $discuz_starttime, $sqldebug, $sqlspenttimes;
		
		$func = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ?
			'mysql_unbuffered_query' : 'mysql_query';
		if(!($query = $func($sql, $this->link))) {
			if(in_array($this->errno(), array(2006, 2013)) && substr($type, 0, 5) != 'RETRY') {
				$this->close();
				$config = array();
				require('../setting/settings.php');
				$this->connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name'], $config['db_persist']);
				$this->query($sql, 'RETRY'.$type);
			} elseif($type != 'SILENT' && substr($type, 5) != 'SILENT') {
				$this->halt('MySQL Query Error', $sql);
			}
		}

		$this->querynum++;
		return $query;
	}

	function affected_rows() {
		return mysql_affected_rows($this->link);
	}

	function error() {
		return (($this->link) ? mysql_error($this->link) : mysql_error());
	}

	function errno() {
		return intval(($this->link) ? mysql_errno($this->link) : mysql_errno());
	}

	function num_rows($query) {
		$query = mysql_num_rows($query);
		return $query;
	}

	function insert_id() {
		return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}
	function version() {
		return mysql_get_server_info($this->link);
	}

	function close() {
		return mysql_close($this->link);
	}

	function halt($msg = '', $sql = '') {
		echo('<br>JishiGou Upgrade : <br>'.$msg."<br>".$sql.'<br><hr><br>');
	}
}		
?>