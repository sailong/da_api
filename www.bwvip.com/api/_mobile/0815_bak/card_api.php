<?php

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}


/**
 * 获取成绩卡
 * 
 //
$uid=$_G['gp_uid']; 该用户的成绩卡列表
$id=$_G['gp_id'];单条成绩卡记录
 */


$t=time();

$ac=$_G['gp_ac'];// show显示列表、记录 edit修改记录 del 删除 rank排名
$uid=$_G['gp_uid'];
$id=$_G['gp_id'];//成绩卡单条记录ID
$sid=$_G['gp_sid']; //赛事ID
$lun=$_G['gp_lun'];//赛事第几轮  
$limit = $_G['gp_limit'] ? $_G['gp_limit'] : '10';//显示条数

$strwhere=$id?' and id='.$id:'';

$username = DB::result_first( "select realname  from " . DB::table ( 'common_member_profile' ) . "  where uid='$uid' ");

//添加记录
if($ac==='insert')
{ 
	$arr ['uid'] = $uid;
	$arr ['fuid'] = $fuid;
	$arr ['par'] = $par;	
	$arr ['score'] = $score;
	$arr ['pars']  =$pars;
	$arr ['total_score']  =$total_score;
	$arr ['addtime']  = time();
	$row = DB::insert('common_score', $arr);
	api_json_result(1,0,$api_error['card']['10020'],$data);
}

//删除记录 
if($ac==='del'){
	 
	 $sql="delete from " . DB::table ( 'common_score' ) . "  where id='$id'";
	 $re=DB::query($sql); 
	api_json_result(1,0,$api_error['card']['10020'],$data); 
}


//修改记录 
if($ac==='edit'){
	
	$showtime = time();
	//$sql="update " . DB::table ( 'tmsg' ) . " set num=num+1,  num0=num0+1, dateline='$showtime'  where mobile='$mobile'";
	//$re=DB::query($sql); 

	api_json_result(1,0,$api_error['card']['10020'],$data);
	
}


//显示记录 
if($ac==='show'){

	if($uid <= 0) {
		if($uid == -1) {
			 api_json_result(1,10011,$api_error['register']['10021'],$data);
		}  
	}
	else
	{ 
	
		$query = DB::query("select id,uid,fuid,par,score,pars,total_score,FROM_UNIXTIME(dateline, '%Y-%m-%d') as dateline,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table('common_score').".uid) as event_name from ".DB::table('common_score')."  where uid=$uid $strwhere order by addtime desc");
		while($row = DB::fetch($query))
		{
			$row['iframe_url']=$site_url."/nd/score.php?ndid=".$row['uid']."&size=small";
			$gscore[] = $row; 
		}
		 
	if($gscore){
	
	/*接口返回的参数*/
		$response         = 0;
		$error_state      = 0;
		$data['title']    = "scorecard";
		$data['data'] = array(
							'uid'=>$uid,
							'username'=>$username,	 
							'list_data'=>$gscore,
							 );
		//print_r($data);
		api_json_result(1,0,$api_error['card']['10020'],$data);
	}
	}
}



//显示排名 新
if($ac=='rank')
{
	$limit=100;

	$pic_width=$_G['gp_pic_width'];
	$login_uid=$_G['gp_login_uid'];

	$lun=1;
	
	if($sid>0)
	{
		$sql=" and event_uid='".$sid."' ";
	}
	else
	{
		$sql =" and event_is_tj='Y' ";
	}

	$source=$_G['gp_source'];
	if($source)
	{
		$source_sql =" and source='waika' ";
	}
	else
	{
		$source_sql =" and source='ndong' ";
	}

	$event_info=DB::fetch_first("select event_id,event_name,event_uid,event_fenzhan_id,event_logo,event_timepic,event_starttime,event_endtime,event_content,event_state,event_is_tj,event_is_baoming,event_addtime from tbl_event where 1=1 ".$sql." order by event_addtime desc limit 1 ");
	if($login_uid)
	{
		$bm=DB::fetch_first("select bm_id,code_pic from ".DB::table("home_dazbm")." where uid='".$login_uid."' and pay_status=1 ");
		if($bm['bm_id'])
		{
			$event_info['event_baoming_state']=$bm['bm_id'];
			if($bm['code_pic'])
			{
				$event_info['event_baoming_pic']=$site_url."".$bm['code_pic'];
			}
			else
			{
				//如果没有就生成二维码
				/*
				include "./tool/phpqrcode/qrlib.php";
				$save_path="./upload/erweima/";
				$full_save_path=$save_path.date("Ymd",time())."/";
				if(!file_exists($save_path))
				{
					mkdir($save_path);
				}
				if(!file_exists($full_save_path))
				{
					mkdir($full_save_path);
				}

				$data=$bm['bm_id'];
				$filename=$full_save_path.$bm['bm_id'].".png";
				if(file_exists($filename))
				{
					unlink($filename);
				}

				$errorCorrectionLevel = "L";
				$matrixPointSize=9;
				$margin=1;
				QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, $margin); 
				if(file_exists($filename))
				{
					$event_info['event_baoming_pic']=$site_url."".$filename;	
					$res=DB::query("update  ".DB::table("home_dazbm")." set code_pi