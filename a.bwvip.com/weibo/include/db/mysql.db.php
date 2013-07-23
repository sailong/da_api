<?php
/**
 * 文件名：mysql.db.php
 * 版本号：1.0
 * 最后修改时间：2006年5月3日 13:55:01
 * 作者：狐狸<foxis@qq.com>
 * 功能描述：MYSQL处理,连接MYSQL,取得,修改,添加,删除数据
 */
if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class MySqlHandler extends DatabaseHandler
{
	var $TableName; 	var $FieldList; 
	
	var $Charset='gbk';

	
	function MySqlHandler($server_host, $server_port = '3306')
	{
		$this->DatabaseHandler($server_host, $server_port);
	}
		
	function DoConnect($username, $password, $database, $persist = true,$setkey=1)
	{
		$host = $this->ServerHost . ':' . $this->ServerPort;

		if($persist)
		{
			@$db=mysql_pconnect($host, $username, $password,true);
		}
		else
		{
			@$db=mysql_connect($host, $username, $password,true);
		}
		$db==false?exit(mysql_errno().":".mysql_error()):$this->setConnectionId($db);

		if($this->GetVersion() > '4.1')
		{
			if($this->Charset)
			{
				@mysql_query("SET character_set_connection={$this->Charset},
							 character_set_results={$this->Charset},
							 character_set_client=binary",$db);
			}

			if($this->GetVersion() > '5.0.1')mysql_query("SET sql_mode=''",$db);
		}

		if(false == mysql_select_db($database, $this->GetConnectionId()))
		{
			$this->setConnectionId(0);
		}
	}
	
	function Charset($charset)
	{
		$this->Charset = ((false!==strpos($charset,'-')) ? str_replace("-", "", strtolower($charset)) : $charset);
	}
	

	function Query($sql,$type='')
	{
		$this->CheckQuery($sql);
		
		$func=$type==='UNBUFFERED'?'mysql_unbuffered_query':'mysql_query';
		$result = $func($sql, $this->GetConnectionId());
		if($result==false)
		{
			if(in_array($this->GetLastErrorNo(), array(2006, 2013)) && substr($type, 0, 5) != 'RETRY') {
				$this->CloseConnection();

				include ROOT_PATH . 'setting/settings.php';
				$this->MySqlHandler($config['db_host'],$config['db_port']);
				$this->Charset($config['charset']);
				$this->DoConnect($config['db_user'],$config['db_pass'],$config['db_name'],$config['db_persist'],0);

				$result = $this->Query($sql, 'RETRY'.$type);
			} elseif (in_array($this->GetLastErrorNo(), array(1040)) && substr($type,0,4) != "WAIT" && substr($type,0,5) < "WAIT3") {
				usleep(100000 * max(1,min(6,2 * ((int) substr($type,4,1) + 1))));

				$result = $this->Query($sql, 'WAIT'.++$WAITTIMES.$type);
			} elseif ($type != 'SKIP_ERROR' && 'SILENT' != $type && substr($type, 5) != 'SKIP_ERROR') {
				//die('MySql query error, Please contact webmaster.');
			} else {
				return false;
			}
		}

		

		return new MySqlIterator($result);
	}
	function CheckQuery($sql) {		
		static $status = null, $checkcmd = array('SELECT', 'UPDATE', 'INSERT', 'REPLACE', 'DELETE'), $static_query_safes = array();		
		
		if($status === null) $status = QUERY_SAFE;
		if($status) {
			$cmd = trim(strtoupper(substr($sql, 0, strpos($sql, ' '))));
			if(in_array($cmd, $checkcmd)) {				
				$cache_id = md5($sql);
				if(false==($test = $static_query_safes[$cache_id])) {				
					$test = $this->_do_query_safe($sql);
					
					$static_query_safes[$cache_id] = $test;
				}
				if($test < 1) exit; //DB::_execute('halt', 'security_error', $sql);
			}
		}
		return true;
	}

	/**
	 * 检查sql是否安全
	 */
	function _do_query_safe($sql) {
		static $_CONFIG = null;
		
		if($_CONFIG === null) {
			$_config = array();
			$_config['security']['querysafe']['status'] = 1;
			$_config['security']['querysafe']['dfunction']['0'] = 'load_file';
			$_config['security']['querysafe']['dfunction']['1'] = 'hex';
			$_config['security']['querysafe']['dfunction']['2'] = 'substring';
			$_config['security']['querysafe']['dfunction']['4'] = 'ord';
			$_config['security']['querysafe']['dfunction']['5'] = 'char';
			$_config['security']['querysafe']['daction']['0'] = 'intooutfile';
			$_config['security']['querysafe']['daction']['1'] = 'intodumpfile';
			$_config['security']['querysafe']['daction']['2'] = 'unionselect';
			$_config['security']['querysafe']['daction']['4'] = 'unionall';
			$_config['security']['querysafe']['daction']['5'] = 'uniondistinct';
			$_config['security']['querysafe']['dnote']['0'] = '/'.'*';
			$_config['security']['querysafe']['dnote']['1'] = '*/';
			$_config['security']['querysafe']['dnote']['2'] = '#';
			$_config['security']['querysafe']['dnote']['3'] = '--';
			$_config['security']['querysafe']['dlikehex'] = 1;
			$_config['security']['querysafe']['afullnote'] = 1;
			$_CONFIG = $_config['security']['querysafe'];
		}


		$sql = str_replace(array('\\\\', '\\\'', '\\"', '\'\''), '', $sql);
		$mark = $clean = '';
		if(strpos($sql, '/') === false && strpos($sql, '#') === false && strpos($sql, '-- ') === false) {
			$clean = preg_replace("/'(.+?)'/s", '', $sql);
		} else {
			$len = strlen($sql);
			$mark = $clean = '';
			for ($i = 0; $i <$len; $i++) {
				$str = $sql[$i];
				switch ($str) {
					case '\'':
						if(!$mark) {
							$mark = '\'';
							$clean .= $str;
						} elseif ($mark == '\'') {
							$mark = '';
						}
						break;
					case '/':
						if(empty($mark) && $sql[$i+1] == '*') {
							$mark = '/'.'*';
							$clean .= $mark;
							$i++;
						} elseif($mark == '/'.'*' && $sql[$i -1] == '*') {
							$mark = '';
							$clean .= '*';
						}
						break;
					case '#':
						if(empty($mark)) {
							$mark = $str;
							$clean .= $str;
						}
						break;
					case "\n":
						if($mark == '#' || $mark == '--') {
							$mark = '';
						}
						break;
					case '-':
						if(empty($mark)&& substr($sql, $i, 3) == '-- ') {
							$mark = '-- ';
							$clean .= $mark;
						}
						break;

					default:

						break;
				}
				$clean .= $mark ? '' : $str;
			}
		}

		$clean = preg_replace("/[^a-z0-9_\-\(\)#\*\/\"]+/is", "", strtolower($clean));

		if($_CONFIG['afullnote']) {
			$clean = str_replace('/'.'*'.'*/','',$clean);
		}

		if(is_array($_CONFIG['dfunction'])) {
			foreach($_CONFIG['dfunction'] as $fun) {
				if(strpos($clean, $fun.'(') !== false) return '-1';
			}
		}

		if(is_array($_CONFIG['daction'])) {
			foreach($_CONFIG['daction'] as $action) {
				if(strpos($clean,$action) !== false) return '-3';
			}
		}

		if($_CONFIG['dlikehex'] && strpos($clean, 'like0x')) {
			return '-2';
		}

		if(is_array($_CONFIG['dnote'])) {
			foreach($_CONFIG['dnote'] as $note) {
				if(strpos($clean,$note) !== false) {
					return '-4';
				}
			}
		}

		return 1;

	}
	
    function fetch_first($sql)
    {
        return $this->FetchFirst($sql);
    }
    function FetchFirst($sql)
    {
        $result = array();

        $query = $this->Query($sql);

        if($query)
        {
            $result = $query->GetRow();
        }

        return $result;
    }
    function ResultFirst($sql)
    {
        $result = '';
        
        $query = $this->Query($sql);
        
        if($query)
        {
            $result = $query->result(0);
        }
        
        return $result;
    }
	
	function SetTable($tableName,$skip_error=false)
	{
		$this->TableName = $tableName;
		if(isset($this->Table[$tableName]))
		{
			$this->FieldList = $this->Table[$tableName];
		}
		else
		{
			if (($fieldList=cache("table/{$tableName}",-1))===false)
			{
				$sql = "SHOW \n\tCOLUMNS \nFROM \n\t`{$this->TableName}`";
				$query = $this->Query($sql,$skip_error?"SKIP_ERROR":"");
				if($query==false)return false;
				$fieldList = array();
				while($row = $query->GetRow())
				{
					if($row['Extra'] === "auto_increment")
					{
						$fieldList[$row['Key']] = $row['Field'];
					}
					else
					{
						$fieldList[] = $row['Field'];
					}
				}
				cache($fieldList);
			}
			$this->FieldList = $fieldList;
			$this->Table[$tableName] = $fieldList;
		}
		Return $this->FieldList;
	}
								function Select($id = '', $condition = NULL, $fields = "*")
	{
		if($condition === NULL)
		{
			if($ids = $this->BuildIn($id))
			{
				$where = "\r\nWHERE \n\t" . $ids;
			}
			else
			{
				Return false;
			}
		}
		else
		{
			if(trim($condition) != "")
			{
				$where = "\r\nWHERE \n\t" . $condition;
			}
		}

		$fieldNames = "\n\t*";
		$field_num=0;
		if($fields != "*")
		{
			$fieldNames = "\n\t".$this->FieldList['PRI'];
			if(is_string($fields) != false)
			{
				$field_list = explode(',', $fields);
			}elseif(is_array($fields) != false)
			{
				$field_list = array_filter($fields, 'strlen');
			}
			$valid_field_list=array();
			foreach($field_list as $key => $field)
			{
				if(in_array($field, $this->FieldList))
				{
					$fieldNames .= ",\n\t`" . $field . '`';
					$valid_field_list[]=$field;
				}
			}
			$field_num=count($valid_field_list);
			$fieldNames = ($field_num>=1)?ltrim($fieldNames, ",\n\t"):"\n\t*";
		}

		$sql = "SELECT {$fieldNames} \nFROM \n\t`{$this->TableName}` {$where}";
		$query = $this->query($sql);
		$data_list = array();
		if($field_num==1)$field_name=implode('',$valid_field_list);
		if($query->GetNumRows() > 1)
		{
			while($row = $query->GetRow())
			{
				$data_list[$row[$this->FieldList['PRI']]] =($field_num==1)?$row[$field_name]:$row;
			}
		}
		else
		{
			$row = $query->GetRow();
			$data_list =($field_num==1)?$row[$field_name]:$row;
		}
		Return $data_list;
	}
							function Replace($dataList)
	{
		if($dataList == "")Return false;
		foreach($this->FieldList as $key => $field)
		{
			if(isset($dataList[$field]))
			{
				$fieldNames .= ",\n\t`" . $field . '`';
				$fieldValues .= ",\n\t\"" . $dataList[$field] . "\"";
			}
		}
		$sql = sprintf("REPLACE INTO \n\t`%s`(%s) \nVALUES(%s)", $this->TableName, ltrim($fieldNames, ','), ltrim($fieldValues, ','));
		$this->query($sql);

		return $this->Insert_ID();
	}
							function Insert($dataList,$continue_primary_key=true)
	{
		if(($sql=$this->BuildInsert($this->TableName,$dataList,$continue_primary_key,true))=="")return false;
		$this->query($sql);
		return $this->Insert_ID();
	}
									function Update($dataList, $condition = NULL)
	{
		if(($sql=$this->BuildUpdate($this->TableName,$dataList,$condition,true))=="")return false;
		if ($this->query($sql))
		{
			return $this->AffectedRows();
		}
		else
		{
			return false;
		}
	}
							function Delete($id = "", $condition = NULL)
	{
		if($condition === NULL)
		{
			if($ids = $this->BuildIn($id))
			{
				$where = "WHERE " . $ids;
			}
			else
			{
				Return false;
			}
		}
		else
		{
			if(trim($condition) != "")
			{
				$where = "\r\nWHERE \n\t" . $condition;
			}
		}

		$sql = "DELETE FROM `{$this->TableName}` {$where}";
		if ($this->query($sql))
		{
			return $this->AffectedRows();
		}
		else
		{
			return false;
		}
	}

	function BuildField($mixed)
	{
		if($mixed==false or trim($mixed)=="*")Return "*";
		$type=gettype($mixed);
		if($type=="string" or $type=="integer" or $type=="double")
		{
			$mixed=trim($mixed,',');
			$mixed=strpos($mixed,',')!==false?"'".str_replace(',',"`,`",$mixed)."'":"`$mixed`";
		}
		elseif($type=="array")
		{
			$mixed="`".implode("`,`",$mixed)."`";
		}
		Return $mixed;
	}
	
	function BuildInsert($tableName,$dataList,$continue_primary_key=true,$filterValid=false)
	{
		if(is_array($dataList) == false)Return '';
		if($filterValid===true)
		{
			$this->SetTable($tableName);
			foreach($this->FieldList as $key => $field)
			{
				if(strcmp($key, "PRI") === 0 and $continue_primary_key===true)
				{
					continue;
				}
				if(isset($dataList[$field]))
				{
					$fieldNames .= ",\n\t`" . $field . '`';
					$fieldValues .= ",\n\t\"" . $dataList[$field] . "\"";
				}
			}
			if ($fieldNames=='' or $fieldValues=='')return '';
		}
		else
		{
			foreach($dataList as $field=>$value)
			{
				$fieldNames .= ",\n\t`" . $field . '`';
				$fieldValues .= ",\n\t\"" .$value. "\"";
			}
			$this->TableName=$tableName;
		}
		$sql = sprintf("INSERT INTO \n\t`%s`(%s) \nVALUES(%s)", $tableName, ltrim($fieldNames, ','), ltrim($fieldValues, ','));
		return $sql;
	}

	function BuildUpdate($tableName,$dataList,$condition=null,$filterValid=false)
	{
		if(is_array($dataList) == false)Return '';
		if($filterValid===true)
		{
			$this->SetTable($tableName);
			foreach($this->FieldList as $key => $field)
			{
				if(isset($dataList[$field]))
				{
					if($key === "PRI")
					{
						if($ids = $this->BuildIn($dataList[$field]))$where = "WHERE \n\t" . $ids;
					}
					else
					{
						$value=$dataList[$field];
						$fieldUpdate .=strpos($value, 'eval:') === 0?"\n\t`{$field}`=".substr($value, 5).",":"\n\t`{$field}`='{$value}',";
					}
				}
			}
		}
		else
		{
			$this->TableName=$tableName;
			foreach ($dataList as $field=>$value)
			{
				$fieldUpdate .= strpos($value, 'eval:') === 0?"\n\t`{$field}`=".substr($value, 5).",":"\n\t`{$field}`='{$value}',";
			}
		}
		if($fieldUpdate == '')Return '';
		if($condition !== NULL)
		{
			$where = (trim($condition) != "")?"WHERE \n\t" . $condition:"";
		}
		elseif($filterValid==true)
		{
			if($dataList[$this->FieldList['PRI']] == "")Return '';
		}
		$sql = sprintf("UPDATE \n\t`%s` \t\nSET %s \n%s", $this->TableName, rtrim($fieldUpdate, ','), $where);
		return $sql;
	}
	function BuildIn($mixed,$name=null)
	{
		if($name === NULL)$name = $this->FieldList['PRI'];
		$type=gettype($mixed);
		if($type=="string" or $type=="integer" or $type=="double")
		{
			$mixed=trim($mixed,',');
			$mixed=strpos($mixed,',')!==false
					?"'".str_replace(',',"','",$mixed)."'"
					:"'$mixed'";
		}
		elseif($type=="array")
		{
			$mixed=!empty($mixed)?"'".implode("','",array_unique($mixed))."'":'null';
		}

		Return $name!=null?"$name IN ($mixed)":$mixed;
	}

	
	function GetVersion()
	{
		return mysql_get_server_info($this->GetConnectionId());
	}

	
	function GetLastError($sql, $file, $line)
	{
		$error = mysql_error($this->GetConnectionId());

		return $error . $sql;
	}
	function GetLastErrorString()
	{
		return mysql_error($this->GetConnectionId());
	}
	function GetLastErrorNo()
	{
		return mysql_errno($this->GetConnectionId());
	}

	
	function Insert_ID()
	{
		return mysql_insert_id($this->GetConnectionId());
	}

	function LastInsertId()
	{
		$this->Insert_ID();
	}

	
	function AffectedRows()
	{
		Return mysql_affected_rows($this->GetConnectionId());
	}

	
	function CloseConnection()
	{
		return mysql_close($this->GetConnectionId());
	}
}



class MySqlIterator
{
	
	var $_resource_id;

	
	var $_current_row;

	
	var $_total_rows;

	
	function MySqlIterator($resource_id)
	{
		$this->_resource_id = $resource_id;
		$this->_total_rows = 0;
		$this->_current_row = 0;
	}

	
	function GetNumRows()
	{
		$this->_total_rows = mysql_num_rows($this->GetResourceId());

		return $this->_total_rows;
	}

	function GetNumFields()
	{
		return mysql_num_fields($this->GetResourceId());
	}

	
	function GetResourceId()
	{
		return $this->_resource_id;
	}

	
	function GetCurrentRow()
	{
		return $this->_current_row;
	}

	
	function isSuccess()
	{
		return $this->GetResourceId() ? true : false;
	}

	
	function _freeResult()
	{
		mysql_free_result($this->GetResourceId());

		return;
	}

	
	function GetRow($result_type = 'assoc')
	{
		$this->_current_row++;

		switch($result_type)
		{
			case 'row':
				return mysql_fetch_row($this->GetResourceId());
				break;

			case 'assoc':
				return mysql_fetch_assoc($this->GetResourceId());
				break;

			case 'both':
				return mysql_fetch_array($this->GetResourceId());
				break;
			case 'object':
				return mysql_fetch_object($this->GetResourceId());
				break;
		}
	}
	function result($row)
	{
		return @mysql_result($this->GetResourceId(),$row);
	}

	
	function GetAll($result_type = 'assoc')
	{
		$list = array();
		while($row = $this->GetRow($result_type))
		{
			$list[] = $row;
		}
		Return $list;
	}
}

?>