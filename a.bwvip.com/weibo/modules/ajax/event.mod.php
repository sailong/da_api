<?php

/*******************************************************************

 * [JishiGou] (C)2005 - 2099 Cenwor Inc.

 *

 * This is NOT a freeware, use is subject to license terms

 *

 * @Package JishiGou $

 *

 * @Filename event.mod.php $

 *

 * @Author http://www.jishigou.net $

 *

 * @Date 2011-09-28 19:16:47 1147271225 1057426487 30156 $

 *******************************************************************/






if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class ModuleObject extends MasterObject
{	
	var $Member;
	
	function ModuleObject($config)
	{
		$this->MasterObject($config);

		$this->initMemberHandler();
		
		Load::logic('topic');
		$this->TopicLogic = new TopicLogic($this);
		
		$this->Member = $this->_member();
		
		$this->Execute();
	}

	
	function Execute()
	{
        ob_start();
        switch($this->Code){
        	case 'onloadPic':
        		$this->onloadPic();
        		break;
        	case 'create':
        		$this->create();
        		break;
        	case 'publish_share':
        		$this->publishShare();
        		break;
        	case 'simple_talk':
        		$this->simpleTalk();
        		break;
        	case 'store':
        		$this->store();
        		break;
        	case 'app':
        		$this->app();
        		break;
        	case 'need_info':
        		$this->need_info();
        		break;
        	case 'manage':
        		$this->manage();
        		break;
        	case 'choose':
        		$this->choose();
        		break;
        	case 'eventpost':
        		$this->eventPost();
        		break;
        	case 'eventlist':
        		$this->eventList();
        		break;
        	case 'dosend':
        		$this->doSend();
        		break;
        	default:
        		break;
		}
        response_text(ob_get_clean());
	}	
	
	
	function doSend(){
		if(MEMBER_ID < 0)
		{
		  json_error("请先登录在操作");
		}		
		$this->noticeConfig = ConfigHandler::get('email_notice');
		
				if($this->MemberHandler->HasPermission($this->Module,$this->Code)==false)
		{
			json_error($this->MemberHandler->GetError());
		}
		
		$to_user_list = array();
		$to_user_list = $this->Post['che'];
		filter($this->Post['message'],'',false,true);
		$this->Post['subject']=htmlspecialchars(trim($this->Post['subject']));
		if($this->Post['message']=='')
		{
			json_error("内容不能为空");
		}
		if ($to_user_list == false)
		{
			json_error("请选择收件人");
		}

						
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'pms');
		foreach($to_user_list as $to_user_id => $to_user_name)
		{
			$data=array(
			"msgfrom"		=>MEMBER_NAME,
			"msgnickname"		=>MEMBER_NICKNAME,
			"msgfromid" => MEMBER_ID,  						"msgtoid"   => $to_user_id,						"subject"   => $this->Post['subject'],
			"message"   => $this->Post['message'],
			"new"=>'1',
			"dateline"=>time(),
			);

			$this->DatabaseHandler->Insert($data);		
		}
				$_tmps=array_keys($to_user_list);
		$to_user_id_list = array();
		foreach($_tmps as $_tmp) {
			$_tmp = (int) $_tmp;
			if($_tmp > 0) {
				$to_user_id_list[$_tmp] = $_tmp;
			}
		}
		$num=$this->Post["save_to_outbox"]?2:1;
		$this->UpdateNewMsgCount($num,$to_user_id_list);
		
		

		foreach ($to_user_list as $user_notice)
		{
			 if($user_notice['notice_pm'] == 1)  			 {	
					if($this->Config['notice_email'] == 1) 					{ 
						Load::lib('mail');
						$mail_to = $user_notice['email'];
			
						$mail_subject = "{$this->noticeConfig['pm']['title']}";
						$mail_content = "{$this->noticeConfig['pm']['content']}";
						$send_result = send_mail($mail_to,$mail_subject,$mail_content,array(),3,false);
						
												$sql = "update `".TABLE_PREFIX."members` set `last_notice_time`= time()  where `uid` = {$user_notice['uid']}";
						$this->DatabaseHandler->Query($sql);
					}
					else
					{
												
						Load::logic('notice');
						$NoticeLogic = new NoticeLogic();
						$pm_content = '您有'.$user_notice['newpm'].'条站内短信没有查看，请立即查看。';
						$NoticeLogic->Insert_Cron($user_notice['uid'],$user_notice['email'],$pm_content,'pm');
						
					}
				}

				
			if($this->Config['imjiqiren_enable'] && imjiqiren_init($this->Config))
			{
				imjiqiren_send_message($user_notice,'m',$this->Config);
			}
				
			if($this->Config['sms_enable'] && sms_init($this->Config))
			{
				sms_send_message($user_notice,'m',$this->Config);
			}
		}
		
		if($this->Config['extcredits_enable'] && MEMBER_ID > 0)
		{
			
			update_credits_by_action('pm',MEMBER_ID,count($to_user_list));
		}
		json_result('发送成功');
	}
	
	
	function eventList(){
		$page = empty($this->Get['page']) ? 0 : intval($this->Get['page']);
		$perpage = 8;
		if ($page == 0) {
			$page = 1;
		}
		$start = ($page - 1) * $perpage;
		
		$uid = MEMBER_ID;
		$type = $this->Get['type'];
		if($type == 'event'){
			$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('event')." WHERE postman = '$uid' ");
			if ($count) {
				$list = array();
				$sys_config = ConfigHandler::get();
				$query = DB::query("SELECT id,title 
									FROM ".DB::table('event')." 
									WHERE postman = '$uid'  
									ORDER BY lasttime DESC 
									LIMIT $start,$perpage ");
				while ($value = DB::fetch($query)) {
					$value['event_url'] = get_full_url($sys_config['site_url'],'index.php?mod=event&code=detail&id='.$value['id']);
					$value['radio_value'] = str_replace(array('"', '\''), '', $value['title']).' - '.$value['event_url'];
					$list[] = $value;
				}
				$multi = ajax_page($count, $perpage, $page, 'getMyEventList');
			}
		}else if($type == 'join'){
			$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('event_member')." WHERE fid = '$uid' ");
			if ($count) {
				$list = array();
				$sys_config = ConfigHandler::get();
				$query = DB::query("SELECT id,title 
									FROM ".DB::table('event_member')." 
									WHERE fid = '$uid'  
									ORDER BY play_time DESC 
									LIMIT $start,$perpage ");
				while ($value = DB::fetch($query)) {
					$value['event_url'] = get_full_url($sys_config['site_url'],'index.php?mod=event&code=detail&id='.$value['id']);
					$value['radio_value'] = str_replace(array('"', '\''), '', $value['title']).' - '.$value['event_url'];
					$list[] = $value;
				}
				$multi = ajax_page($count, $perpage, $page, 'getJoinEventList');
			}
		}

		include(template('event_list'));
		
	}
	
	
	function eventPost(){
		$top = 'top';
				$free = 'checked';
		$all = 'checked';
		$info = array();
		
				$need_info = $this->DatabaseHandler->ResultFirst("select need_info from ".TABLE_PREFIX."event_info");
		$info = unserialize($need_info);

				Load::lib('form');
		$FormHandler = new FormHandler();
		$query = $this->DatabaseHandler->Query("select * from ".TABLE_PREFIX."event_sort order by id");
		while ($rsdb = $query->GetRow()){
			$rs[$rsdb[id]]['value'] = $rsdb['id'];
			$rs[$rsdb[id]]['name'] = $rsdb['type'];
		}
		$event_type = $FormHandler->Select("type",$rs);
		
				if($this->Member['province']){
			$province_id = $this->DatabaseHandler->ResultFirst("select id from ".TABLE_PREFIX."common_district where name = '".$this->Member['province']."' and upid = 0");
		}else{
			$province_id = $this->DatabaseHandler->ResultFirst("select id from ".TABLE_PREFIX."common_district where upid = 0 order by list");
		}
		$query = $this->DatabaseHandler->Query("select * from ".TABLE_PREFIX."common_district where upid = 0 order by list");
		while ($rsdb = $query->GetRow()){
			$province[$rsdb['id']]['value']  = $rsdb['id'];
			$province[$rsdb['id']]['name']  = $rsdb['name'];
		}
		$hid_province = $province_id;
		$province_list = $FormHandler->Select("province",$province,$province_id,"onchange=\"changeProvince();\"");

				if($this->Member['city']){
			$city_id = $this->DatabaseHandler->ResultFirst("select id from ".TABLE_PREFIX."common_district where name = '".$this->Member['city']."' and upid = '$province_id'");
			$where_city = " and upid = '$city_id' ";
		}
		$hid_city = $city_id;
		
				if($this->Member['area']){
			$area_id = $this->DatabaseHandler->ResultFirst("select id from ".TABLE_PREFIX."common_district where name = '".$this->Member['area']."' $where_city");
		}
		$hid_area = $area_id;

				$fromt = my_date_format(TIMESTAMP, 'Y-m-d');
		$hour_select_from = mk_time_select('hour',false,'hour_select_from');
		$min_select_from =  mk_time_select('min',false,'min_select_from');

		$tot = my_date_format(TIMESTAMP+7*24*3600, 'Y-m-d');
		$hour_select_to = mk_time_select('hour',false,'hour_select_to');
		$min_select_to =  mk_time_select('min',false,'min_select_to');
		
		include($this->TemplateHandler->Template('event_publish'));
	}
	
	
	
	function create(){
		$post = $this->Post;
	    if(!$post['name']){
	    	json_error("请输入活动标题");
	    }
		if(filter($post['name'])){
			json_error("活动标题".filter($post['name']));
		}
		if(!$post['content1']){
	    	json_error("请输入活动描述");
	    }
		if(filter($post['content1'])){
			json_error("活动描述".filter($post['content1']));
		}
		if(!$post['address']){
	    	json_error("请输入活动地址");
	    }
		if(filter($post['address'])){
			json_error("活动地址".filter($post['address']));
		}
		if($post['money_r'] == 'money' && !$post['money']){
	    	json_error("请输入活动人均费用");
	    }
	    if($post['money_r'] == 'money' && !is_numeric($post['money'])){
	    	json_error("活动人均费用应为数字");
	    }
		if($post['qua']=='qua' && $post['fans'] && !is_numeric($post['fans_num'])){
	    	json_error("粉丝数应为数字");
	    }
		if(!$post['fromt']){
	    	json_error("请输入活动开始时间");
	    }
		if(!$post['tot']){
	    	json_error("请输入活动结束时间");
	    }
	    	    $fromt = strtotime($post['fromt']." ".$post['hour_select_from'].":".$post['min_select_from']);
	    $tot = strtotime($post['tot']." ".$post['hour_select_to'].":".$post['min_select_to']);
		if($fromt >= $tot){
	    	json_error("活动结束时间不能早于开始时间");
	    }
	    	    $qua_arr = array();
	    if($post['qua'] == 'qua'){
	    	if($post['fans']){
	    		$qua_arr['fans_num'] = $post['fans_num'];
	    	}
	    }
		if($post['same_city']){
	    	$qua_arr['same_city'] = 1;
	    }
	    $qualification = serialize($qua_arr);
	    	    $new_info_arr = array();
	    $info = $this->DatabaseHandler->ResultFirst("select need_info from ".TABLE_PREFIX."event_info");
	    $info_arr = unserialize($info);
	    foreach ($info_arr as $key => $value) {
	    	if($post[$value[ename]]){
	    		$new_info_arr[$key] = $value;
	    	}
	    }
	    $need_app_info = serialize($new_info_arr);
	    	    $postman = MEMBER_ID;
	    	    if($post['money_r'] == 'money'){
	    	$money = $post['money'];
	    }else{
	    	$money = 0;
	    }
	    $time = TIMESTAMP;
	    	    if($post[act]){
	    	$sql = "update ".TABLE_PREFIX."event 
	    			set 
	    			    type_id = '$post[type]',
	    			    title = '$post[name]',
	    			    fromt = '$fromt',
	    			    tot = '$tot',
	    			    content = '$post[content1]',
	    			    image = '$post[hid_pic]',
	    			    province_id = '$post[province]',
	    			    area_id = '$post[area]',
	    			    city_id = '$post[city]',
	    			    address = '$post[address]',
	    			    money = $money,
	    			    posttime = $time,
	    			    lasttime = $time,
	    			    qualification = '$qualification',
	    			    need_app_info = '$need_app_info' 
	    			where id  = $post[id]";
	    	$this->DatabaseHandler->Query($sql);
	    	$values = array(
				'id' => $post[id],
			);
	    	json_result("修改成功",$values);
	    }
	    $sql = "insert into ".TABLE_PREFIX."event (
	    			type_id,title,fromt,tot,content,
	    			image,province_id,area_id,city_id,address,money,
	    			postman,posttime,lasttime,qualification,need_app_info
	    ) values (
	    			'$post[type]','$post[name]','$fromt','$tot','$post[content1]',
	    			'$post[hid_pic]','$post[province]','$post[area]','$post[city]','$post[address]',$money,
	    			'$postman',$time,$time,'$qualification','$need_app_info'
	    )";
	    $this->DatabaseHandler->Query($sql);
	    $id = mysql_insert_id();
	    
	    	    $query = $this->DatabaseHandler->Query("select uid from ".TABLE_PREFIX."event_favorite where type_id = '$post[type]'");
	    $uid_arr = array();
	    while ($rsdb = $query->GetRow()){
	    	$uid_arr[$rsdb['uid']] = $rsdb['uid'];
	    }
	    if(in_array(MEMBER_ID,$uid_arr)){
	    	unset($uid_arr[MEMBER_ID]);
	    }
	    if($uid_arr){
	    	$this->DatabaseHandler->Query("update ".TABLE_PREFIX."members set event_post_new = event_post_new+1 where uid in (".implode(',',$uid_arr).")");
	    }
	    
	    $value = '我发布了一个活动【'.$post[name].'】，地址：' . get_full_url($sys_config['site_url'],"index.php?mod=event&code=detail&id=$id");
	    if($post['top'] == 'top'){
			$values = array(
					'id' => $id,
					'content' => $value,
					'from' => '',
				);
			json_result('发布成功', $values);
		}
		$item_id = $id;
		$msg = '发布成功';
		include($this->TemplateHandler->Template('vote_toweibo'));
		exit;
	}
	
	
	function publishShare(){
		$id= (int)$this->Get['id'];
		$name = $this->DatabaseHandler->ResultFirst("select title from ".TABLE_PREFIX."event where id = '$id'");
		$value = '我觉得活动【'.$name.'】不错，推荐给大家，地址：' . get_full_url($sys_config['site_url'],"index.php?mod=event&code=detail&id=$id");
		$item_id = $id;
		include($this->TemplateHandler->Template('vote_toweibo'));
		exit;
	}
	
	
	function simpleTalk(){
		$id= (int)$this->Get['id'];
		$item_id = $id;
		$msg = '报名成功';
		$chk_topic_type = 'talk';
		include($this->TemplateHandler->Template('vote_toweibo'));
		exit;
	}
	
	
	function store(){
	    $id = (int)$this->Get['id'];
	    $type = $this->Get['type'];
	    $time = TIMESTAMP;
	    if($type == 'cancle'){
	    	$this->DatabaseHandler->Query("update ".TABLE_PREFIX."event_member set store = 0,store_time = $time where id = '$id' and fid = ".MEMBER_ID);
	    }else{
		    $count = $this->DatabaseHandler->ResultFirst("select count(*) from ".TABLE_PREFIX."event_member where id = '$id' and fid = ".MEMBER_ID);
		    if($count){
		    	$this->DatabaseHandler->Query("update ".TABLE_PREFIX."event_member set store = 1,store_time = $time where id = '$id' and fid = ".MEMBER_ID);
		    }else{
		    	$title = $this->DatabaseHandler->ResultFirst("select title from ".TABLE_PREFIX."event where id = '$id'");
		    	$this->DatabaseHandler->Query("insert into ".TABLE_PREFIX."event_member (id,title,fid,store,store_time) values('$id','$title',".MEMBER_ID.",1,$time)");
		    }
	    }
	}
	
	
	function app(){
	    $id = (int)$this->Get['id'];
	    $type = $this->Get['type'];
	    $time = TIMESTAMP;
	    	    $query = $this->DatabaseHandler->Query("select tot,qualification,need_app_info,city_id,postman from ".TABLE_PREFIX."event where id = '$id'");
	    $rs = $query->GetRow();
	  
		if($rs['tot'] < TIMESTAMP){
	    	json_error("活动已截止");
	    }else{
	    	$query = $this->DatabaseHandler->Query("select app,play from ".TABLE_PREFIX."event_member where id = '$id' and fid = ".MEMBER_ID);
	    	$user_info = $query->GetRow();
			if($type == 'cancle'){
				if($user_info['play']){
					json_error("你已参加该活动，请先联系活动发起者取消你的参加资格");
				}elseif($user_info['app'] == 0){
					json_error("你未报名该活动");
				}else{
			    	$this->DatabaseHandler->Query("update ".TABLE_PREFIX."event_member set app = 0,app_time = $time where id = '$id' and fid = ".MEMBER_ID);
			    	$this->DatabaseHandler->Query("update ".TABLE_PREFIX."event set app_num = app_num - 1,lasttime = $time where id = '$id'");
			    	json_result("取消报名成功");
				}
		    }else{
		    			    	if($user_info['app'] == 1){
		    		json_error("你已报名该活动");
		    	}
		    	$qua = unserialize($rs['qualification']);
		    	if($rs['postman'] != MEMBER_ID){
					if($qua['fans_num']){
				    	$fans_num = $this->DatabaseHandler->ResultFirst("select fans_count from ".TABLE_PREFIX."members where uid = ".MEMBER_ID);
				    	if($fans_num < $qua['fans_num']){
				    		json_error("报名活动者的粉丝数不能少于".$qua['fans_num']."人");
				    	}
				    }
					if($qua['same_city']){
						$city_name = $this->DatabaseHandler->ResultFirst("select name from ".TABLE_PREFIX."common_district where id = ".$rs['city_id']);
						if($this->Member[city]){
					    	$city_id = $this->DatabaseHandler->ResultFirst("select id from ".TABLE_PREFIX."common_district where name = '".$this->Member[city]."' and level = 2 ");
					    	if($city_id != $rs['city_id']){
					    		json_error("该活动仅限".$city_name."的朋友参与，您当前不符合要求");
					    	}
						}else{
							json_error("该活动仅限".$city_name."的朋友参与，您当前不符合要求");
						}
				    }
		    	}
		    	
		    	$need_info = unserialize($rs['need_app_info']);
		    			    			    			    	if(count($need_info)){
		    		$html = $this->_makeSimpleHtml($need_info);
		    		include($this->TemplateHandler->Template('need_info'));
					exit;
		    	}else{
				    $count = $this->DatabaseHandler->ResultFirst("select count(*) from ".TABLE_PREFIX."event_member where id = '$id' and fid = ".MEMBER_ID);
				    if($count){
				    	$this->DatabaseHandler->Query("update ".TABLE_PREFIX."event_member set app = 1,app_time = $time where id = '$id' and fid = ".MEMBER_ID);
				    }else{
				    	$title = $this->DatabaseHandler->ResultFirst("select title from ".TABLE_PREFIX."event where id = '$id'");
				    	$this->DatabaseHandler->Query("insert into ".TABLE_PREFIX."event_member (id,title,fid,app,app_time) values('$id','$title',".MEMBER_ID.",1,$time)");
				    }
				    $this->DatabaseHandler->Query("update ".TABLE_PREFIX."event set app_num = app_num + 1,lasttime = $time where id = '$id'");
				    
				    					$query = $this->DatabaseHandler->Query("select fid from ".TABLE_PREFIX."event_member where id = '$id' ");
					while ($rsdb = $query->GetRow()){
						$id_arr[$rsdb['fid']] = $rsdb['fid'];
					}
		    		if(!in_array($rs['postman'],$id_arr)){
						$id_arr[$rs['postman']] = $rs['postman'];
					}
					if(in_array(MEMBER_ID,$id_arr)){
						unset($id_arr[MEMBER_ID]);
					}
					foreach ($id_arr as $val) {
						$this->DatabaseHandler->Query("update ".TABLE_PREFIX."members set event_new = event_new + 1 where uid = '$val'");
					}

				    json_result("1");
		    	}
		    }
		    
	    }
	}
	
	
	function need_info(){
		$id = (int)$this->Get['id'];
		$post = $this->Post;
				$qua = $this->DatabaseHandler->ResultFirst("select need_app_info from ".TABLE_PREFIX."event where id = '$id'");
		$qua_info = unserialize($qua);
		$qua_arr = array();
		$time = TIMESTAMP;
				foreach ($qua_info as $key=>$val) {
			if(!$post[$val['ename']]){
				json_error("请输入$val[name]");
			}
			$qua_arr[$val['name']] = $post[$val['ename']];
		}
		$qua_arr['留言'] = $post['content'];
		
		$qua = serialize($qua_arr);
				$count = $this->DatabaseHandler->ResultFirst("select count(*) from ".TABLE_PREFIX."event_member where id = '$id' and fid = ".MEMBER_ID);
		if($count){
			$this->DatabaseHandler->Query("update ".TABLE_PREFIX."event_member set app = 1 ,app_time = $time ,app_info = '$qua' where id = '$id' and fid = ".MEMBER_ID);
		}else{
			$title = $this->DatabaseHandler->ResultFirst("select title from ".TABLE_PREFIX."event where id = '$id'");
			$this->DatabaseHandler->Query("insert into ".TABLE_PREFIX."event_member (id,title,fid,app,app_time,app_info) values('$id','$title',".MEMBER_ID.",1,$time,'$qua')");
		}
		$this->DatabaseHandler->Query("update ".TABLE_PREFIX."event set app_num = app_num + 1,lasttime = $time where id = '$id'");
		
				$postman = $this->DatabaseHandler->ResultFirst("select postman from ".TABLE_PREFIX."event where id = '$id' ");
		$query = $this->DatabaseHandler->Query("select fid from ".TABLE_PREFIX."event_member where id = '$id' ");
		$id_arr = array();
		while ($rsdb = $query->GetRow()){
			$id_arr[$rsdb['fid']] = $rsdb['fid'];
		}
	    if(!in_array($postman,$id_arr)){
			$id_arr[$postman] = $postman;
		}
		if(in_array(MEMBER_ID,$id_arr)){
			unset($id_arr[MEMBER_ID]);
		}

		foreach ($id_arr as $val) {
			$this->DatabaseHandler->Query("update ".TABLE_PREFIX."members set event_new = event_new + 1 where uid = $val");
		}
		json_result("1");
	}
	
	
	function manage(){
		$id = (int)$this->Get['id'];
		
		$page = empty($this->Get['page']) ? 0 : intval($this->Get['page']);
		$perpage = 8;
		if ($page == 0) {
			$page = 1;
		}
		$start = ($page - 1) * $perpage;
		
		$rs = array();
		$count = $this->DatabaseHandler->ResultFirst("select count(*) from ".TABLE_PREFIX."event_member where id = '$id' and app = 1");
		$sql = "select am.fid,m.nickname,am.app_info,am.app_time,am.play from ".TABLE_PREFIX."event_member am 
				left join ".TABLE_PREFIX."members m on m.uid = am.fid 
				where am.id = '$id' 
				and am.app = 1 
				order by am.app_time desc 
				limit $start,$perpage";
		$multi = ajax_page($count, $perpage, $page, 'manage');
		$query = $this->DatabaseHandler->Query($sql);
		while ($rsdb = $query->GetRow()){
						if($rsdb['play']){
				$rsdb['type'] = "已参加";
				$rsdb['check'] = 'checked';
			}else{
				$rsdb['type'] = "尚未审核";
			}
						$rsdb['app_time'] = date("m-d h:i",$rsdb['app_time']);
						$app_info = unserialize($rsdb['app_info']);
			foreach ($app_info as $key=>$value) {
				$rsdb['app'][$key] = $value;
			}
			$rs[$rsdb['fid']] = $rsdb;
		}
    	include($this->TemplateHandler->Template('event_manage'));
		exit;
	}
	
	
	function choose(){
		$post = $this->Post;
		$type = $this->Get['type'];
		$time = TIMESTAMP;
		$i = 0;
		if($type == 'agree'){
			foreach ($post['che'] as $key=>$val) {
				if(!$val){
					$this->DatabaseHandler->Query("update ".TABLE_PREFIX."event_member set play = 1,play_time = $time where id = $post[id] and fid = $key");
					$i++;
				}
			}
		}else{
			foreach ($post['che'] as $key=>$val) {
				if($val){
					$this->DatabaseHandler->Query("update ".TABLE_PREFIX."event_member set play = 0,play_time = 0 where id = $post[id] and fid = $key");
					$i--;
				}
			}
		}
		$this->DatabaseHandler->Query("update ".TABLE_PREFIX."event set play_num = play_num + $i where id = $post[id] ");
		json_result("成功");
	}
	
	function onloadPic(){
        
        Load::lib('io');
        Load::lib('image');
        $IoHandler = new IoHandler();
        $image = new image();
		Load::lib('upload');
		unlink($this->Post['hid_pic']);
        if($_FILES['pic']['name']){
			$type = trim(strtolower(end(explode(".",$_FILES['pic']['name']))));
			$name = time().MEMBER_ID;
			$image_name = $name."_b".".{$type}";
			$image_path = RELATIVE_ROOT_PATH . './images/event/';
			$image_file = $image_path . $image_name;
			
						$image_name_show = $name."_s".".{$type}";
			$image_file_min = $image_path . $image_name_show;
			
			if (!is_dir($image_path))
			{
				$IoHandler->MakeDir($image_path);
			}

			$UploadHandler = new UploadHandler($_FILES,$image_path,'pic',true);
			$UploadHandler->setMaxSize(2048);
			$UploadHandler->setNewName($image_name);
			$result=$UploadHandler->doUpload();
			
			makethumb($image_file,$image_file_min,100,100,0,0,0,0,0,0);
			
			if($result)
	        {
				$result = is_image($image_file);
			}
			if(!$result)
	        {
				unlink($image_file);
				unlink($image_file_min);
				echo "<script language='Javascript'>";
				echo "parent.document.getElementById('message').style.display='block';";
				echo "parent.document.getElementById('uploading').style.display='none';";
	        	if($this->Post['top'] == 'top'){
					echo "parent.document.getElementById('back1').style.display='block';";
					echo "parent.document.getElementById('next3').style.display='block';";
				}
				echo "parent.document.getElementById('showimg').src='';";
				echo "parent.document.getElementById('hid_pic').value='';";
				echo "parent.document.getElementById('message').innerHTML='图片上载失败'";
				echo "</script>";
				exit;
			}
			$image->param['ignored_animation'] = 0;
			$image->Thumb($image_file,$image_file,128,128);
			
        				if($this->Config['watermark_enable']) 
            {
            	$arr = @getimagesize($image_file);
                if($arr && 'image/gif' != $arr['mime'] && 'image/png' != $arr['mime'])
                {
                	$this->_watermark($image_file,$this->Config['site_url'] . "/" . MEMBER_NAME);
                }
			}

			echo "<script language='Javascript'>";
			echo "parent.document.getElementById('uploading').style.display='none';";
			if($this->Post['top'] == 'top'){
				echo "parent.document.getElementById('back1').style.display='block';";
				echo "parent.document.getElementById('next3').style.display='block';";
			}
			echo "parent.document.getElementById('message').style.display='none';";
			echo "parent.document.getElementById('img').style.display='block';";
			echo "parent.document.getElementById('showimg').src='{$image_file}';";
			echo "parent.document.getElementById('hid_pic').value='{$image_file}';";
			echo "</script>";
			exit;
        }
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
			
		} else {
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
	
	
	function _member()
	{
		if (MEMBER_ID < 1) {
			$this->Messager(null,$this->Config['site_url'] . "/index.php?mod=login");
		}		
		
		$member = $this->TopicLogic->GetMember(MEMBER_ID);
		
		return $member;
	}
	
	
	function _makeSimpleHtml($arr){
		foreach ($arr as $key=>$val) {
			$html .="<tr>";
			if($val['form_type'] == 'text'){
				$html .= "<td>$val[name]:<span style=\"color:red\">*</span></td><td><input type=\"text\" id=\"$val[ename]\" name=\"$val[ename]\"></td>";
			}
			if($val['form_type'] == 'select'){
				$detail=explode("\r\n",$val[form_set]);
				foreach ($detail as $val1){
					$_html .= "<option value=\"$val1\">$val1</option>";
				}
				$html .= "<td>$val[name]:<span style=\"color:red\">*</span></td>
						  <td>
						    <select id=\"$val[ename]\" name=\"$val[ename]\">
						     $_html
						    </select>
						  </td>";
				$_html = "";
			}
			$html .="</tr>";
		}
		$html .="<tr>
				  <td>留言：</td>
				  <td><input type=\"text\" id=\"content\" name=\"content\"></td>
				 </tr>";
		return $html;
	}
	
		function UpdateNewMsgCount($num,$uids='')
	{
		if($uids=='')$uids=MEMBER_ID;
		
		$uids=$this->DatabaseHandler->BuildIn($uids,'uid');
		if(!$uids) return ;
		
		$strpos = strpos($num,'-');
		if($strpos!==0)
		{
			$num="+".$num;
		}
		
		$sql="
		UPDATE
			".TABLE_PREFIX.'members'."
		SET
			newpm=newpm $num
		WHERE
			$uids";
			
		$this->DatabaseHandler->Query($sql);
		
		$ret = $this->DatabaseHandler->AffectedRows();;

		if(0 === $strpos) 
		{
			$this->DatabaseHandler->Query("update ".TABLE_PREFIX."members set `newpm`=0 where $uids and `newpm`<0");
		}
		
		return $ret;
	}
}