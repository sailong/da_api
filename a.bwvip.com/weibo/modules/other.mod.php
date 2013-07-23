<?php

/*******************************************************************

 * [JishiGou] (C)2005 - 2099 Cenwor Inc.

 *

 * This is NOT a freeware, use is subject to license terms

 *

 * @Package JishiGou $

 *

 * @Filename other.mod.php $

 *

 * @Author http://www.jishigou.net $

 *

 * @Date 2011-09-28 19:16:47 193627468 447890055 26914 $

 *******************************************************************/




/**
 * other.mod.php
 * 版本号：1.0
 * 最后修改时间：2009年9月28日 14时10分42秒
 * 作者：狐狸<foxis@qq.com>
 * 功能描述: 网站杂项，其他模块
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
		
		$this->CacheConfig = ConfigHandler::get('cache');
		
		$this->ShowConfig = ConfigHandler::get('show');
		
		$this->InfoConfig = ConfigHandler::get('web_info');
		
		$this->Execute();
		
	}
		
			
	function Execute()
	{
		ob_start();
		if ('wap' == $this->Code) {
			$this->Wap();
		} elseif ('test' == $this->Code) {
			$this->Test();
		}  elseif ('about' == $this->Code) {
			$this->About();
		} elseif ('contact' == $this->Code) {
			$this->Contact();
		} elseif ('joins' == $this->Code) {
			$this->Joins();
		} elseif ('media' == $this->Code) {
			$this->Media();
		} elseif ('groupdelete' == $this->Code) {
			$this->GroupDelete();
		} elseif ('vip_intro'==$this->Code) {
			$this->VipIntro();
		} elseif ('medal'==$this->Code) {
			$this->Medal();
		} elseif ('notice'==$this->Code) {
			$this->Notice();
		} elseif ('checkmedal'==$this->Code) {
			$this->CheckMedal();
		} elseif ('media_more'==$this->Code) {
			$this->Media_More();
		} elseif ('add_favor_tag'==$this->Code) {
			$this->addFavoriteTag();
		} elseif ('regagreement' == $this->Code) {
						$this->regagreement();
		} elseif ('seccode' == $this->Code) {
			$this->Seccode();
		} elseif ('navigation' == $this->Code) {
			$this->Navigation();
		} elseif ('usergroup' == $this->Code) {
			$this->UserGroupList();
		} elseif ('qmd' == $this->Code) {
			$this->Qmd();
		} else {
			$this->Main();
		}		
		$body=ob_get_clean();
		
		$this->ShowBody($body);
	}
    
    function Main()
    {
        $this->Messager("页面不存在",null);
    }
	
	function Test() 
	{
		exit;	
		
	}

	
		function _Media()
	{	
		
		if($this->Get['ids']){
			$where = " where `id` = {$this->Get['ids']} ";
			$per_page_num = $this->ShowConfig['media_view']['user'];	
			$query_link = "index.php?mod=" . ($_GET['mod_original'] ? get_safe_code($_GET['mod_original']) : $this->Module) . ($this->Code ? "&amp;code={$this->Code}&ids={$this->Get['ids']}" : "");
		}
		else{
			$per_page_num = $this->ShowConfig['media']['user'];	
		}
		
		$sql = "select `id`,`media_name`,`media_count` from `".TABLE_PREFIX."media` {$where} order by `id` desc";
		$query = $this->DatabaseHandler->Query($sql);
		$media_list = array();
		$media_ids = array();
		while ($row = $query->GetRow()) 
		{ 
			$media_ids[$row['id']] = $row['id'];	
			$media_list[] = $row;	
		}
		
				$sql = " select count(*) as `total_record` from `".TABLE_PREFIX."members` where `media_id` in ('".implode("','",$media_ids)."')";
		$query = $this->DatabaseHandler->Query($sql);
		extract($query->GetRow());
	
				
		$page_arr = page ($total_record,$per_page_num,$query_link,array('return'=>'array',));		
		
		$sql = "select `uid`,`ucuid`,`media_id` ,`nickname` ,`username` ,`face_url`, `face` from `".TABLE_PREFIX."members` where `media_id` in ('".implode("','",$media_ids)."') order by `uid` desc {$page_arr['limit']}";
		$query = $this->DatabaseHandler->Query($sql);
		$member_media = array();
		while ($row = $query->GetRow()) 
		{	
			$row['face'] = face_get($row);
			
			$member_media[] = $row;
		}
		
				$sql = "select  `uid`,`buddyid` from  `".TABLE_PREFIX."buddys` where uid='".MEMBER_ID."' ";
		$query = $this->DatabaseHandler->Query($sql);
		$buddys_set = array();
		while ($row = $query->GetRow()) 
		{
			$buddys_set[] = $row['buddyid'];
		}	
		
		$this->Title = "媒体汇 ";
		include($this->TemplateHandler->Template('media'));
		
	}

    
	function Media() 
	{
				$sql = "select `id`,`media_name`,`media_count` from `".TABLE_PREFIX."media`  order by `order` asc";		
				$query = $this->DatabaseHandler->Query($sql);
		$media_list = array();
		$media_ids = array();
		while ($row = $query->GetRow()) 
		{ 
			$media_ids[$row['id']] = $row['id'];	
			$media_list[] = $row;	
		}
		
				$limit = $this->ShowConfig['media']['user'];
		
				$media_user = array();
		foreach ($media_list as $row) 
		{
			
			$user_media_id = $row['id'];

			$where_list['media_id'] = build_like_query('media_id',$user_media_id);
			$where = ' where '.implode(' AND ',$where_list).'order by `topic_count` desc limit 0,'.$limit;
	
			$_list = $this->TopicLogic->GetMember($where,"`uid`,`ucuid`,`media_id`,`username`,`aboutme`,`nickname`,`face_url`,`face`,`validate`");		
			foreach ($_list as $row) {
				
				$row['validate_html'] = $row['validate_html'];
				$media_user[] = $row;
			}
			
		}

		
		
	  if(MEMBER_STYLE_THREE_TOL == 1)
	   {
			$member = $this->TopicLogic->GetMember(MEMBER_ID);
		 	if ($member['medal_id']) {
				$medal_list = $this->TopicLogic->GetMedal($member['medal_id'],$member['uid']);
			}
		}
		
		$this->Title = "媒体汇 ";
		include($this->TemplateHandler->Template('media'));
	}
	
		function Media_More() 
	{
		$ids = (int) $this->Get['ids'];
		
		$media_info = DB::fetch_first("SELECT `id`,`media_name` FROM ".DB::table('media')." WHERE id='{$ids}'");
		
		
				$sql = "select `id`,`media_name`,`media_count` from `".TABLE_PREFIX."media`  order by `id` desc";		
		$query = $this->DatabaseHandler->Query($sql);
		$media_list = array();
		$media_ids = array();
		while ($row = $query->GetRow()) 
		{
			$media_ids[$row['id']] = $row['id'];	
			$media_list[] = $row;	
		}
		
		$per_page_num = 15;	
		$query_link = "index.php?mod=" . ($_GET['mod_original'] ? get_safe_code($_GET['mod_original']) : $this->Module) . ($this->Code ? "&amp;code={$this->Code}&ids={$this->Get['ids']}" : "");
	
				$sql = " select count(*) as `total_record` from `".TABLE_PREFIX."members` where `media_id` = '{$ids}'";
		$query = $this->DatabaseHandler->Query($sql);
		extract($query->GetRow());
	
				$page_arr = page ($total_record,$per_page_num,$query_link,array('return'=>'array',));		
		
		$where = " where `media_id` = '{$ids}' order by `topic_count` desc {$page_arr['limit']} ";

		$member_list = $this->TopicLogic->GetMember($where,"`uid`,`ucuid`,`media_id`,`aboutme`,`username`,`nickname`,`face_url`,`face`,`validate`");		
						
									

	   if(MEMBER_STYLE_THREE_TOL == 1)
	   {
			$member = $this->TopicLogic->GetMember(MEMBER_ID);
		 	if ($member['medal_id']) {
				$medal_list = $this->TopicLogic->GetMedal($member['medal_id'],$member['uid']);
			}

		}
		$this->Title = "媒体汇 用户";
		include($this->TemplateHandler->Template('media_more'));
		
		
	}

    function Wap()
  {		
  	if (!$this->Config['wap']) {
  		$this->Messager("{$this->Config['site_name']}的手机访问功能还未开启",null);
  	}
  	
		$this->Title = "手机访问 {$this->Config['site_name']}";
		$this->MetaKeywords = "手机访问,wap,{$this->Config['site_name']}";
		$this->MetaDescription = $this->Title."，可登录、查看、发微博、评论转发等";
		
  	   		if(MEMBER_STYLE_THREE_TOL)
		{
			$member = $this->TopicLogic->GetMember(MEMBER_ID);
						if ($member['medal_id']) {
				$medal_list = $this->TopicLogic->GetMedal($member['medal_id'],$member['uid']);
			}
		}
		include($this->TemplateHandler->Template('topic_wap'));
		
  }
  
    function About()
  {		
  	$this->Title = "关于我们";
  	
  	if(MEMBER_STYLE_THREE_TOL == 1)
	{
		$member = $this->TopicLogic->GetMember(MEMBER_ID);
	}
  	include($this->TemplateHandler->Template('topic_about'));
	
  }
  
   
	
		function Medal()
	{
		$act_list = array('share'=>'分享到微博','qmd'=>'签名档',);
		
		$act_list['show'] = array('name'=>'微博秀','link_mod'=>'show','link_code'=>'show',);    

        if($this->Config['qqwb_enable'] && qqwb_init($this->Config))
        {
            $act_list['qqwb'] = 'QQ微博';
        }                
        $act_list['imjiqiren'] = 'QQ机器人';        
		if ($this->Config['sina_enable'] && sina_weibo_init($this->Config))
		{
			$act_list['sina'] = '新浪微博';
		}
        if('qqrobot'==$this->Code && !isset($act_list['qqrobot']) && isset($act_list['imjiqiren']))
        {
            $this->Code = 'imjiqiren';
        }
        $act_list['medal'] = array('name'=>'勋章','link_mod'=>'other','link_code'=>'medal',);  
        $act_list['sms'] = '短信';       
		$act = isset($act_list[$this->Code]) ? $this->Code : 'share';
		
		$uid = MEMBER_ID;
		
				$member = $this->TopicLogic->GetMember(MEMBER_ID);
		
		
		$medalid = explode(",",$member['medal_id']);
		
				$sql = "select * from `".TABLE_PREFIX."medal`";
		$query = $this->DatabaseHandler->Query($sql);
		$medallist = array();
		while($row = $query->GetRow())
		{   
			$medallist[] = $row;
		}

		if ($member['medal_id']) {
			$medal_list = $this->_Medal($member['medal_id'],$member['uid']);
		}

				$sql = "select * from `".TABLE_PREFIX."user_medal` where `uid` = '".MEMBER_ID."'";
		$query = $this->DatabaseHandler->Query($sql);
		$user_medal = array();
		while($row = $query->GetRow())
		{ 
			$user_medal[] = $row['medalid'];
		}
	
		$this->Title = "{$this->Config['site_name']}勋章";
		include($this->TemplateHandler->Template('topic_medal'));
	}
	
	
		function CheckMedal()
	{ 
		$dateline = time();
		$medalid = (int)$this->Get['mids'];
		
				$sql = " select * from `".TABLE_PREFIX."members` Where  `uid` = ".MEMBER_ID." ";
		$query = $this->DatabaseHandler->Query($sql);
		$members=$query->GetRow();
		
				$sql = " select * from `".TABLE_PREFIX."medal` Where `id` = {$medalid}";
		$query = $this->DatabaseHandler->Query($sql);
		$medal_list=$query->GetRow();
		$medal_value = @unserialize($medal_list['conditions']);
				
		define('QUERY_SAFE_DACTION_3', true);
				if($medal_value['type'] == 'topic')
		{
						$sql ="select * from (select * from `".TABLE_PREFIX."topic` where `uid` = '".MEMBER_ID."' and `type` = 'first' order by `dateline` desc) a group by `uid` ";					
			$query = $this->DatabaseHandler->Query($sql);
			$topic_list=$query->GetRow();
		
			if($topic_list){
				$return = $this->_chackmdealday($topic_list['dateline'],$medal_value['day'],'first');
			} else{
				$this->Messager("未达成获取勋章的条件",-1);
			}
			
		}
		
				if($medal_value['type'] == 'reply')
		{
			$sql = " select * from `".TABLE_PREFIX."topic` Where `type` = 'reply' and `uid` = ".MEMBER_ID." order by 'dateline' desc limit 0,1";			
			$query = $this->DatabaseHandler->Query($sql);
			$reply_list=$query->GetRow();
			
			if($reply_list){
				$return = $this->_chackmdealday($reply_list['dateline'],$medal_value['day'],'reply');
			} else{
				$this->Messager("未达成获取勋章的条件",-1);
			}
		}
		
				if ($medal_value['type'] == 'invite') {
			
			$sql = " select count(*) as `total_record` from `".TABLE_PREFIX."invite` Where `uid` = '".MEMBER_ID."' and `fuid` > '0' ";
			$query = $this->DatabaseHandler->Query($sql);
			extract($query->GetRow());
			
			if ($medal_value['invite'] > $total_record) {
				$this->Messager("未达成获取勋章的条件",-1);
			} else{
				$return =  true;			
			}
		}
		
		
		
				if($medal_value['type'] == 'tag')
		{ 
			$tag = trim($medal_value['tagname']);
			
			$sql = " select `id`,`name` from `".TABLE_PREFIX."tag` Where `name` = '{$tag}' ";
			$query = $this->DatabaseHandler->Query($sql);
			$tags=$query->GetRow();
			
			if($tags)
			{
				$sql = " select `item_id`,`tag_id` from `".TABLE_PREFIX."topic_tag` Where `tag_id` = {$tags['id']} ";
				$query = $this->DatabaseHandler->Query($sql);
				$topicids = array();
				while($row=$query->GetRow())
				{
					$topicids[$row['item_id']] = $row['item_id'];
				}
			}
			if($topicids)
			{
				$sql = " select `tid`,`uid`,`content` from `".TABLE_PREFIX."topic` where `tid` in ('".implode("','",$topicids)."') and `uid` = '".MEMBER_ID."' limit 0,1";
				$query = $this->DatabaseHandler->Query($sql);
				$topiclist=$query->GetRow();
			}
			if($topiclist){
				$return = 1;
			}
			else{
				$this->Messager("未达成获取勋章的条件",-1);
			}
			
		}	
		 
				if($return == 1)
		{  
			$sql = " select * from `".TABLE_PREFIX."user_medal` where `medalid` = '{$medalid}' and `uid` = '".MEMBER_ID."' limit 0,1";
			$query = $this->DatabaseHandler->Query($sql);
			$user_medal=$query->GetRow();
			if($user_medal)
			{
				$this->Messager("已经获得该勋章，不要重复操作",-1);
			}
						$sql = "insert into `".TABLE_PREFIX."user_medal` (`uid`,`nickname`,`medalid`,`dateline`) values ('{$members['uid']}','{$members['nickname']}','{$medalid}','{$dateline}')";
			$query = $this->DatabaseHandler->Query($sql);
			
			
			if(!empty($members['medal_id']))
			{
				         		$sql = "select * from `".TABLE_PREFIX."user_medal` where `uid` = '{$members['uid']}'";
        		$query = $this->DatabaseHandler->Query($sql);
        		$user_medal_id = array();
        		while ($row = $query->GetRow()) {
        			$user_medal_id[] = $row['medalid'];
        		}
        		
        		$user_medal_id = implode(",",$user_medal_id);
			}
			
			$user_medal = $user_medal_id ? $user_medal_id : $medalid;

		
						$sql = "update `".TABLE_PREFIX."members` set  `medal_id`='{$user_medal}'  where `uid` = ".MEMBER_ID."";	
			$update = $this->DatabaseHandler->Query($sql);
			
						$sql = "update `".TABLE_PREFIX."medal` set  `medal_count`=`medal_count`+1  where `id` = '{$medalid}'";	
			$this->DatabaseHandler->Query($sql);
			
			
			$this->Messager("成功点亮",'index.php?mod=other&code=medal');
		}		
		else
		{
			$this->Messager("未达成获取勋章的条件",-1);
		}	
	
	}
	
		function _chackmdealday($date_time=0,$chackday=0,$check_type='')
	{		
	
			$topic_start_time = time() - (86400 * $chackday);
		
			$endtime = time();	

			$sql = " select `dateline`,`tid` from `".TABLE_PREFIX."topic` Where `dateline` >= '{$topic_start_time}' and `dateline` <= {$endtime} and `type` = '{$check_type}' and `uid` = ".MEMBER_ID." order by 'dateline' desc ";					
			$query = $this->DatabaseHandler->Query($sql);
			$topic_date =array();
			while ($row = $query->GetRow()) 
			{
				$topic_date[] = date("Ymd",$row['dateline']);
			}

			
			for ($j = 0; $j < count($topic_date); $j++) 
			{
				if($topic_date[$j] == $topic_date[$j+1])
				{
					unset($topic_date[$j+1]);	
				}
			}
	
			$user_topic_date = array_unique($topic_date);
			$user_topic_date = implode(',',$user_topic_date);
			$user_topic_date = explode(',',$user_topic_date);	
			sort($user_topic_date);
			
	
			if(count($user_topic_date) < $chackday)
			{
				$this->Messager("未达成获取勋章的条件",-1);
			}
			
			
		
		  if($chackday > 1)
		  {	
		  	   for($i=0; $i < count($user_topic_date) - 1  ; $i++)
			   { 
			 	  
					if($user_topic_date[$i] + 1 != $user_topic_date[$i+1])
					{
						$this->Messager("未达成获取勋章的条件",-1);
					}
				}
			
			
				return true;	
			 	
		  }		 
		  		  elseif($user_topic_date)
		  { 
		  	return true;
		  }
		  else
		  {
		  	$this->Messager("未达成获取勋章的条件",-1);
		  }
   } 

	 	function GroupDelete()
	{
		$gid = (int) $this->Get['gid'];
		
		$sql = "select `id`,`uid` from `".TABLE_PREFIX."group` where `id` ='{$gid}'";
		$query = $this->DatabaseHandler->Query($sql);
		$user_group = $query->GetRow();
			
		if($user_group['uid'] != MEMBER_ID)
		{
		  $this->Messager('分组不存在','index.php',0);
		}	
	
		$sql = "delete from `".TABLE_PREFIX."group` where `id`='{$gid}' and `uid` =".MEMBER_ID;
	    $this->DatabaseHandler->Query($sql);		
	  
	  	$sql = "delete from `".TABLE_PREFIX."groupfields` where `gid`='{$gid}'";
	  	$this->DatabaseHandler->Query($sql);	
	
		$this->Messager(NULL,'index.php?mod='.MEMBER_NAME.'&code=follow',0);
	}

   
    function Notice()
  {
  	$ids = (int) $this->Get['ids'];
  	
  	  	if($ids)
  	{
	  	$sql="Select * From ".TABLE_PREFIX.'notice'." Where id = '{$ids}' ";
		$query = $this->DatabaseHandler->Query($sql);
		$view_notice=$query->GetRow();
		
		$title		 =  $view_notice['title'];
		$content  =  $view_notice['content'];
		$dateline =  my_date_format2($view_notice['dateline']);
		
		  		$sql="select `id`,`title` from ".TABLE_PREFIX.'notice'." order by `dateline` desc  ";
    	$query = $this->DatabaseHandler->Query($sql);
    	$list_notice = array();
    	while ($row = $query->GetRow())
    	{	
    		
    		$row['titles'] 	= cutstr($row['title'],26);
    		$list_notice[] 	= $row;
    	}
    	
		$this->Title = "网站公告 - {$view_notice['title']}";
	}
	else{

    	    	$this->Title = '网站公告';
   		
    	$per_page_num = $this->ShowConfig['notice']['list'] ? $this->ShowConfig['notice']['list'] : 10;	
		$query_link = "index.php?mod=" . ($_GET['mod_original'] ? get_safe_code($_GET['mod_original']) : $this->Module) . ($this->Code ? "&amp;code={$this->Code}" : "");
		
		    	$sql = " select count(*) as `total_record` from `".TABLE_PREFIX."notice`";
		$query = $this->DatabaseHandler->Query($sql);
		extract($query->GetRow());
    	
				$page_arr = page ($total_record,$per_page_num,$query_link,array('return'=>'array',));
		
    	$sql="select `id`,`title` from ".TABLE_PREFIX.'notice'." order by `dateline` desc {$page_arr['limit']} ";
    	$query = $this->DatabaseHandler->Query($sql);
    	$list_notice = array();
    	while ($row = $query->GetRow())
    	{	
    		$row['titles'] 	= cutstr($row['title'],26);
    		$list_notice[] 	= $row;
    	}
	}

	include($this->TemplateHandler->Template('view_notice'));
	
  }
  
 	function Contact()
    {
    	$this->Title = "联系我们";
    	
    	if(MEMBER_STYLE_THREE_TOL == 1)
		{
			$member = $this->TopicLogic->GetMember(MEMBER_ID);
		}
    	include($this->TemplateHandler->Template('topic_about'));
    }
	
    function Joins()
    {
      	$this->Title = "加入我们";
      	
    	if(MEMBER_STYLE_THREE_TOL == 1)
		{
			$member = $this->TopicLogic->GetMember(MEMBER_ID);
		}
		
      	include($this->TemplateHandler->Template('topic_about'));  	
    }
	
	
    function VipIntro()
    {
    	$this->Title = "{$this->Config['site_name']}身份验证";
    	include($this->TemplateHandler->Template('topic_vip'));
    }
  
	function Navigation()
    {
    	
    	$slide_config = ConfigHandler::get('navigation');
        $slide_list = $slide_config['list'];
    	
    	
    	include($this->TemplateHandler->Template('test_navigation'));
    }
    

    
    
	function regagreement()
  	{
  		$this->Title = '用户使用协议';
		include(template('regagreement'));
	}
	
	
	function Seccode()
	{
		Load::lib('seccode');
		$seccode = mkseccode();
		$cookie = Obj::registry('CookieHandler');
		$cookie->setVar('seccode', authcode($seccode, 'ENCODE'));
		$s = new Seccode();
		$s->code = $seccode;
		$s->datapath = ROOT_PATH."images/seccode/";
		$s->display();
	}
	
	
	
	
	function UserGroupList() 
	{
		if(MEMBER_ID < 0)
		{
			$this->Messager('请先登录','index.php',0);
		}
		$member = $this->TopicLogic->GetMember(MEMBER_ID);
		
		
		$per_page_num = 15;	
		$query_link = "index.php?mod=" . ($_GET['mod_original'] ? get_safe_code($_GET['mod_original']) : $this->Module) . ($this->Code ? "&amp;code={$this->Code}&ids={$this->Get['ids']}" : "");
	
				$sql = " select count(*) as `total_record` from `".TABLE_PREFIX."group` where `uid` = '".MEMBER_ID."'";
		$query = $this->DatabaseHandler->Query($sql);
		extract($query->GetRow());
	
				$page_arr = page ($total_record,$per_page_num,$query_link,array('return'=>'array',));		
		
		$where = " where `uid` = '".MEMBER_ID."' order by `id` desc {$page_arr['limit']} ";

		$sql = " select * from `".TABLE_PREFIX."group` {$where} ";
		$query = $this->DatabaseHandler->Query($sql);
		$grouplist = array();		
		while ($row = $query->GetRow()) 
		{
			$grouplist[] = $row;
		}
		
		$this->Title = '管理分组';		
		include($this->TemplateHandler->Template('group'));
		
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
	
	function Qmd() 
	{
				
		$uid = (int) $this->Get['ids'];
		
		if ($uid < 1) {
			$this->Messager('请先登录', 'index.php?mod=login');
		}
		
		$member = $this->_member($uid);
		
		Load::logic('other');	        
        $OtherLogic = new OtherLogic();
    		
        if($this->Config['is_qmd'])
        {
        	
        	$image_path =  $this->Config['qmd_file_url']. face_path($uid);
			$image_name = $uid . "_o.gif";
			$image_file = $this->Config['site_url'] .'/'.$image_path . $image_name;

	    	$member_qmd_img = $this->Config['ftp_on'] ? $member['qmd_url'] : $this->Config['site_url'].'/'.$member['qmd_url'];    	
	    	$member_qmd = $member['qmd_img'] ? $member['qmd_img'] : 'images/qmd.jpg';
    		$qmd_return = $OtherLogic->qmd_list($uid,$member_qmd);
			 
        }
        else
        {
        	$image_file = $this->Config['site_url'] .'/images/qmd_error.gif';
        }
        
		$image_type = array_pop(explode('.',$image_file));
		header("Content-type:".$image_type);
		$filecontent = file_get_contents($image_file);
		echo $filecontent;
		die;
		
	}
}

?>
