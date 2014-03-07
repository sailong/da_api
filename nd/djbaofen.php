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
//SQL导入
//Insert into pre_golf_nd_baofen(sid,field_id,uid,realname,tee,zid) select sid,field_id,uid,realname,kq_tee,team_num from pre_fenzu_members where sid=1000333,field_id = 1290,fenz_id='bj513'


//球场ID北京
$field_id = 1203;
//赛事ID
$sid = 1000809;
//比赛时间
$fenz_id='3';
$dateline = strtotime ( "2012-7-31 8:00" );
//$dateline = strtotime ( "2012-5-27 8:00" );
$bssj=time (); 
//参赛人数 

$ac = $_GET ['ac'];
$do = $_GET ['do'];
$fzt = $_GET ['fzt'];
$qc_id = $_GET ['field_id'];

$cachelist = array ('magic', 'userapp', 'usergroups', 'diytemplatenamehome' );
$discuz->cachelist = $cachelist;
$discuz->init ();

//用户组，模板调用
$uid = ! empty ( $_GET ['uid'] ) ? $_GET ['uid'] : $_G ['uid'];

if ($uid > 0) {
	$getstat = array ();
	$getstat = getusrarry ( $uid );
	//$gropid=$getstat['groupid']; 


}

   
	
	 $days=($bssj-$dateline)/(24*3600);
		if($_GET['qc_id']){
		$field_id=$_GET['qc_id'];
		}else{
		$field_id=1083;
		$strwh=' and tlcave<1000';
		} 
	$qc_par_result = DB::fetch_first ( " select `par` from " . DB::table ( "common_field" ) . " where uid='$field_id'" );
	
	$par = explode ( ',', $qc_par_result ['par'] );
	$POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
	$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
	$PTL = $POUT + $PIN;
	
 
		$lib = 'djbaofen';
		$bfuser = $_COOKIE ["bfuser"];
		//$bf = DB::query ( "select *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin  from " . DB::table ( 'golf_nd_baofen' ) . " where sid=$sid and field_id=$field_id $strwh ORDER BY CASE WHEN tlcave<> 0 THEN tlcave ELSE 999 END" );
		if($days>=1)
		{
		$sql="select *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin  from " . DB::table ( 'golf_nd_baofen' ) . " where sid=$sid and field_id=$field_id and tlcave<999 and  cave_1>0 and cave_2>0  and cave_3>0  and cave_4>0  and cave_5>0  and cave_6>0  and cave_7>0  and cave_8>0  and cave_9>0  and cave_10>0  and cave_11>0  and cave_12>0  and cave_13>0  and cave_14>0  and cave_15>0  and cave_16>0  and cave_17>0  and cave_18>0    $strwh ORDER BY  g_team_id,avcave,lin";
		}else{
		$sql="select *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin  from " . DB::table ( 'golf_nd_baofen' ) . " where sid=$sid and field_id=$field_id   $strwh ORDER BY g_team_id,isend desc,avcave,lin";
		} 
		$bf = DB::query ($sql ); 
		while ( $row = DB::fetch ( $bf ) ) {
			$nblist [] = $row;
		}
		$pm = 1;
		if ($nblist) {
			foreach ( $nblist as $key => $value ) {
				$nblist [$key] ['pm'] = $pm ++;
				$nblist [$key] ['chj'] = $nblist [$key] ['tlcave'] - $PTL;
				//$nblist [$key] ['team_num'] = DB::result_first ( "select team_num from " . DB::table ( 'fenzu_members' ) . " where uid='" . $nblist [$key] ['uid'] . "' and sid=$sid and fenz_id='tj57' and field_id='$field_id'  limit 1" );
				//$nblist [$key] ['realname'] = DB::result_first ( "select realname from " . DB::table ( 'home_dazbm' ) . " where uid='" . $nblist [$key] ['uid'] . "'  limit 1" );
			}
		}
 
 

//exit;
 
define ( 'CURMODULE', $mod );

runhooks ();
//距标准杆
function Gpar($cave_, $par) {
	$option = $cave_ - $par;
	 
	return $option;
}

//距标准杆
function Getpar($cave_, $par) {
	$option = $cave_ - $par;
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
function getscore($uid,$sid,$field_id,$dong) {
	 $sql=" select `cave_$dong` from " . DB::table ( "golf_nd_baofen" ) . " where uid=$uid and sid=$sid  and field_id=$field_id " ;
	$cave= DB::result_first($sql);
 
	 
	if ($cave) {
		$dataInfo = $cave;	 
	
	} else {
		$dataInfo = 0;
	}
	
	return $dataInfo; 
}


//显示成绩
function Getchj($uid,$sid,$field_id) {
	$qc_par_result = DB::fetch_first ( " select `par` from " . DB::table ( "common_field" ) . " where uid=$field_id" );
	
	$par = explode ( ',', $qc_par_result ['par'] );
	$POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
	$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
	$PTL = $POUT + $PIN;
	
	$sql = "select  *  from " . DB::table ( 'golf_nd_baofen' ) . " where uid=$uid and sid=$sid and field_id=$field_id ";
	$query = DB::query ( $sql );
	$row = DB::fetch ( $query );
	if ($row ['cave_1'] > 0 && $row ['cave_2'] > 0 && $row ['cave_3'] > 0 && $row ['cave_4'] > 0 && $row ['cave_5'] > 0 && $row ['cave_6'] > 0 && $row ['cave_7'] > 0 && $row ['cave_8'] > 0 && $row ['cave_9'] > 0 && $row ['cave_10'] > 0 && $row ['cave_11'] > 0 && $row ['cave_12'] > 0 && $row ['cave_13'] > 0 && $row ['cave_14'] > 0 && $row ['cave_15'] > 0 && $row ['cave_16'] > 0 && $row ['cave_17'] > 0 && $row ['cave_18'] > 0) {
		$dataInfo = $row ['tlcave'] - $PTL;
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
function Getchd($avcave) {
	 // $avcave=$avcave+1;
		if ($avcave > 0) {
			$avcave = '+' . $avcave;
		}
		if ($avcave == 0) {
			$avcave = 'E';
		} 
	
	return $avcave;
}

//显示DQ RTD
function Getstat($tlcave) {
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
	
	}
	if ($tlcave < 999)
		$dataInfo = $tlcave;
	return $dataInfo;
}

function Getcss($cave_, $par) {
	$option = $cave_ - $par;
	if ($cave_ == - 1) {
		$dataInfo = " style=\"text-align:center;color:#FFffff;background:#089218\"";
	} else {
		switch ($option) {
			//成绩数据显示
			

			//低于标准杆3杆以上或者一杆进洞的：字变白色 底色变为深黄色，
			case - 3 :
				$dataInfo = " style=\"text-align:center;color:#FFffff;background:#fd6804\"";
				break;
			//低于标准杆两杆：字变白色 底变为ffcc01
			case - 2 :
				$dataInfo = " style=\"text-align:center;color:#FFffff;background:#ffcc01\"";
				break;
			
			//低于标准杆1杆：字变白色
			case - 1 :
				$dataInfo = " style=\"text-align:center;color:#FFffff;background:#cd3301\"";
				break;
			
			//平标准杆：字变白色 没背景
			case 0 :
				$dataInfo = " style=\"text-align:center;color:#000000;\"";
				break;
			
			//高于标准杆1杆：字变白色 
			case 1 :
				$dataInfo = " style=\"text-align:center;color:#FFffff;background:#5dcff1\"";
				break;
			
			//高于标准杆2杆：字变成白色 底为正常蓝色
			case 2 :
				$dataInfo = " style=\"text-align:center;color:#FFffff;background:#0166ff\"";
				break;
			
		//高于标准杆3杆以上的：字是白色 底为深蓝色
		//case 3 :
		//$dataInfo = " style=\"text-align:center;color:#FFffff;background:#000033\"";
		//	break;
		

		}
		if ($option >= 3)
			$dataInfo = " style=\"text-align:center;color:#FFffff;background:#000033\"";
		if ($option < - 3)
			$dataInfo = " style=\"text-align:center;color:#FFffff;background:#FF00ff\"";
	}
	return $dataInfo;
}  
include_once template("diy:nd/" . $lib);
?>