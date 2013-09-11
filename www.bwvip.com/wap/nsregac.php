<?php
/*
*
*	报名页面
*
*/
define('APPTYPEID', 0);
define('CURSCRIPT', 'member');
require '../source/class/class_core.php';
$discuz = & discuz_core::instance();

$discuz->init();
$ac=$_G['gp_ac'];

 $provinceArray[1]="北京市";
        
    $provinceArray[2]="天津市";
        
    $provinceArray[3]="河北省";
        
    $provinceArray[4]="山西省";
        
    $provinceArray[5]="内蒙古自治区";
        
    $provinceArray[6]="辽宁省";
        
    $provinceArray[7]="吉林省";
        
    $provinceArray[8]="黑龙江省";
        
    $provinceArray[9]="上海市";
        
    $provinceArray[10]="江苏省";
        
    $provinceArray[11]="浙江省";
        
    $provinceArray[12]="安徽省";
        
    $provinceArray[13]="福建省";
        
    $provinceArray[14]="江西省";
        
    $provinceArray[15]="山东省";
        
    $provinceArray[16]="河南省";
        
    $provinceArray[17]="湖北省";
        
    $provinceArray[18]="湖南省";
        
    $provinceArray[19]="广东省";
        
    $provinceArray[20]="广西壮族自治区";
        
    $provinceArray[21]="海南省";
        
    $provinceArray[22]="重庆市";
        
    $provinceArray[23]="四川省";
        
    $provinceArray[24]="贵州省";
        
    $provinceArray[25]="云南省";
        
    $provinceArray[26]="西藏自治区";
        
    $provinceArray[27]="陕西省";
        
    $provinceArray[28]="甘肃省";
        
    $provinceArray[29]="青海省";
        
    $provinceArray[30]="宁夏回族自治区";
        
    $provinceArray[31]="新疆维吾尔自治区";
        
    $provinceArray[32]="台湾省";
        
    $provinceArray[33]="香港特别行政区";
        
    $provinceArray[34]="澳门特别行政区";
        
    $provinceArray[35]="海外";
        
    $provinceArray[36]="其他";


if($ac=="ns_reg")
{ 
	$qiancheng = $_G['gp_qiancheng'];
	  $width = $_G['gp_width'];
	$family_name = $_G['gp_family_name'];
	$name = $_G['gp_name'];	
	$year = $_G['gp_year'];
	$month = $_G['gp_month'];
	$day = $_G['gp_day']; 
	$phone = $_G['gp_phone'];
	$email = $_G['gp_email'];
	$prov = $_G['gp_province'];	
    $province=$provinceArray[$prov];
	$city = $_G['gp_city']; 


	$name2 = $_G['gp_name2'];
	$name3 = $_G['gp_name3'];
	$name4 = $_G['gp_name4']; 
	if($name2)
	{
	  $str.=",56|".$name2; 
		}
	
	if($name3)
	{
	  $str.=",57|".$name3; 
		}
	
	if($name4)
	{
	  $str.=",58|".$name4; 
		}
	
	
	$address = $_G['gp_address'];
	$postcode = $_G['gp_postcode'];
	//$watchdate = $_G['gp_watch_date']; 
	 $watch_date=$str;
	//$watch_date=implode(",", $watchdate );
	$watch_date=ltrim($watch_date, ",") ;
	$is_owners = $_G['gp_is_owners'];
	$bwm_cars = $_G['gp_bwm_cars']; 
	$buy_car_date = $_G['gp_buy_car_date'];	 
	$learn_channels = $_G['gp_learn_channels'];
	$is_contact = $_G['gp_is_contact']; 
	$is_readed = $_G['gp_is_readed'];	 
	$bwm_addtime = time();
	$source="41";
	if(empty($is_readed)){
		$is_readed = 1;
	}
	$sql = "insert into tbl_user_ticket_get(qiancheng,source,family_name,name,year,month,day,phone,email,province,city,address,postcode,watch_date,is_owners,bwm_cars,buy_car_date,learn_channels,is_contact,is_readed,bwm_addtime)";
	$sql .= " values('{$qiancheng}','{$source}','{$family_name}','{$name}','{$year}','{$month}','{$day}','{$phone}','{$email}','{$province}','{$city}','{$address}','{$postcode}','{$watch_date}','{$is_owners}','{$bwm_cars}','{$buy_car_date}','{$learn_channels}','{$is_contact}','{$is_readed}','{$bwm_addtime}')";
	
    $res = DB::query($sql);
	
		//include "erweima.php";
		$user_ticket_mobile = $phone;//手机号
	$user_ticket_imei = $_G['gp_phone_imei'];//手机窜号
	$ticket_id = empty($_G['gp_ticket_id']) ? 0 : $_G['gp_ticket_id'];//门票ID
	$event_id = empty($_G['gp_event_id']) ? 0 : $_G['gp_event_id'];//赛事ID
	
	$ticket_type = 'ns';//门票类型
	$ticket_times = empty($_G['gp_ticket_times']) ? 1 : $_G['gp_ticket_times'];//门票数量
	$user_ticket_realname = urldecode($family_name.$name);//订票人真实姓名
	$user_ticket_sex = urldecode($qiancheng);//性别
	$user_ticket_age = $_G['gp_age'];//年龄
	$user_ticket_address = urldecode($province.$city.$address);//所在区域
	$user_ticket_company = urldecode($_G['gp_company']);//所在公司
	$user_ticket_company_post = urldecode($_G['gp_company_post']);//公司职位
	//$user_ticket_code = get_randmod_str();//$_G['company_post'];//随机唯一窜
	$user_ticket_addtime = time();//$_G['company_post'];//随机唯一窜
	$ticket_starttime=strtotime('2013-10-10 7:00');
	$ticket_endtime=strtotime('2013-10-13 19:00');
	$out_idtype='tbl_user_ticket_get';	
	$out_id = DB::insert_id(); 
	
//生成二维码 
	//$erweima_path = erweima();
	$user_ticket_codepic = $erweima_path;
	
	$row=explode("/",$user_ticket_codepic);
    $user_ticket_code=str_replace(".png","",$row[4]);	
	
	$user_ticket_status = 0;
		$sql = "insert into tbl_user_ticket(ticket_id,event_id,ticket_type,user_ticket_code,user_ticket_codepic,user_ticket_realname,user_ticket_sex,user_ticket_age,user_ticket_address,user_ticket_mobile,user_ticket_imei,user_ticket_company,user_ticket_company_post,user_ticket_status,user_ticket_addtime,ticket_times,ticket_starttime,ticket_endtime,out_idtype,out_id) values('{$ticket_id}','{$event_id}','{$ticket_type}','{$user_ticket_code}','{$user_ticket_codepic}','{$user_ticket_realname}','{$user_ticket_sex}','{$user_ticket_age}','{$user_ticket_address}','{$user_ticket_mobile}','{$user_ticket_imei}','{$user_ticket_company}','{$user_ticket_company_post}','{$user_ticket_status}','{$user_ticket_addtime}','{$ticket_times}','{$ticket_starttime}','{$ticket_endtime}','{$out_idtype}','{$out_id}')";
	
    //$res = DB::query($sql);
	
	if($res == false){
	 	echo "<script>location='nserror.php?width=$width';</script>";
	}

   	echo "<script>location='nssuccess.php?width=$width';</script>";
}
 
 
 
?>
