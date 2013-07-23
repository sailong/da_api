<?php

/**
 * 个人标签
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

		$this->Member = $this->_member();

		$this->Execute();
	}


	
	function Execute()
	{
		ob_start();
		$load_file = array();
		switch ($this->Code) {
		case 'seach':
				$this->Seach();
				break;
		default:
			$this->Main();
			break;
		}
		$body=ob_get_clean();

		$this->ShowBody($body);
	}

	function Main()
	{

		$act_list = array('base'=>'我的资料','face'=>'我的头像','secret'=>'修改密码','user_medal'=>'我的勋章','exp'=>'微博等级','user_tag'=>array('name'=>'我的标签','link_mod'=>'user_tag',),);
		if ($this->Config['extcredits_enable'])
		{
			$act_list['extcredits'] = '我的积分';
		}
		$act = isset($act_list[$this->Code]) ? $this->Code : 'user_tag';

		$this->Title = "我的标签";

		extract($this->Get);
 		extract($this->Post);
		$uids = (int) MEMBER_ID;
		$member = $this->TopicLogic->GetMember($uids);

define('QUERY_SAFE_DACTION_3', true);
						$sql = "Select * From `".TABLE_PREFIX."user_tag` Where id >= (Select floor(RAND() * (Select MAX(id) From `".TABLE_PREFIX."user_tag`)))  Order By id Limit 20;";
		$query = $this->DatabaseHandler->Query($sql);
		$user_tag = array();
		while($row = $query->GetRow())
		{
			$user_tag[] = $row;
		}

				$sql = "select * from `".TABLE_PREFIX."user_tag_fields` where `uid` = '".MEMBER_ID."'";
		$query = $this->DatabaseHandler->Query($sql);
		$user_tag_fields = array();
		while($row = $query->GetRow())
		{
			$user_tag_fields[] = $row;
		}

		include($this->TemplateHandler->Template('topic_user_tag'));

	}

		function Seach()
	{

		$per_page_num = 6;
		$query_link = 'index.php?mod=user_tag&code=seach';
		$order = " order by `fans_count` desc ";

 		$tag_name = trim($this->Get['k']);
 		$tag_name = getSafeCode($tag_name);

 		if(empty($tag_name))
 		{
 			$this->Messager("请输入需要查找的标签",-1);
 		}
 				if ($tag_name) {
			if (strlen($tag_name) < 2) {
				$this->Messager("请输入至少2个字符的关键词",-1);
			}
			$where_list['tag_name'] = "`tag_name`='".addslashes("{$tag_name}")."'";
			$query_link .= "&k=" . urlencode($tag_name);
		}

 		 		if($where_list)
 		{
	 		$where = (empty($where_list)) ? null : ' WHERE '.implode(' AND ',$where_list).' ';
	 		$sql = "select * from `".TABLE_PREFIX."user_tag_fields` {$where} ";
			$query = $this->DatabaseHandler->Query($sql);
			$uids = array();
			$user_tag_fields = array();
			while($row = $query->GetRow())
			{
				$user_tag_fields[] = $row;
				$uids[$row['uid']] = $row['uid'];
			}

			if(empty($uids))
			{
				include($this->TemplateHandler->Template('search_list'));
			}
		}
			  $member_list = array();
		if ($uids) {

				$sql = "select count(*) as `total_record` from `".TABLE_PREFIX."members` where `uid` in (".implode(",",$uids).")";
				$query = $this->DatabaseHandler->Query($sql);
				extract($query->GetRow());

				if($total_record > 0) {
					$_config = array (
						'return' => 'array',
					);

					$page_arr = page($total_record,$per_page_num,$query_link,$_config);

					$sql = "select `uid`,`ucuid`,`username`,`nickname`,`face_url`,`face`,`fans_count`,`topic_count`,`province`,`city`,`validate` from `".TABLE_PREFIX."members` where `uid` in(".implode(",",$uids).") {$order} {$page_arr['limit']}";
					$query = $this->DatabaseHandler->Query($sql);
					$uids = array();
					while ($row = $query->GetRow())
					{
						$row['face'] = face_get($row);
						$member_list[$row['uid']] = $row;
						$uids[$row['uid']] = $row['uid'];
					}

					if($uids && MEMBER_ID>0) {
						$sql = "select `buddyid` as `id` from `".TABLE_PREFIX."buddys` where `uid`='".MEMBER_ID."' and `buddyid` in(".implode(",",$uids).")";
						$query = $this->DatabaseHandler->Query($sql);
						$buddys = array();
						while ($row = $query->GetRow())
						{
							$buddys[$row['id']] = $row['id'];
						}

						foreach ($uids as $uid) {
							$member_list[$uid]['follow_html'] = follow_html($uid,isset($buddys[$uid]));
						}

					}
				}

										$sql = "select * from `".TABLE_PREFIX."user_tag_fields`where `uid` in (".implode(",",$uids).")";
					$query = $this->DatabaseHandler->Query($sql);
					$member_tag = array();
					while($row = $query->GetRow())
					{
						$member_tag[] = $row;
					}

			 }



								$sql = "select * from `".TABLE_PREFIX."user_tag_fields`where `uid` = '".MEMBER_ID."'";
				$query = $this->DatabaseHandler->Query($sql);
				$mytag = array();
				$mytag_ids = array();
				while($row = $query->GetRow())
				{
					$mytag[] = $row;
					$mytag_ids[$row['tag_id']] = $row['tag_id'];
				}

				$this->Title = "个人标签 - {$tag_name}";
				include($this->TemplateHandler->Template('search_list'));
	}

	function _member()
	{
		if (MEMBER_ID < 1) {
			$this->Messager(null,$this->Config['site_url'] . "/index.php?mod=login");
		}

		$member = $this->TopicLogic->GetMember(MEMBER_ID);

		return $member;
	}

}
?>
