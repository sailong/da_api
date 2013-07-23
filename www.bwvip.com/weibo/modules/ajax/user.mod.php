<?php

/*******************************************************************

 * [JishiGou] (C)2005 - 2099 Cenwor Inc.

 *

 * This is NOT a freeware, use is subject to license terms

 *

 * @Package JishiGou $

 *

 * @Filename user.mod.php $

 *

 * @Author http://www.jishigou.net $

 *

 * @Date 2011-09-28 19:16:47 1025892327 1204928649 20925 $

 *******************************************************************/




/**
 * 文件名：wall.mod.php
 * 版本号：1.0
 * 最后修改时间：2011年5月30日
 * 作者：狐狸<foxis@qq.com>
 * 功能描述: 墙模块
 */


if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class ModuleObject extends MasterObject
{	
	var $WallLogic;
	
	var $WallInfo = array();
	
	var $WallId = 0;

	function ModuleObject($config)
	{
		$this->MasterObject($config);
		
		$this->initMemberHandler();
		
		Load::logic('topic');
		$this->TopicLogic = new TopicLogic($this);

		$this->Execute();
	}

	
	function Execute()
	{
		switch ($this->Code)
        {          
        	case 'myfavoritetags':
        		$this->MyFavoriteTags();
        		break;
        		
			case 'user_tag':
        		$this->UserTag();
        		break;
        	
        	case 'to_user_tag':
        		$this->ToUserTag();
        		break;
        		
        	case 'refresh':
        		$this->Refresh();
        		break;

        	case 'hot_tag':
        		$this->HotTag();
        		break;
        		
			case 'modify_user_three_tol':
        		$this->Modify_User_Three_Tol();
        		break;
        		
        	case 'recommend_user':
        		$this->Recommend_user();
        		break;	

        	case 'user_follow':
        		$this->User_Follow();
        		break;	

        		
        	case 'to_user_event':
        		$this->To_User_Event();
        		break;	
        		
			default:
				$this->Main();
		}
	}

	function Main()
	{		
        response_text("正在建设中");        
	}
	
	
	
	function UserTag() 
	{	
		$code = 'user_tag';
		
		$uid = (int) $this->Post['uid'];
		
		$sql = "select * from `".TABLE_PREFIX."user_tag_fields` where  `uid` = '{$uid}'";
		$query = $this->DatabaseHandler->Query($sql);
		$myuser_tag = array();
		while ($row = $query->GetRow()) 
		{
			$myuser_tag[] = $row;
		}
		
		
		include($this->TemplateHandler->Template("topic_right_user_ajax"));
	}
	
		function ToUserTag() 
	{	
		$code = 'to_user_tag';
		
		$uid = (int) $this->Post['uid'];
		
		$sql = "select * from `".TABLE_PREFIX."user_tag_fields` where  `uid` = '{$uid}'";
		$query = $this->DatabaseHandler->Query($sql);
		$to_user_tag = array();
		while ($row = $query->GetRow()) 
		{
			$to_user_tag[] = $row;
		}
		
		
		include($this->TemplateHandler->Template("topic_right_user_ajax"));
	}
	

	
	function MyFavoriteTags($limit=12)
	{
				$code = 'favorite_tag';
	
		$uid = (int) $this->Post['uid'];
		
		$sql = "select * from `".TABLE_PREFIX."tag_favorite` where `uid`='{$uid}' order by `id` desc limit {$limit} ";
		$query = $this->DatabaseHandler->Query($sql);
		$my_favorite_tags = array();
		while(false != ($row = $query->GetRow()))
		{
			$my_favorite_tags[$row['tag']] = $row;
		}

		include($this->TemplateHandler->Template("topic_right_user_ajax"));
		
	}
	
	
		
	function HotTag() 
	{
		$code = 'hot_tag';
		
		$hot_tag_recommend = ConfigHandler::get('hot_tag_recommend');
		
		include($this->TemplateHandler->Template("topic_right_user_ajax"));
	}
	
	
	function Recommend_user() 
	{
		
		$code = 'recommend_user';
		
		$uid = $this->Post['uid'];
		
		$recommend_user_list = $this->_Recommenduser(12);
		
		
		include($this->TemplateHandler->Template("topic_right_user_ajax"));
	}
	
	
	
	function User_Follow() 
	{
		$code = 'user_follow';
		
		$uid = $this->Post['uid'];

				$member = DB::fetch_first("SELECT * FROM ".DB::table('members')." WHERE uid='{$uid}'");
		
		$user_follow_list = $this->_followList($uid);
		
		include($this->TemplateHandler->Template("topic_right_user_ajax"));
	}
	
	
	
	function To_User_Event()
	{
		$code = 'to_user_event';
		
		$uid = $this->Post['uid'];
		
		
		
				$sql = "select `oid` ,`id`,`title`,`play` from `".TABLE_PREFIX."event_member` where  `fid` = '{$uid}' and `play` = 1 order by `play_time` desc limit 0,6";
		$query = $this->DatabaseHandler->Query($sql);
		$to_user_event = array();
		while ($row = $query->GetRow()) 
		{	
			$to_user_event[] = $row;
		}

		include($this->TemplateHandler->Template("topic_right_user_ajax"));
	} 

	
	
	function Refresh($num_list=1,$check_num=0)
	{
		
		$code = 'refresh';
		
				$my_id = MEMBER_ID;
		
			
		$type_array = array('follow','tag','user_tag','city');
		$refresh_type_rand = array_rand($type_array,1);
		$refresh_type = $type_array[$refresh_type_rand];
			
		

				if($refresh_type == 'follow')
		{	
			
			$query = DB::query("SELECT `buddyid` FROM ".DB::table('buddys')." where uid='{$my_id}'");
			$touser_buddysid = array();
			while ($value = DB::fetch($query))
			{
				$touser_buddysid[] = $value['buddyid'];
			}
			
			
			$query = DB::query("SELECT `buddyid` FROM ".DB::table('buddys')." where `uid` in ('".implode("','",$touser_buddysid)."') and `buddyid` != '{$my_id}' ");
			$touser_buddysid_all = array();
			while ($value = DB::fetch($query))
			{
				$touser_buddysid_all[$value['buddyid']] = $value['buddyid'];
			}
			
			
						
									$uids = array_unique($touser_buddysid_all);
			
		}
		
		
				elseif($refresh_type == 'tag')
		{
						$query = DB::query("SELECT `tag` FROM ".DB::table('tag_favorite')." where uid='{$my_id}'");
			$touser_tag = array();
			while ($value = DB::fetch($query)) 
			{
				$touser_tag[] = $value['tag'];
			}
			
						$query = DB::query("SELECT `uid` FROM ".DB::table('tag_favorite')." where `tag` in ('".implode("','",$touser_tag)."') and `uid` != '{$my_id}' ");
			$touser_taguid_all = array();
			while ($value = DB::fetch($query)) 
			{
				$touser_taguid_all[$value['uid']] = $value['uid'];
			}
			
						$uids = array_unique($touser_taguid_all);
			
		}
		
		
				elseif($refresh_type == 'user_tag')
		{	
						$query = DB::query("SELECT `tag_id`,`uid` FROM ".DB::table('user_tag_fields')." where uid='{$my_id}'");
			$touser_usertag_uid = array();
			while ($value = DB::fetch($query)) 
			{
				$touser_usertag_uid[] = $value['tag_id'];
			}
			
						$query = DB::query("SELECT `uid` FROM ".DB::table('user_tag_fields')." where `tag_id` in ('".implode("','",$touser_usertag_uid)."') and `uid` != '{$my_id}' ");
			$touser_usertag_all = array();
			while ($value = DB::fetch($query)) 
			{
				$touser_usertag_all[$value['uid']] = $value['uid'];
			}			
		
						$uids = array_unique($touser_usertag_all);
						
		}
		
		
				elseif($refresh_type == 'city')
		{	
						$member_info = DB::fetch_first("select `uid`,`province`,`city` from ".DB::table('members')." where `uid` = '{$my_id}' ");
			
						$query = DB::query("select `uid` from ".DB::table('members')." where `city` = '{$member_info['city']}' and `uid` != '{$my_id}' ");
			$member_list = array();
			while ($value = DB::fetch($query)) 
			{
				$member_list[$value['uid']] = $value['uid'];
			}
			
			$uids = $member_list;
		}

		
		
	
		
	
				$member_list = array();
		if($uids)
		{
						$k = array_search(MEMBER_ID, $uids);
			if ($k !== false) {
				unset($k);
			}
			
			
			
						$query = DB::query("SELECT `buddyid` 
								FROM ".DB::table('buddys')." 
								WHERE uid='".MEMBER_ID."' AND buddyid IN(".jimplode($uids).")");
			
			$buddys = array();
			$buddysid = array();
			while ($value = DB::fetch($query)) {
					
					$index = array_search($value['buddyid'], $uids);
					if ($index !== false) {
						unset($uids[$index]);
						continue;
					}
					$buddys[$value['id']] = $value['id'];
					
			}
				
			
			
			
						$rand_number = 4;
		
			if(count($uids) > $rand_number)
			{
								$uids_key  = array_rand($uids,$rand_number);			
				$rand_uids = array();
				foreach ($uids_key as $value) {				
					$rand_uids[] .= $uids[$value];
				}
			}
			
			$list_uids = $rand_uids ? $rand_uids : $uids;
			
					    $condition = "where `uid` in ('" . implode("','", $list_uids) . "') order by `uid` desc limit 0,{$rand_number}";		 
			$_list = $this->TopicLogic->GetMember($condition,"`uid`,`ucuid`,`username`,`face_url`,`face`,`province`,`city`,`fans_count`,`topic_count`,`validate`,`nickname`,`gender`");	
			
						$count = 0;
			foreach ($uids as $uid)
			{
				if(isset($_list[$uid]))
				{
					if($refresh_type == 'follow')
					{
					    					 	$sql="select * from  `".TABLE_PREFIX."buddys` where `buddyid` = '{$_list[$uid]['uid']}' ";				 	
					    $query = $this->DatabaseHandler->Query($sql);
					    $buddysid = array();
					    while ($row = $query->GetRow())
					    {
				    		$buddysid[$row['uid']] = $row['uid'];
					    }
					        
					   						    $where = "where `uid` = '".MEMBER_ID."' and `buddyid` IN (".jimplode($buddysid).")";
					    $count = DB::result_first("SELECT count(*) FROM ".DB::table('buddys')." {$where} ");
					    					}
					if($refresh_type == 'user_tag')
					{
												$sql="select * from  `".TABLE_PREFIX."user_tag_fields` where `uid` = '{$_list[$uid]['uid']}' ";				 	
					    $query = $this->DatabaseHandler->Query($sql);
					    $usertag_id = array();
					    while ($row = $query->GetRow())
					    {
				    		$usertag_id[$row['tag_id']] = $row['tag_id'];
					    }
					   
					    					    $where = "where `uid` = '".MEMBER_ID."' and `tag_id` IN (".jimplode($usertag_id).")";
					    $count = DB::result_first("SELECT count(*) FROM ".DB::table('user_tag_fields')." {$where} ");	
		
					}
					
					if($refresh_type == 'tag')
					{
												$sql="select * from  `".TABLE_PREFIX."tag_favorite` where `uid` = '{$_list[$uid]['uid']}' ";				 	
					    $query = $this->DatabaseHandler->Query($sql);
					    $usertag = array();
					    while ($row = $query->GetRow())
					    {
				    		$usertag[$row['tag']] = $row['tag'];
					    }
					    
					    $where = "where `uid` = '".MEMBER_ID."' and `tag` IN (".jimplode($usertag).")";
					    $count = DB::result_first("SELECT count(*) FROM ".DB::table('tag_favorite')." {$where} ");
					    
											}

					$_list[$uid]['count'] = $count;					
				    $_list[$uid]['follow_html'] = follow_html2($uid,isset($buddys[$uid]));
				    $_list[$uid]['validate_html'] = $_list[$uid]['validate_html'];
				    
				    
					$member_list[$uid] = $_list[$uid];
				}
			}
			
		}
		
				if(empty($member_list))
		{		
			if($num_list < 10)
			{	
				$check_num = $num_list + 1;

				$this->Refresh($check_num);
			}
		}
		
        include($this->TemplateHandler->Template("topic_right_user_ajax"));
	}
	
	
	function Modify_User_Three_Tol() 
	{	
				$uid = (int) $this->Post['uid'];
		
				$list_uid = (int) $this->Post['list_uid'];
		
		$style_three_tol =  (int)$this->Post['style_three_tol'];
		$forceup = (1===$style_three_tol ? -1 : 1);
		
		$get_coed = $this->Post['get_code'];
		
				$ajax_list = $this->Post['ajax_list'];
		
		
		if(empty($uid))
		{
			$this->Messager("请先登录",'index.php?mod=login');
		}
		
		$this->DatabaseHandler->Query("update `" . TABLE_PREFIX . "members` set `style_three_tol`='{$forceup}' where `uid`='{$uid}'");
		
		$condition = "where `uid` ='{$list_uid}'  limit 0,1";
		
		
		$sql = "select `uid`,`medal_id`,`style_three_tol` from `".TABLE_PREFIX."members` where `uid`='{$uid}'  ";
		$query = $this->DatabaseHandler->Query($sql);
		$my_member_info = $query->GetRow();
		
		if($my_member_info['style_three_tol'] == 1)
		{
			$my_member = $this->_member($uid);
		} else{
			$member = $this->_member($list_uid);
		}
		
			

		
		 
		
		$member_medal = $member ? $member : $my_member;
		
		
        $exp_return = user_exp($member_medal['level'],$member_medal['credits']);
		if($exp_return['exp_width'] >= 1){
			$exp_width = $exp_return['exp_width'];
		} else{
			$exp_width = 0;
		}
				$nex_exp_credit  = $exp_return['nex_exp_credit'];		
				$nex_level  = $exp_return['nex_exp_level'];
        
		
				if ($member_medal['medal_id']) {
			$medal_list = $this->_Medal($member_medal['medal_id'],$member_medal['uid']);
		}
		
        $this->Code = $get_coed;
       
       
        if($ajax_list == 'right')
        {
        	include($this->TemplateHandler->Template("topic_right_ajax.inc"));
        }
		else 
		{	
									include($this->TemplateHandler->Template("topic_left.inc"));
		}
	}
	
	
	
	
	function _recommendUser($day=1,$limit=12,$cache_time=0)
	{
		if($limit < 1) return false;

		$time = $day * 86400;
		$cache_time = ($cache_time ? $cache_time : $time / 90);

		if (false === ($list = cache("misc/recommendTopicUser-{$day}-{$limit}",$cache_time))) {
			$dateline = time() - $time;
						$sql = "select DISTINCT(T.uid) AS uid , COUNT(T.tid) AS topics from `".TABLE_PREFIX."topic` T left join `".TABLE_PREFIX."members` M on T.uid=M.uid WHERE T.dateline>=$dateline and M.face!='' GROUP BY uid ORDER BY topics DESC LIMIT {$limit}";
			$query = $this->DatabaseHandler->Query($sql);
			$uids = array();
			while ($row = $query->GetRow())
			{
				$uids[$row['uid']] = $row['uid'];
			}
			$list = array();
			if($uids) {
				$_list = $this->TopicLogic->GetMember($uids,"`uid`,`ucuid`,`username`,`face_url`,`face`,`aboutme`,`validate`,`nickname`");
				foreach ($uids as $uid) {
					if ($uid > 0 && isset($_list[$uid])) {
						$list[$uid] = $_list[$uid];
					}
				}
			}
			cache($list);
		}

		return $list;
	}
	
	
		function _followList($uid,$num=6) 
	{
		
        $uid = max(0,(int) $uid);

		$member_list = array();
		if($uid) 
        {
						$sql = "select `buddyid` as `id` from `".TABLE_PREFIX."buddys` where `uid`='{$uid}' order by `id` desc limit {$num}";
			$query = $this->DatabaseHandler->Query($sql);
			$uids = array();
			while ($row = $query->GetRow())
			{
				$uids[$row['id']] = $row['id'];                
			}
			
			if($uids)
			{
					        	$sql = "select `buddyid` as `id`,remark from `".TABLE_PREFIX."buddys` where `uid`='".MEMBER_ID."' and `buddyid` in(".implode(",",$uids).")";
				$query = $this->DatabaseHandler->Query($sql);
				$buddys = array();
				while ($row = $query->GetRow())
				{
					$buddys[$row['id']] = $row['id'];	
				}
				
					    		$_list = $this->TopicLogic->GetMember($uids);
	    		
	    		foreach ($uids as $_uid) 
	            {
	    			if(isset($_list[$_uid])) 
	                {
	    				$_list[$_uid]['follow_html'] = follow_html2($_uid,isset($buddys[$_uid]));
	                       
	    			    $member_list[$_uid] = $_list[$_uid];
	    		    }
	            }  
			}
        }
 
		return $member_list;
	}
	
	
	
	function _get_buddy($uid)
	{
		$buddyids = array();
		$query = DB::query("SELECT `buddyid`  
							FROM ".DB::table("buddys")." 
							WHERE `uid`='{$uid}'");
		while ($value = DB::fetch($query)) {
			$buddyids[] = $value['buddyid'];
		}
		return $buddyids;
	}
	
	
	function _member($uid=0)
	{
		$member = array();
		if($uid < 1)
        {
			$mod_original = ($this->Post['mod_original'] ? $this->Post['mod_original'] : $this->Get['mod_original']);
			if($mod_original)
			{
				$mod_original = getSafeCode($mod_original);
				$condition = "where `username`='{$mod_original}' limit 1";

				$members = $this->TopicLogic->GetMember($condition);
				if(is_array($members))
                {
					reset($members);
					$member = current($members);
				}
			}
		}
		
		$uid = (int) ($uid ? $uid : MEMBER_ID);
		if($uid > 0 && !$member)
        {
			$member = $this->TopicLogic->GetMember($uid);
		}
		if(!$member)
        {
			return false;
		}
		$uid = $member['uid'];

		if (!$member['follow_html'] && $uid!=MEMBER_ID && MEMBER_ID>0)
        {
			$sql = "select * from `".TABLE_PREFIX."buddys` where `uid`='".MEMBER_ID."' and `buddyid`='{$uid}'";
			$query = $this->DatabaseHandler->Query($sql);
			$member['follow_html'] = follow_html($member['uid'],$query->GetNumRows()>0);
		}

                if(true === UCENTER_FACE && MEMBER_ID == $uid && MEMBER_UCUID > 0 && !($member['__face__']))
        {
            include_once(ROOT_PATH . 'uc_client/client.php');

            $uc_check_result = uc_check_avatar(MEMBER_UCUID);

            if($uc_check_result)
            {
                $this->DatabaseHandler->Query("update ".TABLE_PREFIX."members set `face`='./images/no.gif' where `uid`='{$uid}'");
            }
        }

		return $member;
	}
	
	
	function _check_login()
	{
		if (MEMBER_ID < 1) {
			json_error("你需要先登录才能继续本操作");	
		}
	}
	
	function _Medal($medalid=0,$uid=0)
	{ 
        $uid = (is_numeric($uid) ? $uid : 0);
    	 
        $medal_list = array();
        
        if($uid > 0)
        {
            $sql = "select  U_MEDAL.dateline ,  MEDAL.medal_img , MEDAL.conditions , MEDAL.medal_name ,MEDAL.medal_depict ,MEDAL.id , U_MEDAL.* from `".TABLE_PREFIX."medal` MEDAL left join `".TABLE_PREFIX."user_medal` U_MEDAL on MEDAL.id=U_MEDAL.medalid where U_MEDAL.uid='{$uid}' ";
           
        	$query = $this->DatabaseHandler->Query($sql);  
            while ($row = $query->GetRow())
            {
            	$row['dateline'] = date('m-d日 H:s ',$row['dateline']);
    			$medal_list[$row['id']] = $row;
            }
        }            
        
		return $medal_list;
	}
	
}


?>
