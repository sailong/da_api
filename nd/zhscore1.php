<?php

/**
 * [Discuz!] (C)2001-2099 Comsenz Inc.
 * This is NOT a freeware, use is subject to license terms
 *
 * $Id: home.php 22839 2011-05-25 08:05:18Z monkey $
 */ 
define ( 'APPTYPEID', 1 );
define ( 'CURSCRIPT', 'home' );

if (! empty ( $_GET ['mod'] ) && ($_GET ['mod'] == 'misc' || $_GET ['mod'] == 'invite')) {
	define ( 'ALLOWGUEST', 1 );
}

require_once '../source/class/class_core.php';
require_once '../source/function/function_home.php';

$discuz = & discuz_core::instance ();
 
//分站ID
$fenz_id =  ! empty ( $_GET ['fenz_id'] ) ? $_GET ['fenz_id'] : 29;
//赛事ID
$sid = 3803973; 
//球场id
$qc_id = 3803491;  
if($fenz_id==30) 
{
$onlymark=379021388;
}else
{
$onlymark=1565264560;
}
		
		
 $strwh=$strwh.' and onlymark='.$onlymark; 
		

//球员所在球队
$dyarray = array('3803491' => '孙喜丰|许大军','948' => '陈树新|李源梧','1178' => '陈焕成|Herry Chi','1195' => '陈明刚|左坤','1035' => '吴立国|冯四栋','1013' => '梁启生|梁杰坤','1187' => '孙红星|许韫','1212' => '雷盛生|胡险峰','1210' => '陈冠中|张磊','1150' => '杨坚|张义','3804025' => '柳在烈|杨津石','3804026' => '赵泰丰|黄奎碗','3804027' => '金仁兼|申永植','3804028' => '李锺变|张振焕','3804029' => '宋仁石|崔宝贤','3804031' => '余永奎|李奎焕','3804032' => '朴敬挤|崔文石','3804033' => '闵敬曹|咸明英','3804034' => '姜峰石|李准基','3804035' => '魏强福|张治元');
//国旗
$qzarray = array('3803491' => 'big-guoqi.png','948' => 'big-guoqi.png','1178' => 'big-guoqi.png','1195' => 'big-guoqi.png','1035' => 'big-guoqi.png','1013' => 'big-guoqi.png','1187' => 'big-guoqi.png','1212' => 'big-guoqi.png','1210' => 'big-guoqi.png','1150' => 'big-guoqi.png','3804025' => 'big-hanguo.png','3804026' => 'big-hanguo.png','3804027' => 'big-hanguo.png','3804028' => 'big-hanguo.png','3804029' => 'big-hanguo.png','3804031' => 'big-hanguo.png','3804032' => 'big-hanguo.png','3804033' => 'big-hanguo.png','3804034' => 'big-hanguo.png','3804035' => 'big-hanguo.png');
//颜色
$ysarray = array('3803491' => '#f00','948' => '#f00','1178' => '#f00','1195' => '#f00','1035' => '#f00','1013' => '#f00','1187' => '#f00','1212' => '#f00','1210' => '#f00','1150' => '#f00','3804025' => '#3a9de2','3804026' => '#3a9de2','3804027' => '#3a9de2','3804028' => '#3a9de2','3804029' => '#3a9de2','3804031' => '#3a9de2','3804032' => '#3a9de2','3804033' => '#3a9de2','3804034' => '#3a9de2','3804035' => '#3a9de2');
//积分
$jfarray = array('3803491' => '1','948' => '1','1178' => '1','1195' => '0','1035' => '0','1013' => '1','1187' => '1','1212' => '0','1210' => '0','1150' => '0','3804025' => '0','3804026' => '1','3804027' => '0','3804028' => '1','3804029' => '0','3804031' => '1','3804032' => '0','3804033' => '0','3804034' => '1','3804035' => '1');


 
$size = $_GET ['size'];
$order = $_GET ['order'];
$width = $_GET ['width'];
$width1 = $_GET ['width']-54;
$width2 = $_GET ['width']-120;


if(!$width)
{
	$width=720;
}
 
 //横版缩放
$dguoqi=86/1280;
$dguoqi1=$dguoqi*$width;
 
$zhongjian=185/1280;
$zhongjian1=$zhongjian*$width;
 
$xguoqi=42/1280;
$xguoqi1=$xguoqi*$width;
 
 
$zt2=18/1280;
$hb1=$zt2*$width;
$zt2=30/1280;
$hb2=$zt2*$width;
$zt3=28/1280;
$hb2=$zt3*$width;
$zt4=20/1280;
$hb3=$zt4*$width;

//排名缩放

$dbut=217/720;
$dbut1=$dbut*$width; 
 
$xguoqi=42/720;
$xguoqi1=$xguoqi*$width;

$szhongjian=185/720;
$szhongjian1=$szhongjian*$width;
$wfenz=167/720;
$wfenz1=$wfenz*$width;
$wfenh=73/720;
$wfenz2=$wfenh*$width;
 
$xing=28/720;
$xing1=$xing*$width; 
 
$dy1=16/720;
$sb1=$dy1*$width; 
$dy2=30/720;
$sb2=$dy2*$width; 
$dy3=28/720;
$sb3=$dy3*$width; 
$dy4=18/720;
$sb4=$dy4*$width; 

$st1=80/720;
$sg1=$st1*$width; 

$st2=60/720;
$sg2=$st2*$width; 
$st3=90/720;
$sg3=$st3*$width; 

  
 
$cachelist = array ('magic', 'userapp', 'usergroups', 'diytemplatenamehome' );
$discuz->cachelist = $cachelist;
$discuz->init ();


//获取赛事ID
$score_info=DB::fetch_first("select uid,sid,tlcave,start_time,realname from ".DB::table("golf_nd_baofen")." where nd_id='".$nd_id."'  ");
$event_uid=$score_info['sid'];
$zong_fen=$score_info['tlcave'];
$realname=$score_info['realname'];
$event_name=DB::result_first("select realname from ".DB::table("common_member_profile")." where uid='".$event_uid."' ");
$addtime=date("Y-m-d G:i:s",$score_info['start_time']);


//print_r($score_info);
//$nd_id=DB::result_first("select nd_id from ".DB::table("golf_nd_baofen")." where uid='".$score_info['uid']."' and onlymark='".$score_info['onlymark']."'  ");
//$event_uid=DB::result_first("select sid from ".DB::table("golf_nd_baofen")." where nd_id='".$nd_id."' ");


//用户组，模板调用
$uid = ! empty ( $_GET ['uid'] ) ? $_GET ['uid'] : 0;
 
	$getstat = array ();
	$getstat = getusrarry ( $uid );
	//$gropid=$getstat['groupid']; 
 $orderby=" team_num asc,isout desc";
if($order=="1"){$strwh.=" and isend=1 ";$orderby="  isend desc,avcave,lin,cave_18,cave_17,cave_16";}
 
		
$sql="select *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin  from " . DB::table ( 'golf_nd_baofen' ) . " where 1=1 $strwh ORDER BY   $orderby";
	  
$bf = DB::query ($sql ); 
		while ( $row = DB::fetch ( $bf ) ) {
			$nblist [] = $row;
		} 
		$pm = 1;
		if ($nblist) {
			foreach ( $nblist as $key => $value ) {
				$nblist [$key] ['pm'] = $pm ++;
				$nblist [$key] ['chj'] = $nblist [$key] ['tlcave'] - $PTL; 
			}
		}

 

$qc_par_result = DB::fetch_first ( " select `par` from " . DB::table ( "common_field" ) . " where uid='$qc_id'" );

$par = explode ( ',', $qc_par_result ['par'] );  
	$POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
	$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
	$PTL = $POUT + $PIN;



define ( 'CURMODULE', $mod );

runhooks ();
//距标准杆
function Gpar($score, $par) {
	$option = $score - $par;
	 
	return $option;
}

//距标准杆
function Getpar($score, $par)
{
	$option = $score - $par;
	if ($option == 0) {
		$dataInfo = "E";
	}
	if ($option > 0) {
		$dataInfo = "+" . $option;
	}
	if ($option < 0) {
		$dataInfo = $option;
	}
	return $dataInfo;
}

//当前洞的成绩
function getscore($uid,$sais_id,$qc_id,$dong)
{
	 $sql=" select  `cave_$dong`  from " . DB::table ( "golf_nd_baofen" ) . " where uid=$uid and sais_id=$sais_id  and fieldid=$qc_id " ;
	$score = DB::result_first($sql);
 
	 
	if ($score) {
		$dataInfo = $score;	 
	
	} else {
		$dataInfo = 0;
	}
	
	return $dataInfo;
}


//显示DQ RTD
function Getstat($tlcave) {
	if ($tlcave < 999)
		{$dataInfo = $tlcave;}
	switch ($tlcave) {
		//弃权
		case 999 :		 
			$dataInfo = "Quit";			
			//$dataInfo = "";
			break; 
		//DQ
		case 1000 :	
			$dataInfo = "DQ";			
			//$dataInfo = "";
			break;
			
		 
		//取消
		case 1001 :	 
			$dataInfo = "RTD";
			//$dataInfo = "";
			break;
	   
		case 0 : 
			$dataInfo = "&nbsp;";
			break;
	}
	
	return $dataInfo;
}

//显示成绩
function Getchj($uid,$sais_id,$qc_id)
{
	$qc_par_result = DB::fetch_first ( " select `par` from " . DB::table ( "common_field" ) . " where uid=$qc_id" );
	
	$par = explode ( ',', $qc_par_result ['par'] );
	$POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
	$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
	$PTL = $POUT + $PIN;
	
	$sql = "select  *  from " . DB::table ( 'golf_nd_baofen' ) . " where uid=$uid and sais_id=$sais_id and fieldid=$qc_id ";
	$query = DB::query ( $sql );
	$row = DB::fetch ( $query );
	if ($row ['score1'] > 0 && $row ['score2'] > 0 && $row ['score3'] > 0 && $row ['score4'] > 0 && $row ['score5'] > 0 && $row ['score6'] > 0 && $row ['score7'] > 0 && $row ['score8'] > 0 && $row ['score9'] > 0 && $row ['score10'] > 0 && $row ['score11'] > 0 && $row ['score12'] > 0 && $row ['score13'] > 0 && $row ['score14'] > 0 && $row ['score15'] > 0 && $row ['score16'] > 0 && $row ['score17'] > 0 && $row ['score18'] > 0) {
		$dataInfo = $row ['tlscore'] - $PTL;
		if ($dataInfo > 0) {
			$dataInfo = '+' . $dataInfo;
		}
		if ($dataInfo == 0) {
			$dataInfo = 'E';
		}
	
	} else {
		$dataInfo = 0;
	}
	
	return $dataInfo;
}

//显示距标准杆
function Getchd($avscore)
{
	 // $avscore=$avscore+1;
		if ($avscore > 0) {
			$avscore = '+' . $avscore;
		}
		if ($avscore == 0) {
			$avscore = 'E';
		} 
	
	return $avscore;
}
 
function Getuname($str,$type)
{
	if($type==1){
	 $dataInfo=str_replace("|"," ","$str");
	 }
	 else
	 {
	 $dataInfo=str_replace("|","<br>","$str");
	 }
	return $dataInfo;
}

function Getcss($score, $par)
{
	$option = $score - $par;
	if ($score == - 1) {
		$dataInfo = " style=\"padding:2px 5px;text-align:center;color:#FFffff;background:#089218\"";
	} else {
		switch ($option) {
			//成绩数据显示
			

			//低于标准杆3杆以上或者一杆进洞的：字变白色 底色变为深黄色，
			case - 3 :
				$dataInfo = " style=\"padding:2px 5px;text-align:center;color:#FFffff;background:#fd6804\"";
				break;
			//低于标准杆两杆：字变白色 底变为ffcc01
			case - 2 :
				$dataInfo = " style=\"padding:2px 5px;text-align:center;color:#FFffff;background:#ffcc01\"";
				break;
			
			//低于标准杆1杆：字变白色
			case - 1 :
				$dataInfo = " style=\"padding:2px 5px;text-align:center;color:#FFffff;background:#cd3301\"";
				break;
			
			//平标准杆：字变白色 没背景
			case 0 :
				$dataInfo = " style=\"padding:2px 5px;text-align:center;color:#000000;\"";
				break;
			
			//高于标准杆1杆：字变白色 
			case 1 :
				$dataInfo = " style=\"padding:2px 5px;text-align:center;color:#FFffff;background:#5dcff1\"";
				break;
			
			//高于标准杆2杆：字变成白色 底为正常蓝色
			case 2 :
				$dataInfo = " style=\"padding:2px 5px;text-align:center;color:#FFffff;background:#0166ff\"";
				break;
			
		//高于标准杆3杆以上的：字是白色 底为深蓝色
		//case 3 :
		//$dataInfo = " style=\"text-align:center;color:#FFffff;background:#000033\"";
		//	break;
		

		}
		if ($option >= 3)
			$dataInfo = " style=\"padding:2px 5px;text-align:center;color:#FFffff;background:#000033\"";
		if ($option < - 3)
			$dataInfo = " style=\"padding:2px 5px;text-align:center;color:#FFffff;background:#FF00ff\"";
	}
	return $dataInfo;
}

function Getdefen($score1,$score2,$isend)
{
	if($isend==1&&$score1<200&&$score2<200)
	 {
		 $option=$score1-$score2;
		if($option<0)
		 {
		 $dataInfo=1;
		 }
		 if($option>0)
		 {
		 $dataInfo=0;
		 }
		 if($option==0)
		 {
		 $dataInfo=0.5;
		 }
	 }else{
		  $dataInfo=" ";
	 }
	 
	return $dataInfo;
}
 
function Getwin($score1,$score2,$isend,$type)
{
	
	if($isend==1&&$score1<200&&$score2<200)
	 {
		 $option=$score1-$score2;		 
	
		 $data['wz']="win";
		if($option<0)
		 {
		$data['bg']="but-red-bg.png";
		 }
		 if($option>0)
		 {
		 $data['bg']="but-blue-bg.png";
		 }
		 if($option==0)
		 {
		 $data['wz']="AS";
		 $data['bg']="but-gray-bg.png";
		 }
	 }else{
		 $data['wz']="AS";
		 $data['bg']="but-gray-bg.png";
	 }
	 if($type==1){
		$dataInfo=$data['wz'];
		 }
	 if($type==2){
		$dataInfo=$data['bg'];
		 }
	return $dataInfo;
}
 

function Getteamname($team_id)
{
	 $teamname = DB::result_first( " select `team_name` from " . DB::table ( "golf_team" ) . " where team_id=$team_id" );
	return $teamname;
}


 
	if($size=="small")
	{
		include_once template("diy:nd/zhscore_small"); 
	}
	else
	{
		if($order=="1")
		{
			include_once template("diy:nd/zhscore_pai"); 
		}else
		{
			include_once template("diy:nd/zhscore"); 
		}
	}
	
 

?>