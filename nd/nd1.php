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
//Insert into pre_nd_score(sais_id,fieldid,uid,realname,tee,zid) select sais_id,qc_id,uid,realname,kq_tee,item_ident from pre_nd_quny_fz where sais_id=1000333,qc_id = 1290,fenz_type='bj513'


//球场ID北京
$qc_id = 1203;
//赛事ID
$sais_id = 1900471;
//比赛时间
$fenz_type='1203';
$dateline = strtotime ( "2012-6-29 8:00" );
//$dateline = strtotime ( "2012-5-27 8:00" );
$bssj=time (); 
//参赛人数
$tlmember = 128;

$ac = $_GET ['ac'];
$do = $_GET ['do'];
$fzt = $_GET ['fzt'];

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


/*报分提交*/
if ($ac == 'tjbaofen') {
	$arra = getgpc ( 'userdk' );
	if ($arra) {
	   $avs=0;
		foreach ( $arra as $key => $value ) {
			$eventid = $_COOKIE ["eventid"];
			$fieldid = $_COOKIE ["fieldid"];
			if ($eventid && $fieldid) {
				//echo "$key=>$value";
				//echo "<br>";
				//print_r(array_keys($value));
				
				$qc_par_result = DB::fetch_first ( " select `par` from " . DB::table ( "common_field" ) . " where uid='$qc_id'" );
	
				$par = explode ( ',', $qc_par_result ['par'] );
				
                $ttt=0; 
				foreach ( $value as $k => $var ) {
					// echo "$k=>$var<br>";
					$sql_sets ['score' . $k] = "`score$k`='$var'";
					$data ['score' . $k] = $var;
			 
					//跳过
					if ($var == 0) { 
					$ttt=999; 
					}
					//DQ
					if ($var == - 1) {
					$ttt=1000; 
					}
					//取消
					if ($var == - 2) {
					$ttt=1001; 
					}
				}
				//插入更新操作			

				$baofen = DB::fetch_first ( "select  *  from " . DB::table ( 'nd_score' ) . "  where uid='$key' and sais_id='$eventid' and fieldid='$fieldid' " );
				$data ['uid'] = $key;
				$data ['realname'] = DB::result_first ( "select  realname from " . DB::table ( 'nd_quny_fz' ) . "  where  uid='$key'" );
				$data ['sais_id'] = $eventid;
				$data ['fieldid'] = $fieldid;
				if ($baofen ['id']) {
					$sql = "update " . DB::table ( 'nd_score' ) . " set " . (implode ( " , ", $sql_sets )) . " where  uid='$key' and sais_id='$eventid' and fieldid='$fieldid' ";
				} else {
					$sql = "insert into " . DB::table ( 'nd_score' ) . " (`" . implode ( "`,`", array_keys ( $data ) ) . "`) values ('" . implode ( "','", $data ) . "')";
				}
				
				$rs = DB::query ( $sql );				
				 
			$baofen = DB::fetch_first ( "select  *  from " . DB::table ( 'nd_score' ) . "  where uid='$key' and sais_id='$eventid' and fieldid='$fieldid' " );
			$avscore=0;
			for($i = 1; $i <= 18; $i ++) { 
			
			     if($baofen ['score' . $i]){				 
				 $avscore+=Gpar($baofen ['score' . $i],$par [$i-1]);
				 } 
					
			}
			$sql="update " . DB::table ( 'nd_score' ) . " set avscore=$avscore where  uid='$key' and sais_id='$eventid' and fieldid='$fieldid'";
			 DB::query ($sql);
			if($ttt){ 
				 $sql_sets ['tlscore'] = "`tlscore`='$ttt'";	
				 $sql_sets ['avscore'] = "`avscore`='1000'";	
				  DB::query ("update " . DB::table ( 'nd_score' ) . " set " . (implode ( " , ", $sql_sets )) . " where  uid='$key' and sais_id='$eventid' and fieldid='$fieldid' ");
				}else{ 
				  DB::query ("update " . DB::table ( 'nd_score' ) . " set tlscore=score1+score2+score3+score4+score5+score6+score7+score8+score9+score10+score11+score12+score13+score14+score15+score16+score17+score18 where  uid='$key' and sais_id='$eventid' and fieldid='$fieldid' ");
			 	} 			
			} 	
			
		}
		//echo '提交成功'; 	

		$fzt1 = $_POST ['fzt1'];
		$fzt1 = $fzt1 + 1;
		header ( "Location: nd.php?ac=ndupdate&fzt=$fzt1" );
	}
}



/*报分员登陆*/
$username = $_GET ['username'];
$password = $_GET ['password'];
if ($ac == 'login' || $ac == '') {
	$lib = 'ndlogin';
}
if ($do == 'loggin') {
	$baofen = DB::fetch_first ( "select * from " . DB::table ( 'nd_baofen_user' ) . "  where username='$username' and password='$password'" );
	if ($baofen ['id']) {
		$id = $baofen ['id'];
		$username = $baofen ['username'];
		$hole = $baofen ['hole'];
		$fenz_type = $baofen ['fenz_type'];
		$eventid = $baofen ['eventid'];
		$fieldid = $baofen ['fieldid'];
		
		setcookie ( "bfid", $id, time () + 3600 * 24 );
		setcookie ( "bfuser", $username, time () + 3600 * 24 );
		setcookie ( "bfhole", $hole, time () + 3600 * 24 );
		setcookie ( "fenz_type", $fenz_type, time () + 3600 * 24 );
		setcookie ( "eventid", $eventid, time () + 3600 * 24 );
		setcookie ( "fieldid", $fieldid, time () + 3600 * 24 );
		header ( 'Location: /nd/nd.php?ac=ndupdate&fzt=1&qc_id='.$qc_id );
	}
}
if ($ac == 'ndbaofen') {
	$lib = 'ndbaofen';
	$bfhole = $_COOKIE ["bfhole"];
	$fenz_type = $_COOKIE ["fenz_type"];
	$eventid = $_COOKIE ["eventid"];
	$fieldid = $_COOKIE ["fieldid"];
	$arr = explode ( ',', $bfhole );
	
 
		
		$bf = DB::query ( "select  uid,realname,item_ident from " . DB::table ( 'nd_quny_fz' ) . "  where sais_id='$eventid' and fenz_type='$fenz_type' and qc_id='$fieldid' and item_ident>0 GROUP BY item_ident" );
		while ( $row = DB::fetch ( $bf ) ) {
			$fz [] = $row;
		}
		
		if ($fzt) {
			$sql = 'select uid,realname,qc_id from ' . DB::table ( 'nd_quny_fz' ) . " where sais_id='$eventid' and fenz_type='$fenz_type' and qc_id='$fieldid' and item_ident='$fzt'";
	 
			$query = DB::query ( $sql );
			while ( $list = mysql_fetch_assoc ( $query ) ) {
				$bmlist [] = $list;
			}
 
			/*获取球场的标准杆*/
			if ($bmlist) {
				$qc_par_result = DB::fetch_first ( " select `par` from " . DB::table ( "common_field" ) . " where uid='" . $bmlist [0] ['qc_id'] . "'" );
			}
			$par = explode ( ',', $qc_par_result ['par'] );
			$fraction = array (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15 );
		
		}
	
 
}
/*end报分员登陆*/
/*修改报分*/
if ($ac == 'ndupdate') {
	$lib = 'ndupdate'; 
 	$bfhole = $_COOKIE ["bfhole"];
	$fenz_type = $_COOKIE ["fenz_type"];
	$eventid = $_COOKIE ["eventid"];
	$fieldid = $_COOKIE ["fieldid"];
	$arr = explode ( ',', $bfhole );
		 
		$bf = DB::query ( "select  uid,realname,item_ident from " . DB::table ( 'nd_quny_fz' ) . "  where  sais_id='$eventid' and fenz_type='$fenz_type' and qc_id='$fieldid' and  item_ident>0 GROUP BY item_ident" );
		 	while ( $row = DB::fetch ( $bf ) ) {
			$fz [] = $row;
		}
		
		
		if ($fzt) {
			$sql = 'select uid,realname,qc_id from ' . DB::table ( 'nd_quny_fz' ) . " where sais_id='$eventid' and fenz_type='$fenz_type' and qc_id='$fieldid' and item_ident='$fzt'";		 
		 
			$query = DB::query ( $sql );
			while ( $list = mysql_fetch_assoc ( $query ) ) {
				$bmlist [] = $list;
			}
 
			/*获取球场的标准杆*/ 
			$fraction = array (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15 );
		
		}
	
 
}

if ($ac == 'nlist' || $ac == 'ndtv') {
	
	 $days=($bssj-$dateline)/(24*3600);
		if($_GET['qc_id']){
		$qc_id=$_GET['qc_id'];
		}else{
		$qc_id=1083;
		$strwh=' and tlscore<1000';
		} 
	$qc_par_result = DB::fetch_first ( " select `par` from " . DB::table ( "common_field" ) . " where uid='$qc_id'" );
	
	$par = explode ( ',', $qc_par_result ['par'] );
	$POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
	$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
	$PTL = $POUT + $PIN;
	
	if ($ac == 'nlist') {
		$lib = 'ndbaolist';
		$bfuser = $_COOKIE ["bfuser"];
		//$bf = DB::query ( "select *,(score1+score2+score3+score4+score5+score6+score7+score8+score9) as lout,(score10+score11+score12+score13+score14+score15+score16+score17 +score18) as lin  from " . DB::table ( 'nd_score' ) . " where sais_id=$sais_id and fieldid=$qc_id $strwh ORDER BY CASE WHEN tlscore<> 0 THEN tlscore ELSE 999 END" );
		if($days>=1)
		{
		$sql="select *,(score1+score2+score3+score4+score5+score6+score7+score8+score9) as lout,(score10+score11+score12+score13+score14+score15+score16+score17 +score18) as lin  from " . DB::table ( 'nd_score' ) . " where sais_id=$sais_id and fieldid=$qc_id and tlscore<999 and  score1>0 and score2>0  and score3>0  and score4>0  and score5>0  and score6>0  and score7>0  and score8>0  and score9>0  and score10>0  and score11>0  and score12>0  and score13>0  and score14>0  and score15>0  and score16>0  and score17>0  and score18>0  $strwh ORDER BY avscore,lin";
		}else{
		$sql="select *,(score1+score2+score3+score4+score5+score6+score7+score8+score9) as lout,(score10+score11+score12+score13+score14+score15+score16+score17 +score18) as lin  from " . DB::table ( 'nd_score' ) . " where sais_id=$sais_id and fieldid=$qc_id   $strwh ORDER BY avscore,score18,score17,score16";
		}
		$bf = DB::query ($sql );
 
		while ( $row = DB::fetch ( $bf ) ) {
			$nblist [] = $row;
		}
		$pm = 1;
		if ($nblist) {
			foreach ( $nblist as $key => $value ) {
				$nblist [$key] ['pm'] = $pm ++;
				$nblist [$key] ['chj'] = $nblist [$key] ['tlscore'] - $PTL;
				//$nblist [$key] ['item_ident'] = DB::result_first ( "select item_ident from " . DB::table ( 'nd_quny_fz' ) . " where uid='" . $nblist [$key] ['uid'] . "' and sais_id=$sais_id and fenz_type='tj57' and qc_id='$qc_id'  limit 1" );
				//$nblist [$key] ['realname'] = DB::result_first ( "select realname from " . DB::table ( 'home_dazbm' ) . " where uid='" . $nblist [$key] ['uid'] . "'  limit 1" );
			}
		}
	} else {
		$lib = 'ndbaotv';
		
		$perpage = 20;
		$page = empty ( $_GET ['page'] ) ? 0 : intval ( $_GET ['page'] );
		if ($page < 1) {
			$page = 1;
		}
		
		$start = ($page - 1) * $perpage;
		ckstart ( $start, $perpage );
		
		$count = DB::result_first ( "select count(*) from " . DB::table ( 'nd_score' ) . "    where sais_id=$sais_id and fieldid=$qc_id   order by  tlscore asc" );
		
		$nbtvlist = array ();
		$pm = $_G [gp_pm] ? getgpc ( pm ) : 1;
		$pm = $pm > $count ? 1 : $pm;
		
		if ($count) {
			//$sql = "select  *,(score1+score2+score3+score4+score5+score6+score7+score8+score9) as lout,(score10+score11+score12+score13+score14+score15+score16+score17 +score18) as lin from " . DB::table ( 'nd_score' ) . " where sais_id=$sais_id and fieldid=$qc_id   and tlscore>0  ORDER BY CASE WHEN tlscore<> 0 THEN tlscore ELSE 999 END limit $start,$perpage";
			$sql = "select  *,(score1+score2+score3+score4+score5+score6+score7+score8+score9) as lout,(score10+score11+score12+score13+score14+score15+score16+score17 +score18) as lin from " . DB::table ( 'nd_score' ) . " where sais_id=$sais_id and fieldid=$qc_id    ORDER BY avscore limit $start,$perpage";
		 
			$query = DB::query ( $sql );
			while ( $row = DB::fetch ( $query ) ) {
				$row ['pm'] = $pm ++;
				$nbtvlist [] = $row;
			
			}
		
		}
		
		if ($nbtvlist) {
			foreach ( $nbtvlist as $key => $value ) {
				
				$nbtvlist [$key] ['chj'] = $nbtvlist [$key] ['tlscore'] - $PTL;
				//$nbtvlist [$key] ['item_ident'] = DB::result_first ( "select item_ident from " . DB::table ( 'nd_quny_fz' ) . " where uid='" . $nbtvlist [$key] ['uid'] . "' and sais_id=$sais_id and fenz_type='tj57' and qc_id='$qc_id'  limit 1" );
				//$nbtvlist [$key] ['realname'] = DB::result_first ( "select realname from " . DB::table ( 'home_dazbm' ) . " where uid='" . $nbtvlist [$key] ['uid'] . "'  limit 1" );
			}
		}
		$realpages_x = @ceil ( $count / $perpage );
		if ($realpages_x <= getgpc ( 'page' )) {
			$page = 1;
		} else {
			$page ++;
		}
		
		$multi = multi ( $count, $perpage, $page, "/nd/nd.php?ac=ndtv&qc_id=$qc_id" );
	
	}

}

//导入会员成绩卡
if ($ac == 'addd') {
	$qc_par_result = DB::fetch_first ( " select `par` from " . DB::table ( "common_field" ) . " where uid='$qc_id'" );
	
	$par = explode ( ',', $qc_par_result ['par'] );
	$POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
	$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
	$PTL = $POUT + $PIN;
	$arr ['par'] = $par [0] . '|' . $par [1] . '|' . $par [2] . '|' . $par [3] . '|' . $par [4] . '|' . $par [5] . '|' . $par [6] . '|' . $par [7] . '|' . $par [8] . '|' . $POUT . '|' . $par [9] . '|' . $par [10] . '|' . $par [11] . '|' . $par [12] . '|' . $par [13] . '|' . $par [14] . '|' . $par [15] . '|' . $par [16] . '|' . $par [17] . '|' . $PIN . '|' . $PTL;
	
	$bf = DB::query ( "select *,(score1+score2+score3+score4+score5+score6+score7+score8+score9) as lout,(score10+score11+score12+score13+score14+score15+score16+score17 +score18) as lin  from " . DB::table ( 'nd_score' ) . " where score1>0 and score2>0  and score3>0  and score4>0  and score5>0  and score6>0  and score7>0  and score8>0  and score9>0  and score10>0  and score11>0  and score12>0  and score13>0  and score14>0  and score15>0  and score16>0  and score17>0  and score18>0 and sais_id=$sais_id and fieldid=$qc_id and tlscore<1000" );
	
	
	while ( $row = DB::fetch ( $bf ) ) {
		
		//$nblist [] = $row;
		$total_score = $row ['lin'] + $row ['lout'];
		$arr ['total_score'] = $total_score;
		for($i = 1; $i <= 21; $i ++) {
			if ($i == '10') {
				$data [$i] = $row ['lout'];
			} elseif ($i == '20') {
				$data [$i] = $row ['lin'];
			} elseif ($i == '21') {
				$data [$i] = $total_score;
			} elseif ($i > 9) {
				$data [$i] = $row ['score' . ($i - 1)];
			} else {
				$data [$i] = $row ['score' . $i];
			}
		
		}
		$par = explode ( '|', $arr ['par'] );
		
		//初始化
		$total_eagle = 0;
		//birdie  
		$total_birdie = 0;
		//E  
		$total_evenpar = 0;
		//bogi  
		$total_bogi = 0;
		//doubles 
		$total_doubles = 0;
		
		for($i = 1; $i <= 21; $i ++) {
			$data1 [$i] = Getpar ( $data [$i] - $par [$i - 1] );
			if ($i != '10' && $i != '20' && $i != '21') {
				//eagle
				Getpar ( $data [$i] - $par [$i - 1] ) == '-2' ? $total_eagle ++ : '';
				//birdie 
				Getpar ( $data [$i] - $par [$i - 1] ) == '-1' ? $total_birdie ++ : '';
				//E 
				Getpar ( $data [$i] - $par [$i - 1] ) == 'E' ? $total_evenpar ++ : '';
				//bogi 
				Getpar ( $data [$i] - $par [$i - 1] ) == '+1' ? $total_bogi ++ : '';
				//doubles
				Getpar ( $data [$i] - $par [$i - 1] ) == '+2' ? $total_doubles ++ : '';
			}
		}
		
		$str = implode ( '|', $data );
		$str1 = implode ( '|', $data1 );
		$arr ['score'] = $str;
		$arr ['tee'] = $row ['tee'];
		$arr ['pars'] = $str1;
		$arr ['total_pushs'] = $total_score;
		$arr ['total_avepushs'] = floor ( $total_score / 18 );
		$arr ['total_eagle'] = $total_eagle;
		$arr ['total_birdie'] = $total_birdie;
		$arr ['total_evenpar'] = $total_evenpar;
		$arr ['total_bogi'] = $total_bogi;
		$arr ['total_doubles'] = $total_doubles;
		$arr ['dateline'] = $dateline;
		$arr ['sais_id'] = $sais_id;
		$arr ['fuid'] = $qc_id;
		$arr ['uid'] = $row ['uid'];
		$arr ['ismine'] = '1';
		$arr ['status'] = '1';
		$arr ['member'] = $tlmember;
		$arr ['addtime'] = time ();
		//$row = DB::insert('common_score', $arr);	  
	print_r($arr);
	}

}

//exit;
 
define ( 'CURMODULE', $mod );

runhooks ();
//距标准杆
function Gpar($score, $par) {
	$option = $score - $par;
	 
	return $option;
}

//距标准杆
function Getpar($score, $par) {
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
function getscore($uid,$sais_id,$qc_id,$dong) {
	 $sql=" select `score$dong` from " . DB::table ( "nd_score" ) . " where uid=$uid and sais_id=$sais_id  and fieldid=$qc_id " ;
	$score = DB::result_first($sql);
 
	 
	if ($score) {
		$dataInfo = $score;	 
	
	} else {
		$dataInfo = 0;
	}
	
	return $dataInfo;
}


//显示成绩
function Getchj($uid,$sais_id,$qc_id) {
	$qc_par_result = DB::fetch_first ( " select `par` from " . DB::table ( "common_field" ) . " where uid=$qc_id" );
	
	$par = explode ( ',', $qc_par_result ['par'] );
	$POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
	$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
	$PTL = $POUT + $PIN;
	
	$sql = "select  *  from " . DB::table ( 'nd_score' ) . " where uid=$uid and sais_id=$sais_id and fieldid=$qc_id ";
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
function Getchd($avscore) {
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
function Getstat($tlscore) {
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

function Getcss($score, $par) {
	$option = $score - $par;
	if ($score == - 1) {
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
require_once libfile ( 'nd/' . $lib, 'include' );
?>