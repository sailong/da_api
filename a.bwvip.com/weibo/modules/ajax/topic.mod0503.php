<?php
/**
 * 文件名：topic.mod.php
 * 版本号：1.0
 * 最后修改时间：2009年9月28日 14时10分42秒
 * 作者：狐狸<foxis@qq.com>
 * 功能描述: 微博话题AJAX模块
 */

if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class ModuleObject extends MasterObject
{
	var $TopicLogic;

	var $ID;


	function ModuleObject($config)
	{
		$this->MasterObject($config);

		$this->initMemberHandler();

		Load::logic('topic');
		$this->TopicLogic = new TopicLogic($this);

		$this->ID = (int) ($this->Post['id'] ? $this->Post['id'] : $this->Get['id']);

		$this->Execute();
	}

	
	function Execute()
	{
        ob_start();

		switch($this->Code)
		{
			case 'favor_fenlei':
				$this->favoriteFenlei();
				break;
			case 'favor_event':
				$this->favoriteEvent();
				break;
			case 'favorite_tag':
				$this->FavoriteTag();
				break;
			case 'group_list':
				$this->GroupList();
				break;
			case 'group_menu':
				$this->Group_Menu();
				break;
			case 'forward_menu':
				$this->Forward_Menu();
				break;
		  case 'usermenu':
				$this->UserMenu();
				break;
			case 'tag_menu':
				$this->Tag_Menu();
				break;
			case 'delete_image':
				$this->DeleteImage();
				break;
			case 'delete_video':
				$this->DeleteVideo();
				break;
			case 'delete_music':
				$this->DeleteMusic();
				break;
			case 'follow':
				$this->Follow();
				break;
			case 'favor':
				$this->Favorite();
				break;
			case 'favor_tag':
				$this->FavoriteTag();
				break;
			case 'upload':
				$this->Upload();
				break;
			case 'dovideo':
				$this->DoVideo();
				break;
			case 'do_add':
				$this->DoAdd();
				break;
			case 'delete':
				$this->Delete();
				break;
			case 'list_reply':
				$this->ListReply();
				break;
			case 'forward':
				$this->Do_forward();
				break;
			case 'view_comment':
				$this->ViewComment();
				break;
			case 'create_group':
		  		$this->Create_Group();
		  		break;
			case 'do_group':
				$this->Do_Group();
				break;
			case 'group_fields':
				$this->Group_fields();
				break;
		  	case 'del_group':
				$this->Del_Group();
				break;
		  case 'do_fansgroup':
		  		$this->Do_FansGroup();
		  		break;
		 
		  case 'del_fansgroup':
		  		$this->Del_FansGroup();
		  		break;
		  case 'set_fansgroup':
		  		$this->Set_FansGroup();
		  		break;
		  case 'do_setfansgroup':
		  		$this->Do_SetFansGroup();
		  		break;
		  case 'create_fansgroup':
		  		$this->Create_FansGroup();
		  		break;
		  case 'fansgrouplist':
		  		$this->FansGroupList();
		  		break;
			case 'doreport':
				$this->DoReport();
				break;
			case 'remark':
				$this->Remark();
				break;
		    case 'add_remark':
				$this->Add_Remark();
				break;
		  	case 'add_user_follow':
				$this->Add_User_Follow();
				break;
			case 'follower_choose':
				$this->Follower_choose();
				break;
			case 'doblacklist':
				$this->DoAddMyBlackList();
				break;
			case 'do_delmyblacklist':
				$this->DoDelMyBlackList();
				break;
			case 'modifytopic':
				$this->ModifyTopic();
				break;
			case 'do_modifytopic':
				$this->Do_ModifyTopic();
				break;
			case 'uploadface':
				$this->UploadFace();
				break;
			case 'topicshow':
				$this->TopicShow();
				break;
		  	case 'user_tag':
				$this->User_Tag();
				break;
			case 'del_tag':
				$this->Del_Tag();
				break;
				break;
			case 'pmfriends':
				$this->PmFriends();
				break;
			case 'open_mdeal':
				$this->Open_Mdeal_Index();
				break;
			case 'qmd':
				$this->Qmd();	
				break;
		    case 'do_delmyfans':
				$this->DoDelMyFans();	
				break;
				
					    case 'recd':
				$this->recd();	
				break;
		    case 'do_recd':
		    	$this->do_recd();
		    	break;
					    case 'editarea':
		    	$this->editErea();
		    	break;
		    	
		    		    case 'publishsuccess':
		    	$this->publishSuccess();
		    	break;
		    	
			case 'list':
			case 'tag':
			case 'myhome':
			case 'mycomment':
			case 'mylastpublish':
			case 'updatecurrent':
			case 'myat':
			case 'myblog':
			case 'tocomment':
			case 'myfavorite':
			case 'favoritemy':
			case 'groupview':
				$this->DoList();
				break;
			
			default:
				$this->Main();
				break;
						case 'reg_follow_user':
				$this->Do_Reg_Follow_User();	
				break;
						case 'showlogin':
				$this->ShowLogin();	
				break;
						case 'add_favor_tag':
				$this->addFavoriteTag();	
				break;
						case 'modify_user_signature':
				$this->Modify_User_Signature();	
				break;
		}

        response_text(ob_get_clean());
	}

	function Main()
	{

		response_text("正在建设中……");
	}
	
		function editErea(){
		$province = $this->Get['province'];
		if($province){
			$province_name = $this->DatabaseHandler->ResultFirst("select name from ".TABLE_PREFIX."common_district where id = $province");
		}
		
		$city = $this->Get['city'];
		if($city){
			$city_name = $this->DatabaseHandler->ResultFirst("select name from ".TABLE_PREFIX."common_district where id = $city");
		}
		
		$area = $this->Get['area'];
		if($area){
			$area_name = $this->DatabaseHandler->ResultFirst("select name from ".TABLE_PREFIX."common_district where id = $area");
		}
		
		$street = $this->Get['street'];
		if($street){
			$street_name = $this->DatabaseHandler->ResultFirst("select name from ".TABLE_PREFIX."common_district where id = $street");
		}
		
		$this->DatabaseHandler->Query("update ".TABLE_PREFIX."members set province = '$province_name' , city = '$city_name' , area = '$area_name' , street = '$street_name' where uid = ".MEMBER_ID);
		echo $province_name." ".$city_name;
	}
	
	
	function DoList()
	{
		extract($this->Get);
		extract($this->Post);
		$options = array();
		if(($per_page_num = (int) ConfigHandler::get('show','topic',$this->Code)) < 1) {
			$per_page_num = 20;
		}
		if(isset($uid)) $uid = (int) $uid;
		$topic_parent_disable = false;
		
		$start = max(0, (int) $start);
		$limit = "limit {$start},{$per_page_num}";
		$next = $start + $per_page_num;

		$tag_id = (int) $tag_id;
		if ($tag_id > 0) {
			$sql = "select `item_id` from `".TABLE_PREFIX."topic_tag` where `tag_id`='{$tag_id}' order by `item_id` desc {$limit}";
			$query = $this->DatabaseHandler->Query($sql);
			$topic_ids[0] = 0;
			while ($row = $query->GetRow())
			{
				$topic_ids[$row['item_id']] = $row['item_id'];
			}
			$options['tid'] = $topic_ids;
		}
		
		$options['perpage'] = $per_page_num;
				$tpl = 'topic_list_ajax';
		if ('myhome' == $this->Code) {
            $topic_myhome_time_limit = 0;
            if($this->Config['topic_myhome_time_limit'] > 0)
            {
                $topic_myhome_time_limit = (time() - ($this->Config['topic_myhome_time_limit'] * 86400));
                
                if($topic_myhome_time_limit > 0)
                {
                    $options['dateline'] = $topic_myhome_time_limit;
                }
            }

			if($is_personal) {
              
                $_where_add = '';
                if($topic_myhome_time_limit > 0)
                {
                    $_where_add = " and `buddy_lastuptime`>'$topic_myhome_time_limit' ";
                }
                
				$sql = "select `buddyid` from `".TABLE_PREFIX."buddys` where `uid`='".MEMBER_ID."' $_where_add ";

			  	$query = $this->DatabaseHandler->Query($sql);
				$topic_uids[0] = 0;
				while($row = $query->GetRow())
				{
					$topic_uids[$row['buddyid']] = $row['buddyid'];
				}
			}
			$topic_uids[$uid] = $uid;
			$options['uid'] = $topic_uids;
		} else if('myat' == $this->Code) {
			$uid = MEMBER_ID;
			$sql = "select * from `".TABLE_PREFIX."topic_mention` where `uid`='".MEMBER_ID."'";
			$query = $this->DatabaseHandler->Query($sql);
			$topic_ids = array();
			while ($row = $query->GetRow())
			{
				$topic_ids[$row['tid']] = $row['tid'];
			}
			$options['tid'] = $topic_ids;
		} else if ('groupview' == $this->Code) {
			$gid = (int) $gid;
			
						$sql = "select * from `".TABLE_PREFIX."groupfields` where `gid`='{$gid}' and uid = ".MEMBER_ID." ";
			$query = $this->DatabaseHandler->Query($sql);
			$g_view_uids = array();
			$list = array();
			while ($row = $query->GetRow()) {
				$g_view_uids[$row['touid']] = $row['touid'];
				$groupname = $row['g_name'];
				$groupid = $row['gid'];
			}
			
						if ($g_view_uids) {
				$options['uid'] = $g_view_uids;
			} else {
				exit();
			}
		} else if ('mylastpublish' == $this->Code) {

			$topic_list = $this->TopicLogic->Get(" where `uid`='".MEMBER_ID."' order by `tid` desc limit 0,1 ");
            
						$no_from = false;
									$ref_mod = $this->Post['ref_mod'];
			$ref_code = $this->Post['ref_code'];
			$no_from = $this->_no_from($ref_mod, $ref_code);
			
            $topic_list_get = true;
            
            $no_mBlog_linedot2 = true;
		} else if ('updatecurrent' == $this->Code) {
						$tid = intval($this->Post['tid']);
			if (empty($tid)) {
				exit;
			}
			
			$refcode = trim($this->Post['refcode']);
			$refmod = trim($this->Post['refmod']);
			$tpl = 'topic_item_ajax';
			if ('topic' == $refmod && 'myfavorite' == $refcode) {
				$sql = "SELECT TF.dateline as favorite_time , T.* 
						FROM ".DB::table("topic_favorite")." AS TF 
						LEFT JOIN ".DB::table("topic")." AS T 
						ON T.tid=TF.tid where T.tid='{$tid}'";
				$this->Code = $refcode;
				$topic_parent_disable = true;
			} else {
				$sql = "SELECT * FROM ".DB::table('topic')." WHERE tid='{$tid}'";
			}
			
			$data = DB::fetch_first($sql);
			if (empty($data)) {
				exit;
			}
			if (isset($data['favorite_time'])) {
				$data['favorite_time'] = my_date_format2($data['favorite_time']);
			}
			$val = $this->TopicLogic->Make($data);
			$topic_list[] = $val;
			
			$no_from = false;
						if ('vote' == $refmod && 'view' == $refcode) {
				$no_from = true;
			}
			
						if ($refmod == 'qun') {
				$this->Module = 'qun';
			}
			
						if ($refcode == 'reply_list_ajax') {
				$tpl = 'topic_comment_item';
				$topic_parent_disable = true;
				$v = $val;
			}
			
			$topic_list_get = true;
		} else if ('mycomment' == $this->Code) {

			$options['where'] = " `touid`='".MEMBER_ID."' ";

		} elseif ('tocomment' == $this->Code) {			
			$title = '我评论的';
			$topic_selected = 'tocomment';
			$options['where'] = " `uid` = '".MEMBER_ID."' and `type` in ('both','reply') ";

		} elseif ('myblog' == $this->Code) {
			$options['uid'] = $uid;

		} else if ('myfavorite' == $this->Code) {
			$uid = MEMBER_ID;

			$sql = "select TF.dateline as favorite_time , T.* from `".TABLE_PREFIX."topic_favorite` TF left join `".TABLE_PREFIX."topic` T on T.tid=TF.tid where TF.uid='{$uid}' order by TF.id desc {$limit}";
			$query = $this->DatabaseHandler->Query($sql);
			while ($row = $query->GetRow())
			{
				if($row['tid']<1) continue;

				$row['favorite_time'] = my_date_format2($row['favorite_time']);

				$row = $this->TopicLogic->Make($row);

				$topic_list[$row['tid']] = $row;
			}
			$topic_list_get = true;

		} else if ('favoritemy' == $this->Code) {
			$uid = MEMBER_ID;

			$sql = "select TF.dateline as favorite_time , TF.tuid , T.* from `".TABLE_PREFIX."topic_favorite` TF left join `".TABLE_PREFIX."topic` T on T.tid=TF.tid where TF.tuid='{$uid}' order by TF.id desc {$limit}";
			$query = $this->DatabaseHandler->Query($sql);
			$tuids = array();
			while ($row = $query->GetRow())
			{
				if($row['tid']<1) continue;

				$row['favorite_time'] = my_date_format2($row['favorite_time']);
				$row = $this->TopicLogic->Make($row);
				$topic_list[$row['tid']] = $row;
				$tuids[$row['tuid']] = $row['tuid'];
			}
			$topic_members = $this->TopicLogic->GetMember($tuids,"`uid`,`ucuid`,`username`,`nickname`,`face_url`,`face`,`validate`");

			$topic_parent_disable = true;
			$topic_list_get = true;

		}
		
		if (!$topic_list_get) {
			Load::logic("topic_list");
			$TopicListLogic = new TopicListLogic();
			$info = $TopicListLogic->get_data($options);
			$topic_list = array();
			$total_record = 0;
			if (!empty($info)) {
				$topic_list = $info['list'];
				$total_record = $info['count'];
				$page_arr = $info['page'];
			}
		}
		
		$topic_list_count = 0;
		if($topic_list) {
			$topic_list_count = count($topic_list);

			if(!$topic_parent_disable) {
								$parent_list = $this->TopicLogic->GetParentTopic($topic_list);
							}
		}
		
		include($this->TemplateHandler->Template($tpl));
	}
	
		function _no_from($ref_mod, $ref_code = '')
	{
		$no_from = true;
		if ($ref_mod == 'topic' || $ref_mod == 'qun') {
			$no_from = false;
		}
		return $no_from;
	}
	
	

	function DoAdd()
	{

		if (MEMBER_ID < 1) {
			response_text("请先登录或者注册");
		}

				if($this->MemberHandler->HasPermission($this->Module,$this->Code)==false)
		{
			 response_text("您的角色(禁言组)没有发布的权限");
		}

		$content = trim(strip_tags($this->Post['content']));
		
		if (!$content) {
			response_text("请输入内容");
		}

				$topic_type = $this->Post['topictype'];
		
		
		if('both' == $topic_type){
			$type = 'both';
		} elseif('reply' == $topic_type){
			$type = 'reply';
		} elseif('qun' == $topic_type){
			$type = 'qun';
		} elseif ('personal' == $topic_type) {
			$type = 'personal';
		} elseif (is_numeric($topic_type)) {
			$type = 'first';
		} else{
			$type = 'first';
		}     
        
		$totid = max(0, (int) $this->Post['totid']);

		$fuid = max(0, (int) $this->Post['fuid']);
		$imageid = trim($this->Post['imageid']);
		
		$videoid = max(0, (int) $this->Post['videoid']);
		
		$longtextid = max(0, (int) $this->Post['longtextid']);
		
				$from = trim($this->Post['from']);
		
		
		
		
		$item = trim($this->Post['item']);
		$item_id  = intval(trim($this->Post['item_id']));
		if (!empty($item_id)) {
						Load::functions('app');
			$ret = app_check($item, $item_id);
			if (!$ret) {
				$item = '';
				$item_id = 0;
			}
		} else {
			$item = '';
			$item_id = 0;
		}
		$data = array( 
			'content' => $content,
			'fuid'=>$fuid,
			'totid'=>$totid,
			'imageid'=>$imageid,
			'videoid'=>$videoid,
			'from'=>empty($from) ? 'web' : $from,
			'type'=>$type,
		
						'item' => $item,
			'item_id' => $item_id,
		
						'longtextid' => $longtextid,
		);
		
		$return = $this->TopicLogic->Add($data);
		
		if (is_array($return) && $return['tid'] > 0) {
			
			$r = $this->Post['r'];

			$is_huifu = $this->Post['is_huifu'];

			$return_reply = $this->Post['return_reply'];

			if($totid > 0 && $r) {

				if('vc' == $r) {
					if($is_huifu == 'is_huifu') $return_reply = 'is_huifu';
					$this->ViewComment($return['totid'],$return['tid'],$return_reply);
				} elseif ('rl' == substr($r,0,2)) {
					$_GET['page'] = 999999999;
					$this->ListReply(((is_numeric(($tti=substr($r,3))) && $tti > 0) ? $tti : $return['totid']),$return['tid']);
				} elseif (in_array($r,array('tohome','lt','myblog','myhome','tagview',))) {
					exit;
				}
			}
		} else {		  
			$return = (is_string($return) ? $return : (is_array($return) ? implode("",$return) : "未知错误"));
			response_text("[发布失败]{$return}");
		}
	}

	function Delete()
	{
		$tid = (int) ($this->Post['tid'] ? $this->Post['tid'] : $this->Get['tid']);
		
		if ($tid < 1) {
			js_alert_output("请指定一个您要删除的话题");
		}
		$topic = $this->TopicLogic->Get($tid);
		if (!$topic) {
			js_alert_output("话题已经不存在了");
		}
		if ($topic['uid']!=MEMBER_ID && 'admin'!=MEMBER_ROLE_TYPE) {
			js_alert_output("您无权删除该话题");
		}

		$return = $this->TopicLogic->Delete($tid);

        response_text($return . $this->js_show_msg());
	}

	function ViewComment($tid=0,$highlight=0,$return_reply='')
	{
		$limit = max(0, (int) ConfigHandler::get('show', 'topic_one_comment', 'list'));
		if($limit < 1)
		{
			$limit = 6;
		}
		$highlight = ($highlight ? $highlight : $this->Request['highlight']);
		$_GET['highlight'] = $highlight;

		$tid = max(0,(float) ($tid ? $tid : $this->Request['tid']));

		if($tid > 0)
		{
			$topic_info = $this->TopicLogic->Get($tid);

            $reply_list = array();
            if($topic_info)
            {
                                $tids = array();
				if($return_reply ==  'is_huifu')
				{
				    $topic_info['roottid'] = max(0, (int) $topic_info['roottid']);
				    if($topic_info['roottid'] > 0)
                    {
                        $topic_info = $this->TopicLogic->Get($topic_info['roottid']);
    					$tids = $this->TopicLogic->GetReplyIds($topic_info['tid']);
                    }
				}
				elseif ($topic_info['replys'] > 0)
				{
					$tids = $this->TopicLogic->GetReplyIds($tid);
				}

                if($tids)
                {
                    rsort($tids);

    				$condition = "where `tid` in('".implode("','",array_slice((array) $tids,0,$limit))."')  order by `dateline` desc limit {$limit}";
    				$reply_list = $this->TopicLogic->Get($condition);
    				
    				$r_parent_list = $this->TopicLogic->GetParentTopic($reply_list, 1);
                }
            }
		}

		include($this->TemplateHandler->Template('topic_view_comment_ajax'));
	}

		function ModifyTopic()
	{
		$tid = $modify_tid = max(0, (int) $this->Post['tid']);
		if($tid < 1)
		{
			js_alert_output('微博ID 错误');
		}
		$types = ($this->Post['types'] ? $this->Post['types'] : $this->Get['types']);
		$handle_key = ($this->Post['handle_key'] ? $this->Post['handle_key'] : $this->Get['handle_key']);
		
		
				$types = $this->Post['types'];
		
		$topiclist = $this->TopicLogic->Get($modify_tid);
		
		if(!$topiclist)
		{
			response_text('您要编辑的微博已经不存在了');
		}		

		
				if(MEMBER_ROLE_TYPE != 'admin')
		{
			if(MEMBER_ID != $topiclist['uid'])
	        {
	            response_text("您没有权限编辑该微博");
	        }
        
    		if($topiclist['replys'] >= 1 || $topiclist['forwards'] >= 1 )
    		{
    			response_text("微博已被评论或者转发,不能编辑");
    		}
    		
				        if($this->Config['topic_modify_time'] && (($topiclist['addtime'] ? $topiclist['addtime'] : $topiclist['dateline']) + ($this->Config['topic_modify_time'] * 60) < time()))
	        {
	        	response_text("微博已超出可编辑时间了");
	        }
		}
        

        
                $row = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."topic where `tid`='$tid'");
        $topiclist['content'] = ($row['content'] . $row['content2']);
        
        		$topiclist['content'] = strip_tags($topiclist['content']);	
				if('both'==$topiclist['type'] || 'forward'==$topiclist['type'])
		{
			$topiclist['content'] = $this->TopicLogic->GetForwardContent($topiclist['content']);
		}
		
		
		
		include($this->TemplateHandler->Template('modify_topic_ajax'));

	}

		function Do_ModifyTopic()
	{
	    if(MEMBER_ID < 1)
        {
            js_alert_output("请先登录或者注册一个帐号");
        }

		$tid = max(0, (int) $this->Post['tid']);

		if($tid < 1)
		{
			js_alert_output("微博ID不能为空");
		}
		
		
		$topiclist = $this->DatabaseHandler->FetchFirst("select * from `".TABLE_PREFIX."topic` where `tid`='{$tid}'");
		
        if(!$topiclist)
        {
            js_alert_output("您要编辑的内容已经不存在了");
        }
		
	
				if(MEMBER_ROLE_TYPE != 'admin')
		{
			if(MEMBER_ID != $topiclist['uid'])
	        {
	            js_alert_output("您没有权限编辑该微博");
	        }
        
    		if($topiclist['replys'] >= 1 || $topiclist['forwards'] >= 1 )
    		{
    			js_alert_output("微博已被评论或者转发,不能编辑");
    		}
    		
				        if($this->Config['topic_modify_time'] && (($topiclist['addtime'] ? $topiclist['addtime'] : $topiclist['dateline']) + ($this->Config['topic_modify_time'] * 60) < time()))
	        {
	        	js_alert_output("微博已超出可编辑时间了");
	        }
		}
		
        
		$content = strip_tags($this->Post['content']);
	
					
				if(empty($content))
		{
		 	js_alert_output("微博内容不能为空");
		}
		
		
				if('both'==$topiclist['type'] || 'forward'==$topiclist['type'])
		{
			$content = $this->TopicLogic->GetForwardContent($content);
		}
		
		
		$imageid = $this->Post['imageid'];
		
		$return = $this->TopicLogic->Modify($tid,$content,$imageid);
		        		
            
		if(is_array($return))
		{		      
			$topic_list = $this->TopicLogic->Get($tid);
		}
		else
		{
			js_alert_output("【编辑失败】{$return}");
		}
	}


	function ListReply($tid=0,$highlight=0)
	{
		$per_page_num = 10;
		$tid = max(0,(float) ($tid? $tid : $this->Post['tid']));

		if ($tid < 1)
        {
			response_text("[链接参数错误]不存在的地址");
		}
		$highlight = ($highlight ? $highlight : $this->Request['highlight']);
		$_GET['highlight'] = $highlight;

		$topic_info = $this->TopicLogic->Get($tid);
		
				if ($topic_info['type'] == 'reply') {
			$roottid = $topic_info['roottid'];
			$root_type = DB::result_first("SELECT type FROM ".DB::table('topic')." WHERE tid='{$roottid}'");
		} else {
			$root_type = $topic_info['type'];
		}
		
		if (!$topic_info)
        {
			response_text("您要查看的话题已经不存在了");
		}

        $reply_list = array();
		if ($topic_info['replys'] > 0)
        {
			$total_record = $topic_info['replys'];
			$_config = array
            (
				'return' => 'array',
				'extra' => 'onclick="replyList(this.title);return false;"',
				'var' => 'p',
			);
			$page_arr = page($total_record,$per_page_num,"index.php?mod=topic&code={$topic_info['tid']}",$_config);

            $tids = array();
			$tids = $this->TopicLogic->GetReplyIds($topic_info['tid']);
            if($tids)
            {
                krsort($tids);
    			$condition = "where `tid` in ('".implode("','",array_slice((array) $tids,$page_arr['offset'],$per_page_num))."') order by `dateline` asc limit {$per_page_num}";

    			$reply_list = $this->TopicLogic->Get($condition);
    			
    			    			$r_parent_list = $this->TopicLogic->GetParentTopic($reply_list, 1);
            }
            
		}

		include($this->TemplateHandler->Template('topic_reply_list_ajax'));
	}

	function Upload()
	{

		$error_msg = '';
		if (MEMBER_ID < 1) {
			$error_msg = "请先登录或者注册";
		} else {
			$field = 'topic';
			if (empty($_FILES) || !$_FILES[$field]['name']) {
				$error_msg = "请设置图片";
			} else {
				$timestamp = time();

				$uid = $this->Post['touid'] ? $this->Post['touid'] : MEMBER_ID;
				$username = $this->Post['tousername'] ? $this->Post['tousername'] : MEMBER_NAME;
				$sql = "insert into `".TABLE_PREFIX."topic_image`(`uid`,`username`,`dateline`) values ('{$uid}','{$username}','{$timestamp}')";
				$query = $this->DatabaseHandler->Query($sql);
				$image_id = $this->DatabaseHandler->Insert_ID();

				if ($image_id < 1)
				{
					js_alert_output('图片上传失败');
				}

				Load::lib('io');
				$IoHandler = new IoHandler();

				$image_path = RELATIVE_ROOT_PATH . 'images/' . $field . '/' . face_path($image_id);
				$image_name = $image_id . "_o.jpg";
				$image_file = $image_path . $image_name;
				$image_file_small = $image_path.$image_id . "_s.jpg";
				if (!is_dir($image_path)) 
                {
					$IoHandler->MakeDir($image_path);
				}

				Load::lib('upload');
				$UploadHandler = new UploadHandler($_FILES,$image_path,$field,true);
				$UploadHandler->setMaxSize(2048);
				$UploadHandler->setNewName($image_name);
				$result=$UploadHandler->doUpload();

				if($result) 
                {
					$result = is_image($image_file);
				}

				if(false == $result) {
					$IoHandler->DeleteFile($image_file);
					$sql = "delete from `".TABLE_PREFIX."topic_image` where `id`='{$image_id}'";
					$this->DatabaseHandler->Query($sql);

					$error_msg = implode(" ",(array) $UploadHandler->getError());
				} else {
					
					$this->_removeTopicImage($image_id);

					list($image_width,$image_height,$image_type,$image_attr) = getimagesize($image_file);

					$result = makethumb(
						$image_file,
						$image_file_small,
						min($this->Config['thumbwidth'],$image_width),
						min($this->Config['thumbwidth'],$image_height),
						$this->Config['maxthumbwidth'],
						$this->Config['maxthumbheight']
					);
					if (!$result && !is_file($image_file_small)) 
                    {
						@copy($image_file,$image_file_small);
					}

										if($this->Config['watermark_enable']) 
                    {
                        $arr = @getimagesize($image_file);
                        if($arr && 'image/gif' != $arr['mime'] && 'image/png' != $arr['mime'])
                        {
                            $this->_watermark($image_file,$this->Config['site_url'] . "/" . MEMBER_NAME);
                        }
					}

					$image_size = filesize($image_file);
					$name = addslashes($_FILES[$field]['name']);
                    
                    
                                        $site_url = '';
                    if($this->Config['ftp_on'])
                    {
                        $site_url = ConfigHandler::get('ftp','attachurl');
                        
                        $ftp_result = ftpcmd('upload',$image_file);
                        if($ftp_result > 0)
                        {
                            ftpcmd('upload',$image_file_small);
                            
                            $IoHandler->DeleteFile($image_file);
                            $IoHandler->DeleteFile($image_file_small);
                            
                            $image_file_small = $site_url . '/' . $image_file_small; 
                        }                        
                    }
                    
                    
					$sql = "update `".TABLE_PREFIX."topic_image` set `site_url`='{$site_url}', `photo`='{$image_file}' , `name`='{$name}' , `filesize`='{$image_size}' , `width`='{$image_width}' , `height`='{$image_height}' where `id`='{$image_id}'";
					$this->DatabaseHandler->Query($sql);
                    

					if($this->Get['type'] == 'modify')
					{

						echo "<script language='Javascript'>";
						echo "parent.modifyimgId={$image_id};";
						echo "parent.document.getElementById('imageids').value={$image_id};";
						echo "parent.document.getElementById('modify_img_upload').style.display='block';";
						echo "parent.document.getElementById('modify_viewImg').innerHTML='".cut_str($name,14)."';";
						echo "parent.document.getElementById('modify_add_img').style.display='none';";
						echo "</script>";

					}
					else
					{
						echo "<script language='Javascript'>";
						echo "parent.imgId={$image_id};";
						echo "parent.document.getElementById('uploading').innerHTML='';";
						echo "parent.document.getElementById('publisher_image_form').style.display='none';";
						echo "parent.document.getElementById('insertImgDiv').style.display='none';";
						echo "parent.document.getElementById('uploading').style.display='none';";
						echo "parent.document.getElementById('viewImgDiv').style.display='block';";
						echo "parent.document.getElementById('viewImg').innerHTML='".cut_str($name,14)."';";
						echo "parent.document.getElementById('img_pre').src='{$image_file_small}';";
						echo "if(''==parent.document.getElementById('i_already').value){parent.document.getElementById('i_already').value='分享图片';}";
						echo "parent.document.getElementById('publishSubmit').disabled=false;";
						echo "parent.document.getElementById('i_already').focus();";
						echo "</script>";

					}

				}
			}
		}

		if ($error_msg) {
			echo "<script language='Javascript'>";
			echo "alert('{$error_msg}');";
			echo "</script>";
		}

	}

	function _removeTopicImage($id=0)
	{
		Load::lib('io');
		$IoHandler = new IoHandler();

		$sql = "select * from ".TABLE_PREFIX."topic_image where `tid`<1" . ($id>0?" and `id`<'".($id - 10)."'":"");
		$query = $this->DatabaseHandler->Query($sql);
		while ($row = $query->GetRow())
		{
			$IoHandler->DeleteFile(topic_image($row['id'],'small'));
			$IoHandler->DeleteFile(topic_image($row['id'],'original'));
		}
	}
	
		function Create_Group()
	{
		if (MEMBER_ID < 1) {
			js_alert_output('请先登录或者注册');
		}
		include(template('topic_group_create_ajax'));
	}


		function Do_Group()
	{

		if (MEMBER_ID < 1) {
			js_alert_output('请先登录或者注册');
		}

		$uid = MEMBER_ID;
		$group_name = $this->Post['group_name'];
		$gid = $this->Post['gid'];
		$touid = $this->Post['touid'];
		
		if(empty($group_name)){
				js_alert_output('分组不能为空');
		}
		if (preg_match('~[\~\`\!\@\#\$\%\^\&\*\(\)\=\+\[\{\]\}\;\:\'\"\,\<\.\>\/\?]~',$group_name)) {
        js_alert_output('分组不能包含特殊字符');
		}
				if(($filter_msg = filter($group_name)))  $response = $filter_msg;

       $sql="SELECT * FROM ".TABLE_PREFIX.'group'." WHERE `group_name`='{$group_name}' and `uid` ='{$uid}' limit 0,1";
	     $query = $this->DatabaseHandler->Query($sql);
			 $row = $query->GetRow();
		if($row)
		{
        js_alert_output($group_name.' 分组已经存在');
		}
		if($this->Post['act'] == 'modify' )
		{
			$sql = "update `".TABLE_PREFIX."group` set `group_name`='{$group_name}'  where `id`='{$gid}'";
			$this->DatabaseHandler->Query($sql);

			$sql = "update `".TABLE_PREFIX."groupfields` set `g_name`='{$group_name}'  where `uid` ='{$uid}' and `gid`='{$gid}'";
			$this->DatabaseHandler->Query($sql);

			$sql="SELECT * FROM ".TABLE_PREFIX.'group'." WHERE id='{$gid}' ";
			$query = $this->DatabaseHandler->Query($sql);
			$group_view=$query->GetRow();


			include($this->TemplateHandler->Template('modify_group_ajax'));


		} else {

			$sql = "insert into `".TABLE_PREFIX."group`(`uid`,`group_name`) values ('".MEMBER_ID."','{$group_name}')";
			$query = $this->DatabaseHandler->Query($sql);
			$group_id = $this->DatabaseHandler->Insert_ID();

			if($this->Post['act'] == 'add')
			{
				  echo "<script language='Javascript'>";
					echo "window.location.href='index.php?mod=topic&code=group&gid={$group_id}';";
					echo "</script>";
					exit;
			}

			$sql="SELECT * FROM ".TABLE_PREFIX.'group'." WHERE id='{$group_id}' and `uid` = '{$uid}' ";
			$query = $this->DatabaseHandler->Query($sql);
			$group_list[] = $query->GetRow();
			
			if ($this->Post['act'] == 'menu_add') {
				include($this->TemplateHandler->Template('topic_group_add_item'));
			} else {
        		include($this->TemplateHandler->Template('topic_group_ajax'));
			}

		}

	}



		function Group_fields()
	{
    $uid   = MEMBER_ID;
		$g_id  =  $this->Post['gid'];
		$touid =  $this->Post['touid'];

		$sql="SELECT * FROM ".TABLE_PREFIX.'group'." WHERE uid =".MEMBER_ID." and id=".$g_id;
		$query = $this->DatabaseHandler->Query($sql);
		$group_info=$query->GetRow();

				$sql="SELECT `uid` FROM ".TABLE_PREFIX.'members'." WHERE uid=".$touid;
		$query = $this->DatabaseHandler->Query($sql);
		$member_info=$query->GetRow();

				$sql="SELECT `touid`,`display` FROM ".TABLE_PREFIX.'groupfields'." WHERE touid ='{$touid}' and gid=".$g_id;
		$query = $this->DatabaseHandler->Query($sql);
		$fields_info=$query->GetRow();


		if(empty($fields_info['display']))
		{
						$sql = "insert into `".TABLE_PREFIX."groupfields`(`uid`, `touid`,`gid`,`g_name`,`display`) values ('".MEMBER_ID."','{$member_info['uid']}','{$group_info['id']}','{$group_info['group_name']}','1')";
			$query = $this->DatabaseHandler->Query($sql);

		}
		else
		{
						$sql = "delete from `".TABLE_PREFIX."groupfields` where `touid`='{$touid}' and gid = '{$g_id}'";
		  $this->DatabaseHandler->Query($sql);
		}

				$sql = "select count(*) as group_count from `".TABLE_PREFIX."groupfields` where `uid`='{$uid}' and `gid`='{$g_id}'";
		$query = $this->DatabaseHandler->Query($sql);
		extract($query->GetRow());
		$sql = "update `".TABLE_PREFIX."group` set `group_count`='{$group_count}'  where `uid`='{$uid}' and `id`='{$g_id}'";
		$this->DatabaseHandler->Query($sql);

	}


	
		function Group_Menu()
	{
		if (MEMBER_ID < 1) {
            js_alert_output('请先登录或者注册');
		}
		$uid = MEMBER_ID;
		$timestamp = time();

				$userid = trim($this->Get['to_user']);
		
				$sql = "select  `nickname` from  `".TABLE_PREFIX."members` where uid= '{$userid}' limit 0,1";
		$query = $this->DatabaseHandler->Query($sql);
		$member = $query->GetRow();
		
				$buddys = DB::fetch_first("SELECT remark FROM ".DB::table('buddys')." WHERE uid='{$uid}' AND buddyid='{$userid}'");


				$sql = "select  GF.touid , GF.g_name , GF.display , G.* from `".TABLE_PREFIX."group` G left join `".TABLE_PREFIX."groupfields` GF on G.id=GF.gid where G.uid='".MEMBER_ID." ' ";
		$query = $this->DatabaseHandler->Query($sql);
		$group_list = array();

		while ($row = $query->GetRow())
		{
			$group_list[$row['id']] = $row;
		}

				$sql = "select  `uid`,`gid`,`touid` from  `".TABLE_PREFIX."groupfields` where uid='".MEMBER_ID."' and touid= '{$userid}' ";
		$query = $this->DatabaseHandler->Query($sql);
		$group_set = array();

		while ($row = $query->GetRow())
		{
			$group_set[] = $row['gid'];
		}

		$val["uid"]=$userid;


		include($this->TemplateHandler->Template('topic_group_menu'));

	}

		function GroupList()
	{
		$userid = trim($this->Post['touid']);

		$sql = "select GF.gid,GF.g_name  from  `".TABLE_PREFIX."groupfields` GF  where GF.uid='".MEMBER_ID."'  and GF.touid='$userid' ";
		$query = $this->DatabaseHandler->Query($sql);
		$user_group = array();
		while ($row = $query->GetRow())
		{
			echo '<a href="index.php?mod=topic&code=follow&gid='.$row['gid'].'">[ '.$row['g_name']." ]".'</a> ';
		}

	}
	
		function Remark() {
		$uid = (int) $this->Get['uid'];
		
		$buddy_info = $this->DatabaseHandler->FetchFirst("select * from `".TABLE_PREFIX."buddys` where `buddyid`='{$uid}' and `uid`=".MEMBER_ID); 
		
		include($this->TemplateHandler->Template('topic_remark_ajax'));
	}
	
		function Add_Remark()
	{   
				$remark  =  $this->Post['remark'];
		
		
        		$buddyid =  (is_numeric($this->Post['buddyid']) ? $this->Post['buddyid'] : 0);
        if($buddyid < 1)
        {
            response_text('请指定一个好友ID');           
        }
               
        $buddy_info = $this->DatabaseHandler->FetchFirst("select * from `".TABLE_PREFIX."buddys` where `buddyid`='{$buddyid}' and `uid`=".MEMBER_ID); 
        
        if(!$buddy_info)
        {
            response_text('你的好友已经不存在了');
        }
		
		if (false != ($filter_msg = filter($remark)))
        {
            response_text($filter_msg);
        }
        
		if ($remark && preg_match('~[\~\`\!\@\#\$\%\^\&\*\(\)\=\+\[\{\]\}\;\:\'\"\,\<\.\>\/\?]~',$remark)) 
        {
            response_text('不能包含特殊字符');
		}
        
		if($remark != $buddy_info['remark'])
        {
            $sql = "update `".TABLE_PREFIX."buddys` set `remark`='{$remark}'  where `buddyid`='{$buddyid}' and `uid` =".MEMBER_ID." ";
            $return = $this->DatabaseHandler->Query($sql); 
        }
        
        	}
	

	
		function DoDelMyFans()
	{	
	    $buddyid = MEMBER_ID;
	    
	     	    $touid = (int) $this->Post['touid'];
	    
				$is_black = $this->Post['is_black'];		

	    if($is_black)
	    {
	    				$this->_AddBlackList($buddyid,$touid,'add');
	    }
	    
		$sql = "select * from `".TABLE_PREFIX."buddys` where `uid`='{$touid}' and `buddyid`='{$buddyid}' limit 1";
		$query = $this->DatabaseHandler->Query($sql);
		$row = $query->GetRow();
		if ($row) 
		{
			$sql = "delete from `".TABLE_PREFIX."buddys` where `uid`='{$touid}' and `buddyid`='{$buddyid}'";
			$this->DatabaseHandler->Query($sql);
            
			update_my_fans_follow_count($touid);
			update_my_fans_follow_count($buddyid);
			
		}
	
		include(template('topic_fans'));
	   
	}

		function Follow()
	{
		
		$uid = MEMBER_ID;
		$response = '';
		$timestamp = time();
		
				$follow_button = $this->Post['follow_button'];
		
	
		if($uid < 1) js_show_login('登录后才能执行此操作');
		
		if($uid == $this->ID) js_alert_output('您不能关注自己');

		$member = $this->TopicLogic->GetMember($this->ID);
		

		if (!$member) 
		{
			js_alert_output('TA已消失不见了');
		} 
		else 
		{	
						
			if($member['disallow_beiguanzhu'])
            {
                js_alert_output('此用户禁止被关注');
			}
			
			
					
			$sql = "select `gid` from `".TABLE_PREFIX."groupfields` where `touid`='{$this->ID}' and `uid`='".MEMBER_ID."'";
			$query = $this->DatabaseHandler->Query($sql);
			$groupfields = array();
			while ($row = $query->GetRow())
			{
				$groupfields[$row['gid']] = $row['gid'];
			}

			if($groupfields){
				
			    				$sql = "update `".TABLE_PREFIX."group` set `group_count`=if(`group_count`>1,`group_count`-1,0) where `id` in(".implode(",",$groupfields).")";
				$this->DatabaseHandler->Query($sql);

								$sql = "delete from `".TABLE_PREFIX."groupfields` where `touid`='{$this->ID}' and `uid`='".MEMBER_ID."'";
				$this->DatabaseHandler->Query($sql);
			}
			
			
			
			
			
			
			
			
						$sql = "select * from `".TABLE_PREFIX."buddys` where `uid`='{$uid}' and `buddyid`='{$member['uid']}' limit 1";
			$query = $this->DatabaseHandler->Query($sql);
			$row = $query->GetRow();
			
			if ($row) 
			{
								
				$sql = "delete from `".TABLE_PREFIX."buddys` where `uid`='{$uid}' and `buddyid`='{$member['uid']}'";
				$this->DatabaseHandler->Query($sql);
                
                if($this->Config['extcredits_enable'] && MEMBER_ID>0)
				{
					
					update_credits_by_action('buddy_del',MEMBER_ID);
				}

								$sql = "delete from `".TABLE_PREFIX."groupfields` where `touid`='{$this->ID}' and `uid`='".MEMBER_ID."'";
				$this->DatabaseHandler->Query($sql);
				
								if($follow_button == 'xiao'){
					$response = follow_html2($this->ID,0,false);
				} else{
					$response = follow_html($this->ID,0,false);
				}
			} 
			else 
			{
			    				
								$sql = "select `uid`,`touid` from `".TABLE_PREFIX."blacklist` where `uid` = '{$this->ID}' and `touid`='{$uid}' ";
				$query = $this->DatabaseHandler->Query($sql);
				$blacklist = $query->GetRow();
				if($blacklist)
				{
				    js_alert_output('无法关注，对方已将你拉入黑名单');
				}

								$sql = "insert into `".TABLE_PREFIX."buddys` (`uid`,`buddyid`,`dateline`,`buddy_lastuptime`) values ('{$uid}','{$member['uid']}','{$timestamp}','{$timestamp}')";
				$this->DatabaseHandler->Query($sql);
				
								if($follow_button == 'xiao'){
					$response = follow_html2($this->ID,1,false);
				} else{
					$response = follow_html($this->ID,1,false);
				}
					
				
                
				if($this->Config['imjiqiren_enable'] && imjiqiren_init($this->Config))
				{
					imjiqiren_send_message($member,'f',$this->Config);
				}

				if($this->Config['sms_enable'] && sms_init($this->Config))
				{
					sms_send_message($member,'f',$this->Config);
				}

				if($this->Config['extcredits_enable'] && MEMBER_ID>0)
				{
					
					$update_credits = false;
					if($member['username'])
					{
						$update_credits = update_credits_by_action(("_U".crc32($member['username'])),MEMBER_ID);
					}
					if(!$update_credits)
					{
						
						update_credits_by_action('buddy',MEMBER_ID);
					}
				}
				
			}

						update_my_fans_follow_count($uid);
			update_my_fans_follow_count($member['uid']);
		}

		$response .= $this->js_show_msg();
        $response .= '<success></success>';
		response_text($response);
	}
    
	
	

		function UserMenu()
	{
		extract($this->Get);
		extract($this->Post);
		
		if($this->Post['nickname'])
		{
			$sql = " select `uid`,`nickname` from `".TABLE_PREFIX."members`  where  `nickname` = '{$this->Post['nickname']}'";
			$query = $this->DatabaseHandler->Query($sql);
			$member = $query->GetRow();
		}
		
	 	$uids = $this->Post['uid'] ? $this->Post['uid'] : $member['uid'];
	 		 	$uids = (is_numeric($uids) ? $uids : 0);

		if($uids > 0) 
		{
			$buddysid = array();
			if(MEMBER_ID > 0) 
			{
				$sql = "select `buddyid` as `id`,`remark`,`buddyid` from `".TABLE_PREFIX."buddys` where `uid`='".MEMBER_ID."' and `buddyid` = ".$uids;
				$query = $this->DatabaseHandler->Query($sql);
				while (false != ($row = $query->GetRow()))
				{
					$buddysid[$row['id']] = $row['id'];
					$remark_name = $row['remark'];
				}
			}
		}
		else
		{
			exit;
		}

		$list_members = $this->TopicLogic->GetMember($uids,"`uid`,`ucuid`,`medal_id`,`username`,`nickname`,`face`,`fans_count`,`topic_count`,`validate`,`aboutme`,`province`,`city`,`level`");
		
		$list_members['aboutme'] = cut_str($list_members['aboutme'],54);
		$follow_html = $list_members[$uids]['follow_html'] = follow_html($uids,isset($buddysid[$uids]));

				$sql = "select `uid`,`touid` from `".TABLE_PREFIX."blacklist` where `touid`='{$uids}' and `uid` = '".MEMBER_ID."'";
		$query = $this->DatabaseHandler->Query($sql);
		$blackList = $query->GetRow();

				$sql = "select * from `".TABLE_PREFIX."user_tag_fields` where `uid` = '{$uids}'";
		$query = $this->DatabaseHandler->Query($sql);
		$usertag=$query->GetAll();
		
				$sql="SELECT `uid`,`buddyid`,`remark`  FROM ".TABLE_PREFIX.'buddys'." where `uid` = '".MEMBER_ID."' and `buddyid` ='{$uids}'";
    	$query = $this->DatabaseHandler->Query($sql);
    	$buddys = $query->GetRow();
    	
		include($this->TemplateHandler->Template('topic_user_menu'));

	}

		function Follower_choose()
	{
		extract($this->Get);
		extract($this->Post);

		$touid = (int) $this->Post['uid'] ? $this->Post['uid'] : $this->Get['uid'];
		
		if($touid)
		{
			$sql = "select `uid`,`ucuid`,`nickname`,`username`,`signature` from `".TABLE_PREFIX."members` where `uid`='{$touid}' ";
			$query = $this->DatabaseHandler->Query($sql);
			$members = $query->GetRow();
		}
		
				Load::lib('form');
		$FormHandler = new FormHandler();
		$query = $this->DatabaseHandler->Query("select * from ".TABLE_PREFIX."common_district where upid = 0 order by list");
		while ($rsdb = $query->GetRow()){
			$province[$rsdb['id']]['value']  = $rsdb['id'];
			$province[$rsdb['id']]['name']  = $rsdb['name'];
		}
		$province_list = $FormHandler->Select("province",$province,''," onchange=\"changeProvince();\"");
		
		
	
		
		include($this->TemplateHandler->Template('user_follower_menu'));
	}
	
		function DoAddMyBlackList()
	{    
        		$uid  = MEMBER_ID;  
		
		if ($uid < 1) {
			json_error("请先登录或者注册");
		}
		
				$touid  = (int) $this->Post['touid']; 

				$types	= $this->Post['types'];	
   		
				$member = $this->TopicLogic->GetMember($touid);
		
				$follow_html = $this->_AddBlackList($uid,$touid,$types);
		
				$template	= $this->Post['template']; 
		
		include($this->TemplateHandler->Template($template));
	}

		function DoDelMyBlackList()
	{
				$uid  = MEMBER_ID;  
		
		if ($uid < 1) {
			json_error("请先登录或者注册");
		}
		
				$touid  = (int) $this->Post['touid']; 
		
				$this->_AddBlackList($uid,$touid,'del');
		
		include($this->TemplateHandler->Template('blacklist'));
		
	}

	
		function Tag_Menu()
	{
		$uid  = (int) $this->Post['uid'];
		$type = $this->Post['type'];
				if('my_tag' == $type) 
		{
			$sql = "select `id`,`tag` as tag_name,`uid` from `".TABLE_PREFIX."tag_favorite` where `uid`='{$uid}' order by `id` desc limit 0,12 ";
			$query = $this->DatabaseHandler->Query($sql);
			$list = $query->GetAll();
			$my_tag_class = 'here';
				} elseif('day_tag' == $type){  

			$sql = "select `id`,`name` as tag_name,`topic_count` from `".TABLE_PREFIX."tag`  WHERE dateline>='".(time() - 86400 * 7)."' GROUP BY `tag_count` DESC limit 0,12";
			$query = $this->DatabaseHandler->Query($sql);
			$list = $query->GetAll();
			$day_tag_class = 'here';
				} elseif('day_hot' == $type){  

			$sql = "select `id`,`name` as tag_name,`topic_count`,`tag_count` from `".TABLE_PREFIX."tag`  WHERE dateline>='".(time() - 86400 * 7)."' GROUP BY `topic_count` DESC limit 0,12";
			$query = $this->DatabaseHandler->Query($sql);
			$list = $query->GetAll();
			$day_hot_class = 'here';
				} elseif('tui_tag' == $type){ 

			            
            $hot_tag_recommend = ConfigHandler::get('hot_tag_recommend');
            $list = $hot_tag_recommend['list'];
			$tui_tag_class = 'here';
		}


		include($this->TemplateHandler->Template('tag_menu'));
	}
	
	
	

	function Add_User_Follow()
	{
	
		$timestamp = time();
		
		$uid = MEMBER_ID;
		if ($uid < 1) {
			json_error("请先登录或者注册");
		}
		
				$uids = $this->Post['ids'];
		
				$media_uid = $this->Post['media_uids_'.$this->Post['media_id']];
		
		$ids = $uids ? $uids : $media_uid;

		if(empty($ids))
		{
		   		   echo "<script language='Javascript'>";
		   echo "alert('请先选择关注对象');";		
		   echo "</script>";
		   die;
		}
		
		

		if('add' == $this->Post['type'])
		{	
			
			
			$array_value = $ids;
			
			if(MEMBER_ID > 0 && $array_value) 
			{
				foreach ($array_value as $id) {
					$id = (int) $id;
					if($id > 0) {
						buddy_add($id);
					}
				}
				
				$response = "关注成功";
	
			}
	

		} elseif('del' == $this->Post['type']){
  
			$sql = "delete from `".TABLE_PREFIX."buddys` where `uid`='{$uid}' and `buddyid`  in('".implode("','",$ids)."')";
			$this->DatabaseHandler->Query($sql);
			
			$response = "取消成功";
		}
		
		update_my_fans_follow_count($uid);
		
		 echo "<script language='Javascript'>";
	    echo "alert('{$response}');top.location.href='/home.php?mod=space&uid=".MEMBER_ID."';";	
		 echo "</script>";
		 die;
		
       
	}
	
	
	    function addFavoriteTag() 
    {
    	$uid = MEMBER_ID;
    	
        if ($uid < 1) {
        	
           echo "<script language='Javascript'>";
		   echo "alert('请登录');";		
		   echo "</script>";
		   die;
        }
    	
    	    	$tagid = (int) $this->Post['tag'] ? $this->Post['tag'] : $this->Get['tag'];
		
    	if(!$tagid){
    	      	   
    	   echo "<script language='Javascript'>";
		   echo "alert('请选择关注对象');";		
		   echo "</script>";
		   die;
    	}
    	
    	
	    Load::logic('other');
    	$OtherLogic = new OtherLogic();
    	$jsg_result = $OtherLogic->AddFavoriteTag($uid,$tagid);
    	
	    if($jsg_result <= 1)
		{
			$rets = array(
	        	'1'  => '话题关注成功',
	        	'-1' => '关注话题失败,请选择关注对象',
	        );
	   
	        echo "<script language='Javascript'>";
		    echo "alert('{$rets[$jsg_result]}');";		
		    echo "</script>";
		    die;
	        
		}

    }

	
	
    	function Favorite() 
	{
	    if (MEMBER_ID < 1) {
			response_text("请登录");
		}
		
        $uid = MEMBER_ID;
		$tid = (int) ($this->Post['tid']);
		
	    if ($tid < 1) {
			return  "请指定一个微博";
		}
		
		$act = $this->Post['act'];

		
	    Load::logic('other');
    	$OtherLogic = new OtherLogic();
    	$TopicFavorite = $OtherLogic->TopicFavorite($uid,$tid,$act);
    	
    	response_text($TopicFavorite);
	
	}
	
		function favoriteFenlei(){
		$id = $this->Post['id'];
		$act = $this->Post['act'];
		$time = time();
		if($act == 'add'){
			$this->DatabaseHandler->Query("insert into ".TABLE_PREFIX."fenlei_favorite (fid,uid,dateline) values ($id,".MEMBER_ID.",$time)");
		}elseif($act == 'delete'){
			$this->DatabaseHandler->Query("delete from ".TABLE_PREFIX."fenlei_favorite where fid = $id");
		}
	}
	
		function favoriteEvent(){
		$id = $this->Post['id'];
		$act = $this->Post['act'];
		$time = time();
		if($act == 'add'){
			$this->DatabaseHandler->Query("insert into ".TABLE_PREFIX."event_favorite (type_id,uid,dateline) values ($id,".MEMBER_ID.",$time)");
		}elseif($act == 'delete'){
			$this->DatabaseHandler->Query("delete from ".TABLE_PREFIX."event_favorite where type_id = $id");
		}
	}
	
		function FavoriteTag()
	{
		if (MEMBER_ID < 1) {
			
			js_show_login("请登录");
		}
		$uid = MEMBER_ID;
		$timestamp = time();

		$tag = trim($this->Post['tag'] ? $this->Post['tag'] : $this->Get['tag']);
		if (!$tag) {
			js_alert_showmsg("请指定一个标签");
		}
		
		$sql = "select * from `".TABLE_PREFIX."tag` where `name`='{$tag}'";
		$query = $this->DatabaseHandler->Query($sql);
		$tag_info = $query->GetRow();

		if(!$tag_info) {
			js_alert_showmsg("指定的话题已经不存在了");
		}

		$sql = "select * from `".TABLE_PREFIX."tag_favorite` where `uid`='{$uid}' and `tag`='{$tag}'";
		
		$query = $this->DatabaseHandler->Query($sql);
		$is_favorite = ($query->GetNumRows()>0);
		$tag_favorite = $query->GetRow();
		

		$sql = "select count(*) as `tag_favorite_count` from `".TABLE_PREFIX."tag_favorite` where `uid`='{$uid}'";
		$query = $this->DatabaseHandler->Query($sql);
		$row = $query->GetRow();
		if($row) {
			$sql = "update `".TABLE_PREFIX."members` set `tag_favorite_count`='{$row['tag_favorite_count']}' where `uid`='{$uid}'";
			$this->DatabaseHandler->Query($sql);
		}
				if ('delete' == $this->Post['act']) {
			if ($is_favorite) {
				$id = $tag_favorite['id'];

				$sql = "delete from `".TABLE_PREFIX."tag_favorite` where `id`='{$id}'";
				$this->DatabaseHandler->Query($sql);

				$sql = "update `".TABLE_PREFIX."members` set `tag_favorite_count`=if(`tag_favorite_count`>1,`tag_favorite_count`-1,0) where `uid`='{$uid}'";
				$this->DatabaseHandler->Query($sql);


								$sql = "update `".TABLE_PREFIX."tag` set `tag_count`=`tag_count`-1 where `id`='{$tag_info['id']}'";
				$this->DatabaseHandler->Query($sql);

			}

			js_alert_showmsg("已取消话题关注");
		} 
		
				else 
		{
			
			if(!$is_favorite) {
				
				$sql = "insert into `".TABLE_PREFIX."tag_favorite` (`uid`,`tag`,`dateline`) values ('{$uid}','{$tag}','{$timestamp}')";
				$this->DatabaseHandler->Query($sql);
				$favorite_tag_id = $this->DatabaseHandler->Insert_ID();
				
				$sql = "update `".TABLE_PREFIX."members` set `tag_favorite_count`=`tag_favorite_count`+1 where `uid`='{$uid}'";
				$this->DatabaseHandler->Query($sql);

								$sql = "update `".TABLE_PREFIX."tag` set `tag_count`=`tag_count`+1 where `id`='{$tag_info['id']}'";
				$this->DatabaseHandler->Query($sql);
				
								$sql = "select * from `".TABLE_PREFIX."tag_favorite` where `uid`='{$uid}' order by `id` desc  limit 0,12";			
				$query = $this->DatabaseHandler->Query($sql);
				$list = $my_favorite_tags = array();
				while ($row = $query->GetRow()) {
					$my_favorite_tags[] = $row;
					$list[] = $row;
				}
			
			}
			if('input_add' != $this->Post['act'])
			{
				js_alert_showmsg("话题关注成功");
			}
			else
			{
				include($this->TemplateHandler->Template('tag_favorite_ajax'));	
			}
			
			
		}

	}
	


		function Forward_Menu()
	{
	  $tid = $this->Post['tid'];
	  $forward_topic = $this->TopicLogic->Get($tid);
	
				$returncode = $this->Post['r'];
		
	  if($forward_topic['roottid'])
	  {
	  	$forward_topic = $this->TopicLogic->Get($forward_topic['roottid']);
	  }
	
	  $forward_tid		 = $forward_topic['tid'];
				include($this->TemplateHandler->Template('topic_forward_menu'));
	}


		function Do_forward()
	{
		if (MEMBER_ID < 1) {
			response_text("请登录");
		}
	
				if($this->MemberHandler->HasPermission('topic','do_add')==false)
		{
			 response_text("转发失败:您的角色(禁言组)没有发布的权限");
		}
	
		$content = addslashes(strip_tags($this->Post['content']));
	
		$totid  		= (int) $this->Post['tid'];
		$imageid = trim($this->Post['imageid']);
	
		$type = $this->Post['topictype'];
		$from = 'web';
		
		
		$item = trim($this->Post['item']);
		$item_id  = intval(trim($this->Post['item_id']));
		if (!empty($item_id)) {
						Load::functions('app');
			$ret = app_check($item, $item_id);
			if (!$ret) {
				$item = '';
				$item_id = 0;
			} else {
				$from = $item;
			}
		} else {
			$item = '';
			$item_id = 0;
		}
		
		$data = array( 
			'content' => $content,
			'totid'=>$totid,
			'imageid'=>$imageid,
			'from'=>$from,
			'type'=>$type,
		
						'item' => $item,
			'item_id' => $item_id,
		);
	
		$return = $this->TopicLogic->Add($data);
	
		if (is_array($return) && $return['tid'] > 0) 
	    {
		   response_text('<success></success>');
	    }
	    else 
	    {
	    	$return = is_string($return) ? $return : "未知错误";
	        
	        response_text("转发失败{$return}");
	    }
	}

		 function DoReport()
	 {
		 if(MEMBER_ID < 1 && !$this->Config['is_report'])
		 {
		 	response_text('您是游客，没有权限举报');
		 }
	 
		$tid =  $this->Post['totid'];
		$report_reason = $this->Post['report_reason'];
		$report_content = $this->Post['report_content'];
	
				
		$data = array(
				'uid' => MEMBER_ID,
				'username' => MEMBER_NICKNAME,
				'ip' => client_ip(),
				'reason' => (int) $report_reason,
				'content' => addslashes(strip_tags($report_content)),
				'tid' => (int) $tid,
				'dateline' => time(),
			);
			
		$this->DatabaseHandler->SetTable(TABLE_PREFIX . 'report');
		$result = $this->DatabaseHandler->Insert($data);
	
	    response_text('举报成功');
	
	}

		function TopicShow()
	{
		extract($this->Get);
		extract($this->Post);
		
		$uid = MEMBER_ID;
		
		
		
		$sql = "select `uid` from `".TABLE_PREFIX."topic_show` where `uid` =  '{$uid}' ";
		$query = $this->DatabaseHandler->Query($sql);
		$showlist = array();
		while ($row = $query->GetRow()) {
			$showlist[] = $row;
		}

		$styleData = array(
				'titleColor' 	=> $titleColor,
				'width' 		=> $width,
				'height' 		=> $height,
				'bgColor' 		=> $bgColor,
				'textColor' 	=> $textColor,
				'linkColor' 	=> $linkColor,
				'borderColor'	=> $borderColor,
				'showFans' 		=> $showFans,
				'isFans' 		=> $isFans 		? $isFans 		: '0',
				'isTopic' 		=> $isTopic 	? $isTopic 		: '0',
				'isTitle' 		=> $isTitle 	? $isTitle 		: '0',
				'isBorder'		=> $isBorder 	? $isBorder 	: '0',
			);
		
				if($showlist){

			$sql = "update `".TABLE_PREFIX."topic_show` set `stylevalue`='".serialize($styleData)."'  where `uid`='{$uid}'";
			$this->DatabaseHandler->Query($sql);

		} else{

			$sql = "insert into `".TABLE_PREFIX."topic_show` (`uid`,`stylevalue`) values ('{$uid}','".serialize($styleData)."')";
			$this->DatabaseHandler->Query($sql);
		}

		echo "<script language='Javascript'>";
		echo "location.replace('index.php?mod=show&code=show');";
		echo "</script>";
		exit;
	}


		function User_Tag()
	{
		extract($this->Get);
		extract($this->Post);


		$uid 	 	= (int) MEMBER_ID;
		$tagid 		= (int) $this->Post['tagid'];
		$tag_name 	= strip_tags($this->Post['tag_name']);
		$addtime 	= time();

        if($uid < 1)
        {
            js_alert_output("请先登录或者注册一个帐号");
        }

				if(($filter_msg = filter($tag_name)))
		{
			js_alert_output($filter_msg);
		}
		
				$sql = "select count(*) as `total_record` from `".TABLE_PREFIX."user_tag_fields` where `uid` = '".MEMBER_ID."'";
		$query = $this->DatabaseHandler->Query($sql);
		extract($query->GetRow());


		if($total_record >= 10)
		{
			js_alert_output('最多只能设置10个标签');
		}

				if($this->Post['types'] == 'add')
		{
			if(empty($tag_name))
			{
          		js_alert_output('请输入标签');
			}

			$sql = "select * from `".TABLE_PREFIX."user_tag_fields` where `tag_name` = '{$tag_name}' and `uid` = '".MEMBER_ID."'";
			$query = $this->DatabaseHandler->Query($sql);
			$row = $query->GetRow();

			if(!empty($row))
			{
					js_alert_output($tag_name.' 标签已经打上');
			}

						$sql = "select * from `".TABLE_PREFIX."user_tag` where `name` = '{$tag_name}'";
			$query = $this->DatabaseHandler->Query($sql);
			$usertag = $query->GetRow();
			if(empty($usertag))
			{
								$sql = "insert into `".TABLE_PREFIX."user_tag`(`name`,`dateline`) values ('{$tag_name}','{$addtime}')";
				$this->DatabaseHandler->Query($sql);
				$tag_insertid = $this->DatabaseHandler->Insert_ID();
			}

			$tag_insertid = $tag_insertid ? $tag_insertid :$usertag['id'];

						$sql = "insert into `".TABLE_PREFIX."user_tag_fields`(`tag_id`,`uid`,`tag_name`) values ('{$tag_insertid}','{$uid}','{$tag_name}')";
			$this->DatabaseHandler->Query($sql);
			$tag_fields_id = $this->DatabaseHandler->Insert_ID();

		}


	  		  	if($this->Post['types'] == 'useradd')
	  	{
	  			$sql = "select `tag_name` from `".TABLE_PREFIX."user_tag_fields` where `tag_name`='{$tag_name}' and `uid` = '".MEMBER_ID."'";
				$query = $this->DatabaseHandler->Query($sql);
				$row = $query->GetRow();
	
				if(!empty($row))
				{
	         		js_alert_output('标签 '.$tag_name.' 已经打上');
				}
	
	
	  			$sql = "insert into `".TABLE_PREFIX."user_tag_fields`(`tag_id`,`uid`,`tag_name`) values ('{$tagid}','{$uid}','{$tag_name}')";
				$this->DatabaseHandler->Query($sql);
				$tag_fields_id = $this->DatabaseHandler->Insert_ID();
	
	  	}

		$sql = "select * from `".TABLE_PREFIX."user_tag_fields` where `id` = '{$tag_fields_id}' and `uid` = '".MEMBER_ID."' limit 0,1";
		$query = $this->DatabaseHandler->Query($sql);
		$user_tag_fields[]=$query->GetRow();

				$sql = "update `".TABLE_PREFIX."user_tag` set `count`=`count`+1 where `id`='{$tagid}'";
		$this->DatabaseHandler->Query($sql);

		include($this->TemplateHandler->Template('user_tag_ajax'));
	}

		function Del_Tag()
	{

		extract($this->Get);
		extract($this->Post);

		$uid 		= (int) MEMBER_ID;
		$tag_id = (int) $tag_id;

		$sql = "delete from `".TABLE_PREFIX."user_tag` where `id`='{$tag_id}' and `type` = 'user' ";
		$this->DatabaseHandler->Query($sql);

		$sql = "delete from `".TABLE_PREFIX."user_tag_fields` where `tag_id`='{$tag_id}' and `uid` = '".MEMBER_ID."'";
		$this->DatabaseHandler->Query($sql);

		$sql = "update `".TABLE_PREFIX."user_tag` set `count`=if(`count`>1,`count`-1,0) where `id`='{$tag_id}'";
		$this->DatabaseHandler->Query($sql);


		include($this->TemplateHandler->Template('user_tag_ajax'));
	}

		function Del_Group()
	{

		extract($this->Get);
		extract($this->Post);

		$uid 		= (int) MEMBER_ID;
		$group_id = (int) $group_id;

		$sql = "select `id`,`uid` from `".TABLE_PREFIX."group` where `id` ='{$group_id}'";
		$query = $this->DatabaseHandler->Query($sql);
		$user_group = $query->GetRow();


		$sql = "delete from `".TABLE_PREFIX."group` where `id`='{$group_id}' and `uid` =".MEMBER_ID;
	  	$this->DatabaseHandler->Query($sql);

	 	$sql = "delete from `".TABLE_PREFIX."groupfields` where `gid`='{$group_id}'";
	 	$this->DatabaseHandler->Query($sql);
		include($this->TemplateHandler->Template('topic_follow'));
	}

		function UploadFace()
	{
		if (MEMBER_ID < 1)
		{
			js_alert_output('请先登录或者注册');
		}

		$field = 'face';

		Load::lib('io');
		$IoHandler = new IoHandler();



		$type = trim(strtolower(end(explode(".",$_FILES[$field]['name']))));
		if($type != 'gif' && $type != 'jpg' && $type != 'png')
		{
			js_alert_output('图片格式不对');
		}

		$image_name = substr(md5($_FILES[$field]['name']),-10).".{$type}";
		$image_path = RELATIVE_ROOT_PATH . './cache/temp_images/'.$image_name{0}.'/';
		$image_file = $image_path . $image_name;

		if (!is_dir($image_path))
		{
			$IoHandler->MakeDir($image_path);
		}

		Load::lib('upload');
		$UploadHandler = new UploadHandler($_FILES,$image_path,$field,true);
		$UploadHandler->setMaxSize(2048);
		$UploadHandler->setNewName($image_name);
		$result=$UploadHandler->doUpload();
		if($result)
        {
			$result = is_image($image_file);
		}


		if(!$result)
        {
			$IoHandler->RemoveDir($image_path);
			js_alert_output('图片上载失败');
		}


        
        list($w,$h) = getimagesize($image_file);
        if($w > 601)
        {
            $tow = 599;
            $toh = round($tow * ($h / $w));

            $result = makethumb($image_file,$image_file,$tow,$toh);

            if(!$result)
            {
                $IoHandler->RemoveDir($image_path);
                js_alert_output('大图片缩略失败');
            }
        }


		$up_image_path = addslashes($image_file);

        echo "<script language='Javascript'>";
        if($this->Post['temp_face'])
        {
            echo "window.parent.location.href='{$this->Config[site_url]}/index.php?mod=settings&code=face&temp_face={$up_image_path}'";
        }
        else
        {
    		echo "parent.document.getElementById('cropbox').src='{$up_image_path}';";
    		echo "parent.document.getElementById('img_path').value='{$up_image_path}';";
    		echo "parent.document.getElementById('temp_face').value='{$up_image_path}';";
    		echo "parent.document.getElementById('jcrop_init_id').onclick();";
        }
        echo "</script>";
	}
	
		function DoVideo()
	{
		$url = $this->Post['url'];
		
		preg_match_all('~(?:https?\:\/\/)(?:[A-Za-z0-9_\-]+\.)+[A-Za-z0-9]{2,4}(?:\/[\w\d\/=\?%\-\&_\~`@\[\]\:\+\#]*(?:[^<>\'\"\n\r\t\s])*)?~',$url,$match);
		
		if (empty($match)) 
        {
       	 	js_alert_output('输入正确的视频地址');
       	}
        
        $ext = trim(strtolower(substr($url,strrpos($url,'.'))));
        
                $return = array();
        if('.swf'==$ext)
        {
                        $return = array
            ( 
                'id' => $url,    
                'host' => 'flash', 
                'url' => $url,
                'title' => $url,          
            );
        }
       	else
        {
            $return = $this->TopicLogic->_parse_video($url);
        }		
  		
                $return_content = (
        	$return['title'] ? 
	        $return['title'] . (
	        	$this->Config['video_status'] ? 
	        	"" : 
	        	" $url"
	        ) 
	        : '分享链接  '.$url
	    );
	    
  		
       	if ($return) 
        {
           	$video_link 	= $return['id'];
           	$video_hosts 	= $return['host'];
           	$video_url		= $return['url'];
            $video_img = '';
            if($return['image_src'])
            {
                $return['image_local'] = $this->TopicLogic->_parse_video_image($return['image_src']);
            }
            $video_img = $return['image_local'];
            $video_img_url = '';
            if($video_img)
            {
                $video_img_url = ($this->Config['ftp_on'] ? ConfigHandler::get('ftp','attachurl') : "");
            }
           	$timestamp 		= time();
     
           	$sql = "insert into `".TABLE_PREFIX."topic_video`
           	(`uid`,`tid`,`username`,`video_hosts`,`video_link`,`video_url`,`video_img`,`video_img_url`,`dateline`) 
           	values 
           	('".MEMBER_ID."','".''."','".MEMBER_NAME."','".$video_hosts."','".$video_link."','".$video_url."','".$video_img."','$video_img_url','".$timestamp."')";
    		$this->DatabaseHandler->Query($sql);
          	
            $videoid = $this->DatabaseHandler->Insert_ID();
            
            if($video_img) $video_img_src = $video_img_url . $video_img;
            
            
            if(empty($video_img_src))
            {
                $video_img_src = 'images/vd.gif';  
            }
                       	echo "<script language='Javascript'>";                          
            echo "parent.videoid={$videoid};";
           	echo "parent.document.getElementById('upload_video_list').style.display='block';";
           	echo "parent.document.getElementById('add_video').style.display='none';";
    		echo "parent.document.getElementById('videoid').value='".$videoid."';";
    		echo "parent.document.getElementById('video_img').src='".$video_img_src."';";
    		echo "parent.document.getElementById('url').value='';";    		
    		echo "parent.document.getElementById('i_already').value=parent.document.getElementById('i_already').value + ' ".$return_content." ';";   		
    		echo "parent.document.getElementById('return_ajax_video_title').innerHTML='[".cut_str($return_content,36)."]';";
    		echo "parent.document.getElementById('publishSubmit').disabled=false;";
    		echo "parent.document.getElementById('i_already').focus();";   		
    		echo "</script>";
         } 
         else 
         {
            
         	echo "<script language='Javascript'>";          
           	echo "parent.document.getElementById('add_video').style.display='none';";	
    		echo "parent.document.getElementById('url').value='';";
    		echo "if(''==parent.document.getElementById('i_already').value){parent.document.getElementById('i_already').value='".$return_content."';}";
    		echo "parent.document.getElementById('publishSubmit').disabled=false;";
    		echo "parent.document.getElementById('i_already').focus();";
    		echo "</script>";
         }
	
	}
		function DeleteImage()
	{
		if($this->ID > 0) {
			$topic_image = $this->TopicLogic->GetTopicImage($this->ID);
		}
		if (!$topic_image) {
			response_text("图片已经不存在了");
		}

		if($topic_image['uid'] == MEMBER_ID || 'admin' == MEMBER_ROLE_TYPE)
		{

				$sql = "delete from `".TABLE_PREFIX."topic_image` where `id`='{$this->ID}'";
				$this->DatabaseHandler->Query($sql);

				Load::lib('io');
				@IoHandler::DeleteFile(topic_image($this->ID,'small'));
				@IoHandler::DeleteFile(topic_image($this->ID,'original'));

				$updata = "update `".TABLE_PREFIX."topic` set `imageid`='0' where `imageid`='{$this->ID}'";
				$result = $this->DatabaseHandler->Query($updata);

				$sql = "delete from `".TABLE_PREFIX."topic_image` where `uid`='".MEMBER_ID."' and `tid`=0";
				$this->DatabaseHandler->Query($sql);
		}
		else
		{
			response_text("您没有删除这张图片的权限");
		}
	}
	
		function DeleteVideo()
	{
		if($this->ID > 0) {
			$sql = "select `id`,`tid`,`uid`,`video_img` from `".TABLE_PREFIX."topic_video` where `id`='".$this->ID."' ";
			$query = $this->DatabaseHandler->Query($sql);
			$topic_video=$query->GetRow();
		}

		if (!$topic_video) {
			response_text("视频已经不存在了");
		}

		if($topic_video['uid'] == MEMBER_ID || 'admin' == MEMBER_ROLE_TYPE)
		{
				$sql = "delete from `".TABLE_PREFIX."topic_video` where `id`='{$this->ID}'";
				$this->DatabaseHandler->Query($sql);

				Load::lib('io');
				IoHandler::DeleteFile($topic_video['video_img']);

				$updata = "update `".TABLE_PREFIX."topic` set `videoid`='0' where `tid`='{$topic_video['tid']}'";
			  $result = $this->DatabaseHandler->Query($updata);
	  }
	  else
	  {
	  	response_text("您没有删除这个视频的权限");
	  }

	}
	
		function DeleteMusic()
	{
		if($this->ID > 0) {
			$sql = "select * from `".TABLE_PREFIX."topic_music` where `id`='".$this->ID."' ";
			$query = $this->DatabaseHandler->Query($sql);
			$topic_music=$query->GetRow();
		}

		if (!$topic_music) {
			response_text("视频已经不存在了");
		}

		if($topic_music['uid'] == MEMBER_ID || 'admin' == MEMBER_ROLE_TYPE)
		{
				$sql = "delete from `".TABLE_PREFIX."topic_music` where `id`='{$this->ID}'";
				$this->DatabaseHandler->Query($sql);

				$updata = "update `".TABLE_PREFIX."topic` set `musicid`='0' where `tid`='{$topic_music['tid']}'";
			  $result = $this->DatabaseHandler->Query($updata);
	  }
	  else
	  {
	  	response_text("您没有删除这个视频的权限");
	  }

	}

	
		function Open_Mdeal_Index()
	{
		$medalid = (int) $this->Post['medalid'];
		
				$sql = "select `id`,`uid`,`is_index` from `".TABLE_PREFIX."user_medal` where `id` = '{$medalid}' ";
		$query = $this->DatabaseHandler->Query($sql);
		$user_medal = $query->GetRow();
		
		if($user_medal)	
		{  
						$is_index = $user_medal['is_index'] ? 0 : 1;
			
			$sql = "update `".TABLE_PREFIX."user_medal` set  `is_index`='{$is_index}'  where `id` = '{$user_medal['id']}'";	
			$this->DatabaseHandler->Query($sql);	
			
		}
		
				$sql = "select * from `".TABLE_PREFIX."user_medal` where `uid` = '".MEMBER_ID."' and `is_index` = 1";
		$query = $this->DatabaseHandler->Query($sql);
		$user = array();
		$userlist = array();
		while ($row = $query->GetRow()) 
		{
			$user[$row['medalid']] = $row['medalid'];
			$userlist[] = $row;
		}
		$new_medal = implode(",",$user);

		$sql = "update `".TABLE_PREFIX."members` set  `medal_id`='{$new_medal}'  where `uid` = '".MEMBER_ID."' ";
		$this->DatabaseHandler->Query($sql);
			
		include($this->TemplateHandler->Template('setting_main'));
	}
	
		function Qmd()
	{
		
	    $uid = MEMBER_ID;
	    
	    $sql = "select `uid`,`qmd_img` from `".TABLE_PREFIX."members` where `uid`='{$uid}'";
		$query = $this->DatabaseHandler->Query($sql);
		$row = $query->GetRow();
		
	    	    $qmd_bg_path = $this->Post['qmd_bg_path'] ? $this->Post['qmd_bg_path'] : $row['qmd_img'];
	 	    
        Load::logic('other');	        
    	$OtherLogic = new OtherLogic();
    	$qmd_return = $OtherLogic->qmd_list($uid,$qmd_bg_path);
    	
	    $sql = "update `".TABLE_PREFIX."members` set  `qmd_img`='{$qmd_bg_path}'  where `uid` = '{$uid}' ";
		$this->DatabaseHandler->Query($sql);
			
        	}
	
		function ShowLogin() 
	{	

		include($this->TemplateHandler->Template('show_login_ajax'));
	}
	
		function Do_Reg_Follow_User() 
	{	
		
				$follow_type = $this->Post['followType'] ;
		
				$_limit = $this->Post['list_limit'] ? $this->Post['list_limit'] + 15 : '15';
		
		$list = array();
		
				if ($follow_type == 'recommend') {
			
			$limit = (int) $this->ShowConfig['reg_follow']['user'];
			if($limit < 1) $limit = 20;
		
			$regfollow = ConfigHandler::get('regfollow');
			
						
			if (!empty($regfollow)) {
				$count = count($regfollow);
				if ($count > $limit) {
					$keys = array_rand($regfollow, $limit);
					foreach ($keys as $k) {
						$uids[] = $regfollow[$k];	
					}
				} else {
					$uids = $regfollow;
				}
				
			}
			 
		} 
				elseif ($follow_type == 'huoyue') {
			
			$sql = "select DISTINCT(T.username) AS username , T.uid AS uid , COUNT(T.tid) AS count from `".TABLE_PREFIX."topic` T left join `".TABLE_PREFIX."members` M on T.uid=M.uid WHERE T.dateline>='".(time() - 86400 * 7)."' and M.face!='' GROUP BY username ORDER BY count DESC LIMIT 0,{$_limit}";
			$query = $this->DatabaseHandler->Query($sql);
			$uids =  array();
			while ($row = $query->GetRow())
			{
				$uids[$row['uid']] = $row['uid'];
			}
			
		} 
				elseif ($follow_type == 'renqi') {
			
			$sql = "select DISTINCT(B.buddyid) AS buddyid , COUNT(B.uid) AS count  from `".TABLE_PREFIX."buddys` B left join `".TABLE_PREFIX."members` M on B.buddyid=M.uid WHERE B.dateline>='".(time() - 86400 * 7)."' and M.face!='' GROUP BY buddyid ORDER BY count DESC LIMIT 0,{$_limit}";
			$query = $this->DatabaseHandler->Query($sql);
			$uids = array();
			while ($row = $query->GetRow())
			{
				$uids[$row['buddyid']] = $row['buddyid'];	
			}
					}
				elseif ($follow_type == 'yingxiang') {
			
			$sql = "select DISTINCT(T.tousername) AS username ,  COUNT(T.tid) AS count, M.face ,M.username,M.uid from `".TABLE_PREFIX."topic` T left join `".TABLE_PREFIX."members` M on T.tousername=M.username WHERE M.face !='' and  T.dateline>='".(time() - 86400 * 7)."' and T.touid > 0  GROUP BY tousername ORDER BY count DESC LIMIT 0,{$_limit}";
			$query = $this->DatabaseHandler->Query($sql);
			$uids = array();
			while ($row = $query->GetRow())
			{	
				$uids[$row['uid']] = $row['uid'];
			}
					
		}
		
		if($uids)		
		{	
			$_list = $this->TopicLogic->GetMember($uids,"`uid`,`ucuid`,`username`,`face_url`,`face`,`validate`,`nickname`,`aboutme`");
			foreach ($uids as $uid) {
				if ($uid > 0 && isset($_list[$uid]) && $uid!=MEMBER_ID) {
					$list[$uid] = $_list[$uid];
				}
			}
						$user_count = $list ? count($list) : '0';
			
		} else {
			;	
		}
		
		
				if ($follow_type == 'tag') 
		{

						
			
		
			$sql = "select * from `".TABLE_PREFIX."tag_recommend` order by `id` desc limit  0,{$_limit}";
			$query = $this->DatabaseHandler->Query($sql);
			$tag_name = array();
			while ($row = $query->GetRow())
			{	
				$tag_name[$row['name']] = $row['name'];
			}
			
						if($tag_name)
			{
				$query = DB::query("SELECT `id`,`name` FROM ".DB::table('tag')." where `name` in ('".implode("','", $tag_name)."') order by `id` desc limit 0,{$_limit} ");
				$tag_list = array();
				while ($row = DB::fetch($query)) 
				{
					$tag_list[] = $row;
				}
				
			}
			$tag_count = count($tag_list);
			
		}

		include($this->TemplateHandler->Template('reg_follow_user_ajax'));
	}
	
	
		
	function Modify_User_Signature() 
	{

		$uid = (int) $this->Post['uid'];
		
		if($uid < 1){
			showjsmessage('请先登录');
		}

				$signature = $signature = trim(strip_tags($this->Post['signature']));

				if(($filter_msg = filter($signature)))
        {
            response_text($filter_msg);
        }
        
        
		$datas = array();	
		$datas['signature'] = $signature;	
	
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'members');
		$this->DatabaseHandler->Update($datas,"uid={$uid}");
		
		
		$sql = "select `uid`,`signature` from `".TABLE_PREFIX."members` where `uid`='{$uid}' ";
		$query = $this->DatabaseHandler->Query($sql);
		$members = $query->GetRow();

		echo  $members['signature'];
		die;
				
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
	
	
	function _AddBlackList($uid=0,$touid=0,$types='') 
	{	
		if ($uid < 1) {
			json_error("请先登录或者注册");
		}
		
				if('add' == $types)
		{
			if($touid == MEMBER_ID)
			{
				json_error('不能拉黑自己');
			}

			$sql = "insert into `".TABLE_PREFIX."blacklist` (`uid`,`touid`) values ('{$uid}','{$touid}')";
			$this->DatabaseHandler->Query($sql);

						$sql = "delete from `".TABLE_PREFIX."buddys` where `buddyid`='{$touid}' and `uid` = '{$uid}'";
			$this->DatabaseHandler->Query($sql);
			
			$sql = "delete from `".TABLE_PREFIX."buddys` where `buddyid`='{$uid}' and `uid` = '{$touid}'";
			$this->DatabaseHandler->Query($sql);

						update_my_fans_follow_count($uid);
			update_my_fans_follow_count($touid);

			$follow_html = follow_html($touid,isset($uid));
		}

				if('del' == $types)
		{
			$sql = "delete from `".TABLE_PREFIX."blacklist` where `touid`='{$touid}' and `uid` = '".MEMBER_ID."'";
			$this->DatabaseHandler->Query($sql);
			$follow_html = follow_html($touid,isset($buddysid[$uids]));
		}
		
		return $follow_html;
	}
	
		function _recd_levels($type = 'all')
	{
		Load::logic('topic_recommend');
		$TopicRecommendLogic = new  TopicRecommendLogic();
		$recd_levels = $TopicRecommendLogic->recd_levels($type);
		return $recd_levels;
	}

	
		function recd()
	{
		Load::logic('topic_recommend');
		$TopicRecommendLogic = new  TopicRecommendLogic();
		$tid = intval($this->Get['tid']);
		$tag_id = intval($this->Get['tag_id']);
		
				$topic = DB::fetch_first("SELECT * FROM ".DB::table("topic")." WHERE tid='{$tid}'");
		if (empty($topic)) {
			json_error("当前微博不存在或者已经被删除了");
		}
		
				$topic_recd = $TopicRecommendLogic->get_info($tid);
		if (!empty($topic_recd)) {
			$topic_recd['expiration'] = empty($topic_recd['expiration']) ? '' : my_date_format($topic_recd['expiration'], 'Y-m-d ');
		}
		
		
		if ('admin' != MEMBER_ROLE_TYPE) {
			if ($topic['item'] == 'qun' && $topic['item_id'] > 0) {
								Load::logic('qun');
				$QunLogic = new QunLogic();
				$tmp_perm = $QunLogic->chk_perm($topic['item_id'], MEMBER_ID);
				if (!in_array($tmp_perm, array(1,2))) {
					json_error("你没有权限进行当前操作");	
				} else {
					$recd_levels = $this->_recd_levels('qun');
				}
			} else {
				json_error("你没有权限进行当前操作");
			}
		} else {
						$recd_levels = $this->_recd_levels('topic');
			if (!empty($tag_id)) {
				$count = DB::result_first("SELECT COUNT(*) 
										   FROM ".DB::table('topic_tag')." 
										   WHERE item_id='{$tid}' AND tag_id='{$tag_id}' ");
				if (empty($count)) {
					json_error("当前微博不再该话题下");
				}
				$recd_levels = $this->_recd_levels('tag');
			} else {
				if ($topic_recd['item'] == 'tag' && $topic_recd['item_id'] > 0) {
					$recd_levels = $this->_recd_levels('tag');
				} else {
					if ($topic['item'] == 'qun' && $topic['item_id'] > 0) {
						$recd_levels = $this->_recd_levels('admin_qun');
					}
				}
			}
		}
		
		include(template("topic_recd"));
	}
	
		function do_recd()
	{
		Load::logic('topic_recommend');
		$TopicRecommendLogic = new  TopicRecommendLogic();
		$tid = intval($this->Post['tid']);
				$topic = DB::fetch_first("SELECT * FROM ".DB::table("topic")." WHERE tid='{$tid}'");
		if (empty($topic)) {
			json_error("当前微博不存在或者已经被删除了");
		}
		
		$recd = intval($this->Post['recd']);
		
				if ($recd>4 || $recd < 0) {
			json_error("推荐等级错误");
		}
		
		
		if ('admin' != MEMBER_ROLE_TYPE) {
			if ($topic['item'] == 'qun' && $topic['item_id'] > 0) {
								Load::logic('qun');
				$QunLogic = new QunLogic();
				$perm = $QunLogic->chk_perm($topic['item_id'], MEMBER_ID);
				if (!in_array($perm, array(1,2))) {
					json_error("你没有权限进行当前操作");	
				} else {
					$perm = 2;
					if (in_array($recd, array(2,3,4))) {
						json_error("你没有权限进行当前操作");		
					}
				}
			} else {
				json_error("你没有权限进行当前操作");
			}
		} else {
			$perm = 1;
		}
		
		if ($recd == 0) {
			$topic_recd = $TopicRecommendLogic->delete(array($tid));
		} else {
			$expiration = jstrtotime(trim($this->Post['expiration']));
			$display_order = intval($this->Post['display_order']);
			$tag_id = intval($this->Post['tag_id']);
			if (!empty($tag_id)) {
								$count = DB::result_first("SELECT COUNT(*) 
										   FROM ".DB::table('topic_tag')." 
										   WHERE item_id='{$tid}' AND tag_id='{$tag_id}' ");
				if (empty($count)) {
					json_error("当前微博不再该话题下");
				}
				$topic['item'] = 'tag';
				$topic['item_id'] = $tag_id;
			}
			$data = array(
				'expiration' => $expiration,
				'display_order' => $display_order,
				'item' => $topic['item'],
				'item_id' => $topic['item_id'],
				'tid' => $tid,
				'recd' => $recd,
				'dateline' => TIMESTAMP,
			);
			$TopicRecommendLogic->add($data);
		}
		json_result("推荐成功了");
	}
	
		function publishSuccess()
	{
		echo $this->js_show_msg();
	}
	
}

?>
