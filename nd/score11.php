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
 
//球场ID北京
$field_id = 1328;
//赛事ID
$sid = 1000333; 
//ndid
$nd_id = 1669; 
//$dateline = strtotime ( "2012-5-27 8:00" ); 
//参赛人数 

$ac = $_GET ['ac'];
$do = $_GET ['do']; 
$nd_id = $_GET ['ndid']; 

//载入模板
$size = $_GET ['size'];
$width = $_GET ['width'];
if(!$width)
{
	$width=800;
}

//echo $width;

$cachelist = array ('magic', 'userapp', 'usergroups', 'diytemplatenamehome' );
$discuz->cachelist = $cachelist;
$discuz->init ();


//获取赛事ID
$score_info=DB::fetch_first("select uid,event_id,field_id,fenzhan_id,total_score,start_time,realname from tbl_baofen where baofen_id='".$nd_id."'  ");
$event_uid=$score_info['event_id'];
$field_id=$score_info['field_id']; 
$fenzhan_id=$score_info['fenzhan_id'];  
$zong_fen=$score_info['total_score'];
$realname=$score_info['realname'];
$event_name=DB::result_first("select event_name from tbl_event where event_uid='".$event_uid."' ");

$addtime=date("Y-m-d G:i:s",$score_info['start_time']);

 

//用户组，模板调用
$uid = ! empty ( $_GET ['uid'] ) ? $_GET ['uid'] : 0;
 
	$getstat = array ();
	$getstat = getusrarry ( $uid );
	//$gropid=$getstat['groupid']; 
if($nd_id > 0)
{
 $bf = DB::query ( "select  *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin   from tbl_baofen  where  baofen_id=".$nd_id );}else{	
		if ($uid > 0) {
		
		 $bf = DB::query ( "select  *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin   from tbl_baofen where sid='$sid'  and field_id='$field_id' and uid=".$uid );
		}else{
		 $bf = DB::query ( "select *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin  from tbl_baofen where  onlymark= 1271827009  ORDER BY   isend desc,avcave,lin"); 
		}
 }
while ( $row = DB::fetch ( $bf ) ) {
	$fz [] = $row;
} 
 
 
 
	

     asort($new_cg);
 
	$qc_par_result = DB::fetch_first ( " select `fenzhan_a`,fenzhan_b from tbl_fenzhan where fenzhan_id='".$fenzhan_id."' " );

	 
	$par = explode(',',$qc_par_result['fenzhan_a'].','.$qc_par_result['fenzhan_b'] ); 
	 
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
function getscore($nd_id,$dong) {
	  
	 $sql=" select `cave_$dong` from tbl_baofen where nd_id='$nd_id' " ; 
	$cave= DB::result_first($sql);
 
	 
	if ($cave) {
		$dataInfo = $cave;	 
	
	} else {
		$dataInfo = 0;
	}
	
	return $dataInfo; 
}


//显示成绩
function Getchj($uid,$sid,$fenzhan_id) {
     $qc_par_result = DB::fetch_first ( " select `fenzhan_a`,fenzhan_b from tbl_fenzhan where fenzhan_id='".$fenzhan_id."' " );

	 
	$par = explode(',',$qc_par_result['fenzhan_a'].','.$qc_par_result['fenzhan_b'] );
	$POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
	$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
	$PTL = $POUT + $PIN;
	
	$sql = "select  *  from tbl_baofen where uid=$uid and event_id=$sid and fenzhan_id=$fenzhan_id ";
	$query = DB::query ( $sql );
	$row = DB::fetch ( $query );
	if ($row ['cave_1'] > 0 && $row ['cave_2'] > 0 && $row ['cave_3'] > 0 && $row ['cave_4'] > 0 && $row ['cave_5'] > 0 && $row ['cave_6'] > 0 && $row ['cave_7'] > 0 && $row ['cave_8'] > 0 && $row ['cave_9'] > 0 && $row ['cave_10'] > 0 && $row ['cave_11'] > 0 && $row ['cave_12'] > 0 && $row ['cave_13'] > 0 && $row ['cave_14'] > 0 && $row ['cave_15'] > 0 && $row ['cave_16'] > 0 && $row ['cave_17'] > 0 && $row ['cave_18'] > 0) {
		$dataInfo = $row ['total_score'] - $PTL;
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

//显示DQ RTD
function Getstat($tlscore)
{
	switch ($tlscore) {
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
	
	}
	if ($tlscore < 999)
		$dataInfo = $tlscore;
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


 

if($nd_id > 0)
{
	if($size=="small")
	{
		include_once template("diy:nd/ndscore_small"); 
	}
	else
	{
		include_once template("diy:nd/ndscore"); 
	}
	
}
else
{	
	include_once template("diy:nd/ndscore1"); 
}

?>