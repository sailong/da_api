<?php

error_reporting(E_ERROR);
@set_time_limit(600);
@ini_set("memory_limit","256M");


$this_file = basename(__FILE__);
 
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
	 
$username=$_GET['username'];
 

 $query = $db->query("select `uid` , `nickname` from {$db_prefix}members where username='$username' limit 0 ,1");	
	while (false != ($row = $db->fetch_array($query)))
{	
 $uid= $row['uid'];
 $url='/space-uid-'.$uid.'.html';
 //$url='/home.php?mod=space&uid='.$uid;
 ?>
 <script>location.href="<?php echo $url;?>"</script>
 <?php
}		
			
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