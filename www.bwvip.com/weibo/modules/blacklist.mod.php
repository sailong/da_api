<?php

/**
 * 黑名单
 *
 * @author 狐狸<foxis@qq.com>
 * @package jishigou.net
 */
if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class ModuleObject extends MasterObject
{
	
	function ModuleObject($config)
	{
		$this->MasterObject($config);	
		
		Load::logic('topic');
		$this->TopicLogic = new TopicLogic($this);
		
		$this->Execute();
	}

	
		
	function Execute()
	{
		$load_file = array();
		switch ($this->Code) {
		  	case 'topic':
					$this->TopicSearch();
					break;
			case 'tag':
					$this->TagSearch();
					break;
			case 'user':
					$this->UserSearch();
					break;
			case 'usertag':
					$this->UserTagSearch();
					break;
			default:
			$this->Main();
			break;
		}
	}
	
	function Main()
	{ 
				$uid = MEMBER_ID;
		
		if($uid < 1){
			$this->Messager("请先登录",'index.php?mod=login');
		}
		
		$member = $this->_member($uid);	
		
		$sql = "select * from `".TABLE_PREFIX."blacklist` where `uid` = '".MEMBER_ID."' ";
		$query = $this->DatabaseHandler->Query($sql);
		$uids = array();
		while ($row = $query->GetRow()) 
		{
			$uids[$row['touid']] = $row['touid'];
		}
		
		if($uids)
		{
			$where = "where `uid` in (".implode(",",$uids).")";	
			
			$member_list = $this->_MemberList($where);
		
		
			if($uids && MEMBER_ID>0) {
				$sql = "select `buddyid` as `id` from `".TABLE_PREFIX."buddys` where `uid`='".MEMBER_ID."' and `buddyid` in(".implode(",",$uids).")";
				$query = $this->DatabaseHandler->Query($sql);
				$buddys = array();
				while ($row = $query->GetRow())
				{
					$buddys[$row['id']] = $row['id'];
				}
				
				foreach ($uids as $uid) {
					$follow_html = follow_html($uid,isset($buddys[$uid]));		
						
				}
				
			
								$sql = "select `uid`,`tid`,`content`,`dateline` from `".TABLE_PREFIX."topic` where `uid` in (".implode(",",$uids).") group by `uid` order by `dateline` desc";
				$query = $this->DatabaseHandler->Query($sql);
				$topic_list = array();
				while ($row = $query->GetRow())
				{
					$row['content'] 	= cut_str($row['content'],100);
					$row['dateline'] 	= my_date_format2($row['dateline']);
					$topic_list[] = $row;
				}
				
			}
		}
						$sql="Select `id`,`group_name`,`group_count` from ".TABLE_PREFIX.'group'." where `uid` = ".MEMBER_ID.""; 
		$query = $this->DatabaseHandler->Query($sql);
		$group_list = array();
		while ($row = $query->GetRow()) 
		{
			$group_list[] = $row;
		}
		
		include($this->TemplateHandler->Template('blacklist'));
	}

	
	function _MemberList($where='')
	{
		
	  	   		 $member_list = array();
		
		$sql = "select count(*) as `total_record` from `".TABLE_PREFIX."members` {$where}";
		$query = $this->DatabaseHandler->Query($sql);
		extract($query->GetRow());
		
		if($total_record > 0) 
		{
			$_config = array (
				'return' => 'array',
			);
			
			$page_arr = page($total_record,$per_page_num,$query_link,$_config);
			$sql = "select `uid`,`ucuid`,`username`,`nickname`,`face_url`,`face`,`fans_count`,`topic_count`,`follow_count`,`province`,`city`,`validate` from `".TABLE_PREFIX."members` {$where} {$order} {$page_arr['limit']}";
			$query = $this->DatabaseHandler->Query($sql);
			$uids = array();
			while ($row = $query->GetRow()) 
			{
				$row['face'] = face_get($row);
				$member_list[] = $row;
			}

		}
		
	return $member_list;
	
	}
	

		function _member($uid=0)
	{
		$member = array();
		if($uid < 1) {
			
			$mod_original = ($this->Post['mod_original'] ? $this->Post['mod_original'] : $this->Get['mod_original']);
			$mod_original = getSafeCode($mod_original);	
		
			$condition = "where `username`='{$mod_original}' limit 1";
			
			$members = $this->TopicLogic->GetMember($condition);
			
			if(is_array($members)) {
				reset($members);
				$member = current($members);
			}
		}		
		$uid = (int) ($uid ? $uid : MEMBER_ID);	
		if($uid > 0 && !$member) {
			$member = $this->TopicLogic->GetMember($uid);
		}
		if(!$member) {
			return false;
		}
		$uid = $member['uid'];
		
		if (!$member['follow_html'] && $uid!=MEMBER_ID) {
			$sql = "select * from `".TABLE_PREFIX."buddys` where `uid`='".MEMBER_ID."' and `buddyid`='{$uid}'";
			$query = $this->DatabaseHandler->Query($sql);
			$member['follow_html'] = follow_html($member['uid'],$query->GetNumRows()>0);
		}
		
		return $member;
	}
	
	
}
?>
