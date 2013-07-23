<?php
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}


$ac=$_G['gp_ac'];
$uid = $_G['gp_uid'];//会员账号
$now_time = time();
if(empty($ac) || empty($uid)) {
    api_json_result(1,1,"参数不完整",'');
}

//会员年费信息 
if($ac == 'money') {
    $field_uid = $_G['gp_field_uid'];//球场编号
    $source = $_G['gp_source'];//金额类型
    if(!in_array($source, array(1, 2, 3)) || empty($field_uid)) {
        api_json_result(1,1,"参数不完整",'');
	    exit;
    }
    $first_time = '';
    if($source == 1 || $source == 2) {
        //今年到现在，总共，使用，剩余
        //年初时间戳
        $first_time = strtotime(date('Y-1-1',$now_time));
    }
    $money_arr = get_money_list($uid, $field_uid, $source, $first_time);
    if(empty($money_arr)) {
	    api_json_result(1,1,"没有数据",$money_arr);
	    exit;
	}
    $data['title'] = 'money';
	$data['data']=$money_arr;
	unset($money_arr);
	//print_r($data);die;
	api_json_result(1,0,$app_error['event']['10502'],$data);
	
}else if($ac == 'quan') {
    $field_uid = $_G['gp_field_uid'];//球场编号
    if(empty($field_uid)) {
        api_json_result(1,1,"缺少参数",'');
    }
    $quan_list = array();
    $year_time = strtotime(date('Y-1-1',$now_time));
    $quan_list['first_quan'] = get_quan_list($uid, $field_uid, 1, $year_time);
	
    $yue_time = strtotime(date('Y-m-1',$now_time));
    $quan_list['second_quan'] = get_quan_list($uid, $field_uid, 2, $yue_time);
	
    if(empty($quan_list)) {
	    api_json_result(1,1,"没有数据",$quan_list);
	    exit;
	}
    
    if(empty($quan_list)) {
        $quan_list = null;
    }
    $data['title'] = 'quan';
	$data['data']=$quan_list;
	api_json_result(1,0,$app_error['event']['10502'],$data);
}
//会员申请
elseif($ac == 'app') 
{
    $field_uid = $_G['gp_field_uid'];//球场编号
    $card = $_G['gp_card'];
    $phone = $_G['gp_phone'];
    $intime = $_G['gp_intime'];
    $data['title'] = 'apply';
    $data['data'] = 0;
    if(empty($card) || empty($phone) || empty($field_uid)) {
        api_json_result(1,1,"参数不完整",$data);
    }
    if(empty($intime)) {
        $intime = date('Y-m-d',time());
    }
    $sql = "insert into tbl_field_user(uid,field_uid,field_user_card,field_user_phone,field_user_intime,field_user_addtime) values ('{$uid}','{$field_uid}','{$card}','{$phone}','{$intime}','{$now_time}')";
    $res = DB::query($sql);
    if(empty($res)) 
    {
        $data['data'] = 0;
        api_json_result(1,1,"申请失败",$data);
    }
    else 
    {
        $data['data'] = 1;
        api_json_result(1,0,"申请成功，等待审核",$data);
    }
}
elseif($ac == 'updpwd') 
{
    $bzdmima = $_G['gp_bzdmima'];
    $oldpwd = $_G['gp_oldpwd'];
    $newpwd1 = $_G['gp_newpwd1'];
    $newpwd2 = $_G['gp_newpwd2'];
    if(!empty($bzdmima)){
        $uid_info = DB::fetch_first("select uid from pre_ucenter_members where username='{$bzdmima}'");
		$uid = $uid_info['uid'];
    }else{
        if(empty($oldpwd) || empty($newpwd1) || empty($newpwd2)) 
        {
            api_json_result(1,1,"参数不完整",'');
        }
        if($newpwd1 != $newpwd2)
        {
            api_json_result(1,1,"新密码两次输入不一致",'');
        }
        $uc_res = DB::fetch_first("select password,salt from pre_ucenter_members where uid='{$uid}'");
        $jsg_res = DB::fetch_first("select password from jishigou_members where uid='{$uid}'");
        $discuz_res = DB::fetch_first("select password from pre_common_member where uid='{$uid}'");
        if(empty($jsg_res) || empty($discuz_res) || empty($uc_res)) {
    
            api_json_result(1,1,"用户不存在",'');
        }
        
        $oldpwd = md5(md5($oldpwd).$uc_res['salt']);
       
        if(($oldpwd != $uc_res['password']))
        {
            api_json_result(1,1,"旧密码输入不正确",'');
        }
    }
    
    $uc_res = DB::fetch_first("select password,salt from pre_ucenter_members where uid='{$uid}'");
    $newpwd1 = md5(md5($newpwd1).$uc_res['salt']);
    $jsg_res = DB::query("update jishigou_members set password='{$newpwd1}' where uid='{$uid}'");
    $discuz_res = DB::query("update pre_common_member set password='{$newpwd1}' where uid='{$uid}'");
    $uc_res = DB::query("update pre_ucenter_members set password='{$newpwd1}' where uid='{$uid}'");

    api_json_result(1,0,"修改成功",'');
}


//tbl_field_money表
function get_money_list ($uid, $field_uid, $source, $pretime, $is_list=false) {
    $now_time = time();
    $sql = "select * from tbl_field_money where uid='{$uid}' and money_source='{$source}'";
    if(!empty($field_uid)) 
    {
        $sql .= " and field_uid='{$field_uid}'";
    }
    if(!empty($pretime)) 
    {
        $sql .= " and money_addtime>{$pretime} and money_endtime>{$now_time}";
    }
    
    //echo $sql;
    $list=DB::query($sql);
    $money_arr['outlay'] = 0;
	$money_arr['income'] = 0;
    while($row=DB::fetch($list)) {
        if($row['money_type'] == 1) {//存入
	        $money_arr['income'] += $row['money_num'];
	    }else if($row['money_type'] == 2) {//支出
	        $money_arr['outlay'] += $row['money_num'];
	    }
        $list_data[$row['money_id']]=array_default_value($row);
	}
	if(empty($is_list)) {
	    $money_arr['lave'] = $money_arr['income'] - $money_arr['outlay'];
	    $money_arr['lave'] = abs($money_arr['lave']);
	    unset($list_data);
	    $list_data = array_default_value($money_arr);
	}
	
    return !empty($list_data) ? $list_data : false;
}


//tbl_field_quan表
function get_quan_list ($uid, $field_uid, $source, $time, $is_list=false) {
    $sql = "select * from tbl_field_quan where uid='{$uid}'";
    $now_time = time();
    if(!empty($source)) {
        $sql .= " and quan_source='{$source}'";
    }
    if(!empty($field_uid)) {
        $sql .= " and field_uid='{$field_uid}'";
    }
    if(!empty($time)) {
        $sql .= " and quan_addtime>{$time} and quan_endtime>{$now_time}";
    }
    //echo $sql;
    $list=DB::query($sql);
    $quan_arr['outlay'] = 0;
	$quan_arr['income'] = 0;
    while($row=DB::fetch($list)) {
        if($row['quan_type'] == 1) {//存入
	        $quan_arr['income'] += $row['quan_num'];
	    }else if($row['quan_type'] == 2) {//支出
	        $quan_arr['outlay'] += $row['quan_num'];
	    }
        $list_data[$row['quan_id']]=array_default_value($row);
	}
	
	if(empty($is_list)) {
	    $quan_arr['lave'] = $quan_arr['income'] - $quan_arr['outlay'];
	    unset($list_data);
	    $list_data = array_default_value($quan_arr);
	}
	
    return !empty($list_data) ? $list_data : false;
}

function get_field_user($uid, $field_uid) {
    $sql = "select * from tbl_field_user where uid='{$uid}' and field_uid='{$field_uid}'";
    $detail_data=DB::fetch_first($sql);
    $detail_data = array_default_value($detail_data);
	if($detail_data['field_addtime'])
	{
		$detail_data['field_adddate']=date("Y-m-d G:i",$detail_data['field_addtime']);
	}

	return !empty($detail_data) ? $detail_data : false;
	
}
if($ac == 'up_menupic') {
    echo $site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=middle";die;
    
	if($_FILES["pic"]["error"]<=0 && $_FILES["pic"]["name"])
	{
		$save_path="../upload/score/";
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
		
		$file_path="/upload/score/".date("Ymd",time())."/".$time_name.$_FILES["pic"]["name"];
		$extname=end(explode(".",$file_path));
		if($extname=="jpg")
		{
			$pic_source=imagecreatefromjpeg($file_path);
		}

		$file_path2="/upload/score/".date("Ymd",time())."/".$time_name.$_FILES["pic"]["name"]."_small";
		//echo $file_path2;
		if(file_exists($file_path))
		{
			$aa=resizeImage($pic_source,100,100,$file_path2,".".$extname);
			//print_r($aa);

			$res=DB::query("update tbl_field_menu set field_menu_pic='".$file_path."' where field_menu_id='4' ");	
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