<?php

/**
 * 搜索
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
		Load::logic("topic_list");
		$this->TopicListLogic = new TopicListLogic();
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
		case 'vote':
				$this->VoteSearch();
				break;
		case 'qun':
				$this->QunSearch();
				break;
		default:
			$this->Main();
			break;
		}
	}
	
	function Main()
	{
		
				if(MEMBER_STYLE_THREE_TOL)
		{
			$member = $this->TopicLogic->GetMember(MEMBER_ID);
						if ($member['medal_id']) {
				$medal_list = $this->TopicLogic->GetMedal($member['medal_id'],$member['uid']);
			}
		}
		include($this->TemplateHandler->Template('search_list'));
	}
	
	function UserSearch()
	{ 	
				if(MEMBER_STYLE_THREE_TOL)
		{
			$member = $this->TopicLogic->GetMember(MEMBER_ID);
						if ($member['medal_id']) {
				$medal_list = $this->TopicLogic->GetMedal($member['medal_id'],$member['uid']);
			}
		}

		$per_page_num = 10;
		$query_link = 'index.php?mod=search&code=user';
		$order = " order by `fans_count` desc "; 
		$where_list = array();
		
				$keyword = trim($this->Get['nickname']);	
		$keyword = getSafeCode($keyword);	
		if ($keyword) 
		{
			$keyword = $this->_filterKeyword($keyword);
			
			$search_keyword = $keyword;
	
			$where_list['keyword'] = false!==strpos($keyword,'@') ? "`email`='".addslashes("{$keyword}")."'" : build_like_query("nickname",$keyword);
			$query_link .= "&keyword=" . urlencode($keyword);
			$where = (empty($where_list)) ? null : ' WHERE '.implode(' AND ',$where_list).' ';
		}
		
		
		$province = is_numeric($this->Get['province']) ? $this->Get['province'] : 0;
		if($province > 0) {
			$province = $this->DatabaseHandler->ResultFirst("select `name` from ".TABLE_PREFIX."common_district where id = '$province'");
			$where_list['province'] = "`province`='".addslashes("{$province}")."'";
			$query_link .= "&province=" . urlencode($province);
			
			$city = is_numeric($this->Get['city']) ? $this->Get['city'] : 0;
			if ($city > 0) {
				$city = $this->DatabaseHandler->ResultFirst("select `name` from ".TABLE_PREFIX."common_district where id = '$city'");
				$where_list['city'] = "`city`='".addslashes("{$city}")."'";
				$query_link .= "&city=" . urlencode($city);
				
				$area = is_numeric($this->Get['area']) ? $this->Get['area'] : 0;
				if ($area > 0) {
					$area = $this->DatabaseHandler->ResultFirst("select `name` from ".TABLE_PREFIX."common_district where id = '$area'");
					$where_list['area'] = "`area`='".addslashes("{$area}")."'";
					$query_link .= "&area=" . urlencode($area);
					
					$street = is_numeric($this->Get['street']) ? $this->Get['street'] : 0;
					if ($street > 0) {
						$street = $this->DatabaseHandler->ResultFirst("select `name` from ".TABLE_PREFIX."common_district where id = '$street'");
						$where_list['street'] = "`street`='".addslashes("{$street}")."'";
						$query_link .= "&street=" . urlencode($street);
					}
				}
			}
			
			$where = (empty($where_list)) ? null : ' WHERE '.implode(' AND ',$where_list).' ';
		}
		

				$tags = trim($this->Get['tags']);	
 		$tags = getSafeCode($tags);
 		if($tags) 
		{
			$tags = $this->_filterKeyword($tags);
			
			$search_keyword = $tags;			
			
						$sql = "select distinct(`uid`) as `uid` from `".TABLE_PREFIX."tag_favorite` where `tag` = '{$tags}' order by `id` desc ";
			$query = $this->DatabaseHandler->Query($sql);
			$uids = array();
			while ($row = $query->GetRow()) 
			{
				$uids[$row['uid']] = $row['uid'];
			}
		
			if(!empty($uids))
			{
				$where = $where_list = " where `uid` in (".implode(",",$uids).")";
				$query_link .= "&tags=" . urlencode($tags);
			}
		}

				if($where_list)
		{
			$result = $this->_MemberList($where,true);
            extract($result);
            
						
			if($uids)
			{
								$sql = "select * from `".TABLE_PREFIX."user_tag_fields`where `uid` in (".implode(",",$uids).")";
				$query = $this->DatabaseHandler->Query($sql);
				$member_tag = array();
				while($row = $query->GetRow())
				{
					$member_tag[] = $row;
				}
			}
			
		}

		$this->Title = "用户搜索";
		include($this->TemplateHandler->Template('search_list'));
	}
	
    function UserTagSearch()
  {		
  	
 	 		if(MEMBER_STYLE_THREE_TOL)
		{
			$member = $this->TopicLogic->GetMember(MEMBER_ID);
						if ($member['medal_id']) {
				$medal_list = $this->TopicLogic->GetMedal($member['medal_id'],$member['uid']);
			}
		}
		
        $per_page_num = 10;
		$query_link = 'index.php?mod=search&code=user';
		$order = " order by `fans_count` desc "; 

		 		$usertag = trim($this->Get['usertag']);	
 		$usertag = getSafeCode($usertag);
 		
		if($usertag) 
		{
			$usertag = $this->_filterKeyword($usertag);
			
			$search_keyword = $usertag;
			
 			 			$where = ' where '.build_like_query('`tag_name`',$usertag);
 			
	 		$sql = "select * from `".TABLE_PREFIX."user_tag_fields` {$where}";
			$query = $this->DatabaseHandler->Query($sql);
			$uids = array();
			$user_tag_fields = array();
			while($row = $query->GetRow())
			{ 
				$user_tag_fields[] = $row;
				$uids[$row['uid']] = $row['uid'];
			}	

			if(!empty($uids))
			{
				$where = $where_list = " where `uid` in (".implode(",",$uids).")";
				$query_link .= "&usertag=" . urlencode($usertag);
			}
		}
		
				if($where_list)
		{
			$result = $this->_MemberList($where,true);
			extract($result);
			
			if($uids)
			{
								$sql = "select * from `".TABLE_PREFIX."user_tag_fields`where `uid` in (".implode(",",$uids).")";
				$query = $this->DatabaseHandler->Query($sql);
				$member_tag = array();
				while($row = $query->GetRow())
				{
					$member_tag[] = $row;
				}
			}		
		}
		
				$mytag = $this->_MyUserTag(MEMBER_ID);
	
		$this->Title = "个人标签搜索";
		include($this->TemplateHandler->Template('search_list'));
 	}

	
		function TopicSearch()
	{	
		
				if(MEMBER_STYLE_THREE_TOL)
		{
			$member = $this->TopicLogic->GetMember(MEMBER_ID);
						if ($member['medal_id']) {
				$medal_list = $this->TopicLogic->GetMedal($member['medal_id'],$member['uid']);
			}
		}
		
 		$per_page_num = 10;
		$query_link = 'index.php?mod=search&code=topic';
		$topic_parent_disable = false; 
		$starttime = strtotime($this->Get['starttime']);
		$endtime   = strtotime($this->Get['endtime']);
		
		$keyword = trim($this->Get['topic']);
		$keyword = getSafeCode($keyword);		
		if($keyword) 
		{			
			$keyword = $this->_filterKeyword($keyword);
			
			$search_keyword = $keyword;
				  		$_GET['highlight'] = $search_keyword;
	  		
			$where_list['keyword'] = build_like_query('`content`,`content2`',$keyword);
   			$query_link .= "&topic=" . urlencode($keyword);
   		}		
		
		if ($starttime) {
			$where_list['starttime'] = "`dateline` > '{$starttime}'";
			$query_link .= "&starttime=" . urlencode($starttime);
		}
			
		if ($endtime) {
			$endtime += 86400;
			$where_list['endtime'] = "`dateline` < '{$endtime}'";
			$query_link .= "&endtime=" . urlencode($endtime);
		}
   		
   		if ($where_list) {
			$where = (empty($where_list)) ? '' : ' '.implode(' AND ',$where_list).' ';	
			$options = array(
				'where' => $where,
				'type' => get_topic_type(),
				'order' => ' `dateline` desc ',
				'page_url' => $query_link,
				'perpage' => $per_page_num,
			);
			$info = $this->TopicListLogic->get_data($options);
			$topic_list = array();
			$total_record = 0;
			if (!empty($info)) {
				$topic_list = $info['list'];
				$total_record = $info['count'];
				$page_arr = $info['page'];
			}
		}
		
		
		$topic_list_count = 0;
		if($topic_list)
		{
			$topic_list_count = count($topic_list);
			
			if(!$topic_parent_disable) 
			{
								$parent_list = $this->TopicLogic->GetParentTopic($topic_list);
							}
		}

		$this->Title = "搜索微博";
		include($this->TemplateHandler->Template('search_list'));
	}
	
		
		function TagSearch()
	{
		$per_page_num = 10;
		$topic_parent_disable = false;
		$query_link = 'index.php?mod=search&code=tag';
		
		$tag = trim($this->Get['tag']);
		$tag = getSafeCode($tag);
	
		if($tag)
		{
			$tag = $this->_filterKeyword($tag);
			
			$search_keyword = $tag;
			  			$_GET['highlight'] = $search_keyword;
		
			$where = ' where '.build_like_query('`name`',$tag);
   		
			$sql = "select `id` from `".TABLE_PREFIX."tag` {$where}";
			$query = $this->DatabaseHandler->Query($sql);
			$tag_id = array();
			while ($row = $query->GetRow()) 
			{
				$tag_id[$row['id']] = $row['id'];
			}
	
			if($tag_id)
			{
				$sql = "select `item_id` from `".TABLE_PREFIX."topic_tag` where `tag_id` in(".implode(",",$tag_id).")";
				$query = $this->DatabaseHandler->Query($sql);
				$topic_ids = array();
				while ($row = $query->GetRow()) 
				{
					$topic_ids[$row['item_id']] = $row['item_id'];
				}
				$where = $where_list = " `tid` in('".implode("','",$topic_ids)."') ";
				$query_link .= "&tag=" . urlencode($tag);
			}
		} 
		
		if ($where_list) {
			$where = $where_list;	
			$options = array(
				'where' => $where,
				'type' => get_topic_type(),
				'order' => ' `dateline` desc ',
				'page_url' => $query_link,
				'perpage' => $per_page_num,
			);
			$info = $this->TopicListLogic->get_data($options);
			$topic_list = array();
			$total_record = 0;
			if (!empty($info)) {
				$topic_list = $info['list'];
				$total_record = $info['count'];
				$page_arr = $info['page'];
			}
			
			$topic_list_count = 0;
			if($topic_list)
			{
				$topic_list_count = count($topic_list);
				
				if(!$topic_parent_disable) 
				{
										$parent_list = $this->TopicLogic->GetParentTopic($topic_list);
									}
			}
		}		
		
				if(MEMBER_STYLE_THREE_TOL)
		{
			$member = $this->TopicLogic->GetMember(MEMBER_ID);
						if ($member['medal_id']) {
				$medal_list = $this->TopicLogic->GetMedal($member['medal_id'],$member['uid']);
			}
		}
		
		$this->Title = "话题搜索";
		include($this->TemplateHandler->Template('search_list'));
		
	}
	
	
	function VoteSearch()
	{
		$vote_setting = ConfigHandler::get('vote');
		if (!$vote_setting['vote_open']) {
			$this->Messager("当前站点没有开放投票功能", null);	
		}
		$perpage = 10;
		$q = trim($this->Get['q']);
		$q = getSafeCode($q);
		$gets = array(
			'mod' => 'search',
			'code' => 'vote',
			'q' => $this->Get['q'],
		);
		$page_url = 'index.php?'.url_implode($gets);
		$count = 0;
		if (!empty($q)) {
			
			$q = $this->_filterKeyword($q);
			
			$search_keyword = $q;
			$where = ' '.build_like_query('v.subject', $q).' ';
			$order = " ORDER BY dateline DESC ";
			Load::logic("vote");
			$VoteLogic = new VoteLogic();
			$param = array(
				'where' => $where,
				'order' => $order,
				'page' => true,
				'perpage' => $perpage,
				'page_url' => $page_url,
			);
			$vote_info = $VoteLogic->find($param);
			$vote_list = array();
			$page_arr['html'] = '';
			$uid_ary = array();
			if (!empty($vote_info)) {
				$count = $vote_info['count'];
				$vote_list = $vote_info['vote_list'];
				$page_arr['html'] = $vote_info['page']['html'];
				$uid_ary = $vote_info['uids'];
			}
			
						
						if (!empty($uid_ary)) {
				$members = $this->TopicLogic->GetMember($uid_ary);
			}
		}
		
				if(MEMBER_STYLE_THREE_TOL)
		{
			$member = $this->TopicLogic->GetMember(MEMBER_ID);
						if ($member['medal_id']) {
				$medal_list = $this->TopicLogic->GetMedal($member['medal_id'],$member['uid']);
			}
		}
		
		$this->Title = '投票搜索';
		include($this->TemplateHandler->Template('search_list'));
	}
	
		function QunSearch()
	{
		$qun_setting = ConfigHandler::get('qun_setting');
		if (!$qun_setting['qun_open']) {
			$this->Messager("当前站点没有开放微群功能", null);	
		}
		
		$perpage = 10;
		$q = trim($this->Get['q']);
		$q = getSafeCode($q);
		$gets = array(
			'mod' => 'search',
			'code' => 'qun',
			'q' => $this->Get['q'],
		);
		$page_url = 'index.php?'.url_implode($gets);
		$count = 0;
		if (!empty($q)) {
			
			$q = $this->_filterKeyword($q);
			
			$search_keyword = $q;
						$where = ' gview_perm=0 AND '.build_like_query('name', $q).' ';
			$order = " ORDER BY dateline DESC ";
			Load::logic("qun");
			$QunLogic = new QunLogic();
			$qun_list = array();
			$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('qun')." WHERE {$where}");
			if ($count > 0) {
				$page_arr = page($count, $perpage, $page_url, array('return'=>'array'));
				$query = DB::query("SELECT * FROM ".DB::table('qun')." WHERE {$where} {$order} {$page_arr['limit']}");
				while ($value=DB::fetch($query)) {
					if (empty($value['icon'])) {
						$value['icon'] = $QunLogic->qun_avatar($value['qid'], 's'); 
					}
					$value['dateline'] = my_date_format2($value['dateline']);
					$qun_list[] = $value;
				}
			}
		}
		
				if(MEMBER_STYLE_THREE_TOL)
		{
			$member = $this->TopicLogic->GetMember(MEMBER_ID);
						if ($member['medal_id']) {
				$medal_list = $this->TopicLogic->GetMedal($member['medal_id'],$member['uid']);
			}
		}
		
		$this->Title = '微群搜索';
		include($this->TemplateHandler->Template('search_list'));
	}
	
	
	function _MemberList($where='',$return_more = false)
	{
            $return = array();
            		
                        $member_list = array();
			
			$sql = "select count(*) as `total_record` from `".TABLE_PREFIX."members` {$where}";
			$query = $this->DatabaseHandler->Query($sql);
			extract($query->GetRow());
			if($total_record > 0) 
            {
                $return['total_record'] = $total_record;			 
             
    			$_config = array (
    				'return' => 'array',
    			);
    			
    			$page_arr = page($total_record,$per_page_num,$query_link,$_config);
                
                $return['page_arr'] = $page_arr;
                
    			$sql = "select `uid`,`ucuid`,`username`,`nickname`,`face_url`,`face`,`fans_count`,`topic_count`,`province`,`city`,`validate` from `".TABLE_PREFIX."members` {$where} {$order} {$page_arr['limit']}";
    			$query = $this->DatabaseHandler->Query($sql);
    			$uids = array();
    			while ($row = $query->GetRow()) 
    			{
    				$row['face'] = face_get($row);
    				$member_list[$row['uid']] = $row;
    				$uids[$row['uid']] = $row['uid'];
    			}
    		
    			if($uids && MEMBER_ID>0) 
                {
    				$sql = "select `buddyid` as `id` from `".TABLE_PREFIX."buddys` where `uid`='".MEMBER_ID."' and `buddyid` in(".implode(",",$uids).")";
    				$query = $this->DatabaseHandler->Query($sql);
    				$buddys = array();
    				while ($row = $query->GetRow())
    				{
    					$buddys[$row['id']] = $row['id'];
    				}
    				
    				foreach ($uids as $uid) 
                    {
    					$member_list[$uid]['follow_html'] = follow_html($uid,isset($buddys[$uid]));	
    				}
    		  }		  
		}
        $return['member_list'] = $member_list;
        
        if($return_more)
        {
            return $return;
        }
		
		return $member_list;
	
	}
	

		function _MyUserTag($uid)
	{
		$sql = "select * from `".TABLE_PREFIX."user_tag_fields` where `uid` = '{$uid}'";
		$query = $this->DatabaseHandler->Query($sql);
		$mytag = array();
		$mytag_ids = array();
		while($row = $query->GetRow())
		{
			$mytag[] = $row;
			$mytag_ids[$row['tag_id']] = $row['tag_id'];
		}
			
		return $mytag;
	}
	
	function _filterKeyword($keyword)
	{
		$keyword = str_replace(array('"', "'", '\\', ), '', $keyword);
		$keyword = jstripslashes($keyword);
		$keyword = strip_tags($keyword);
		$keyword = trim($keyword);
		
		if(2 > strlen($keyword))
		{
			$this->Messager("请输入至少三个字符以上的关键词", -1);
		}
		
		$filter_result = filter($keyword);
		if($filter_result)
		{
			$this->Messager("输入的搜索词 " . $filter_result, null);
		}
		
		$keyword = jaddslashes($keyword);
		
		return $keyword;
	}
	
}
?>
