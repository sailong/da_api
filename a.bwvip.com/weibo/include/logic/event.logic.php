<?php
/*******************************************************************
 * [JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename event.logic.php $
 *
 * @Author http://www.jishigou.net $
 *
 * @Date 2011-09-09 10:58:30 1853841005 184204933 653 $
 *******************************************************************/




if(!defined('IN_JISHIGOU')) {
    exit('invalid request');
}

class EventLogic
{
	function EventLogic()
	{
	}
	
	
	function is_exists($id)
	{
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('event')." WHERE id='{$id}'");
		return $count;
	}
	
	
	function get_event_info($id)
	{
		$event_info = DB::fetch_first("SELECT * FROM ".DB::table('event')." WHERE id='{$id}'");
		return $event_info;
	}
}