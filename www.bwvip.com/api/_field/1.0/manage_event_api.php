<?php
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}

$ac=$_G['gp_ac'];
$field_uid = $_G['gp_field_uid'];//球场编号
$language=$_G['gp_language'];
$now_time = time();
//是否检查field_uid
if(!in_array($ac,array('free_ticket','free_ticket2'))) {
    if(empty($ac) || empty($field_uid)) 
    {
        api_json_result(1,1,"参数不完整",'');
    }
}

//分类ID
$category_id = $_G['gp_category_id'];
if(!empty($category_id)){
	$category_sql = " and category_id='{$category_id}' ";
}

//赛事列表
if($ac == 'filedevent') 
{
    $page = $_G['gp_page'];
    $page = max(1,$page);
	$page_size=$_G['gp_page_size'];
	$page_size = !empty($page_size) ? $page_size : 10;
	
	$offset = ($page-1)*$page_size;
    //拿到最新一条的赛事
    $sql = "select * from tbl_field_event where field_uid='{$field_uid}' order by field_event_id desc limit {$offset},{$page_size}";
    $list = DB::query($sql);
    $field_event_list = array();//球童列表
    while($row=DB::fetch($list)) 
    {
        if($language == 'en') 
        {
            $row['field_event_name'] = $row['field_event_name_en'];
        }
        unset($row['field_event_name_en']);
        $row['field_event_logo'] = $site_url.'/'.$row['field_event_logo'];
        $row['field_event_date'] = date('Y年m月d日',$row['field_event_time']);
        $field_event_list[] = array_default_value($row);
    }
    unset($list);
    $field_event_list = array_default_value($field_event_list);
    if(empty($field_event_list)) 
    {
        $return_data['title'] = 'fieldevent';
        $return_data['data'] = null;
        api_json_result(1,0,$app_error['event']['10502'],$return_data);
        exit;
    }
    
    if(empty($field_event_list)) {
        $field_event_list = null;
    }
    $return_data['title'] = 'fieldevent';
    $return_data['data'] = $field_event_list;
    api_json_result(1,0,$app_error['event']['10502'],$return_data);
    
}
//冠军排行
if($ac == 'eventsort') 
{
    $field_event_id = $_G['gp_field_eventid'];
    //拿到最新一条的赛事
    $sql = "select * from tbl_field_event where field_event_id='{$field_event_id}' and field_uid='{$field_uid}' order by field_event_id desc limit 1";
    $new_event = DB::fetch_first($sql);//赛事信息
    
    if(empty($new_event)) 
    {
        api_json_result(1,1,'没有数据',null);
    }
    if($language == 'en') {
        $new_event['field_event_name'] = $new_event['field_event_name_en'];
    }
    unset($new_event['field_event_name_en']);
    $new_event['field_event_date'] = date('Y年m月d日',$new_event['field_event_time']);
    $new_event['field_event_logo'] = $site_url.'/'.$new_event['field_event_logo'];
    $sql = "select * from tbl_field_event_rank where field_uid='{$field_uid}'";
    $list = DB::query($sql);
    //赛事比赛排行
    $new_event_rank = array();//赛事冠军列表
    $uid_arr = array();
    while($row=DB::fetch($list)) 
    {
        if($language == 'en') 
        {
            $row['field_event_rank_name'] = $row['field_event_rank_name_en'];
        }
        unset($row['field_event_rank_name_en']);
        $row['touxiang'] = $site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=middle";
        $new_event_rank[$row['field_event_rank_id']]=array_default_value($row);
        $uid_arr[$row['uid']] = $row['uid'];
        $sort_ids[$row['field_event_rank_id']] = $row['field_event_rank_sort'];
    }
    unset($list);
    $sql = "select * from pre_common_member_profile where uid in(".implode(",",$uid_arr).")";
    $list = DB::query($sql);
    $user_info = array();
    while($row=DB::fetch($list)) 
    {
        $user_info[$row['uid']]=$row;
    }
    
    unset($list);
    foreach($new_event_rank as $key=>&$val) 
    {
        $val['username'] = $user_info[$val['uid']]['realname'];
    }
    unset($user_info);
    array_multisort($sort_ids,$new_event_rank);
    if(empty($new_event)) {
        $new_event = null;
    }
    if(empty($new_event_rank)) {
        $new_event_rank = null;
    }
    $return_data = array(
        'title' => 'event',
        'data' => array(
            'event_info' => array_default_value($new_event),
        	'member_sort' => array_default_value($new_event_rank)
        )
    );
    unset($new_event_rank,$new_event);
   
    api_json_result(1,0,$app_error['event']['10502'],$return_data);
}
//球童列表
if($ac == 'qiutong')
{
    $search = $_G['gp_search'];
    $page = $_G['gp_page'];
    $page = max(1,$page);
	$page_size=$_G['gp_page_size'];
	$page_size = !empty($page_size) ? $page_size : 10;
	
	$offset = ($page-1)*$page_size;
	$sql =  "select * from tbl_qiutong where field_uid='{$field_uid}' ".$category_sql."  order by qiutong_id limit {$offset},{$page_size}";
    if(!empty($search)) 
    {
        $sql = "select * from tbl_qiutong where field_uid='{$field_uid}'  ".$category_sql." and (qiutong_number='{$search}' or qiutong_name like '%{$search}%' or qiutong_name_en like '%{$search}%') limit {$offset},{$page_size}";
    }
	
    $list = DB::query($sql);
    $qiutong_list = array();//球童列表
    while($row=DB::fetch($list)) 
    {
        if($language == 'en') {
            $row['qiutong_name'] = $row['qiutong_name_en'];
        }
		if(!$row['uid'])
		{
			$row['uid']="";
		}
        unset($row['fqiutong_name_en']);
        $row['qiutong_photo'] = $site_url.'/'.$row['qiutong_photo'];
        $qiutong_list[] = array_default_value($row);
    }
    unset($list);
    
    if(empty($qiutong_list)) 
    {
        $return_data['title'] = 'qiutong_list';
        $return_data['data'] = null;
        api_json_result(1,0,$app_error['event']['10502'],$return_data);
        exit;
    }
    
    $return_data['title'] = 'qiutong_list';
    $return_data['data'] = $qiutong_list;
    
    api_json_result(1,0,$app_error['event']['10502'],$return_data);
}
//预约球童显示球童信息
if($ac == 'qiutong_info')
{
    $qiutong_id = $_G['gp_qiutong_id'];
    if(empty($qiutong_id)) 
    {
        api_json_result(1,1,'参数有误',$qiutong_id);
        exit;
    }
    $sql = "select * from tbl_qiutong where qiutong_id='{$qiutong_id}' and field_uid='{$field_uid}' limit 1";
    
    $qiutong_info = DB::fetch_first($sql);
    
    if(empty($qiutong_info)) 
    {
        api_json_result(1,0,'没有球童信息',$qiutong_info);
        exit;
    }
    if($language == 'en') 
    {
        $qiutong_info['qiutong_name'] = $qiutong_info['qiutong_name_en'];
    }
    unset($qiutong_info['fqiutong_name_en']);
    $qiutong_info['qiutong_photo'] = $site_url.'/'.$qiutong_info['qiutong_photo'];
    $return_data['title'] = 'qiutong_info';
    $return_data['data'] = array_default_value($qiutong_info);
    unset($qiutong_info);
   
    api_json_result(1,0,$app_error['event']['10502'],$return_data);
}
//确定预约球童
if($ac == 'order_qiutong')
{
    $uid = $_G['gp_uid'];//会员账号
    $qiutong_id = $_G['gp_qiutong_id'];
    $order_date = $_G['gp_order_date'];
    $teetime = $_G['gp_teetime'];
    
    if(empty($qiutong_id) || empty($order_date) || empty($teetime) || empty($uid)) 
    {
        api_json_result(1,1,'参数有误','');
        exit;
    }
    
    $sql = "insert into tbl_qiutong_order(uid,qiutong_id,field_uid,qiutong_order_date,qiutong_order_teetime,qiutong_order_state,qiutong_order_addtime)";
    $sql .= " values ('{$uid}','{$qiutong_id}','{$field_uid}','{$order_date}','{$teetime}','0','{$now_time}')";
    
    $res = DB::query($sql);
    if(empty($res)) 
    {
        api_json_result(1,1,"预约失败",null);
        exit;
    }
    
    api_json_result(1,0,'预约成功',null);
}
//一级菜单列表
if($ac == 'menulist1')
{
    $menu_type = $_G['gp_menu_type'];
    if(!empty($menu_type))
	{
		$menu_type_sql = " and field_1stmenu_type='{$menu_type}'";
	}
    $return_data['title'] = 'menulist';
    $sql = "select * from tbl_field_1stmenu where field_uid='{$field_uid}' ".$category_sql." {$menu_type_sql} order by field_1stmenu_id asc";
    
    $list = DB::query($sql);
    $menu_list = array();
    $field_1stmenu_ids =array();
    while($row=DB::fetch($list))
    {
        if($language == 'en' && empty($row['field_1stmenu_name_en'])) 
        {
            $row['field_1stmenu_name'] = $row['field_1stmenu_name_en'];
        }
        unset($row['field_1stmenu_name_en']);
        $field_1stmenu_ids[$row['field_1stmenu_id']] = $row['field_1stmenu_id'];
        $menu_list[] = array_default_value($row);
    }
    unset($list);
    if(empty($field_1stmenu_ids)) 
    {
        $return_data['data'] = null;
        api_json_result(1,0,'没有菜单了',$return_data);
    }
    
    $sql = "select * from tbl_field_2ndmenu where field_uid='{$field_uid}' and field_1stmenu_id='{$menu_list[0]['field_1stmenu_id']}' and field_1stmenu_type='{$menu_list[0]['field_1stmenu_type']}' order by field_2ndmenu_id desc";
    $list = DB::query($sql);
    $menu_list2 = array();
    while($row=DB::fetch($list)) 
    {
        if($language == 'en' && empty($row['field_2ndmenu_name_en'])) 
        {
            $row['field_2ndmenu_name'] = $row['field_2ndmenu_name_en'];
        }
        unset($row['field_2ndmenu_name_en']);
        $extname=end(explode(".",$row['field_2ndmenu_pic']));
        $row['field_2ndmenu_pic_big'] = $site_url.'/'.$row['field_2ndmenu_pic'];
        $row['field_2ndmenu_pic'] = $site_url.$row['field_2ndmenu_pic'].'_small.'.$extname;
        
        $menu_list2[] = array_default_value($row);
        
    }
    
    $menu_list[0]['menu_list'] = !empty($menu_list2) ? $menu_list2 : null;
    foreach($menu_list as $key=>&$val) {
        if(empty($val['menu_list'])) {
            $val['menu_list'] = null;
        }
    }
    unset($menu_list2);
    $return_data['data'] = $menu_list;
    unset($menu_list);
    
    api_json_result(1,0,$app_error['event']['10502'],$return_data);
    
}
//一级菜单列表for iphone
if($ac == 'menulist1_iphone')
{
    $menu_type = $_G['gp_menu_type'];
    if(!empty($menu_type))
	{
		$menu_type_sql = " and field_1stmenu_type='{$menu_type}'";
	}
    $return_data['title'] = 'menulist';
    $sql = "select * from tbl_field_1stmenu where field_uid='{$field_uid}' ".$category_sql." {$menu_type_sql} order by field_1stmenu_id asc";
    
    $list = DB::query($sql);
    $menu_list = array();
    $field_1stmenu_ids =array();
    while($row=DB::fetch($list))
    {
        if($language == 'en' && empty($row['field_1stmenu_name_en'])) 
        {
            $row['field_1stmenu_name'] = $row['field_1stmenu_name_en'];
        }
        unset($row['field_1stmenu_name_en']);
        $field_1stmenu_ids[$row['field_1stmenu_id']] = $row['field_1stmenu_id'];
        $menu_list[] = array_default_value($row);
    }
    unset($list);
    if(empty($field_1stmenu_ids)) 
    {
        $return_data['data'] = null;
        api_json_result(1,0,'没有菜单了',$return_data);
    }
    $field_1stmenu_id_str = implode("','",$field_1stmenu_ids);
    $sql = "select * from tbl_field_2ndmenu where field_uid='{$field_uid}' and field_1stmenu_id in('{$field_1stmenu_id_str}') order by field_2ndmenu_id desc";
    $list = DB::query($sql);
    $menu_list2 = array();
    while($row=DB::fetch($list)) 
    {
        if($language == 'en' && empty($row['field_2ndmenu_name_en'])) 
        {
            $row['field_2ndmenu_name'] = $row['field_2ndmenu_name_en'];
        }
        unset($row['field_2ndmenu_name_en']);
        $extname=end(explode(".",$row['field_2ndmenu_pic']));
        $row['field_2ndmenu_pic_big'] = $site_url.'/'.$row['field_2ndmenu_pic'];
        $row['field_2ndmenu_pic'] = $site_url.$row['field_2ndmenu_pic'].'_small.'.$extname;
        $menu_list2[$row['field_1stmenu_id']][] = array_default_value($row);
        unset($row);
    }
    
    foreach($menu_list as $key=>&$val) {
        if(!empty($menu_list2[$val['field_1stmenu_id']])) {
            $val['menu_list'] = $menu_list2[$val['field_1stmenu_id']];
        }else{
            $val['menu_list'] = null;
        }
    }
    unset($menu_list2);
    if(empty($menu_list)) {
        $menu_list = null;
    }
    $return_data['data'] = $menu_list;
    unset($menu_list);
    
    api_json_result(1,0,$app_error['event']['10502'],$return_data);
    
}
//二级菜单列表
if($ac == 'menulist2')
{
    $up_id = $_G['gp_up_id'];
    $menu_type = $_G['gp_menu_type'];
    if(empty($up_id)) 
    {
        api_json_result(1,1,'缺少参数',null);
        exit;
    }
	if(!empty($menu_type))
	{
		$menu_type_sql = " and field_1stmenu_type='{$menu_type}'";
	}
    $return_data['title'] = 'menulist';
    $sql = "select * from tbl_field_2ndmenu where field_uid='{$field_uid}' and field_1stmenu_id='{$up_id}' {$menu_type_sql} order by field_2ndmenu_id desc";
    
    $list = DB::query($sql);
    $menu_list = array();
    while($row=DB::fetch($list)) 
    {
        if($language == 'en' && empty($row['field_2ndmenu_name_en'])) 
        {
            $row['field_2ndmenu_name'] = $row['field_2ndmenu_name_en'];
        }
        unset($row['field_2ndmenu_name_en']);
        $extname=end(explode(".",$row['field_2ndmenu_pic']));
        $row['field_2ndmenu_pic_big'] = $site_url.'/'.$row['field_2ndmenu_pic'];
        $row['field_2ndmenu_pic'] = $site_url.$row['field_2ndmenu_pic'].'_small.'.$extname;
        
        $menu_list[] = array_default_value($row);
    }
    unset($list);
    if(empty($menu_list)) 
    {
        $return_data['data'] = null;
        api_json_result(1,0,'没有菜单了',$return_data);
    }
    if(empty($menu_list)) {
        $menu_list = null;
    }
    $return_data['data'] = $menu_list;
    unset($menu_list);
    foreach($menu_list as $key=>&$val) {
        if(empty($val['menu_list'])) {
            $val['menu_list'] = null;
        }
    }
    
    api_json_result(1,0,$app_error['event']['10502'],$return_data);
    
}
//订餐
if($ac == 'ordermenu')
{
    function objectToArray($d) {
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}
 
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map(__FUNCTION__, $d);
		}
		else {
			// Return array
			return $d;
		}
	}
    $menu_json_str = $_G['gp_menu_json'];//'{"uid":"1","menu_type":"1","people_nums":"3","menu_list":[{"menu_id":"1","menu_nums":"4"},{"menu_id":"2","menu_nums":"3"}]}';
    if(empty($menu_json_str))
    {
        api_json_result(1,1,'缺少menu_json参数，订餐失败1',null);
    }
    $menu_json_str = stripslashes($menu_json_str);
    $menu_arr = objectToArray(json_decode($menu_json_str));
    $uid = $menu_arr['uid'];
    $field_menu_type = $menu_arr['menu_type'];
    $field_menu_type_nums = $menu_arr['people_nums'];
    $phone = $menu_arr['phone'];
    /**
     * {"people_nums":"123",
     * "uid":3802649,
     * "menu_list":"[{\"menu_id\":8,\"menu_nums\":1}]","phone":"123","menu_type":2}
     * {"people_nums":"1","uid":"3802649","menu_list":"[{\"menu_id\":4,\"menu_nums\":1}]","phone":"","menu_type":"1"}
     */
    
    if(empty($phone)) 
    {
        $phone = '';
    }
    $menu_list = $menu_arr['menu_list'];
    if(empty($uid) ||  empty($field_menu_type) || empty($field_menu_type_nums) || empty($menu_list))
    {
        api_json_result(1,1,'缺少参数，订餐失败',null);
    }
    $sql = "insert into tbl_field_user_menu_rel(uid,field_uid,field_1stmenu_type,field_1stmenu_type_nums,field_user_menu_phone,filed_user_menu_addtime)";
    $sql .= " values ('{$uid}','{$field_uid}','{$field_menu_type}','{$field_menu_type_nums}','{$phone}','{$now_time}')";
    $res = DB::query($sql);
    if(empty($res)) 
    {
        api_json_result(1,1,'订餐失败2',null);
    }
    $insert_id=DB::insert_id();
    $intoarr = array();
    foreach($menu_list as $key=>$val) 
    {
        $val['rel_id']=$insert_id;
        $val['field_user_menu_addtime']=$now_time;
        $intostr ="('";
        $intostr .= implode("','",$val);
        $intostr .="')";
        $intoarr[]=$intostr;
    }
    $values_str = implode(',',$intoarr);
    $sql = "insert into tbl_field_user_menu(field_2ndmenu_id,field_menu_nums,rel_id,field_user_menu_addtime)";
    $sql .=" values{$values_str}";
    $res = DB::query($sql);
    if(empty($res)) 
    {
        api_json_result(1,1,'订餐失败3',null);
    }
    api_json_result(1,0,'订餐成功',null);
}
//用户已订菜单列表
if($ac == 'usermenulist')
{
    $uid = $_G['gp_uid'];
    if(empty($uid)) 
    {
        api_json_result(1,1,'缺少参数',null);
    }
    $sql = "select * from tbl_field_user_menu_rel where uid='{$uid}' and field_uid='{$field_uid}' and field_user_menu_is_finished='N'";
    $usermenu_rel = DB::fetch_first($sql);
    $return_data['title'] = 'usermenulist';
    if(empty($usermenu_rel)) 
    {
        $return_data['data'] = null;
        api_json_result(1,0,'没有菜单了',$return_data);
    }
    
    $rel_id = $usermenu_rel['rel_id'];
    
    $sql = "select * from tbl_field_user_menu where rel_id in(".implode(',',(array)$rel_id).") and field_user_menu_is_finished='N'";
    $list = DB::query($sql);
    $user_menu_list = array();
    $field_menu_ids = array();
    while($row=DB::fetch($list)) 
    {
        $row['field_user_menu_adddate'] = date('Y-m-d H:i:s',$row['field_user_menu_addtime']);
        $field_menu_ids[$row['field_2ndmenu_id']] = $row['field_2ndmenu_id'];
        $user_menu_list[] = array_default_value($row);
    }
    unset($list);
    if(empty($field_menu_ids)) 
    {
        $return_data['data'] = null;
        api_json_result(1,0,'没有菜单了',$return_data);
    }

    $sql = "select * from tbl_field_2ndmenu where field_2ndmenu_id in(".implode(',',(array)$field_menu_ids).") and field_uid='{$field_uid}'";
    
    $list = DB::query($sql);
    while($row=DB::fetch($list)) 
    {
        $field_menu_list[$row['field_2ndmenu_id']] = array_default_value($row);
    }

    unset($list);
    foreach($user_menu_list as $key=>&$val) 
    {
        if(!empty($language) && $language == 'en') 
        {
            $val['field_2ndmenu_name'] = $field_menu_list[$val['field_2ndmenu_id']]['field_2ndmenu_name_en'];
        }
        else
        {
            $val['field_2ndmenu_name'] = $field_menu_list[$val['field_2ndmenu_id']]['field_2ndmenu_name'];
        }
        $val['field_2ndmenu_pic_big'] = $field_menu_list[$val['field_2ndmenu_id']]['field_2ndmenu_pic'];
        $extname=end(explode(".",$val['field_2ndmenu_pic_big']));
        $val['field_2ndmenu_pic'] = $field_menu_list[$val['field_2ndmenu_id']]['field_2ndmenu_pic'].'_small.'.$extname;
        $val['field_2ndmenu_price'] = $field_menu_list[$val['field_2ndmenu_id']]['field_2ndmenu_price'];
    }
    unset($field_menu_list);
    if(empty($user_menu_list)) {
        $user_menu_list = null;
    }
    $return_data['data'] = array_default_value($user_menu_list);
    
    api_json_result(1,0,$app_error['event']['10502'],$return_data);
}
//添加会员订场的订场内容接口
if($ac == 'add_orderinfo') 
{
    $uid = $_G['gp_uid'];
    $content = $_G['gp_content'];
    $is_memberphone = $_G['gp_is_memberphone'];
    $isnot_memberphone = $_G['gp_isnot_memberphone'];
    if(empty($uid) || empty($content) || empty($is_memberphone) || empty($isnot_memberphone)) 
    {
        api_json_result(1,1,'缺少参数',null);
    }
    
    $sql = "insert into tbl_field_orderinfo(uid,field_uid,field_orderinfo_content,field_orderinfo_is_memberphone,field_orderinfo_isnot_memberphone,field_orderinfo_addtime)";
    $sql .= " values('{$uid}','{$field_uid}','{$content}','{$is_memberphone}','{$isnot_memberphone}','{$now_time}')";
    $res = DB::query($sql);
    
    $data['title'] = 'return_data'; 
    if(empty($res)) 
    {
        $data['data'] = null;
        api_json_result(1,0,$app_error['event']['10502'],$data);
        exit;
    }
    $data['data'] = 1;
    api_json_result(1,0,$app_error['event']['10502'],$data);
}
//订场列表及搜索接口
if($ac == 'orderinfo_list')
{
    $page = $_G['gp_page'];
    $page = max(1,$page);
	$page_size=$_G['gp_page_size'];
	$page_size = !empty($page_size) ? $page_size : 10;
	
	$offset = ($page-1)*$page_size;
    $sql = "select * from tbl_field_orderinfo group by field_orderinfo_id desc limit {$offset},{$page_size}";
    $list = DB::query($sql);
    $orderinfo_list = array();
    while($row=DB::fetch($list)) 
    {
        $row['field_orderinfo_adddate'] = $row['field_orderinfo_addtime'];
        unset($row['field_orderinfo_addtime']);
        $orderinfo_list[] = array_default_value($row);
    }
    unset($list);
    $data['title'] = 'return_data';
    if(empty($orderinfo_list)) 
    {
        $data['data'] = null;
    }
    else
    {
        $data['data'] = $orderinfo_list;
        unset($orderinfo_list);
    }
    api_json_result(1,0,$app_error['event']['10502'],$data);
}
////获取订场显示内容
//if($ac == 'get_orderinfo') 
//{
//    $sql = "select * from tbl_field_about where field_uid='{$field_uid}' and about_type='field_dingchang' order by about_id desc limit 5";
//    $list = DB::query($sql);
//    
//    $orderinfo_list = array();
//    $orderinfo_list['field_uid'] = $field_uid;
//    while($row=DB::fetch($list)) 
//    {
//        if(empty($orderinfo_list['about_tel'])) {
//            $orderinfo_list['is_memberphone'] = $row['about_tel'];
//        }
//        if(empty($orderinfo_list['about_tel2'])) {
//            $orderinfo_list['isnot_memberphone'] = $row['about_tel2'];
//        }
//        if(empty($orderinfo_list['adddate'])) {
//            $orderinfo_list['adddate'] = date('Y年m月d日',$row['about_addtime']);
//        }
//        unset($row['about_addtime']);
//        if(!empty($language) && $language == 'en') {
//            $row['field_orderinfo_title'] = '';
//            $row['field_orderinfo_content'] = $row['about_content'];
//        }
//        $tmp_arr['field_orderinfo_title'] = $row['field_orderinfo_title'];
//        $tmp_arr['field_orderinfo_content'] = $row['field_orderinfo_content'];
//        unset($row);
//        $orderinfo_list['info_list'][] = $tmp_arr;
//    }
//    
//    $data['title'] = 'return_data';
//    $data['data'] = $orderinfo_list;
//    if(empty($orderinfo_list)) 
//    {
//        $data['data'] = null;
//    }
//    unset($orderinfo_list);
//    
//    api_json_result(1,0,$app_error['event']['10502'],$data);
//}
//获取订场显示内容
if($ac == 'get_orderinfo') 
{
    $sql = "select * from tbl_field_orderinfo where field_uid='{$field_uid}' order by field_orderinfo_id desc limit 5";
    $list = DB::query($sql);
    
    $orderinfo_list = array();
    $orderinfo_list['field_uid'] = $field_uid;
    while($row=DB::fetch($list)) 
    {
        if(empty($orderinfo_list['is_memberphone'])) {
            $orderinfo_list['is_memberphone'] = $row['field_orderinfo_is_memberphone'];
        }
        if(empty($orderinfo_list['isnot_memberphone'])) {
            $orderinfo_list['isnot_memberphone'] = $row['field_orderinfo_isnot_memberphone'];
        }
        if(empty($orderinfo_list['adddate'])) {
            $orderinfo_list['adddate'] = date('Y年m月d日',$row['field_orderinfo_addtime']);
        }
        unset($row['field_orderinfo_addtime']);
        if(!empty($language) && $language == 'en') {
            $row['field_orderinfo_title'] = $row['field_orderinfo_title_en'];
            $row['field_orderinfo_content'] = $row['field_orderinfo_content_en'];
        }
        $tmp_arr['field_orderinfo_title'] = $row['field_orderinfo_title'];
        $tmp_arr['field_orderinfo_content'] = $row['field_orderinfo_content'];
        unset($row);
        $orderinfo_list['info_list'][] = array_default_value($tmp_arr);
    }
    
    $data['title'] = 'return_data';
    $data['data'] = $orderinfo_list;
    if(empty($orderinfo_list)) 
    {
        $data['data'] = null;
    }
    unset($orderinfo_list);
    api_json_result(1,0,$app_error['event']['10502'],$data);
}
//添加会员订场记录接口
if($ac == 'add_orderlist') 
{
    $uid = $_G['gp_uid'];
    $orderinfo_id = $_G['gp_orderinfo_id'];
    $orderlist_phone = $_G['gp_orderlist_phone'];
    $orderlist_detail = $_G['gp_orderlist_detail'];
    if(empty($uid) || empty($orderinfo_id)) {
        api_json_result(1,1,'缺少参数',null);
    }
    
    $sql = "insert into tbl_field_orderlist(uid,field_uid,field_orderinfo_id,field_orderlist_phone,field_orderlist_detail,field_orderlist_addtime)";
    $sql .= " values('{$uid}','{$field_uid}','{$orderinfo_id}','{$orderlist_phone}','{$orderlist_detail}','{$now_time}')";
    $res = DB::query($sql);
    
    $data['title'] = 'return_data'; 
    if(empty($res)) {
        $data['data'] = null;
        api_json_result(1,0,$app_error['event']['10502'],$data);
        exit;
    }
    $data['data'] = 1;
    api_json_result(1,0,$app_error['event']['10502'],$data);
}
//会员订场记录列表及搜索接口
if($ac == 'orderlist') 
{
    $page = $_G['gp_page'];
    $page = max(1,$page);
	$page_size=$_G['gp_page_size'];
	$page_size = !empty($page_size) ? $page_size : 10;
	
	$offset = ($page-1)*$page_size;
    $sql = "select * from tbl_field_orderlist where field_uid='{$field_uid}' group by field_orderlist_id desc limit {$offset},{$page_size}";
    $list = DB::query($sql);
    $orderlist = array();
    while($row=DB::fetch($list)) 
    {
        $row['field_orderlist_adddate'] = $row['field_orderlist_addtime'];
        unset($row['field_orderlist_addtime']);
        $orderlist[] = array_default_value($row);
    }
    unset($list);
    $data['title'] = 'return_data';
    if(empty($orderlist)) 
    {
        $data['data'] = null;
    }
    else
    {
        $data['data'] = $orderlist;
        unset($orderlist);
    }
    api_json_result(1,0,$app_error['event']['10502'],$data);
}
if($ac == 'user_orderlist')
{
    $sql = "select * from tbl_field_orderlist where field_uid='{$field_uid}' limit 1";
    $user_orderlist = DB::fetch_first($sql);
    
    $data['title'] = 'return_data';
    if(empty($user_orderlist)) 
    {
        $data['data'] = null;
    }
    else
    {
        $user_orderlist['field_orderlist_adddate'] = $user_orderlist['field_orderlist_addtime'];
        unset($user_orderlist['field_orderinfo_addtime']);
        $data['data'] = array_default_value($user_orderlist);
        unset($user_orderlist);
    }
    api_json_result(1,0,$app_error['event']['10502'],$data);
}
//通过用户名获取手机号
if($ac == 'uidtomobile')
{
    $uid = $_G['gp_uid'];
    if(empty($uid)) 
    {
        api_json_result(1,1,'参数错误',null);
    }
	else
	{
		//最后这样写
		$mobile=DB::result_first(" select mobile from pre_common_member_profile where uid='".$uid."' ");
		$data['title'] = 'mobile';
		$data['data'] = $mobile;
	}
	api_json_result(1,0,$app_error['event']['10502'],$data);
	
	/*

	
    $sql = "select * from pre_common_member_profile where uid in(".implode(",",(array)$uid).")";
    $user_info = DB::fetch_first($sql);
    $data['title'] = 'mobile';
    $data['data'] = null;
    if(!empty($user_info) && !empty($user_info['mobile'])) {
        $data['data'] = $user_info['mobile'];
        api_json_result(1,0,$app_error['event']['10502'],$data);
    }
    
    $sql = "select * from pre_common_member where uid in(".implode(",",(array)$uid).")";
    $user_info = DB::fetch_first($sql);
    
    if(eregi('^[0-9]*$',$user_info['username']) && strlen($user_info['username'])==11){
        $data['data'] = $user_info['mobile'];
        api_json_result(1,0,$app_error['event']['10502'],$data);
    }
  
    
	*/
}

//生成二维码成功返回路径，失败返回 false
function erweima()
{
	$phone = mt_rand(1000000000,9999999999);
    //如果没有就生成二维码
	$path_erweima_core = dirname(dirname(dirname(dirname(__FILE__))));
	
	include $path_erweima_core."/tool/phpqrcode/qrlib.php";
	$prefix = $path_erweima_core;
	$save_path="/upload/erweima/";
	$now_date = date("Ymd",time());
	$full_save_path=$path_erweima_core.$save_path.$now_date."/";

	if(!file_exists($prefix.$save_path))
	{
		mkdir($prefix.$save_path);
	}
	if(!file_exists($full_save_path))
	{
		$a = mkdir($full_save_path);
	}
	
	$pic_filename=$full_save_path.$phone.".png";
	$sql_save_path = $save_path.$now_date.'/'.$phone.".png";
	$errorCorrectionLevel = "L";
	$matrixPointSize=9;
	$margin=1;
	
	QRcode::png($phone, $pic_filename, $errorCorrectionLevel, $matrixPointSize, $margin); 
	
	if(file_exists($pic_filename))
	{
		return $sql_save_path;
	}
	else
	{
		return false;
	}
}
//获取随机字符串
function get_randmod_str(){
	$str = 'abcdABCefgD69EFhigkGHI7nm8JKpqMNrs3PQRtuS5vw4TxyU1VWzXYZ20';
    $len = strlen($str); //得到字串的长度;

    //获得随即生成的积分卡号
    $s = rand(0, 1);
    $serial = '';

    for($s=1;$s<=10;$s++)
    {
       $key     = rand(0, $len-1);//获取随机数
       $serial .= $str[$key];
    }

   //strtoupper是把字符串全部变为大写
   $serial = strtoupper(substr(md5($serial.time()),10,10));
   if($s)
   {
      $serial = strtoupper(substr(md5($serial),mt_rand(0,22),10));
   }
   
   return $serial;

}


//用户预定门票信息
if($ac == 'free_ticket2')
{
	//post: user_ticket_mobile *user_ticket_imei* ticket_id ticket_type user_ticket_realname user_ticket_sex user_ticket_age  user_ticket_address *user_ticket_company user_ticket_company_post*
	//api生成: user_ticket_code user_ticket_codepic user_ticket_status
	if(empty($_G['gp_phone']))
	{
		api_json_result(1,1,"缺少参数phone",null);
	}
	if(empty($_G['gp_ticket_id']))
	{
		api_json_result(1,1,"缺少参数ticket_id",null);
	}
	if(empty($_G['gp_ticket_type']))
	{
		api_json_result(1,1,"缺少参数ticket_type",null);
	}
	
	$user_ticket_mobile = $_G['gp_phone'];//手机号
	$user_ticket_imei = $_G['gp_phone_imei'];//手机窜号
	$ticket_id = $_G['gp_ticket_id'];//门票ID
	$ticket_type = $_G['gp_ticket_type'];//门票类型
	$ticket_nums = empty($_G['gp_ticket_nums']) ? 1 : $_G['gp_ticket_nums'];//门票数量
	$user_ticket_realname = urldecode($_G['gp_realname']);//订票人真实姓名
	$user_ticket_sex = urldecode($_G['gp_sex']);//性别
	$user_ticket_age = $_G['gp_age'];//年龄
	$user_ticket_address = urldecode($_G['gp_address']);//所在区域
	$user_ticket_company = urldecode($_G['gp_company']);//所在公司
	$user_ticket_company_post = urldecode($_G['gp_company_post']);//公司职位
	$user_ticket_code = get_randmod_str();//$_G['company_post'];//随机唯一窜
	$user_ticket_addtime = time();//$_G['company_post'];//随机唯一窜
	
	if($ticket_id)
	{
		//$event_id=DB::result_first("select event_id from tbl_ticket where ticket_id='".$ticket_id."' limit 1 ");		
		$row=DB::fetch_first("select event_id,ticket_times,ticket_starttime,ticket_endtime from tbl_ticket where ticket_id='".$ticket_id."' limit 1 ");
		$event_id=$row['event_id'];
		$ticket_times=$row['ticket_times'];
		$ticket_starttime=$row['ticket_starttime'];
		$ticket_endtime=$row['ticket_endtime']; 		
		
	}
	
	
	//检查用户是否已提交申请
    $sql = "select user_ticket_id,ticket_id,user_ticket_codepic,user_ticket_status from tbl_user_ticket where ticket_id='{$ticket_id}' and ticket_type='{$ticket_type}' and (user_ticket_mobile='{$user_ticket_mobile}' or user_ticket_imei='{$user_ticket_imei}')";
    $list = DB::fetch_first($sql);
	//已经索取过
	if($list)
	{
		$data['title'] = 'erweima';
		$erweima_path = erweima($user_ticket_mobile);
		if(empty($erweima_path)) {
			api_json_result(1,1,"二维码生成失败",null);
		}
		$sql = "update tbl_user_ticket set user_ticket_codepic='{$erweima_path}' where user_ticket_id='{$list['user_ticket_id']}'";
		$res = DB::query($sql);
		if(empty($res))
		{
			api_json_result(1,1,"索取二维码失败",null);
		}
		if($ticket_type == 'BASE'){
			if(empty($list['user_ticket_codepic'])) 
			{
				$data['data'] = $site_url.$erweima_path;
				api_json_result(1,0,"索取门票成功",$data);
			}
			else 
			{
				$data['data'] = $site_url.$list['user_ticket_codepic'];
				api_json_result(1,0,"索取门票成功",$data);
			}
		}
		if($ticket_type == 'VIP'){
			$data['title'] = 'erweima';
			$data['data'] = null;
			if($list['user_ticket_status'] == 1){
				$data['data'] = $site_url.$erweima_path;
				api_json_result(1,0,"门票索取成功",$data);
			}
			
			api_json_result(1,0,"门票索取成功，等待审核",$data);
		}
	}
    
    
	//生成二维码
	$erweima_path = erweima($user_ticket_mobile);
	$user_ticket_codepic = $erweima_path;
	
	$row=explode("/",$user_ticket_codepic);
    $user_ticket_code=str_replace(".png","",$row[4]);
	//普通票
	if($ticket_type=='BASE')
	{
		$user_ticket_status = 1;
		$sql = "insert into tbl_user_ticket(ticket_id,event_id,ticket_type,user_ticket_code,user_ticket_codepic,user_ticket_realname,user_ticket_sex,user_ticket_age,user_ticket_address,user_ticket_mobile,user_ticket_imei,user_ticket_company,user_ticket_company_post,user_ticket_status,user_ticket_addtime,ticket_times,ticket_starttime,ticket_endtime) values('{$ticket_id}','{$event_id}','{$ticket_type}','{$user_ticket_code}','{$user_ticket_codepic}','{$user_ticket_realname}','{$user_ticket_sex}','{$user_ticket_age}','{$user_ticket_address}','{$user_ticket_mobile}','{$user_ticket_imei}','{$user_ticket_company}','{$user_ticket_company_post}','{$user_ticket_status}','{$user_ticket_addtime}','{$ticket_times}','{$ticket_starttime}','{$ticket_endtime}')";
		$res = DB::query($sql);
		if($res)
		{
			
			$ticket_detail = DB::fetch_first("select ticket_name,ticket_price,ticket_ren_num,ticket_num,ticket_pic,ticket_starttime,ticket_endtime,ticket_times,ticket_content from tbl_ticket where ticket_id='{$ticket_id}' limit 1");
			$ticket_detail['ticket_name'] = $ticket_detail['ticket_name'];
			$ticket_detail['ticket_pic'] = $site_url.$erweima_path;
			//$ticket_detail['ticket_starttime'] = date('Y年m月d日',$ticket_detail['ticket_starttime']);
			//$ticket_detail['ticket_endtime'] = date('Y年m月d日',$ticket_detail['ticket_endtime']);
			
			$data['title'] = 'erweima';
			$data['data'] =$ticket_detail;
			api_json_result(1,0,"门票索取成功",$data);
		}
		api_json_result(1,1,"门票索取失败",null);
	}
	
	//VIP票
	if($ticket_type=='VIP')
	{
		$user_ticket_status = 0;
		
		$sql = "insert into tbl_user_ticket(ticket_id,event_id,ticket_type,user_ticket_code,user_ticket_codepic,user_ticket_realname,user_ticket_sex,user_ticket_age,user_ticket_address,user_ticket_mobile,user_ticket_imei,user_ticket_company,user_ticket_company_post,user_ticket_status,user_ticket_addtime,ticket_times,ticket_starttime,ticket_endtime) values('{$ticket_id}','{$event_id}','{$ticket_type}','{$user_ticket_code}','{$user_ticket_codepic}','{$user_ticket_realname}','{$user_ticket_sex}','{$user_ticket_age}','{$user_ticket_address}','{$user_ticket_mobile}','{$user_ticket_imei}','{$user_ticket_company}','{$user_ticket_company_post}','{$user_ticket_status}','{$user_ticket_addtime}','{$ticket_times}','{$ticket_starttime}','{$ticket_endtime}')";
		
		$res = DB::query($sql);
		if($res)
		{
			/* $data['title'] = 'data_list';
			$ticket_detail = DB::fetch_first("select ticket_name,ticket_price,ticket_ren_num,ticket_num,ticket_pic,ticket_starttime,ticket_endtime,ticket_times,ticket_content from tbl_ticket where ticket_id='{$ticket_id}' limit 1");
			$ticket_detail['ticket_pic'] = $site_url.'/'.$ticket_detail['ticket_pic'];
			$ticket_detail['ticket_starttime'] = date('Y年m月d日',$ticket_detail['ticket_starttime']);
			$ticket_detail['ticket_endtime'] = date('Y年m月d日',$ticket_detail['ticket_endtime']);
			$data['data'] = array(
						'erweima_path' => $site_url.$erweima_path;
						'ticket_detail'=>$ticket_detail
					); */
			$data['title'] = 'erweima';
			$data['data'] = null;
			api_json_result(1,0,"门票索取成功，等待审核",$data);
		}
		api_json_result(1,1,"门票索取失败",null);
	}
}


//获取比赛门票
if($ac == 'free_ticket')
{
    $event_id = $_G['gp_event_id'];
    $fenzhan_id = $_G['gp_fenzhan_id'];
    $user_name = $_G['gp_user_name'];
    $user_phone = $_G['gp_user_phone'];
    if(empty($event_id) || empty($user_name) || empty($user_phone)) 
    {
        api_json_result(1,1,"queshaocanshu缺少参数",null);
    }

    $data['title'] = 'erweima';
    //是否索取过
    $sql = "select free_tickets_id,user_name,user_phone,erweima_path from tbl_field_free_tickets where event_id='{$event_id}' and fenzhan_id='{$fenzhan_id}' order by free_tickets_id desc";
    $list = DB::query($sql);
    $free_tickets_id = 0;
    while($row=DB::fetch($list))
	{
        if(($row['user_name'] == $user_name) || $row['user_phone'] == $user_phone) {
            //返回二维码
            $user_ticket = array_default_value($row);
            break;
        }
    }

    if(!empty($user_ticket)) {
        if(empty($user_ticket['erweima_path'])) 
        {
            $erweima_path = erweima($user_phone);
            if(empty($erweima_path)) {
                api_json_result(1,1,"二维码生成失败",null);
            }
            $sql = "update tbl_field_free_tickets set erweima_path='{$erweima_path}',user_phone='{$user_phone}' where free_tickets_id='{$user_ticket['free_tickets_id']}'";
            $res = DB::query($sql);
            if(empty($res)) {
                api_json_result(1,1,"二维码生成失败",null);
            }
            $data['data'] = $site_url.$erweima_path;
            api_json_result(1,0,"索取门票成功",$data);
        }
        else 
        {
            $data['data'] = $site_url.$user_ticket['erweima_path'];
            api_json_result(1,0,"索取门票成功",$data);
        }
    }
    $erweima_path = erweima($user_phone);
    if(empty($erweima_path)) {
        api_json_result(1,1,"二维码生成失败",null);
    }
    if(empty($fenzhan_id)) {
        $fenzhan_id=DB::fetch_first("select fenzhan_id from tbl_fenzhan where event_id='{$event_id}' limit 1");
    }
    $sql = "insert into tbl_field_free_tickets(event_id,fenzhan_id,user_name,user_phone,erweima_path,free_tickets_addtime)";
    $sql .= " values('{$event_id}','{$fenzhan_id}','{$user_name}','{$user_phone}','{$erweima_path}','{$now_time}')";
    
    $res = DB::query($sql);
    if(empty($res)) 
    {
        api_json_result(1,1,"索取门票失败",null);
    }
    $data['data'] = $site_url.$erweima_path;
    api_json_result(1,0,"索取门票成功",$data);
}

if($ac == 'up_menupic') 
{
	if($_FILES["pic"]["error"]<=0 && $_FILES["pic"]["name"])
	{
		$save_path="../upload/menu_pic/";
			$full_save_path=$save_path.date("Ymd",time())."/";
			if(!file_exists($save_path))
			{
				mkdir($save_path);
			}
			if(!file_exists($full_save_path))
			{
				mkdir($full_save_path);
			}
			
			$time_name=time();

			move_uploaded_file($_FILES["pic"]["tmp_name"], $full_save_path. $time_name.$_FILES["pic"]["name"]);//将上传的文件存储到服务器
			
			$file_path="../upload/menu_pic/".date("Ymd",time())."/".$time_name.$_FILES["pic"]["name"];
			$file_path_sql="/upload/menu_pic/".date("Ymd",time())."/".$time_name.$_FILES["pic"]["name"];
			$extname=end(explode(".",$file_path));
		if($extname=="jpg")
		{
			$pic_source=imagecreatefromjpeg($file_path);
		}

		$file_path2="../upload/menu_pic/".date("Ymd",time())."/".$time_name.$_FILES["pic"]["name"]."_small";
		//echo $file_path2;
		if(file_exists($file_path))
		{
			$aa=resizeImage($pic_source,100,100,$file_path2,".".$extname);
			//print_r($aa);

			$res=DB::query("update tbl_field_1stmenu set field_menu_pic='".$file_path_sql."' where field_menu_id='3' ");	
			api_json_result(1,0,"1",$data);
		}
		else
		{
			api_json_result(1,1,"2",$data);
		}

	}
	else
	{
		api_json_result(1,2,"图片上传失败",$data);
	}
}
if($ac == 'curl_post') {
    $a = $_G['sssss'];
    $b = $_POST['sssss'];
    echo '4444a'.$a.'b'.$b;
}
//    $uid = $_G['gp_uid'];
//    if(empty($uid)) 
//    {
//        api_json_result(1,1,'缺少参数',null);
//        exit;
//    }