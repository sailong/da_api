<?php
/*********************************************************
 *文件名： longtext.logic.php
 *作  者： 狐狸<foxis@qq.com>
 *创建时间： 2010年5月30日
 *修改时间：
 *功能描述：长文本逻辑操作
 *使用方法：

 ******************************************************/
if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

/**
 * 
 * 长文本的数据库逻辑操作类
 * 
 * @author 狐狸<foxis@qq.com>
 *
 */
class LongtextLogic
{
	var $table;
	
	function LongtextLogic()
	{
		$this->table = 'topic_longtext';	
	}
	
	function get_info($id)
	{
		$id = is_numeric($id) ? $id : 0;
		if($id < 1) return array();
		
		$ret = DB::fetch_first("select * from ".DB::table($this->table)." where `id`='$id'");
		return $ret;
	}
	
	function add($longtext)
	{
		$longtext = trim($longtext);
		
		$arr = array(
			'longtext' => $longtext,
			'uid' => MEMBER_ID,
			'username' => MEMBER_NAME,
			'dateline' => time(),
			'tid' => 0,
			'views' => 0,
		);
		$ret = DB::insert($this->table, $arr, 1);
		
		return $ret;
	}
	
	function set_tid($id, $tid)
	{
		$id = is_numeric($id) ? $id : 0;
		if($id < 1) return 0;
		
		$tid = is_numeric($tid) ? $tid : 0;
		
		return DB::query("update ".DB::table('topic_longtext')." set `tid`='$tid' where `id`='$id'");
	}
}

?>