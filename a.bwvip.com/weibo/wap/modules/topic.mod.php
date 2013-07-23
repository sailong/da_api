<?php

/*******************************************************************

 * [JishiGou] (C)2005 - 2099 Cenwor Inc.

 *

 * This is NOT a freeware, use is subject to license terms

 *

 * @Package JishiGou $

 *

 * @Filename topic.mod.php $

 *

 * @Author http://www.jishigou.net $

 *

 * @Date 2011-09-28 19:16:47 1927670780 564471264 35035 $

 *******************************************************************/




/**
 * 文件名：topic.mod.php
 * 版本号：1.0
 * 最后修改时间：2009年9月28日 14时10分42秒
 * 作者：狐狸<foxis@qq.com>
 * 功能描述: 微博话题模块
 */

if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class ModuleObject extends MasterObject
{
	var $ShowConfig;

	var $CacheConfig;

	var $TopicLogic;

	var $ID = '';


	function ModuleObject($config)
	{
		$this->MasterObject($config);

		$this->ID = (int) ($this->Post['id'] ? $this->Post['id'] : $this->Get['id']);

		Load::logic('topic');
		$this->TopicLogic = new TopicLogic($this);

		Load::logic('longtext');
		$this->LongtextLogic = new LongtextLogic($this);
		
		$this->CacheConfig = ConfigHandler::get('cache');

		$this->ShowConfig = ConfigHandler::get('show');

		$this->Execute();

	}

	
	function Execute()
	{
		ob_start();

		if(empty($_GET['mod_original']))
		{
			$this->Code = $this->Code ? $this->Code :'new';
		}
		if('fans' == $this->Code) {
			$this->Fans();
		} elseif('do_add' == $this->Code) {
			$this->DoAdd();
		} elseif('doreply' == $this->Code) {
			$this->DoAdd();
		} elseif('forward' == $this->Code) {
			$this->Forward();
		} elseif('do_forward' == $this->Code) {
			$this->DoForward();
		} elseif('dofollow' == $this->Code) {
			$this->DoFollow();
		} elseif ('follow' == $this->Code) {
			$this->Follow();
		} elseif ('top' == $this->Code) {
			$this->Top();
		} elseif (in_array($this->Code,array('new','hot',))) {
			$this->Hot();
		} elseif ('view' == $this->Code) {
			$this->View();
		} elseif ('modify' == $this->Code) {
			$this->DoModify();
		} elseif ('addpic' == $this->Code) {
			$this->DoAddPic();
		} elseif ('favorite' == $this->Code) {
			$this->DoFavorite();
		} elseif ('del' == $this->Code) {
			$this->DelTopic();
		} elseif ('dodel' == $this->Code) {
			$this->DoDelTopic();
		} elseif (is_numeric($this->Code)) {
			$this->ID = (int) $this->Code;
			$this->View();
		} else {
			$this->Main();
		}
		$body=ob_get_clean();

		$this->ShowBody($body);
	}





	function Main()
	{
		extract($this->Get);
		extract($this->Post);
		$options = array();
				if('topic'==$this->Get['mod'] && count($this->Get)<2) {

						if (MEMBER_ID > 0)
			{
				$this->Code = 'myhome';
			}
			else
			{
				$this->Hot();
				exit;
			}
		}

		$title = '';
		$per_page_num = 10;			$topic_uids = $topic_ids = $order_list = $where_list = $params = array();
		$where = $order = $limit = "";
		$query_link = "index.php?mod=" . ($_GET['mod_original'] ? get_safe_code($_GET['mod_original']) : $this->Module) . ($this->Code ? "&amp;code={$this->Code}" : "");


		if('myat' == $this->Code) {
			$title = '@提到我的';
			$topic_selected = 'myat';
			$uid = MEMBER_ID;
			$member = $this->_member($uid);

			if (!$member) {
				$this->Hot();
				return false;
			}

			if($member['uid']!=MEMBER_ID) {
				$this->Messager("您无权查看该页面",null);
			}

			if ($member['at_new']) {
				
								$sql = "update `".TABLE_PREFIX."members` set `at_new`=0 where `uid`='{$member['uid']}'";
				$this->DatabaseHandler->Query($sql);

				$this->MemberHandler->MemberFields['at_new'] = 0;
			}

			$sql = "select * from `".TABLE_PREFIX."topic_mention` where `uid`='{$uid}'";
			$query = $this->DatabaseHandler->Query($sql);
			$topic_ids[0] = 0;
			while ($row = $query->GetRow())
			{
				$topic_ids[$row['tid']] = $row['tid'];
			}
			$options['tid'] = $topic_ids;
		} else {
			$member = $this->_member();
			if(!$member) {
				$this->Hot();
				return false;
			}
		}

		$params['uid'] = $uid = $member['uid'];

		$is_personal = ($uid == MEMBER_ID);
		$params['is_personal'] = $is_personal;

		$start = max(0, (int) $start);
		$limit = "limit {$start},{$per_page_num}";
		$next = $start + $per_page_num;

		$params['code'] = $this->Code;
	
		if (!in_array($params['code'],array('myblog','mycomment','myhome','myat','myfavorite','favoritemy','tocomment',))) {
			$params['code'] = 'myblog';		}

				if (($show_topic_num = $this->ShowConfig['topic'][$params['code']]) > 0) {
			$per_page_num = $show_topic_num;
		}
		$options['perpage'] = $per_page_num;
		
		if ('myhome'==$params['code']) {

			$topic_selected = 'myhome';
						if($member['uid']==MEMBER_ID) {
				$title = '我的首页';
				$sql_buddy_lastuptime = '';
				if($this->Config['topic_myhome_time_limit'])
				{
					$sql_buddy_lastuptime = " and `buddy_lastuptime`>'" . (time() - 86400 * $this->Config['topic_myhome_time_limit']) ."'";
				}
				$sql = "select `buddyid` from `".TABLE_PREFIX."buddys` where `uid`='{$params['uid']}' $sql_buddy_lastuptime ";
				$query = $this->DatabaseHandler->Query($sql);

				while($row = $query->GetRow())
				{
					$topic_uids[$row['buddyid']] = $row['buddyid'];
				}

			  } else {
				  $title = "{$member['username']}的微博";
			  }
			  $topic_uids[$uid] = $uid;
			  $options['uid'] = $topic_uids;
			
		} elseif ('mycomment' == $params['code']) {

			$title = '评论我的';
			if($member['uid']!=MEMBER_ID) {
				$this->Messager("您无权查看该页面",null);
			}

			if ($member['comment_new']) {
				$sql = "update `".TABLE_PREFIX."members` set `comment_new`=0 where `uid`='{$member['uid']}'";
				$this->DatabaseHandler->Query($sql);

				$this->MemberHandler->MemberFields['comment_new'] = 0;
			}

			$topic_selected = 'mycomment';
			$options['where'] = " `touid`='{$member['uid']}' and `type` in ('both','reply') ";

		} elseif ('tocomment' == $params['code']) {				
				$title = '我评论的';

				if($member['uid']!=MEMBER_ID) {
					$this->Messager("您无权查看该页面",null);
				}

				$topic_selected = 'mycomment';
				$options['where'] = " `uid` = '{$member['uid']}' and `type` in ('both','reply') ";

		} elseif ('myblog' == $params['code']) {
		
			$where = " and `type` != 'reply' ";
			
			if($member['uid']!=MEMBER_ID) {
				$title = "{$member['username']}的微博";
			} else {
				$title = '我的微博';
			}

			$topic_selected = 'myblog';
						$options['uid'] = $member['uid'];
		} elseif ('myfavorite' == $params['code']) {

			$topic_selected = 'myfavorite';
			$title = '我的收藏';
			if($member['uid']!=MEMBER_ID) {
				$this->Messager("您无权查看该页面",null);
			}

						$sql = "select count(*) as `total_record` from `".TABLE_PREFIX."topic_favorite` TF where TF.uid='{$uid}'";
			$query = $this->DatabaseHandler->Query($sql);
			extract($query->GetRow());

						$page_arr = wap_page($total_record,$per_page_num,$query_link,array('return'=>"Array"));

						$sql = "select TF.dateline as favorite_time , T.* from `".TABLE_PREFIX."topic_favorite` TF left join `".TABLE_PREFIX."topic` T on T.tid=TF.tid where TF.uid='{$uid}' order by TF.id desc {$page_arr['limit']}";
			$query = $this->DatabaseHandler->Query($sql);
			while ($row = $query->GetRow())
			{
				if($row['tid']<1) continue;

				$row['favorite_time'] = my_date_format2($row['favorite_time']);

				$row = $this->_topicLogicMake($row);

				$topic_list[$row['tid']] = $row;
			}
			$topic_list_get = true;

		} elseif ('favoritemy' == $params['code']) {

			$topic_selected = 'favoritemy';
			$title = '收藏我的';
			if($member['uid']!=MEMBER_ID) {
				$this->Messager("您无权查看该页面",null);
			}

						if ($member['favoritemy_new']>0) {
				$sql = "update `".TABLE_PREFIX."members` set `favoritemy_new`=0 where `uid`='{$member['uid']}'";
				$this->DatabaseHandler->Query($sql);

				$this->MemberHandler->MemberFields['favoritemy_new'] = 0;
			}

						$sql = "select count(*) as `total_record` from `".TABLE_PREFIX."topic_favorite` TF where TF.tuid='{$uid}'";
			$query = $this->DatabaseHandler->Query($sql);
			extract($query->GetRow());

						$page_arr = wap_page($total_record,$per_page_num,$query_link,array('return'=>"Array"));

						$sql = "select TF.dateline as favorite_time , TF.uid as fuid , T.* from `".TABLE_PREFIX."topic_favorite` TF left join `".TABLE_PREFIX."topic` T on T.tid=TF.tid where TF.tuid='{$uid}' order by TF.id desc {$page_arr['limit']}";
			$query = $this->DatabaseHandler->Query($sql);
			$fuids = array();
			while ($row = $query->GetRow())
			{
				if($row['tid']<1) continue;

				$row['favorite_time'] = my_date_format2($row['favorite_time']);
				$row = $this->_topicLogicMake($row);
				$topic_list[$row['tid']] = $row;
				$fuids[$row['fuid']] = $row['fuid'];
			}
			$favorite_members = $this->_topicLogicGetMember($fuids,"`uid`,`ucuid`,`username`,`face_url`,`face`,`validate`");

			$topic_parent_disable = true;
			$topic_list_get = true;

		}

		if(!$topic_list_get)
		{

						
			
			
			Load::logic("topic_list");
			$TopicListLogic = new TopicListLogic();
			$options['page_url'] = $query_link;
			$options['order'] = " dateline DESC ";
			$info = $TopicListLogic->get_data($options, 'wap');
			$topic_list = array();
			$total_record = 0;
			if (!empty($info)) {
				$topic_list = wap_iconv($info['list']);
				$total_record = $info['count'];
				$page_arr = $info['page'];
			}

		}

		$topic_list_count = 0;
		if($topic_list) {
			$topic_list_count = count($topic_list);

			if(!$topic_parent_disable) {
								$parent_id_list = array();
				foreach ($topic_list as $key => $row) {
					if(0 < ($p = (int) $row['parent_id'])) {
						$parent_id_list[$p] = $p;
					}
					if (0 < ($p = (int) $row['top_parent_id'])) {
						$parent_id_list[$p] = $p;
					}
					
										
				}

				if($parent_id_list) {
										$parent_list = $this->_topicLogicGet($parent_id_list);
				}


							}
		}
 

		$this->Title = $title;
		include($this->TemplateHandler->Template('topic_index'));
	}


		function View()
	{
		if ($this->ID < 1) {
			$this->Messager("请指定一个ID",null);
		}
		$per_page_num = 5;
		$query_link = "index.php?mod=" . ($_GET['mod_original'] ? get_safe_code($_GET['mod_original']) : $this->Module) . ($this->Code ? "&amp;code={$this->Code}" : "");

		$topic_info = $this->_topicLogicGet($this->ID);

		if (!$topic_info) {
			$this->Messager("您要查看的话题已经不存在了",null);
		}
		
		
		if ($topic_info['item'] == 'qun') {
			$ret = $this->TopicLogic->is_qun_member($topic_info['item_id'], MEMBER_ID);
			if (!$ret) {
				$this->Messager("你不是当前微群的成员，无法查看");
			}
		} else {
									if ($topic_info['type'] == 'reply') {
				$roottid = $topic_info['roottid'];
				$root_type = DB::result_first("SELECT type FROM ".DB::table('topic')." WHERE tid='{$roottid}'");
			} else {
				$root_type = $topic_info['type'];
			}
			if (!$this->TopicLogic->check_view_perm($topic_info['uid'], $topic_info['type'])) {
				$this->Messager("你没有权限查看");
			}
		}
		
		if($topic_info['parent_id']) {
				$parent_id_list = array(
				$topic_info['parent_id'] => $topic_info['parent_id'],
				$topic_info['t
				op_parent_id'] => $topic_info['top_parent_id'],
			);

			if($parent_id_list) {

				$parent_list = $this->_topicLogicGet($parent_id_list);
			}

		}

		if ($topic_info['replys'] > 0) {
			$total_record = $topic_info['replys'];
			  $_config = array(
				'return' => 'array',
			);

			$tids = $this->TopicLogic->GetReplyIds($topic_info['tid']);
			$page_arr = wap_page($total_record,$per_page_num,$query_link,$_config);
			if($tids)
			{
				
				$condition = "where `tid` in ('".implode("','",$tids)."') order by `dateline` desc {$page_arr['limit']}";
				$reply_list = $this->_topicLogicGet($condition);
			}
		}
		
				if($topic_info['longtextid'])
		{
			$longtext_info = $this->_LongtextLogic($topic_info['longtextid']);
			$topic_info['content'] = $longtext_info['longtext'];
					}
		
		if (MEMBER_ID > 0) {
			$sql = "select * from `".TABLE_PREFIX."topic_favorite` where `uid`='".MEMBER_ID."' and `tid`='{$topic_info['tid']}'";
			$query = $this->DatabaseHandler->Query($sql);
			$is_favorite = $query->GetRow();
		}

		$member = $this->_member($topic_info['uid']);
		$this->Title = cut_str(strip_tags($topic_info['content']),50)." - {$member['nickname']}的微博";

		include($this->TemplateHandler->Template('topic_view'));
	}

	function Follow()
	{
		$member = $this->_member();
	
		if (!$member) {
			$this->Messager("链接错误，请检查",null);
		}
		$per_page_num = $this->ShowConfig['topic']['follow'];

		$_config = array(
			'return' => 'array',
		);
		$page_arr = wap_page($member['follow_count'],$per_page_num,"index.php?mod={$member['username']}&amp;code=follow",$_config);
		$sql = "select `buddyid` as id from `".TABLE_PREFIX."buddys` where `uid`='{$member['uid']}' order by `id` desc {$page_arr['limit']}";
		$query = $this->DatabaseHandler->Query($sql);
		$uids = array();
		while ($row = $query->GetRow())
		{
			$uids[$row['id']] = $row['id'];
		}

		$member_list = array();
		if($uids) {
			$buddys = array();
			if(MEMBER_ID > 0) {
				$sql = "select `buddyid` as `id` from `".TABLE_PREFIX."buddys` where `uid`='".MEMBER_ID."' and `buddyid` in(".implode(",",$uids).")";
				$query = $this->DatabaseHandler->Query($sql);
				while ($row = $query->GetRow())
				{
					$buddys[$row['id']] = $row['id'];
				}
			}

			$_list = $this->_topicLogicGetMember($uids,"`uid`,`ucuid`,`username`,`face_url`,`face`,`province`,`city`,`fans_count`,`topic_count`,`validate`,`nickname`,`follow_count`");
			foreach ($uids as $uid) {
				if(isset($_list[$uid])) {
					$_list[$uid]['follow_html'] = wap_follow_html($uid,isset($buddys[$uid]));

					$member_list[$uid] = $_list[$uid];
				}
			}
		}
	
		$this->Title = "{$member['nickname']}关注的人";
		include($this->TemplateHandler->Template('topic_follow'));
	}

	function Fans()
	{
		$member = $this->_member();
		
		if (!$member) {
			$this->Messager("链接错误，请检查",null);
		}
		
				if ($member['uid']==MEMBER_ID && $member['fans_new']>0) {
			
			$sql = "update `".TABLE_PREFIX."members` set `fans_new`=0 where `uid`='{$member['uid']}'";
			$this->DatabaseHandler->Query($sql);

			$this->MemberHandler->MemberFields['fans_new'] = 0;
		}
		
		
		$per_page_num = $this->ShowConfig['topic']['fans'];

				$_config = array(
			'return' => 'array',
		);
		$page_arr = wap_page($member['fans_count'],$per_page_num,"index.php?mod={$member['username']}&amp;code=fans",$_config);


				$sql = "select `uid` as id from `".TABLE_PREFIX."buddys` where `buddyid`='{$member['uid']}' order by `id` desc {$page_arr['limit']}";
		$query = $this->DatabaseHandler->Query($sql);
		$uids = array();
		while ($row = $query->GetRow())
		{
			$uids[$row['id']] = $row['id'];
		}


		$member_list = array();
		if($uids) {
			$buddys = array();
			if(MEMBER_ID > 0) {
				$sql = "select `buddyid` as `id` from `".TABLE_PREFIX."buddys` where `uid`='".MEMBER_ID."' and `buddyid` in(".implode(",",$uids).")";
				$query = $this->DatabaseHandler->Query($sql);
				while ($row = $query->GetRow())
				{
					$buddys[$row['id']] = $row['id'];
				}
			}

			$_list = $this->_topicLogicGetMember($uids,"`uid`,`ucuid`,`username`,`face_url`,`face`,`province`,`city`,`fans_count`,`topic_count`,`validate`,`nickname`");
			foreach ($uids as $uid) {
				if(isset($_list[$uid])) {
					$_list[$uid]['follow_html'] = wap_follow_html($uid,isset($buddys[$uid]));
					$member_list[$uid] = $_list[$uid];
				}
			}
		}

		$this->Title = "关注{$member['nickname']}的人";
		include($this->TemplateHandler->Template('topic_fans'));
	}


	function Hot()
	{	
		$this->Title = "微博广场";
		
		$uid = MEMBER_ID;
		$member = $this->_member($uid);

		$per_page_num = 10;
		$query_link = "index.php?mod=" . ($_GET['mod_original'] ? get_safe_code($_GET['mod_original']) : $this->Module) . ($this->Code ? "&amp;code={$this->Code}" : "");

	
			if($this->ShowConfig['topic_new']['topic'] > 0) {

		    
			
				Load::logic("topic_list");
				$TopicListLogic = new TopicListLogic();
				$options = array(
					'page_url' => $query_link,
					'perpage' => $per_page_num,
					'order' => " dateline DESC ",
					'type' => get_topic_type(),
				);
				$info = $TopicListLogic->get_data($options, 'wap');
				$topic_list = array();
				$total_record = 0;
				if (!empty($info)) {
					$topics = wap_iconv($info['list']);
					$total_record = $info['count'];
					$page_arr = $info['page'];
				}
			}

				$parent_id_list = array();
		if ($topics) {
			foreach ($topics as $row) {
				if(0 < ($p = (int) $row['parent_id'])) {
					$parent_id_list[$p] = $p;
				}
				if (0 < ($p = (int) $row['top_parent_id'])) {
					$parent_id_list[$p] = $p;
				}
			}
		}
		if($parent_id_list) {

			$parent_list = $this->_topicLogicGet($parent_id_list);
		}
		
		
		include($this->TemplateHandler->Template('topic_new'));
	}
	
	
		function DoFavorite()
	{
			if (MEMBER_ID < 1) {
				$this->Messager("请登录");
			}
			
	    $uid = MEMBER_ID;
	    
			$tid = (int) ($this->Get['tid']);
			
		  if ($tid < 1) {		
				$this->Messager("请指定一个微博");
			}
			
			$act = $this->Get['act'];

			$Favorite = $this->_OtherLogicFavorite($uid,$tid,$act);
			
						
			$this->Messager($Favorite,'','','','','favorite');
		
	}


	function _member($uid=0)
	{
		$member = array();
		if($uid < 1) {
			$mod_original = ($this->Post['mod_original'] ? $this->Post['mod_original'] : $this->Get['mod_original']);
			if($mod_original)
			{
				$mod_original = getSafeCode($mod_original);
				$condition = "where `username`='{$mod_original}' limit 1";
				$members = $this->_topicLogicGetMember($condition);
				if(is_array($members)) {
					reset($members);
					$member = current($members);
				}
			}
		}
		$uid = (int) ($uid ? $uid : MEMBER_ID);
		if($uid > 0 && !$member) {
			$member = $this->_topicLogicGetMember($uid);
		}
		if(!$member) {
			return false;
		}
		$uid = $member['uid'];

		if (!$member['follow_html'] && $uid!=MEMBER_ID) {
			$sql = "select * from `".TABLE_PREFIX."buddys` where `uid`='".MEMBER_ID."' and `buddyid`='{$uid}'";
			$query = $this->DatabaseHandler->Query($sql);
			$member['follow_html'] = wap_follow_html($member['uid'],$query->GetNumRows()>0);
		}


		return $member;
	}

	function _followList($uid,$num=6) {
		$sql = "select `buddyid` from `".TABLE_PREFIX."buddys` where `uid`='{$uid}' order by `dateline` desc limit {$num}";
		$query = $this->DatabaseHandler->Query($sql);
		$ids = array();
		while ($row = $query->GetRow())
		{
			$id = $row['buddyid'];

			$ids[$id] = $id;
		}
		$_list = $this->_topicLogicGetMember($ids);

		$member_list = array();
		foreach ($ids as $id) {
			if($id > 0 && isset($_list[$id])) {
				$member_list[$id] = $_list[$id];
			}
		}
		return $member_list;

	}

	function _fansList($uid,$num=6) {
		$sql = "select `uid` from `".TABLE_PREFIX."buddys` where `buddyid`='{$uid}' order by `dateline` desc limit {$num}";
		$query = $this->DatabaseHandler->Query($sql);
		$ids = array();
		while ($row = $query->GetRow())
		{
			$id = $row['uid'];

			$ids[$id] = $id;
		}
		$_list = $this->_topicLogicGetMember($ids);

		$member_list = array();
		foreach ($ids as $id) {
			if($id > 0 && isset($_list[$id])) {
				$member_list[$id] = $_list[$id];
			}
		}

		return $member_list;

	}


		function DoFollow()
	{

		$response = '';
		$timestamp = time();
		$uid = MEMBER_ID;
		if($uid < 1) response_text("登录后才能执行此操作");
		if($uid == $this->ID) response_text("您不能关注自己");

		$member = $this->_topicLogicGetMember($this->ID);
		if (!$member) {
			$response = 'TA已消失不见了';
		} else {
		if($member['disallow_beiguanzhu']) {
			response_text("此用户禁止被关注");
		}

			$sql = "select * from `".TABLE_PREFIX."buddys` where `uid`='{$uid}' and `buddyid`='{$member['uid']}' limit 1";
			$query = $this->DatabaseHandler->Query($sql);
			$row = $query->GetRow();
			
						if ($row) {
				$sql = "delete from `".TABLE_PREFIX."buddys` where `uid`='{$uid}' and `buddyid`='{$member['uid']}'";
				$this->DatabaseHandler->Query($sql);
				$response = '取消成功';
			} else {
								$sql = "insert into `".TABLE_PREFIX."buddys` (`uid`,`buddyid`,`dateline`,`buddy_lastuptime`) values ('{$uid}','{$member['uid']}','{$timestamp}','{$timestamp}')";
				$this->DatabaseHandler->Query($sql);
				$fans_new_update = " , `fans_new`=`fans_new`+1 ";
				$response = '加入成功';
			}

			update_my_fans_follow_count($uid);
			update_my_fans_follow_count($member['uid']);

		}
			if('follow' == $this->Get['act']){
				$this->Messager('关注成功','index.php?mod=topic&code=fans','','','','follow');
			}
			else{
				$this->Messager('取消关注成功','index.php?mod=topic&code=follow','','','','follow');
			}

	}


		function DoAdd()
	{
		extract($this->Get);
		extract($this->Post);
		
		$field 			= 'topic';
		$timestamp 		= time();
		$content 		= $this->Post['content'];
		
		
		if (MEMBER_ID < 1) {
			$this->Messager("请先登录或者注册",'index.php?mod=login');
		}
		
				if($_POST['addPic']){
			
			$this->Title = '发表微博';
			$getvalue = $content;
		
			include($this->TemplateHandler->Template('add_pic'));
			die();	
		}
		
		if(empty($content)) 
		{ 
		
			if($this->Code == 'do_add'){

			  $this->Messager(NULL,'index.php?mod=topic&code=addpic&return=addtopic');
			
			}else{
		
				$this->Messager(NULL,'index.php?mod=topic&code='.$this->Post['return_code'].'&return=addreply');
			}
			
			
		}


		if(!empty($_FILES[$field]['name']))
		{
			
			$sql = "insert into `".TABLE_PREFIX."topic_image`(`uid`,`username`,`dateline`) values ('".MEMBER_ID."','".MEMBER_NAME."','{$timestamp}')";
			$query = $this->DatabaseHandler->Query($sql);

			$image_id = $this->DatabaseHandler->Insert_ID();

			include_once(ROOT_PATH.'include/lib/io.han.php');
			$IoHandler = new IoHandler();

			$image_path = RELATIVE_ROOT_PATH.'images/'.$field . '/' . face_path($image_id);

			$image_name = $image_id . "_o.jpg";
			$image_file = $image_path . $image_name;
			$image_file_small = $image_path.$image_id . "_s.jpg";
			if (!is_dir($image_path)) {
				$IoHandler->MakeDir($image_path);
			}

			include_once(ROOT_PATH.'include/lib/upload.han.php');
			$UploadHandler = new UploadHandler($_FILES,$image_path,$field,true);
			$UploadHandler->setMaxSize(2048);
			$UploadHandler->setNewName($image_name);
			$result=$UploadHandler->doUpload();
			if($result) {
				$result = is_image($image_file);
			}

			if(false == $result) {
				$IoHandler->DeleteFile($image_file);

				$sql = "delete from `".TABLE_PREFIX."topic_image` where `id`='{$image_id}'";
				$this->DatabaseHandler->Query($sql);

				$error_msg = implode(" ",(array) $UploadHandler->getError());
			} else {
				list($image_width,$image_height,$image_type,$image_attr) = getimagesize($image_file);

				$result = makethumb(
					$image_file,
					$image_file_small,
					min($this->Config['thumbwidth'],$image_width),
					min($this->Config['thumbwidth'],$image_height),
					$this->Config['maxthumbwidth'],
					$this->Config['maxthumbheight']
				);
				if (!$result && !is_file($image_file_small)) {
					@copy($image_file,$image_file_small);
				}
				
								if($this->Config['watermark_enable']) {
					if($_FILES[$field]['type'] != 'image/gif')
					{
						$this->_watermark($image_file,$this->Config['site_url'] . "/" . MEMBER_NAME);
					}
				}
				
				$image_size = filesize($image_file);
				$name = addslashes($_FILES[$field]['name']);

				$sql = "update `".TABLE_PREFIX."topic_image` set `photo`='{$image_file}' , `name`='{$name}' , `filesize`='{$image_size}' , `width`='{$image_width}' , `height`='{$image_height}' where `id`='{$image_id}'";
				$this->DatabaseHandler->Query($sql);

			}
		}
		

		
		$imgId = $image_id;
		
				$topic_type = $this->Post['topictype'];
		if(empty($topic_type)){
			$type = 'reply';
		}
		elseif($topic_type == 'both'){
			$type = 'both';
		}
		else{
			$type = 'first';
		}
	
		$content = wap_iconv($content,'utf-8',$this->Config['charset']);
		
		$return = $this->TopicLogic->Add($content,$totid,$imgId,'wap',$type);
		
		$return = wap_iconv($return);

		if (is_array($return) && $return['tid'] > 0) {
			;
		} else {
			$return = (is_string($return) ? $return : "未知错误");
		}
	
		if('replycontent' == $this->Post['reply'])
		{
			$this->Messager("评论成功",'index.php?mod=topic&amp;code='.$this->Post['totid']);
		}
		else
		{
			$this->Messager("发布成功",'index.php?mod=topic&amp;code='.$return['tid']);
		}

	}

	 	 function Forward()
	 {
	 	  extract($this->Get);
			extract($this->Post);
	
			$uid = MEMBER_ID;
			$member = $this->_member($uid);
	
		  $list_topic = $this->_topicLogicGet($tid);
		 
		  if($list_topic['roottid'])
		  {
		  	$list_topic = $this->_topicLogicGet($list_topic['roottid']);
		  }
	
			$roottid  = (int) $list_topic['tid'];
			$totid    =  (int) $this->Get['tid'];
			
			$this->Title = "转发微博";
			
	 		include($this->TemplateHandler->Template('forward'));
	 }

	 	 function DoForward()
	 {
	 		extract($this->Get);
			extract($this->Post);
	
			$totid    = (int) $this->Post['totid'];					$roottid  = (int) $this->Post['roottid'];  	
			$content = trim($content) ? trim($content) : '转发微博';
	
			$type = ($this->Post['topictype'] ? 'both' : 'forward');
	
	    $content = wap_iconv($content,'utf-8',$this->Config['charset']);
	
			$return = $this->TopicLogic->Add($content,$totid,$imgid,'wap',$type);
	
	 		$this->Messager("转发成功",'index.php?mod=topic&amp;code='.$return['tid']);
	
	 }

	 	 function DoModify()
	 {
	 	 $tid = (int) ($this->Post['tid'] ? $this->Post['tid'] : $this->Get['tid']);     
		
	 	 if($this->Get['tid'] > 0)
	 	 {
				$action = 'index.php?mod=topic&amp;code=modify';
				
				$topiclist = $this->_topicLogicGet($tid);
				
			 	 if($topiclist==false) 
				 {
				 	$this->Messager("您要编辑的微博信息已经不存在!",NULL);
				 }
		
				        		$row = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."topic where `tid`='$tid'");
       			$topiclist['content'] = ($row['content'] . $row['content2']);
				
				
			 	 				$topiclist['content'] = preg_replace('~<U ([0-9a-zA-Z]+)>(.+?)</U>~','',$topiclist['content']);
		
			    				$topiclist['content'] = strip_tags($topiclist['content']);	
				
								if('both'==$topiclist['type'] || 'forward'==$topiclist['type'])
				{
					$topiclist['content'] = $this->TopicLogic->GetForwardContent($topiclist['content']);
				}
	 	
				$topiclist['content'] = wap_iconv($topiclist['content']);
				
				$this->Title = cut_str(strip_tags($topiclist['content']),50)." ";
				
				
				$return_messager = $this->Get['return'];
				
	 	 		include($this->TemplateHandler->Template('topic_modify'));
	 	 }
	 	 else
	 	 {
	     	 $tid = (int) $this->Post['tid'];
	 	 	 $imageid = (int) $this->Post['imageid'];
		 	 $content = strip_tags($this->Post['content']);
		 	 
		 	 $sql = "select * from `".TABLE_PREFIX."topic` where `tid`='{$tid}'";
			 $query = $this->DatabaseHandler->Query($sql);
			 $topiclist=$query->GetRow();

		  
		 	 $content = wap_iconv($content,'utf-8',$this->Config['charset']);
		 	 if(empty($content))
		 	 { 
		 	 	 $return_messager = "modify_content_null";
		 	 	 $this->Messager(NULL,'index.php?mod=topic&code=modify&tid='.$tid.'&return='.$return_messager);		 	 	 
		 	 }
		  
	     	 $modify_result = $this->TopicLogic->Modify($tid,$content,$imageid);
	       
		     if(is_array($modify_result))
		     {
		        $this->Messager('编辑成功','index.php?mod=topic&code=modify&tid='.$tid);
		     }
		     else
		     {
		        $this->Messager("编辑失败",null);
		     }	 	 
	 	}
	
	 }
 
 
	 	 function DoAddPic()
	 {
	 	  $this->Title = '发表微博';
			
			if($this->Get['nickname'])
			{
				$getvalue = '@'.$this->Get['nickname'].' ';
			}
			
			if($this->Get['tagvalve'])
			{
				$getvalue = '##';
			}
			
	 		include($this->TemplateHandler->Template('add_pic'));
	 }


	 	 function DelTopic()
	 {		
	 	$tid = (int) ($this->Post['tid'] ? $this->Post['tid'] : $this->Get['tid']);
	
		$this->Messager('确定删除微博，删除后不可恢复','index.php?mod=topic&amp;code=dodel&amp;tid='.$tid,'','','','del');
	 }
	 function DoDelTopic()
	 {		
 		$tid = (int) ($this->Post['tid'] ? $this->Post['tid'] : $this->Get['tid']);

		if ($tid < 1) {
			$this->Messager('请指定一个您要删除的话题','index.php?mod=topic&amp;code=myhome');
		}

		$topic = $this->TopicLogic->Get($tid);

		if (!$topic) {
			$this->Messager('话题已经不存在了','index.php?mod=topic&amp;code=myhome');
		}

		if ($topic['uid']!=MEMBER_ID && 'admin'!=MEMBER_ROLE_TYPE) {
			$this->Messager('您无权删除该话题','index.php?mod=topic&amp;code=myhome');
		}

		$return = $this->TopicLogic->Delete($tid);

		$this->Messager(NULL,'index.php?mod=topic&code=myhome');
	 }
 
 	
	function _watermark($pic_path,$watermark,$new_pic_path='')
	{
		if(false === is_file($pic_path)) {
			return false;
		}
		if('' == trim($watermark)) {
			 return false;
		}
		$sys_config = ConfigHandler::get();
		if (!$sys_config['watermark_enable']) {
			return false;
		}
		if('' == $new_pic_path) {
			$new_pic_path = $pic_path;
		}

		require_once(ROOT_PATH . 'include/lib/thumb.class.php');
		$_thumb = new ThumbHandler();
		$_thumb->setSrcImg($pic_path);
		$_thumb->setDstImg($new_pic_path);
		$_thumb->setImgCreateQuality(80);
	
		$_thumb->setMaskPosition($sys_config['watermark_position']);
	
		if(is_file($watermark))
		{
			$_thumb->setMaskImgPct(100);
			
			$_thumb->setMaskImg($watermark);
			
		}
		else
		{
						$mask_word = (string) $watermark;
			if (preg_match('~[\x7f-\xff][\x7f-\xff]~',$mask_word)) {
				if(is_file(RELATIVE_ROOT_PATH . 'images/jsg.ttf')) {
					$_thumb->setMaskFont(RELATIVE_ROOT_PATH . 'images/jsg.ttf');
					$mask_word = array_iconv($this->Config['charset'],'utf-8',$mask_word);
				} else {
					$mask_word = $sys_config['site_url'];
				}
			}

			$_thumb->setMaskWord($mask_word);
		}
		
		return $_thumb->createImg(100);
		
	}

}

?>
