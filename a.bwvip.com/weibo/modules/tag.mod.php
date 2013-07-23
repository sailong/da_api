<?php
/**
 * 文件名：tag.mod.php
 * 版本号：1.0
 * 文件创建时间：2006-9-22 14:46:11
 * 最后修改时间：2008年2月26日15时53分
 * 作者：狐狸<foxis@qq.com>
 * 功能描述：标签操作模块
 */

if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class ModuleObject extends MasterObject
{
	
	var $ID = 0;
	
	var $ModuleConfig;	

	var $TagLogic;
	
	var $Item;	
	var $ItemConfig;
	var $ItemName;
	var $ItemUrl;

	
	function ModuleObject($config)
	{		
		$this->MasterObject($config);
		
		$this->ModuleConfig = ConfigHandler::get('tag');

		$this->ID = (int) ($this->Get['id'] ? $this->Get['id'] : $this->Post['id']);

		$this->Item = isset($this->Get['item']) ? $this->Get['item'] : $this->Post['item'];
		if(false == isset($this->ModuleConfig['item_list'][$this->Item]))
		{
			$this->Item = $this->ModuleConfig['item_default'];
		}
		
		$this->ItemConfig = $this->ModuleConfig['item_list'][$this->Item];
		$this->ItemName = $this->ItemConfig['name'];
		$this->ItemUrl = $this->ItemConfig['url'];
		global $rewriteHandler;
		if($rewriteHandler) $this->ItemUrl = $rewriteHandler->formatURL($this->ItemUrl);
		
		$this->TagLogic = Tag($this->Item);
		$this->CacheConfig = ConfigHandler::get('cache');
		$this->ShowConfig = ConfigHandler::get('show');
		
		Load::logic('topic');
		$this->TopicLogic = new TopicLogic($this);
		
		$this->Execute();
	}

	
	function Execute()
	{
		ob_start();
		if ($this->Code) {
			$this->View();
		} elseif ('mytag' == $this->Code) {
			$this->Mytag();
		} else {
			$this->Main();
		}
		$Contents=ob_get_clean();
		
		$this->ShowBody($Contents);

	}
	
	function Main()
	{	
		
		$timestamp = time();

				$limit = $this->ShowConfig['tag_index']['hot'];
		if ($limit>0 && false == ($tag_list = cache("tag/tag_hot",$this->CacheConfig['tag_index']['hot']))) {	
			$tag_ids = array();
			$tag_list = array();

			$sql = "SELECT DISTINCT(`tag_id`) AS `tag_id`, COUNT(item_id) AS `count` FROM `".TABLE_PREFIX."topic_tag` WHERE dateline>='".($timestamp - 86400*20)."' GROUP BY `tag_id` ORDER BY `count` DESC LIMIT {$limit}";
			$query = $this->DatabaseHandler->Query($sql);
			while ($row = $query->GetRow()) 
			{
				$tag_ids[$row['tag_id']] = $row['tag_id'];
			}
			
			if($tag_ids) {
				
				$sql = "select `id`,`name`,`topic_count`,`status`,`total_count`,`tag_count` from `".TABLE_PREFIX."tag` where id in('".implode("','",$tag_ids)."') order by `topic_count` desc";
				$query = $this->DatabaseHandler->Query($sql);
				while ($row = $query->GetRow()) 
				{
					$tag_list[$row['id']] = $row;
				}
			}
		
			cache($tag_list);
			
		}
		
		
				
		$limit = $this->ShowConfig['tag_index']['guanzhu'];
		if ($limit>0 && false == ($tag_guanzu = cache("tag/tag_guanzu",$this->CacheConfig['tag_index']['guanzhu']))) {		
			$sql = "select `id`,`name`,`topic_count`,`tag_count` from `".TABLE_PREFIX."tag`  ORDER BY `tag_count` DESC LIMIT {$limit}";
			$query = $this->DatabaseHandler->Query($sql);
			$tag_guanzu = array();
			while ($row = $query->GetRow()) 
			{
				$tag_guanzu[$row['id']] = $row;
			}
				cache($tag_guanzu);

		}
	 
	 			$limit = $this->ShowConfig['tag_index']['day7'];
		if ($limit>0 && false == ($tag_r_day7 = cache("tag/tag_r_day7",$this->CacheConfig['tag_index']['day7']))) {			
			$sql = "select `id`,`name`,`topic_count`,`tag_count` from `".TABLE_PREFIX."tag`  WHERE last_post>='".(time() - 86400 * 7)."' GROUP BY `topic_count` DESC LIMIT {$limit}";			
						$query = $this->DatabaseHandler->Query($sql);			
			$tag_r_day7 = array();			
			while ($row = $query->GetRow()) 
			{
				$tag_r_day7[$row['id']] = $row;
			}
		
			cache($tag_r_day7);
		}					
	
				$limit = $this->ShowConfig['tag_index']['day7_guanzhu'];
		if ($limit>0 && false == ($day7_guanzhu = cache("tag/day7_guanzhu",$this->CacheConfig['tag_index']['day7_guanzhu']))) {			

			$sql = "select `id`,`name`,`topic_count`,`tag_count` from `".TABLE_PREFIX."tag`  WHERE `tag_count` > 0 and last_post>='".(time() - 86400 * 7)."' GROUP BY `tag_count` DESC LIMIT {$limit}";			
			$query = $this->DatabaseHandler->Query($sql);		
			$day7_guanzhu = array();			
			while ($row = $query->GetRow()) 
			{
				$day7_guanzhu[$row['id']] = $row;
			}
			
			cache($day7_guanzhu);
			
		}
		
				$limit = $this->ShowConfig['tag_index']['tag_tuijian'];
		if ($limit>0 && false == ($tag_tuijian = cache("tag/tag_tuijian",$this->CacheConfig['tag_index']['tag_tuijian']))) {			
			
			
			
						$sql = "select * from `".TABLE_PREFIX."tag_recommend`  order by `id` desc  Limit {$limit}";			
			$query = $this->DatabaseHandler->Query($sql);		
			$tag_name = array();			
			while ($row = $query->GetRow()) 
			{
				$tag_name[$row['name']] = $row['name'];
			}

						if($tag_name)
			{
				$query = DB::query("SELECT `id`,`name` FROM ".DB::table('tag')." where `name` in ('".implode("','", $tag_name)."') order by `id` desc limit 0,{$limit} ");
				$tag_tuijian = array();
				while ($row = DB::fetch($query)) 
				{
					$tag_tuijian[] = $row;
				}
				
			}
			
			cache($tag_tuijian);
		}
        
		if(MEMBER_STYLE_THREE_TOL == 1)
		{
			if(MEMBER_ID > 0) {
				$member = $this->TopicLogic->GetMember(MEMBER_ID);
	       
				if ($member['medal_id']) {
					$medal_list = $this->TopicLogic->GetMedal($member['medal_id'],$member['uid']);
				}
			}
		}
		
		$this->Title = "话题榜";
		include($this->TemplateHandler->Template('tag_index'));
		
	}

	function View()
	{
		$params = array();
		
		$tag = getSafeCode($this->Code);
		
		if (!$tag) {
			$this->Messager("请输入正确的链接地址",null);
		}
		$sql = "select * from `".TABLE_PREFIX."tag` where `name`='".addslashes($tag)."'";
		$query = $this->DatabaseHandler->Query($sql);
		$tag_info = $query->GetRow();
		
		$tag_id = $tag_info['id'];
		$total_record = $tag_info['topic_count'];
		$tag_count = $tag_info['tag_count'];
		
		Load::logic('topic');
		$TopicLogic = new TopicLogic($this);
		Load::logic("topic_list");
		$TopicListLogic = new TopicListLogic();
		 
		$params['tag_id'] = $tag_id;
		
		$gets = array(
			'mod' =>  $_GET['mod_original'] ? get_safe_code($_GET['mod_original']) : $this->Module,
			'code' => $this->Code ? $tag : "",
			'type' => $this->Get['type'],
			'view' => $this->Get['view'],
		);
		$query_link = "index.php?".url_implode($gets);
		unset($gets['type']);
		$type_url = "index.php?".url_implode($gets);
		$per_page_num = 10;
		$options = array (
			'type' => get_topic_type(),
			'filter' => $this->Get['type'],
		);
		
		$view = trim($this->Get['view']);
		if ($view == 'recd') {
			$p = array(
				'where' => " tr.recd <= 2 AND tr.item='tag' AND tr.item_id='{$tag_id}' ",
				'perpage' => $per_page_num ,
				'filter' => $this->Get['type'],
			);
			$info = $TopicListLogic->get_recd_list($p);
			if (!empty($info)) {
				$total_record = $info['count'];
				$topic_list = $info['list'];
				$page_arr = $info['page'];
			}
		} else {
			 			 if (empty($this->Get['type'])) {
				$page_arr = page($total_record,$per_page_num,$query_link,array('return'=>"Array"));
				$sql = "select `item_id` from `".TABLE_PREFIX."topic_tag` where `tag_id`='{$tag_id}' order by `item_id` desc {$page_arr['limit']}";
				$query = $this->DatabaseHandler->Query($sql);
				$topic_ids = array();
				while ($row = $query->GetRow()) {
					$topic_ids[$row['item_id']] = $row['item_id'];
				}
				$options['tid'] = $topic_ids;
				$options['limit'] = $per_page_num;
			 } else {
			 	$sql = "select `item_id` from `".TABLE_PREFIX."topic_tag` where `tag_id`='{$tag_id}' order by `item_id` desc ";
				$query = $this->DatabaseHandler->Query($sql);
				$topic_ids = array();
				while ($row = $query->GetRow()) {
					$topic_ids[$row['item_id']] = $row['item_id'];
				}
				$options['tid'] = $topic_ids;
				$options['page_url'] = $query_link;
				$options['perpage'] = $per_page_num;
			 }
	
			$info = $TopicListLogic->get_data($options);
			$topic_list = array();
			if (!empty($info)) {
				$topic_list = $info['list'];
				if (isset($info['page'])) {
					$page_arr = $info['page'];
					$total_record = $info['count'];
				}
			}
		}
		
		$topic_list_count = 0;
		if($topic_list) 
		{
			$topic_list_count = count($topic_list);
			
						$parent_list = $TopicLogic->GetParentTopic($topic_list);
					} 
		else 
		{
			$total_record = 0;
		}
	
			
		$show_config = ConfigHandler::get('show');
		$day1_r_tags = cache("misc/recommendTopicTag-1-{$show_config['topic_index']['hot_tag']}",-1,true);
		$day7_r_tags = cache("misc/recommendTopicTag-7-{$show_config['topic_index']['hot_tag']}",-1,true);
		
		$is_favorite = false;
		if($tag_info) {
			if(MEMBER_ID > 0) {
				$sql = "select * from `".TABLE_PREFIX."tag_favorite` where `uid`='".MEMBER_ID."' and `tag`='{$tag}'";
				$query = $this->DatabaseHandler->Query($sql);
				$is_favorite = $query->GetRow();
			}
			
			$sql = "select TF.uid,M.username,M.nickname,M.face_url,M.face,M.ucuid from `".TABLE_PREFIX."tag_favorite` TF left join `".TABLE_PREFIX."members` M on M.uid=TF.uid where TF.tag='{$tag}' order by TF.id desc limit 12";
			$query = $this->DatabaseHandler->Query($sql);
			$tag_favorite_list = array();
			while ($row = $query->GetRow()) 
			{
				$row['face'] = face_get($row);	
				$tag_favorite_list[] = $row;
			}		
		}
		
		$my_favorite_tags = $this->_myFavoriteTags(12);
		
		$tag_extra = array();
		if($tag_info && $tag_info['extra'])
		{
			Load::logic('tag_extra');
			$TagExtraLogic = new TagExtraLogic();
			
			$tag_extra_info = $TagExtraLogic->get_info($tag_info['id']);
			$tag_extra = $tag_extra_info['data'];
		}
		
		
		$_GET['searchKeyword'] = $this->Title = $tag;
		$this->MetaKeywords = $tag;
		
        $content = "#{$tag}#";
        
        
		
				if(MEMBER_ID > 0) 
				{
					$member = $this->TopicLogic->GetMember(MEMBER_ID);
		       
				if ($member['medal_id']) {
					$medal_list = $this->TopicLogic->GetMedal($member['medal_id'],$member['uid']);
				}
				}
		  
			
		include($this->TemplateHandler->Template('tag_list_topic_box'));
		
	}		

	function _topicListBox($data)
	{
		Load::logic('topic');
		$TopicLogic = new TopicLogic($this);
		foreach ($data  as $row)
		{
			$row = $TopicLogic->Make($row);
			
			$topic_list[]=$row;
		}

		include($this->TemplateHandler->Template('tag_list_topic_box'));
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
	
	
function _myFavoriteTags($limit=12)
{
	$uid = MEMBER_ID;
	
	$sql = "select * from `".TABLE_PREFIX."tag_favorite` where `uid`='{$uid}' order by `id` desc limit {$limit} ";
	$query = $this->DatabaseHandler->Query($sql);
	$list = $query->GetAll();
	
	return $list;
}
	
}

?>