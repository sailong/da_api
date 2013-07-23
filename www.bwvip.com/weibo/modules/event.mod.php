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

 * @Date 2011-09-28 19:16:47 1991330428 2128215106 37176 $

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

		Load::logic('topic');
		$this->TopicLogic = new TopicLogic($this);
		
		if(!ConfigHandler::get('event_setting')){
			$this->Messager("管理员已关闭活动功能");
		};
		$this->Member = $this->_member();
		
		$this->Execute();

	}

	
	function Execute()
	{
		ob_start();
		switch ($this->Code){
			case 'pevent':
				$this->pevent();
				break;
			case 'editevent':
				$this->editEvent();
				break;
			case 'myevent':
				$this->myevent();
				break;
			case 'followevent':
				$this->followevent();
				break;
			case 'detail':
				$this->eventDetail();
				break;
			case 'del':
				$this->delevent();
				break;
			case 'alluser':
				$this->getAllUser();
				break;
			case 'export_to_excel':
            	$this->ExportToExcel();
            	break;   
			default:
				$this->main();
				break;			
		}
		$body=ob_get_clean();

		$this->ShowBody($body);
	}
	
	
	function main(){
		Load::lib('form');
		$FormHandler = new FormHandler();
		
		$hot_event = $this->getHotEvent();
				$uids = array();
		$sql = "SELECT postman  
				FROM `".TABLE_PREFIX."event` 
				GROUP BY postman 
				ORDER BY count( postman ) DESC 
				LIMIT 12 ";
		$query = $this->DatabaseHandler->Query($sql);
		while ($rsdb = $query->GetRow()){
			$uids[] = $rsdb['postman'];
		}
		if (!empty($uids)) {
			$hd_daren = $this->TopicLogic->GetMember($uids);
		}
		
				$event_type = array();
		$query = $this->DatabaseHandler->Query("select * from ".TABLE_PREFIX."event_sort order by id");
		while ($rsdb = $query->GetRow()){
			$acount = $this->DatabaseHandler->ResultFirst("select count(*) from ".TABLE_PREFIX."event where type_id = '$rsdb[id]'");
			$event_type[$rsdb['id']]['count'] = $acount;
			$event_type[$rsdb['id']]['type'] = $rsdb['type'];
		}

		$where = "";
		$type = intval($this->Get['type']);
		if($type){
			$where = " and a.type_id = $type ";
			$tclass[$type] = " class='fred'";
			$hid_type = $type;
		}else{
			$tclass[0] = " class='fred'";
		}

						$choose_province = intval($this->Get['province']);
		$choose_city = intval($this->Get['city']);
		$choose_area = intval($this->Get['area']);
		$nocity = $this->Get['nocity'];
		
		if($choose_province){
			$hid_province = $choose_province;
		}
		if($this->Member['province']){
			$province_id = $this->DatabaseHandler->ResultFirst("select id from ".TABLE_PREFIX."common_district where name = '".$this->Member['province']."' and upid = 0");
		}
		else{
			$province_id = $this->DatabaseHandler->ResultFirst("select id from ".TABLE_PREFIX."common_district where upid = 0 order by list");
		}
		$query = $this->DatabaseHandler->Query("select * from ".TABLE_PREFIX."common_district where upid = 0 order by list");
		while ($rsdb = $query->GetRow()){
			$province[$rsdb['id']]['value']  = $rsdb['id'];
			$province[$rsdb['id']]['name']  = $rsdb['name'];
		}
		$province_id = $choose_province ? $choose_province : $province_id;
		$province_list = $FormHandler->Select("province",$province,$province_id," style=\"display:none\" onchange=\"changeProvince();\"");

		if($province_id){
			$hid_province = $province_id;
			if($this->Member['city']){
				$city_id = $this->DatabaseHandler->ResultFirst("select id from ".TABLE_PREFIX."common_district where name = '".$this->Member['city']."'");
			}else{
				$city_id = $this->DatabaseHandler->ResultFirst("select id from ".TABLE_PREFIX."common_district where upid = '$province_id' order by list");
			}
			$query = $this->DatabaseHandler->Query("select * from ".TABLE_PREFIX."common_district where upid = $province_id order by list");
			while ($rsdb = $query->GetRow()){
				$city[$rsdb['id']]['value'] = $rsdb['id'];
				$city[$rsdb['id']]['name'] = $rsdb['name'];
			}
			$city_id = $choose_city ? $choose_city : $city_id;
			$city_name = $this->DatabaseHandler->ResultFirst("select name from ".TABLE_PREFIX."common_district where id = '$city_id' ");
			$city_list = $FormHandler->Select("city",$city,$city_id," style=\"display:none\" onchange=\"changeCity();\"");
			
			if($city_id){
				$query = $this->DatabaseHandler->Query("select * from ".TABLE_PREFIX."common_district where upid = '$city_id' order by list");
				while ($rsdb = $query->GetRow()){
					$area[$rsdb['id']] = $rsdb['name'];
				}
			}
		}
		
		if($nocity){
			$cclass['no'] = " class='fred'";
			$hid_city = $city_id;
			$hid_no_city = $nocity;
		}else{
			if($city_id && !$choose_area){
				$where .= " and a.city_id = '$city_id' ";
				$cclass[$city_id] = " class='fred'";
				$hid_city = $city_id;
			}else{
				if($choose_area){
					$where .= " and a.area_id = '$choose_area' ";
					$cclass[$choose_area] = " class='fred'";
					$hid_city = $city_id;
					$hid_area = $choose_area;
				}
			}
		}
		
				$event = array();
		$_config = array('return' => 'array',);
		$per_page_num = 10;
		$page_url = "index.php?mod=event&province=$choose_province&city=$choose_city&area=$choose_area&type=$type&nocity=$nocity";
		$count = $this->DatabaseHandler->ResultFirst("select count(*) from ".TABLE_PREFIX."event a where 1 $where ");
		$page_arr = page($count,$per_page_num,$page_url,$_config);
	    $sql = "select a.id,a.title,a.fromt,a.tot,
	    			   a.content,a.image,fp.name as province,fa.name as area,fc.name as city,
	    			   a.address,a.money,a.app_num,a.play_num,
	    			   a.postman,a.posttime,m.nickname,m.username,m.uid 
	    		from ".TABLE_PREFIX."event a 
	    		left join ".TABLE_PREFIX."common_district fp on fp.id = a.province_id 
	    		left join ".TABLE_PREFIX."common_district fa on fa.id = a.area_id  
				left join ".TABLE_PREFIX."common_district fc on fc.id = a.city_id 
				left join ".TABLE_PREFIX."members m on m.uid = a.postman 
				where 1 $where
	    		order by a.posttime DESC , a.app_num DESC 
	    		$page_arr[limit]";
		$query = $this->DatabaseHandler->Query($sql);
		while ($rsdb = $query->GetRow()){
			if($rsdb['tot'] <= TIMESTAMP){
		    	$rsdb['event_type'] = "活动已截止";
		    }else{
		    	$rsdb['event_type'] = "活动进行中";
		    	$app = "style='display:none'";
		    }
		    
		    		    $rsdb['fromt_day'] = $this->_getDay($rsdb['fromt']);
		    $rsdb['fromt'] = date("Y年m月d日 H:i",$rsdb['fromt']);
		    
		    		    $rsdb['tot_day'] = $this->_getDay($rsdb['tot']);
		    $rsdb['tot'] = date("Y年m月d日 H:i",$rsdb['tot']);
		    
		    		    $rsdb['address'] = $rsdb['province'].$rsdb['city'].$rsdb['area'].$rsdb['address'];
						if(!$rsdb[image]){
				$rsdb[image] = "images/kuang.png";
			}
		    		    if($rsdb['money'] == 0){
		    	$rsdb['money'] = "免费";
		    }else{
		    	$rsdb['money'] = "人均".$rsdb['money']."元";
		    }
		    $event[$rsdb['id']] = $rsdb;
		}		
		
				if(MEMBER_STYLE_THREE_TOL == 1)
		{
			$member = $this->Member;
			if ($member['medal_id']) {
				$medal_list = $this->TopicLogic->GetMedal($member['medal_id'],$member['uid']);
			}
		}

		$this->Title = "活动";
		include($this->TemplateHandler->Template('event'));
	}
	
	
	function getAllUser(){
		$id = intval($this->Get['id']);
		$type = $this->Get['type'];
		$per_page_num = 20;
		$page_url = "index.php?mod=event&code=alluser&id=$id&type=$type";
		$_config = array('return' => 'array',);
		$title = $this->DatabaseHandler->ResultFirst("select title from ".TABLE_PREFIX."event where id = '$id'");
				if($type == 'app'){
			$count = $this->DatabaseHandler->ResultFirst("select count(*) from ".TABLE_PREFIX."event_member where id = '$id' and app = 1 and play = 0  ");
			$page_arr = page($count,$per_page_num,$page_url,$_config);
		    $sql = "select m.uid,m.nickname,m.username,m.face,m.province,m.city,a.app_time 
		    		from ".TABLE_PREFIX."event_member a 
		    		left join ".TABLE_PREFIX."members m on m.uid=a.fid 
		    		where a.id = '$id' and a.app = 1 and a.play = 0 
		    		order by a.app_time 
		    		$page_arr[limit] ";
		    $query = $this->DatabaseHandler->Query($sql);
		    while ($rsdb = $query->GetRow()){
		    	$rsdb['face'] = face_get($rsdb['uid']);
		    	$rsdb['time'] = date("Y-m-d H:i",$rsdb['app_time']);
		    	$member[$rsdb['uid']] = $rsdb;
		    }
			$name = "报名";
				}else{
			$count = $this->DatabaseHandler->ResultFirst("select count(*) from ".TABLE_PREFIX."event_member where id = '$id' and play = 1  ");
			$page_arr = page($count,$per_page_num,$page_url,$_config);
			$sql = "select m.uid,m.nickname,m.username,m.face,m.province,m.city,a.play_time  
					from ".TABLE_PREFIX."event_member a 
		    		left join ".TABLE_PREFIX."members m on m.uid=a.fid 
		    		where a.id = '$id' and a.play = 1 
		    		order by a.play_time 
		    		$page_arr[limit] ";
		    $query = $this->DatabaseHandler->Query($sql);
		    while ($rsdb = $query->GetRow()){
		    	$rsdb['face'] = face_get($rsdb['uid']);
		    	$rsdb['time'] = date("Y-m-d H:i",$rsdb['play_time']);
		    	$member[$rsdb['uid']] = $rsdb;
		    }
		    $name = "参与";
		}
		if(MEMBER_STYLE_THREE_TOL == 1)
		{
			$my_member = $this->TopicLogic->GetMember(MEMBER_ID);
			if ($my_member['medal_id']) {
				$medal_list = $this->TopicLogic->GetMedal($my_member['medal_id'],$my_member['uid']);
			}
			
		}

		$this->Title = $title."的所有".$name."者";
		include($this->TemplateHandler->Template('event_member'));
	}
	
	
	function delevent(){
		$id = intval($this->Get['id']);
						$count = $this->DatabaseHandler->ResultFirst("select count(*) from ".TABLE_PREFIX."event_member where id='$id' and app = 1 ");
		if($count){
			$this->Messager("该活动已有报名者，您不能删除该活动，如要删除，请联系管理员",-1);
		}
				$image = $this->DatabaseHandler->ResultFirst("select image from ".TABLE_PREFIX."event where id = '$id' ");
		if($image){
			$type = trim(strtolower(end(explode(".",$image))));
			$name = explode("_",$rsdb['image']);
			$image_s = $name[0]."_s.".$type;
			
		    unlink($image);
			unlink($image_s);
		}
				$this->DatabaseHandler->Query("delete from ".TABLE_PREFIX."event where id = '$id' ");
		$this->DatabaseHandler->Query("delete from ".TABLE_PREFIX."event_member where id = '$id' ");
		$this->Messager("删除活动成功","index.php?mod=event&code=myevent");
		
	}
	
	
	function ExportToExcel(){
		$id = intval($this->Get['id']);
		$list = array();
		$query = $this->DatabaseHandler->Query("select a.title,a.fromt,a.tot,a.money,a.play_num,a.address,c3.name as province,c1.name as area,c2.name as city,m.nickname,a.need_app_info from ".TABLE_PREFIX."event a 
												left join ".TABLE_PREFIX."common_district c3 on c3.id = a.province_id 
									    		left join ".TABLE_PREFIX."common_district c1 on c1.id = a.area_id  
												left join ".TABLE_PREFIX."common_district c2 on c2.id = a.city_id 
												left join ".TABLE_PREFIX."members m on m.uid = a.postman 
												where a.id = '$id'");
		$event = $query->GetRow();
		
				$list[0][0] = '标题:';
		$list[0][1] = $event['title'];
				$list[1][0] = '时间:';
		$list[1][1] = date("Y/m/d/ H:i",$event['fromt'])."-".date("Y/m/d H:i",$event['tot']);
				$list[2][0] = '地点:';
		$list[2][1] = $event['province'].$event['city'].$event['area'].$event['address'];
				$list[3][0] = '发起人:';
		$list[3][1] = $event['nickname'];
				$list[4][0] = '参与人数:';
		$list[4][1] = $event['play_num']."人";
		
		$list[5] = array();
		
				$list[6][0] = '申请者';
		$j = 1;
		foreach (unserialize($event['need_app_info']) as $value) {
			$list[6][$j] = $value['name'];
			$j++;
		}
		$list[6][$j] = '留言';

		$i = 7;
		$query = $this->DatabaseHandler->Query("select a.app_info,m.nickname from ".TABLE_PREFIX."event_member a
												left join ".TABLE_PREFIX."members m on m.uid = a.fid  
												where id = '$id' and play = 1 ");
		while ($member = $query->GetRow()){
			$app_info = unserialize($member['app_info']);
			$list[$i][0] = $member['nickname'];
			$k = 1;
			foreach ($app_info as $value) {
				$list[$i][$k] = $value;
				$k++;
			}
			$i++;
			
		}
		Load::lib('php-excel');
		$xls = new Excel_XML($this->Config['charset']);		
		$xls->addArray($list);
		$xls->generateXML('event-'.date("YmdHis"));
		exit;
	}
	
	
	function eventDetail(){
	    $id = intval($this->Get['id']);
	    	    $sql = "select a.id,a.title,a.fromt,a.tot,
	    			   a.content,a.image,fp.name as province,fa.name as area,fc.name as city,
	    			   a.address,a.money,a.app_num,a.play_num,
	    			   a.postman,a.posttime,a.qualification,a.need_app_info,
	    			   m.uid,m.username,m.nickname,am.store,am.app
	    		from ".TABLE_PREFIX."event a 
	    		left join ".TABLE_PREFIX."event_member am on am.id = a.id and am.fid = ".MEMBER_ID."
	    		left join ".TABLE_PREFIX."common_district fp on fp.id = a.province_id 
	    		left join ".TABLE_PREFIX."common_district fa on fa.id = a.area_id  
				left join ".TABLE_PREFIX."common_district fc on fc.id = a.city_id 
				left join ".TABLE_PREFIX."members m on m.uid = a.postman 
	    		where a.id = '$id' ";
	    $query = $this->DatabaseHandler->Query($sql);
	    $rs = $query->GetRow();
	    if(!$rs){
	    	$this->Messager("活动不存在或已删除",-1);
	    }
	    	    if($rs['tot'] <= TIMESTAMP){
	    	$rs['event_type'] = "活动已截止";
	    }else{
	    	$rs['event_type'] = "活动进行中";
	    	$app = "style='display:none'";
	    }
	    
	    	    $rs['fromt_day'] = $this->_getDay($rs['fromt']);
	    $rs['fromt'] = date("Y年m月d日 H:i",$rs['fromt']);
	    
	    	    $rs['tot_day'] = $this->_getDay($rs['tot']);
	    $rs['tot'] = date("Y年m月d日 H:i",$rs['tot']);
	    
	    	    $rs['address'] = $rs['province'].$rs['city'].$rs['area'].$rs['address'];
				if(!$rs[image]){
			$rs[image] = "images/kuang.png";
		}
	    	    if($rs['money'] == 0){
	    	$rs['money'] = "免费";
	    }else{
	    	$rs['money'] = "人均".$rs['money']."元";
	    }
	    
		 	    $sql = "select m.uid,m.nickname,m.username,m.face from ".TABLE_PREFIX."event_member a 
	    		left join ".TABLE_PREFIX."members m on m.uid=a.fid 
	    		where a.id = '$id' and a.app = 1 and a.play = 0 
	    		order by a.app_time 
	    		limit 6 ";
	    $query = $this->DatabaseHandler->Query($sql);
	    $app_count = $this->DatabaseHandler->ResultFirst("select count(*) from ".TABLE_PREFIX."event_member where id = '$id' and app = 1 and play = 0 ");
	    while ($rsdb = $query->GetRow()){
	    	$rsdb['face'] = face_get($rsdb['uid']);
	    	$app_member[$rsdb['uid']] = $rsdb;
	    }
	    
	    	    $sql = "select m.uid,m.nickname,m.username,m.face from ".TABLE_PREFIX."event_member a 
	    		left join ".TABLE_PREFIX."members m on m.uid=a.fid 
	    		where a.id = '$id' and a.play = 1 
	    		order by a.play_time 
	    		limit 6 ";
	    $query = $this->DatabaseHandler->Query($sql);
	    $play_count = $this->DatabaseHandler->ResultFirst("select count(*) from ".TABLE_PREFIX."event_member where id = '$id' and play = 1 ");;
	    while ($rsdb = $query->GetRow()){
	    	$rsdb['face'] = face_get($rsdb['uid']);
	    	$play_member[$rsdb['uid']] = $rsdb;
	    }
	  
	    if(MEMBER_STYLE_THREE_TOL == 1)
	    {
	    	$member = $this->TopicLogic->GetMember(MEMBER_ID);
		    if ($member['medal_id']) {
				$medal_list = $this->TopicLogic->GetMedal($member['medal_id'],$member['uid']);
			}
	    }
	    
	    		Load::functions('app');
			$gets = array(
			'mod' => 'event',
			'code' => "detail",
			'id' => $id,
		);
		$page_url = 'index.php?'.url_implode($gets);

		$options = array(
			'page' => true,
			'perpage' => 5,				'page_url' => $page_url,
		);
		$topic_info = app_get_topic_list('event', $id, $options);
		$topic_list = array();
		if (!empty($topic_info)) {
			$topic_list = $topic_info['list'];
			$page_arr['html'] = $topic_info['page']['html'];
			$no_from = true;
		}
	    
	    $this->Title = "$rs[title]";
		include($this->TemplateHandler->Template('event_dateil'));
	}
	
	
	function myevent(){
		$hot_event = $this->getHotEvent();
		
				$uids = array();
		$sql = "SELECT postman  
				FROM `".TABLE_PREFIX."event` 
				GROUP BY postman 
				ORDER BY count( postman ) DESC 
				LIMIT 12 ";
		$query = $this->DatabaseHandler->Query($sql);
		while ($rsdb = $query->GetRow()){
			$uids[] = $rsdb['postman'];
		}
		if (!empty($uids)) {
			$hd_daren = $this->TopicLogic->GetMember($uids);
		}
		
		
				$event_type = array();
		$query = $this->DatabaseHandler->Query("select * from ".TABLE_PREFIX."event_sort order by id");
		while ($rsdb = $query->GetRow()){
			$acount = $this->DatabaseHandler->ResultFirst("select count(*) from ".TABLE_PREFIX."event where type_id = $rsdb[id]");
			$event_type[$rsdb['id']]['count'] = $acount;
			$event_type[$rsdb['id']]['type'] = $rsdb['type'];
		}
		
	    $type = $this->Get['type'];
	    $uid = intval($this->Get['uid']);
	    	    if($uid && $uid != MEMBER_ID){
	    	$user = $this->DatabaseHandler->ResultFirst("select nickname from ".TABLE_PREFIX."members where uid = '$uid'");
	    }else{
	    	$user = "我";
	    }
	    $uid = ($uid && $uid != MEMBER_ID) ? $uid : MEMBER_ID;
	    	    $per_page_num = 10;
	    $page_url = "";
	    $count = 0;
	    $rs = array();
	    $_config = array('return' => 'array',);
	    		if($type == 'part'){
			$sql = "select a.id,a.title,a.fromt,a.tot,a.image,fp.name as province,fa.name as area,fc.name as city, 
					m.app,m.app_time,m.play,m.play_time,m.store,m.store_time,a.address,a.app_num,a.play_num 
					from ".TABLE_PREFIX."event_member m  
					left join ".TABLE_PREFIX."event a on a.id = m.id 
					left join ".TABLE_PREFIX."common_district fp on fp.id = a.province_id 
					left join ".TABLE_PREFIX."common_district fa on fa.id = a.area_id 
					left join ".TABLE_PREFIX."common_district fc on fc.id = a.city_id 
					where 
					m.play = 1 
					and m.fid = '".$uid."' 
                    order by a.lasttime desc,a.app_num desc,a.posttime desc ";
			$page_url = "index.php?mod=event&code=myevent&type=part&uid=$uid";
			$count = $this->DatabaseHandler->ResultFirst("select count(*) from ".TABLE_PREFIX."event_member where play = 1 and fid = '".$uid."'");
			$this->Title = $user."参与的活动";
				}else if($type == 'app'){
			$sql = "select a.id,m.title,a.fromt,a.tot,a.image,fp.name as province,fa.name as area,fc.name as city, 
					m.app,m.app_time,m.play,m.play_time,m.store,m.store_time,a.address,a.app_num,a.play_num  
					from ".TABLE_PREFIX."event_member m  
					left join ".TABLE_PREFIX."event a on a.id = m.id 
					left join ".TABLE_PREFIX."common_district fp on fp.id = a.province_id 
					left join ".TABLE_PREFIX."common_district fa on fa.id = a.area_id 
					left join ".TABLE_PREFIX."common_district fc on fc.id = a.city_id 
					where 
					m.app = 1 
					and m.fid = '".$uid."'
                    order by a.lasttime desc,a.app_num desc,a.posttime desc ";
			$page_url = "index.php?mod=event&code=myevent&type=app&uid=$uid";
			$count = $this->DatabaseHandler->ResultFirst("select count(*) from ".TABLE_PREFIX."event_member where app = 1 and fid = '".$uid."'");
			$this->Title = $user."报名的活动";
				}else if($type == 'store'){
			$sql = "select a.id,a.title,a.fromt,a.tot,a.image,fp.name as province,fa.name as area,fc.name as city, 
					m.app,m.app_time,m.play,m.play_time,m.store,m.store_time,a.address,a.app_num,a.play_num   
					from ".TABLE_PREFIX."event_member m  
					left join ".TABLE_PREFIX."event a on a.id = m.id 
					left join ".TABLE_PREFIX."common_district fp on fp.id = a.province_id 
					left join ".TABLE_PREFIX."common_district fa on fa.id = a.area_id 
					left join ".TABLE_PREFIX."common_district fc on fc.id = a.city_id 
					where 
					m.store = 1 
					and m.fid = '".$uid."'
                    order by a.lasttime desc,a.app_num desc,a.posttime desc ";
			$page_url = "index.php?mod=event&code=myevent&type=store&uid=$uid";
			$count = $this->DatabaseHandler->ResultFirst("select count(*) from ".TABLE_PREFIX."event_member where store = 1 and fid = '".$uid."'");
			$this->Title = $user."收藏的活动";
				} else if ($type == 'new'){
						$this->DatabaseHandler->Query("update ".TABLE_PREFIX."members set event_new = 0 where uid = '$uid'");
			$this->MemberHandler->MemberFields['event_new'] = 0;
			
			$query = $this->DatabaseHandler->Query("select * from ".TABLE_PREFIX."event_member where fid = '$uid' ");
			while ($rsdb = $query->GetRow()){
				$id_arr[$rsdb['id']] = $rsdb['id'];
				$count++;
			}
			if($id_arr){
				$id_list = implode(',',$id_arr);
				$where = "and a.id not in ($id_list)";
			}
			
			$sql = "select a.id,a.title,a.fromt,a.tot,a.image,fp.name as province,fa.name as area,fc.name as city, 
					m.app,m.app_time,m.play,m.play_time,m.store,m.store_time,a.address,a.app_num,a.play_num   
					from ".TABLE_PREFIX."event_member m  
					right join ".TABLE_PREFIX."event a on a.id = m.id 
					left join ".TABLE_PREFIX."common_district fp on fp.id = a.province_id 
					left join ".TABLE_PREFIX."common_district fa on fa.id = a.area_id 
					left join ".TABLE_PREFIX."common_district fc on fc.id = a.city_id 
					where 
					    m.fid = '$uid' 
					  or
					    (a.postman = '$uid' 
					    $where) 				    
                    order by a.lasttime desc,a.posttime desc ";
			$page_url = "index.php?mod=event&code=myevent&type=new";

			$postcount = $this->DatabaseHandler->ResultFirst("select count(*) from ".TABLE_PREFIX."event a where a.postman = '$uid' $where");
			$count = $count + $postcount;
			$this->Title = "最近更新的活动";
			
				}else{
			$sql = "select a.id,a.title,a.fromt,a.tot,a.image,fp.name as province,fa.name as area,fc.name as city,a.address,a.app_num,a.play_num   
					from ".TABLE_PREFIX."event a 
					left join ".TABLE_PREFIX."common_district fp on fp.id = a.province_id 
			        left join ".TABLE_PREFIX."common_district fa on fa.id = a.area_id 
					left join ".TABLE_PREFIX."common_district fc on fc.id = a.city_id 
			        where postman = '".$uid."' 
			        order by a.lasttime desc,a.app_num desc,a.posttime desc ";
			$page_url = "index.php?mod=event&code=myevent&uid=$uid";
			$count = $this->DatabaseHandler->ResultFirst("select count(*) from ".TABLE_PREFIX."event where postman = '".$uid."'");
			$this->Title = $user."的活动";
		}
		$page_arr = page($count,$per_page_num,$page_url,$_config);
		$sql .= $page_arr['limit'];
		if($count){
			$query = $this->DatabaseHandler->Query($sql);
			while ($rsdb = $query->GetRow()){
								$rsdb['fromt'] = date("Y年m月d日 H:i",$rsdb['fromt']);
				$rsdb['tot'] = date("Y年m月d日 H:i",$rsdb['tot']);
								if(!$rsdb[image]){
					$rsdb[image] = "images/kuang.png";
				}
								if($rsdb['app'] == 1){
					$rsdb['app'] = "已报名";
					$rsdb['app_time'] = date("Y年m月d日 H:i",$rsdb['app_time']);
				}else{
					$rsdb['app'] = "未报名";
				}
				if($rsdb['play'] == 1){
					$rsdb['play_time'] = date("Y年m月d日 H:i",$rsdb['play_time']);
				}
				if($rsdb['store'] == 1){
					$rsdb['store_time'] = date("Y年m月d日 H:i",$rsdb['store_time']);
				}
								$rsdb['address'] = $rsdb['province'].$rsdb['city'].$rsdb['area'].$rsdb['address'];
				$rs[$rsdb['id']] = $rsdb;
			}
		}
		
				if(MEMBER_STYLE_THREE_TOL == 1)
		{
			$member = $this->Member;
			if ($member['medal_id']) {
				$medal_list = $this->TopicLogic->GetMedal($member['medal_id'],$member['uid']);
			}
		}
		
		include($this->TemplateHandler->Template('my_event'));
	}
	
	
	function followevent(){
		$hot_event = $this->getHotEvent();
		
				$uids = array();
		$sql = "SELECT postman  
				FROM `".TABLE_PREFIX."event` 
				GROUP BY postman 
				ORDER BY count( postman ) DESC 
				LIMIT 12 ";
		$query = $this->DatabaseHandler->Query($sql);
		while ($rsdb = $query->GetRow()){
			$uids[] = $rsdb['postman'];
		}
		if (!empty($uids)) {
			$hd_daren = $this->TopicLogic->GetMember($uids);
		}
		
		
				$query = $this->DatabaseHandler->Query("select buddyid from ".TABLE_PREFIX."buddys where uid = ".MEMBER_ID);
		while ($rsdb = $query->GetRow()){
			$uid_arr[$rsdb['buddyid']] = $rsdb['buddyid'];
		}
		$uid_list = implode(',',$uid_arr);
		
		$type = $this->Get['type'];
		
	    	    $per_page_num = 10;
	    $page_url = "";
	    $count = 0;
	    $_config = array('return' => 'array',);
	    if($uid_list){
						if($type == 'part'){
				$sql = "select a.id,a.title,a.fromt,a.tot,a.image,fp.name as province,fa.name as area,fc.name as city, 
						a.address,a.app_num,a.play_num,m1.username,m1.nickname   
						from ".TABLE_PREFIX."event_member m  
						left join ".TABLE_PREFIX."event a on a.id = m.id 
						left join ".TABLE_PREFIX."members m1 on m1.uid = a.postman 
						left join ".TABLE_PREFIX."common_district fp on fp.id = a.province_id 
						left join ".TABLE_PREFIX."common_district fa on fa.id = a.area_id 
						left join ".TABLE_PREFIX."common_district fc on fc.id = a.city_id 
						where 
						m.play = 1 
						and m.fid in ($uid_list) 
	                    order by a.lasttime desc,a.app_num desc,a.posttime desc ";
	
				$page_url = "index.php?mod=event&code=followevent&type=part";
				$count = $this->DatabaseHandler->ResultFirst("select count(*) from ".TABLE_PREFIX."event_member where play = 1 and fid in ($uid_list)");
				$this->Title = "他们参与的活动";
				
						}else{
				$sql = "select a.id,a.title,a.fromt,a.tot,a.image,fp.name as province,fa.name as area,fc.name as city,a.address,a.app_num,a.play_num,m.username,m.nickname   
						from ".TABLE_PREFIX."event a 
						left join ".TABLE_PREFIX."members m on m.uid = a.postman 
						left join ".TABLE_PREFIX."common_district fp on fp.id = a.province_id 
				        left join ".TABLE_PREFIX."common_district fa on fa.id = a.area_id 
						left join ".TABLE_PREFIX."common_district fc on fc.id = a.city_id 
				        where a.postman in ($uid_list) 
				        order by a.lasttime desc,a.app_num desc,a.posttime desc ";
	
				$page_url = "index.php?mod=event&code=followevent";
				$count = $this->DatabaseHandler->ResultFirst("select count(*) from ".TABLE_PREFIX."event where postman in ($uid_list)");
				$this->Title = "他们管理的活动";
			}
			
			$page_arr = page($count,$per_page_num,$page_url,$_config);
			$sql .= $page_arr['limit'];
			if($count){
				$query = $this->DatabaseHandler->Query($sql);
				while ($rsdb = $query->GetRow()){
										$rsdb['fromt'] = date("Y年m月d日 H:i",$rsdb['fromt']);
					$rsdb['tot'] = date("Y年m月d日 H:i",$rsdb['tot']);
										if(!$rsdb[image]){
						$rsdb[image] = "images/kuang.png";
					}
										$rsdb['address'] = $rsdb['province'].$rsdb['city'].$rsdb['area'].$rsdb['address'];
					$rs[$rsdb['id']] = $rsdb;
				}
			}
	    }else{
	    	$this->Messager("你没有关注的人哦、可以通过找人来关注你感兴趣的人","index.php?mod=profile&code=search");
	    }
		
				if(MEMBER_STYLE_THREE_TOL == 1)
		{
			$member = $this->Member;
			if ($member['medal_id']) {
				$medal_list = $this->TopicLogic->GetMedal($member['medal_id'],$member['uid']);
			}
		}
		
		include($this->TemplateHandler->Template('follow_event'));
	}
	
	
	function pevent(){
		if(MEMBER_ID < 1){
			$this->Messager("你需要先登录才能继续本操作", 'index.php?mod=login');	
		}		
		
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
		$event_type = $FormHandler->Select("type",$rs,$val['type_id']);

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
			$city_id = $this->DatabaseHandler->ResultFirst("select id from ".TABLE_PREFIX."common_district where name = '".$this->Member['city']."' and upid= '$province_id'");
			$where_city = " and upid = '$city_id'";
		}
		$hid_city = $city_id;
				if($this->Member['area']){
			$area_id = $this->DatabaseHandler->ResultFirst("select id from ".TABLE_PREFIX."common_district where name = '".$this->Member['area']."' $where_city");
		}
		$hid_area = $area_id;
		
				$fromt = $edit_fromt ? $edit_fromt : my_date_format(TIMESTAMP, 'Y-m-d');
		$edit_fromt_h = $edit_fromt_h ? $edit_fromt_h : false;
		$edit_fromt_i = $edit_fromt_i ? $edit_fromt_i : false;
		$hour_select_from = mk_time_select('hour',$edit_fromt_h,'hour_select_from');
		$min_select_from =  mk_time_select('min',$edit_fromt_i,'min_select_from');

		$tot = $edit_tot ? $edit_tot : my_date_format(TIMESTAMP+7*24*3600, 'Y-m-d');
		$edit_tot_h = $edit_tot_h ? $edit_tot_h : false;
		$edit_tot_i = $edit_tot_i ? $edit_tot_i : false;
		$hour_select_to = mk_time_select('hour',$edit_tot_h,'hour_select_to');
		$min_select_to =  mk_time_select('min',$edit_tot_i,'min_select_to');
		
				if(MEMBER_STYLE_THREE_TOL == 1)
		{
			$member = $this->Member;
			if ($member['medal_id']) {
				$medal_list = $this->TopicLogic->GetMedal($member['medal_id'],$member['uid']);
			}
		}
		
		$this->Title = "发起活动";
		include($this->TemplateHandler->Template('event_create'));
	}
	
	
	function editEvent(){
		$id =  (int) $this->Get['id'];
		$postman = $this->DatabaseHandler->ResultFirst("select postman from ".TABLE_PREFIX."event where id = '$id'");
		if($postman != MEMBER_ID){
			$this->Messager("你无权修改该活动");
		}
				$free = 'checked';
		$all = 'checked';
		$info = array();
	
				$need_info = $this->DatabaseHandler->ResultFirst("select need_info from ".TABLE_PREFIX."event_info");
		$info = unserialize($need_info);

		$val =array();
		if($id){
			$act = "edit";
			$query = $this->DatabaseHandler->Query("select * from ".TABLE_PREFIX."event where id = '$id'");
			$val = $query->GetRow();
			$title = $val['title'];
			$moneys = $val['money'];

			$content = $val['content'];
			$address = $val['address'];
			if($val['image']){
				$image = $val['image'];
			}
						$edit_fromt = date("Y-m-d",$val['fromt']);
						$edit_fromt_h = date("H",$val['fromt']);
						$edit_fromt_i = date("i",$val['fromt']);
						$edit_tot = date("Y-m-d",$val['tot']);
						$edit_tot_h = date("H",$val['tot']);
						$edit_tot_i = date("i",$val['tot']);
						if($val['money']){
				$money = 'checked';
				$free = '';
			}else{
				$money = '';
			    $free = 'checked';
			}
						$qualification  = unserialize($val['qualification']);
			if(count($qualification)){
				$qua = "checked";
				$all = "";
				if($qualification['fans_num']){
					$fans = "checked";
					$fans_num = $qualification['fans_num'];
				}
				if($qualification['same_city']){
					$same_city = "checked";
				}
			}
						$need_app_info = unserialize($val['need_app_info']);
			if(count($need_app_info)){
				foreach ($need_app_info as $value) {
					$info[$value['id']][$value['ename']] = "checked";
				}
			}
		}

				Load::lib('form');
		$FormHandler = new FormHandler();
		$query = $this->DatabaseHandler->Query("select * from ".TABLE_PREFIX."event_sort order by id");
		while ($rsdb = $query->GetRow()){
			$rs[$rsdb[id]]['value'] = $rsdb['id'];
			$rs[$rsdb[id]]['name'] = $rsdb['type'];
		}
		$event_type = $FormHandler->Select("type",$rs,$val['type_id']);

				$query = $this->DatabaseHandler->Query("select * from ".TABLE_PREFIX."common_district where upid = 0 order by list");
		while ($rsdb = $query->GetRow()){
			$province[$rsdb['id']]['value']  = $rsdb['id'];
			$province[$rsdb['id']]['name']  = $rsdb['name'];
		}
		$province_id = $val['province_id'];
		$hid_province = $province_id;
		$province_list = $FormHandler->Select("province",$province,$province_id,"onchange=\"changeProvince();\"");

				$city_id = $val['city_id'];
		$hid_city = $city_id;
				$area_id = $val['area_id'];
		$hid_area = $area_id;
		
				$fromt = $edit_fromt ? $edit_fromt : my_date_format(TIMESTAMP, 'Y-m-d');
		$edit_fromt_h = $edit_fromt_h ? $edit_fromt_h : false;
		$edit_fromt_i = $edit_fromt_i ? $edit_fromt_i : false;
		$hour_select_from = mk_time_select('hour',$edit_fromt_h,'hour_select_from');
		$min_select_from =  mk_time_select('min',$edit_fromt_i,'min_select_from');

		$tot = $edit_tot ? $edit_tot : my_date_format(TIMESTAMP+7*24*3600, 'Y-m-d');
		$edit_tot_h = $edit_tot_h ? $edit_tot_h : false;
		$edit_tot_i = $edit_tot_i ? $edit_tot_i : false;
		$hour_select_to = mk_time_select('hour',$edit_tot_h,'hour_select_to');
		$min_select_to =  mk_time_select('min',$edit_tot_i,'min_select_to');
		
				if(MEMBER_STYLE_THREE_TOL == 1)
		{
			$member = $this->Member;
		}
		
		$this->Title = "修改活动";
		include($this->TemplateHandler->Template('event_create'));
	}
	
	
	function _member()
	{		
		$member = $this->TopicLogic->GetMember(MEMBER_ID);
		
		return $member;
	}
	
	
	function _getDay($time){
		$day_num = date("w",$time);
		switch ($day_num) {
			case 1:
				$day = "星期一";
			    break;
			case 2:
				$day = "星期二";
			    break;
			case 3:
				$day = "星期三";
			    break;
			case 4:
				$day = "星期四";
			    break;
			case 5:
				$day = "星期五";
			    break;
			case 6:
				$day = "星期六";
			    break;
			case 0:
				$day = "星期天";
			    break;
			default:
				break;
		}
		return $day;
	}
	
	
	function getHotEvent(){
				$hot_event = array();
		$query = $this->DatabaseHandler->Query("select * from ".TABLE_PREFIX."event where recd = 1 order by lasttime desc , app_num desc ");
		while ($rsdb = $query->GetRow()){
			if(!$rsdb['image']){
				$rsdb[image] = "images/kuang.png";
			}else{
				$type = trim(strtolower(end(explode(".",$rsdb['image']))));
				$name = explode("_",$rsdb['image']);
				$rsdb['image'] = $name[0]."_s.".$type;
			}
			if(time()>=$rsdb['fromt'] && time()<=$rsdb['tot'] ){
				$rsdb['show'] = "火热进行中";
			}else if(time() < $rsdb['fromt']){
				$rsdb['show'] = "即将开始";
			}else if(time() > $rsdb['tot']){
				$rsdb['show'] = "活动已结束";
			}
			$hot_event[$rsdb['id']] = $rsdb;
		}
		return $hot_event;
	}
}