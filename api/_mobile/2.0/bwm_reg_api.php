<?php
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}
$userAgent = $_SERVER['HTTP_USER_AGENT'];

if(strpos($userAgent,"iPhone") || strpos($userAgent,"iPad") || strpos($userAgent,"iPod") || strpos($userAgent,"iOS"))
{
	$user_device = 'IOS';
}else if(strpos($userAgent,"Android") || $_G['gp_from'] == 'android')
{
	$user_device = 'Android';
}else{
	$user_device = '';
}

$ac=$_G['gp_ac'];
//修改密码
if($ac=="bwm_reg")
{	
	$ticket_id = 42;
	$field_uid = $_G['gp_field_uid'];
	$uid = $_G['uid'];
	
	if($field_uid == 0)
	{
		$field_name = '大正客户端';
	}else{
		$field_info = DB::fetch_first("select * from pre_common_field where uid='{$field_uid}' limit 1");
		$field_name = $field_info['fieldname'];
	}
	
	$user_ticket_imei = urldecode($_G['gp_user_ticket_imei']);
	$qiancheng = urldecode($_G['gp_qiancheng']);
	$family_name = urldecode($_G['gp_family_name']);
	$name = urldecode($_G['gp_name']);
	$year = urldecode($_G['gp_year']);
	$month = urldecode($_G['gp_month']);
	$day = urldecode($_G['gp_day']);
	$phone = urldecode($_G['gp_phone']);
	if(!preg_match_type($phone,'phone')){
		api_json_result(1,1,"手机号格式不正确",$data);
	}
	$email = urldecode($_G['gp_email']);
	if(!preg_match_type($email,'email')){
		api_json_result(1,1,"电子邮件格式不正确",$data);
	}
	$province = urldecode($_G['gp_province']);
	$city = urldecode($_G['gp_city']);
	$address = urldecode($_G['gp_address']);
	$postcode = urldecode($_G['gp_postcode']);
	if(!preg_match_type($postcode,'postcode')){
		api_json_result(1,1,"邮政编码不正确",$data);
	}
	$watch_date = urldecode($_G['gp_watch_date']);
	$is_owners = $_G['gp_is_owners'];
	$bwm_cars = urldecode($_G['gp_bwm_cars']);
	$buy_car_date = urldecode($_G['gp_buy_car_date']);
	$learn_channels = urldecode($_G['gp_learn_channels']);
	$is_contact = $_G['gp_is_contact'];
	$is_readed = '是';//$_G['gp_is_readed'];
	$bwm_addtime = time();
	$bwm_adddate = date('Y年m月d日 H:i:s',$bwm_addtime);
	
	if($uid == ''){
		$uid = user_add_return($family_name.$name,$phone,$email);
	}
	
	if(empty($uid)){
		$uid = 0;
	}
	
	$ticket_info = get_ticket_info($ticket_id);
	$user_ticket_age = intval(date('Y',time()))-intval(($year?$year:date('Y',time())));
	
	$watch_date_arr = explode(',',$watch_date);
	
	$erweima_path = erweima();
	$user_ticket_data = array(
		'uid' => $uid,
		'ticket_id' => $ticket_info['ticket_id'],
		'event_id' => $ticket_info['event_id'],
		'ticket_type' => $ticket_info['ticket_type'],
		'ticket_starttime' =>$ticket_info['ticket_starttime'],
		'ticket_endtime' => $ticket_info['ticket_endtime'],
		'ticket_times' => $ticket_info['ticket_times'],
		'ticket_price' => $ticket_info['ticket_price'],
		'user_ticket_codepic' => $erweima_path,
		'user_ticket_imei' => $user_ticket_imei,
		'user_ticket_nums' => count($watch_date_arr),
		'user_ticket_realname' => $family_name.$name,
		'user_ticket_sex' => $qiancheng == '先生' ? '男':'女',
		'user_ticket_age' => $user_ticket_age,
		'user_ticket_address' => $province.$city.$address,
		'user_ticket_mobile' => $phone
	);
	if(!empty($ticket_info['ticket_price'])){
		$user_ticket_data['user_ticket_status'] = '0';
	}else{
		$user_ticket_data['user_ticket_status'] = '1';
	}
	//$nian = date('Y',time());
	
	$user_ticket_ids = $user_ticket_id = insert_into_user_ticket($user_ticket_data);
	
	if(empty($user_ticket_id)){
		$sql = "delete from tbl_user_ticket where user_ticket_id in({$user_ticket_id})";
		DB::query($sql);
		api_json_result(1,1,"提交失败",null);
	}
	
	if(!empty($is_contact))
	{
		$is_contact = '是';
	}else{
		$is_contact = '否';
	}
	if(!empty($is_owners))
	{
		$is_owners = '是';
	}else{
		$is_owners = '否';
	}
	
	/*
	 	if(empty($erweima_path)) {
			api_json_result(1,1,"二维码生成失败",null);
		}
	*/	
	$sql = "insert into tbl_user_ticket_bmw(user_ticket_id,user_ticket_ids,qiancheng,family_name,name,year,month,day,phone,email,province,city,address,postcode,watch_date,is_owners,bwm_cars,buy_car_date,learn_channels,is_contact,is_readed,bwm_addtime,bwm_adddate,user_device,field_name)";
	$sql .= " values('{$user_ticket_id}','{$user_ticket_ids}','{$qiancheng}','{$family_name}','{$name}','{$year}','{$month}','{$day}','{$phone}','{$email}','{$province}','{$city}','{$address}','{$postcode}','{$watch_date}','{$is_owners}','{$bwm_cars}','{$buy_car_date}','{$learn_channels}','{$is_contact}','{$is_readed}','{$bwm_addtime}','{$bwm_adddate}','{$user_device}','{$field_name}')";
	
    $rs = DB::query($sql);
	if($rs == false){
		$sql = "delete from tbl_user_ticket where user_ticket_id in({$user_ticket_ids})";
		DB::query($sql);
		api_json_result(1,1,"提交失败",null);
	}
	$insert_id = DB::insert_id();
	
	$sql = "update tbl_user_ticket set out_idtype='tbl_user_ticket_bmw',out_id='{$insert_id}' where user_ticket_id in({$user_ticket_ids})";
	$rs = DB::query($sql);
	if(!$rs){
		$sql = "delete from tbl_user_ticket where user_ticket_id in({$user_ticket_ids})";
		DB::query($sql);
		$sql = "delete from tbl_user_ticket_bmw where id='{$insert_id}'";
		DB::query($sql);
		api_json_result(1,1,"提交失败",null);
	}
	sys_message_add_return($user_ticket_data,$field_uid);
	/* $data['title']='erweima';
	$data['data']=$site_url.$erweima_path;*/
    api_json_result(1,0,"国内注册用户，我们将寄送门票到您注册邮寄地址，海外注册用户烦请您在比赛当日至BMW大师赛现场领取门票，每日限领2张。",$data);
}

function get_ticket_info($ticket_id)
{
	$sql = "select * from tbl_ticket where ticket_id='{$ticket_id}'";
	
    $ticket_info = DB::fetch_first($sql);
	
	return $ticket_info;
}

function insert_into_user_ticket($data_list)
{
	if(empty($data_list))
	{
		return false;
	}
	$data_list['user_ticket_addtime'] = time();
	$data_list['user_ticket_code'] = get_randmod_str();
	$fields = array();
	$values = array();
	foreach($data_list as $key=>$val){
		if(!empty($val)){
			$fields[] = $key;
			$values[] = $val;
		}
	}
	
	$sql = "insert into tbl_user_ticket(".implode(',',$fields).") values('".implode("','",$values)."')";
	//echo $sql.'<br>';
	$rs = DB::query($sql);
	if($rs){
		$last_id = DB::insert_id();
		return $last_id;
	}
	return $rs;
}


if($ac == 'preg'){
	
	$ticket_id = 12;
	if($uid == ''){
		$uid = user_add_return($phone);
	}
	
	if(empty($uid)){
		$uid = 0;
	}
	$watch_date = "10月28日,10月27日";
	$ticket_info = get_ticket_info($ticket_id);
	$user_ticket_age = intval(date('Y',time()))-intval(($year?$year:date('Y',time())));
	
	$watch_date_arr = explode(',',$watch_date);
	
	$user_ticket_data = array(
		'uid' => $uid,
		'ticket_id' => $ticket_info['ticket_id'],
		'event_id' => $ticket_info['event_id'],
		'ticket_type' => $ticket_info['ticket_type'],
		'ticket_starttime' =>$ticket_info['ticket_starttime'],
		'ticket_endtime' => $ticket_info['ticket_endtime'],
		'ticket_times' => $ticket_info['ticket_times'],
		'ticket_price' => $ticket_info['ticket_price'],
		'user_ticket_imei' => $user_ticket_imei,
		'user_ticket_nums' => '1',
		'user_ticket_realname' => $family_name.$name,
		'user_ticket_sex' => $qiancheng == '先生' ? '男':'女',
		'user_ticket_age' => $user_ticket_age,
		'user_ticket_address' => $province.$city.$address,
		'user_ticket_mobile' => $phone
	);
	$nian = date('Y',time());
	$insert_ids = array();
	$watch_num = 0;
	foreach($watch_date_arr as $key=>$val){
		if(empty($val))
		{
			continue;
		}
		$watch_num++;
		$yue = $val[0].$val[1];
		$ri = $val[5].$val[6];
		$ticket_starttime = mktime(0,0,0,$yue,$ri,$nian);
		$ticket_enttime = $ticket_starttime+86400;
		$user_ticket_data['ticket_starttime']=$ticket_starttime;
		$user_ticket_data['ticket_endtime']=$ticket_enttime;
		//echo strtotime("{$nian}-{$yue}-{$ri}").'<br>';die;
		//echo date('Y-m-d',mktime(0,0,0,$yue,$ri,$nian)).'<br>';
		
		$user_ticket_data['user_ticket_codepic']=erweima();
		$insert_ids[] = $user_ticket_id = insert_into_user_ticket($user_ticket_data);
		sys_message_add_return($user_ticket_data,$field_uid);
	}
	
	//sys_message_add_return($user_ticket_data);
	/* $str = $_G['gp_str'];
	$type = $_G['gp_type'];
	echo $str;
	echo '<br>';
	echo $type;
	echo '<br>';
	if(!preg_match_type($str,$type)){
		echo '正确';
	}
	else
	{
		echo '错误';
	} */
}
function preg_match_type($str,$type="int")
{
	switch ($type)
	{
	case 'int':
	  $pattern = "/^[0-9]*$/";
	  break;  
	case 'phone':
	  $pattern = "/13[0-9]{9}|15[0-9]\d{8}|18[0-9]\d{8}/";
	  break;
	case 'email':
	  $pattern = "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/";
	  break;
	case 'postcode':
	  $pattern = "/^[0-9]\d{5}$/";
	  break;
	}
	
	return preg_match($pattern,$str);
}


//生成二维码成功返回路径，失败返回 false
function erweima()
{
	$phone = mt_rand(1000000000,9999999999);
	//如果没有就生成二维码
	$path_erweima_core = dirname(dirname(dirname(dirname(__FILE__))));
	
	require_once($path_erweima_core."/tool/phpqrcode/qrlib.php");
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

/*
*  添加用户注册
*/
function user_add_return($username,$phone,$email)
{
	if(!empty($phone))
	{
		$sql = "select uid,mobile from pre_common_member_profile where mobile='{$phone}' order by uid desc";
		$rs=DB::fetch_first($sql);
		if(!empty($rs)){
			return $rs['uid'];
		}
	}
	if(empty($username)){
		$username = time(). mt_rand(1000,9999);
	}
	//$username=$phone;//time(). mt_rand(1000,9999);//post("user_ticket_realname");	
	$password_tmp = $password=mt_rand(100000,999999);
	$salt = substr(uniqid(rand()), -6);
	$password = md5(md5($password).$salt);
	$salt=$salt;
	$password=$password;
	$email=$email; 
	$mobile=$phone; 
	$regip=time();
	$regdate=time();
	$gender = '';
	//生成ucenter会员
	$sql = "insert into pre_ucenter_members(username,salt,password,email,regip,regdate) values('{$username}','{$salt}','{$password}','{$email}','{$regip}','{$regdate}')";
	$rs = DB::query($sql);
	$ucuid=DB::insert_id();
	$groupid=10;  
	//生成社区会员
	$sql = "insert into pre_common_member(uid,username,password,email,regdate,groupid) values('{$ucuid}','{$username}','{$password}','{$email}','{$regdate}','{$groupid}')";
	$rs = DB::query($sql);
	
	$sql = "insert into pre_common_member_profile(uid,realname,gender,mobile,regdate) values('{$ucuid}','{$username}','{$gender}','{$mobile}','{$regdate}')";
	//生成真实姓名
	$rs = DB::query($sql);
	
	$role_id = 3;
	$sql = "insert into jishigou_members(uid,username,nickname,password,email,phone,regip,regdate,gender,role_id) values('{$ucuid}','{$username}','{$username}','{$password}','{$email}','{$mobile}','{$regip}','{$regdate}','{$gender}','{$role_id}')";
	///生成微博记录
	$rs = DB::query($sql);
	if($rs!=false)
	{
		//发送短信
		if($mobile){
			$msg_content="您的门票已购买成功并成为大正网用户,请您下载并登录大正网客户端 个人中心，我的门票中查看具体信息。您的大正登录名为:".$mobile."，密码为:".$password_tmp."，大正客户端下载地址：http://www.bwvip.com/app ";
			$msg_content=iconv('UTF-8', 'GB2312', $msg_content);;
			send_msg($mobile,$msg_content);
		}
		return $ucuid;
	}
	else
	{
		return false;
	}
}
//添加系统消息
function sys_message_add_return($user_ticket_info,$field_uid=0)
{
	$sys_event_id = $user_ticket_info['event_id'];
	
	$sql = "select field_uid,event_name from tbl_event where event_id='{$sys_event_id}'";

	$sys_event_info = DB::fetch_first($sql);
	

	
	if($user_ticket_info["uid"])
	{
		$sys_uid=$user_ticket_info["uid"];
	}
	else
	{
		$sys_uid=0;
	}
	$uid=$sys_uid;
	$message_title=$sys_event_info['event_name']."门票申请成功";

	$n_title=$message_title;
	$n_content=$message_title;
	
	$message_extinfo=array('action'=>"system_msg");	
	
	$msg_content = json_encode(array('n_title'=>urlencode($n_title), 'n_content'=>urlencode($n_content),'n_extras'=>$message_extinfo));

	$message_content=$msg_content;
	$receiver_type=3;//3:指定用户
	$message_pic=$user_ticket_info['user_ticket_codepic'];
	

	
	$message_totalnum=0;
	$message_sendnum=0;
	$message_errorcode="";
	$message_errormsg="";
	$message_addtime=time();
	
	$sql = "insert into tbl_sys_message(field_uid,uid,message_title,message_content,receiver_type,message_pic,message_totalnum,message_sendnum,message_errorcode,message_errormsg,message_addtime) values('{$field_uid}','{$uid}','{$message_title}','{$message_content}','{$receiver_type}','{$message_pic}','{$message_sendnum}','{$message_errorcode}','{$message_errormsg}','{$field_uid}','{$message_addtime}')";
	$rs = DB::query($sql);

	if($rs!=false)
	{
		return true;
	}
	else
	{				
		return false;
	}

}


?>
