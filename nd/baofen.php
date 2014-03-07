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
 
 //print_r($nblist); 


$jfarray = array('1' => 150,'2' => 120,'3' => 110,'4' => 100,'5' => 95,'6' => 90,'7' => 85,'8' => 80,'9' => 75,'10' => 70,'11' => 67,'12' => 64,'13' => 61,'14' => 58,'15' => 55,'16' => 52,'17' => 49,'18' => 46,'19' => 43,'20' => 40,'21' => 38,'22' => 36,'23' => 34,'24' => 32,'25' => 30,'26' => 28,'27' => 26,'28' => 24,'29' => 22,'30' => 20,'31' => 18,'32' => 16,'33' => 14,'34' => 12,'35' => 10,'36' => 8,'37' => 6,'38' => 4,'39' => 2,'40' => 1);


//球场ID北京 
$qc_id = ! empty ( $_GET ['qc_id'] ) ? $_GET ['qc_id'] : 1201;
//赛事ID 
$sid = ! empty ( $_GET ['sid'] ) ? $_GET ['sid'] : 1000333;

//分站
$fzid = ! empty ( $_GET ['fzid'] ) ? $_GET ['fzid'] : 1; 
//比赛时间
//$tdays= date("Y-m-d");
 $tdays=strtotime ( "2013-7-29 8:00" );
$dateline = ! empty ( $_GET ['dateline'] ) ? $_GET ['dateline'] : strtotime ( $tdays );  
$dateline =  $tdays;  
$bssj=time (); 
//参赛人数
$tlmember = 104;

//print_r($_COOKIE);

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
	 
 $fenz_id = isset($_COOKIE ["fenz_id"]) ? $_COOKIE ["fenz_id"] : 34;		

 $sid =$_COOKIE ["sid"];	
 $field_id = $_COOKIE ["field_id"];	  
  
	 $tlc='avcave as avcave,';
	 $ody='avcave';
 
 $sql="select uid,realname,g_team_name, g_team_id,$tlc tlcave from pre_golf_nd_baofen where fenz_id= $fenz_id and avcave<999 and isout=0 order by  $ody ";
 
$res = DB::query ($sql ); 
				while ( $row = DB::fetch ( $res ) ) { 
	   $new_row[$row['g_team_id']][] = $row;
	 }	 
	 
    /*临时 分组成绩*/
	$one= array();
	if($mark=="1618032573")	 {
	 	$one = Array (
	  	'16' => 9,'20' => 15 ,'23' => 17 ,'19' => 24 ,'15' => 24 ,'21' => 31 ,'18' => 34 ,'22' => 39 ,'24' => 40 ,'14' => 43 ,'17' => 48 ,'25' => 56 
		);
	 }
		 if($mark=="1271827009")	 {
	 	$one = Array (
	  	'16' => 38,'20' => 46 ,'23' =>  39 ,'19' => 77 ,'15' => 65 ,'21' => 56 ,'18' => 69 ,'22' => 81 ,'24' => 89 ,'14' => 79 ,'17' => 96 ,'25' => 115 
		);
	 } 
	 foreach($new_row  as $key=> $value){
		 foreach($value as $uid=>$v){
	
			  $a += $v['avcave'];			  
			  $i++; 	
			  $user_team_order[$v['g_team_id']][] =$v ;		 
			  if($i>4) break;
		 }
		 $new_cg_team[$v['g_team_id']] = $a+$one[$v['g_team_id']];
		 $new_cg[$v['g_team_id']] = $a;			 
		 $a =$i=0;;
	 }
    asort($new_cg); 
    asort($new_cg_team);
   



/*报分提交*/
if ($ac == 'tjbaofen') {
	$arra = getgpc ( 'userdk' );
	if ($arra) {
	   $avs=0;
	    
		foreach ( $arra as $key => $value )
		{

			if($sid && $field_id) {
				//echo "$key=>$value";
				//echo "<br>";
				//print_r(array_keys($value));
				
				$qc_par_result = DB::fetch_first ( " select `par` from " . DB::table ( "common_field" ) . " where uid='$field_id'" );
	
				$par = explode ( ',', $qc_par_result ['par'] );
				
                $ttt=0; 
				foreach ( $value as $k => $var ) {
					// echo "$k=>$var<br>";
					$sql_sets ['cave_' . $k] = "`cave_$k`='$var'";
					$data ['cave_' . $k] = $var;
			 
					//跳过
					if ($var == - 3) { 
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

				$baofen = DB::fetch_first ( "select  *  from " . DB::table ( 'golf_nd_baofen' ) . "  where nd_id='$key'" );
				$data ['uid'] = $key;
			 
				$data ['sid'] = $sid;
				$data ['field_id'] = $field_id; 
				if ($baofen ['nd_id']) {
					$nd_id=$baofen ['nd_id'];
					$sql = "update " . DB::table ( 'golf_nd_baofen' ) . " set " . (implode ( " , ", $sql_sets )) . " where nd_id='$key' "; 
				}  
				
				$rs = DB::query ( $sql );				
				 
			$baofen = DB::fetch_first ( "select  *  from " . DB::table ( 'golf_nd_baofen' ) . "  where nd_id='$key'" );
			$avcave=0;
			for($i = 1; $i <= 18; $i ++) { 
			
			     if($baofen ['cave_' . $i]>0){				 
				 $avcave+=Gpar($baofen ['cave_' . $i],$par [$i-1]);
				 } 
					
			}
			$sql="update " . DB::table ( 'golf_nd_baofen' ) . " set avcave=$avcave where   nd_id='$key'";
			
			DB::query ($sql);   
			 
			if($ttt){ 
				 $sql_sets ['tlcave'] = "`tlcave`='$ttt'";	
				 $sql_sets ['isend'] = "`isend`='0'";	
				 $sql_sets ['avcave'] = "`avcave`='1000'";	
				  DB::query ("update " . DB::table ( 'golf_nd_baofen' ) . " set " . (implode ( " , ", $sql_sets )) . " where  nd_id='$key' ");
				}else{ 
				  DB::query ("update " . DB::table ( 'golf_nd_baofen' ) . " set tlcave=cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9+cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18 where  nd_id='$key' ");
			 	} 			
			} 	
			
		}
//未打球排名成绩初始化	
$sql="update " . DB::table ( 'golf_nd_baofen' ) . " set avcave=1000 where cave_1=0 and cave_2=0  and cave_3=0  and cave_4=0  and cave_5=0  and cave_6=0  and cave_7=0  and cave_8=0  and cave_9=0  and cave_1=0  and cave_10=0  and cave_11=0  and cave_12=0  and cave_13=0  and cave_14=0  and cave_15=0  and cave_16=0  and cave_17=0  and cave_18=0   and  fenz_id='$fenz_id'";
 DB::query ($sql); 
//更新比赛状态 	
  DB::query ("update " . DB::table ( 'golf_nd_baofen' ) . " set isend=1 where   cave_1>0 and cave_2>0  and cave_3>0  and cave_4>0  and cave_5>0  and cave_6>0  and cave_7>0  and cave_8>0  and cave_9>0  and cave_10>0  and cave_11>0  and cave_12>0  and cave_13>0  and cave_14>0  and cave_15>0  and cave_16>0  and cave_17>0  and cave_18>0  and tlcave<999  and   fenz_id='$fenz_id'"); 	

		$fzt1 = $_POST ['fzt1'];
		$fzt1 = $fzt1 + 1;
		if($fzt1>60)
		{$fzt1 = 1;;}
		//header ( "Location: baofen.php?ac=ndupdate&fzt=$fzt1&fenz_id='.$fenz_id);
		header ( "Location: /nd/baofen.php?ac=ndupdate&fzt=$fzt1&fenz_id=".$fenz_id);
	}
}



/*报分员登陆*/
$username = $_GET ['username'];
$password = $_GET ['password'];
$sid = $_GET ['sid'];
$field_id=$_GET['qc_id'];
if ($ac == 'login' || $ac == '') {
	$lib = 'ndlogin';
}
if ($do == 'loggin')
{

	
	//jack edit
	$baofen = DB::fetch_first ( "select * from " . DB::table ( 'nd_baofen_users' ) . "  where username='$username' and password='$password' and sid='$sid'" );
	//$baofen = DB::fetch_first ( "select * from " . DB::table ( 'nd_baofen_users' ) . "  where username='$username' and password='$password'" ); 
	if($baofen ['id'])
	{
		$id = $baofen ['id'];
		$onlymark = $baofen ['onlymark'];
		$hole = $baofen ['hole'];
		$fenz_id = $baofen ['fz_id'];
		$sid = $baofen ['sid'];
		$field_id = $baofen ['fieldid']; 
		//print_r($baofen);exit;
		setcookie ( "bfid", $id, time () + 3600 * 24 );
		setcookie ( "onlymark", $onlymark, time () + 3600 * 24 );
		setcookie ( "bfhole", $hole, time () + 3600 * 24 );
		setcookie ( "fenz_id", $fenz_id, time () + 3600 * 24 );
		setcookie ( "sid", $sid, time () + 3600 * 24 );
		setcookie ( "field_id", $field_id, time () + 3600 * 24 );
		header ( 'Location: /nd/baofen.php?ac=ndupdate&fzt=1&fenz_id='.$fenz_id);
	}
	else
	{
		echo "<script>alert('用户名或密码错误，请重试');location='/nd/baofen.php?sid=".$sid."';</script>";
	}
}
if ($ac == 'ndbaofen') {
	$lib = 'ndbaofen';
	$bfhole = $_COOKIE ["bfhole"]; 
	$sid = $_COOKIE ["sid"];
	$field_id = $_COOKIE ["field_id"];
	$arr = explode ( ',', $bfhole );
	
 
		
		$bf = DB::query ( "select  nd_id,uid,realname,team_num from " . DB::table ( 'golf_nd_baofen' ) . "  where sid='$sid' and fenz_id='$fenz_id' and field_id='$field_id' and team_num>0 GROUP BY team_num" );
		while ( $row = DB::fetch ( $bf ) ) {
			$fz [] = $row;
		}
		
		if ($fzt) {
			$sql = 'select nd_id, uid,realname,field_id from ' . DB::table ( 'golf_nd_baofen' ) . " where fenz_id='$fenz_id' team_num='$fzt'";
			$query = DB::query ( $sql );
			while ( $list = mysql_fetch_assoc ( $query ) ) {
				$bmlist [] = $list;
			}
 
			/*获取球场的标准杆*/
			if ($bmlist) {
				$qc_par_result = DB::fetch_first ( " select `par` from " . DB::table ( "common_field" ) . " where uid='" . $bmlist [0] ['field_id'] . "'" );
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
	$arr = explode ( ',', $bfhole );
		 $sql="select  nd_id,uid,realname,team_num from " . DB::table ( 'golf_nd_baofen' ) . "  where fenz_id='$fenz_id' and   team_num>0 GROUP BY team_num" ;   
		$bf = DB::query ( $sql );
		 	while ( $row = DB::fetch ( $bf ) ) {
			$fz [] = $row;
		}
		 
		if ($fzt) {
			$sql = 'select nd_id,uid,realname,field_id from ' . DB::table ( 'golf_nd_baofen' ) . " where fenz_id='$fenz_id' and team_num='$fzt'";		 
		 	 
			$query = DB::query ( $sql );
			while ( $list = mysql_fetch_assoc ( $query ) ) {
				$bmlist [] = $list;
			}
 
			/*获取球场的标准杆*/ 
			$fraction = array (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15 );
		
		}
	$addd=$_GET ['addd'];	
 	if($addd=='ok'){
		 
	$sid = $_COOKIE ["sid"];
	$qc_id = $_COOKIE ["field_id"]; 
	//$tlmember=1;
	$tlmember=DB::result_first("select  count(*) from pre_golf_nd_baofen where fenz_id='$fenz_id'  ");
	$fz_id=DB::result_first("select  fenz_id from pre_golf_nd_baofen where fenz_id='$fenz_id' limit 1 ");
	$lun=DB::result_first("select  lun from pre_fenzhan where fz_id='".$fz_id."'  limit 1 ");
	$qc_par_result = DB::fetch_first ( " select `par` from " . DB::table ( "common_field" ) . " where uid='$qc_id'" );
	
	$par = explode ( ',', $qc_par_result ['par'] );
	$POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
	$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
	$PTL = $POUT + $PIN;
	$arry ['par'] = $par [0] . '|' . $par [1] . '|' . $par [2] . '|' . $par [3] . '|' . $par [4] . '|' . $par [5] . '|' . $par [6] . '|' . $par [7] . '|' . $par [8] . '|' . $POUT . '|' . $par [9] . '|' . $par [10] . '|' . $par [11] . '|' . $par [12] . '|' . $par [13] . '|' . $par [14] . '|' . $par [15] . '|' . $par [16] . '|' . $par [17] . '|' . $PIN . '|' . $PTL;
		$i=0;
	
	$sql="select *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin  from " . DB::table ( 'golf_nd_baofen' ) . " where cave_1>0 and cave_2>0  and cave_3>0  and cave_4>0  and cave_5>0  and cave_6>0  and cave_7>0  and cave_8>0  and cave_9>0  and cave_10>0  and cave_11>0  and cave_12>0  and cave_13>0  and cave_14>0  and cave_15>0  and cave_16>0  and cave_17>0  and cave_18>0 and fenz_id='$fenz_id' and tlcave<1000";

 
	$bf = DB::query ( $sql );
	while ( $row = DB::fetch ( $bf ) )
	{
				$i=$i+1;
				//$nblist [] = $row;
				$total_score = $row ['lin'] + $row ['lout'];
				$arry ['total_score'] = $total_score;
				for($i = 1; $i <= 21; $i ++) {
					if ($i == '10') {
						$data [$i] = $row ['lout'];
					} elseif ($i == '20') {
						$data [$i] = $row ['lin'];
					} elseif ($i == '21') {
						$data [$i] = $total_score;
					} elseif ($i > 9) {
						$data [$i] = $row ['cave_' . ($i - 1)];
					} else {
						$data [$i] = $row ['cave_' . $i];
					}
				
				}
				$par = explode ( '|', $arry ['par'] );
				
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
				$arry ['score'] = $str;
				$arry ['tee'] = $row ['tee'];
				$arry ['pars'] = $str1;
				$arry ['total_pushs'] = $total_score;
				$arry ['total_avepushs'] = floor ( $total_score / 18 );
				$arry ['total_eagle'] = $total_eagle;
				$arry ['total_birdie'] = $total_birdie;
				$arry ['total_evenpar'] = $total_evenpar;
				$arry ['total_bogi'] = $total_bogi;
				$arry ['total_doubles'] = $total_doubles;
				$arry ['dateline'] = $row ['start_time'];
				$arry ['sais_id'] = $sid;
				$arry ['fuid'] = $field_id;
				$arry ['uid'] = $row ['uid'];
				$arry ['ismine'] = '0';
				$arry ['status'] = '2'; //状态通过
				$arry ['member'] = $tlmember; 
				$arry ['lun'] = $lun;
				$arry ['fz_id'] = $fz_id;
				$arry ['addtime'] = time ();
				//$row = DB::insert('common_cave_', $arry);	  
					$uid=$row ['uid'];
					if($i<=40)
					{
						$jf=20+$jfarray[$i]; 
					}else{
					
						$jf=20;
					}
					

				$if_have=DB::fetch_first("select id from " . DB::table ( 'common_score' ) . " where fz_id='".$fenz_id."' and uid='".$row ['uid']."' ");
				if(!$if_have['id']&&$arry ['uid'] )
				{
					$row = DB::insert('common_score', $arry);
				}
				else
				{
				 DB::update("common_score",$arry,array('fz_id'=>$fenz_id,'uid'=>$row ['uid']));
				}
				//$zong_score=DB::result_first("select sum(total_score) from ".DB::table("common_score")." where uid='".$row ['uid']."' and sais_id='".$sid."' ");
				//$user_up=DB::query("update ".DB::table("common_score")." set zong_score='".$zong_score."' where uid='".$row ['uid']."' and sais_id='".$sid."'  ");
				//$sql="update " . DB::table ( 'common_member_profile' ) . " set jifen=$jf where uid=".$row ['uid'] ;
				//$re=DB::query($sql);  	
	
	}
	
		echo "<script language=javascript>alert('已经添加！');history.back(-1);</script>";
		exit;
	}
	
 
}

if ($ac == 'nlist' || $ac == 'ndtv' || $ac == 'ndled') {
	
	 $days=($bssj-$dateline)/(24*3600);
   
		$fenz_id=$_GET['fenz_id'];
		 
	$field_id = DB::result_first ( " select `field_id` from " . DB::table ( "golf_nd_baofen" ) . " where fenz_id='".$fenz_id."'  limit 1" );
	 	
	$qc_par_result = DB::fetch_first ( " select `par` from " . DB::table ( "common_field" ) . " where uid='$field_id'" );
	
	$par = explode ( ',', $qc_par_result ['par'] );
	$POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
	$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
	$PTL = $POUT + $PIN;
	
	if ($ac == 'nlist') {
		$lib = 'ndbaolist';
		if($_GET['od']==1)
		{			
			
			$sql= "SELECT g_team_id from " . DB::table ( 'golf_nd_baofen' ) . " where tlcave>0  GROUP BY g_team_id ORDER BY avg(tlcave) ";
			$bf = DB::query ($sql ); 
				while ( $row = DB::fetch ( $bf ) ) {
				$djpm =$djpm.','. $row['g_team_id'];				
				} 
			//$orderby=" find_in_set(g_team_id,'$djpm'),";
			}
		//$bf = DB::query ( "select *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin  from " . DB::table ( 'golf_nd_baofen' ) . " where sid=$sid and field_id=$field_id $strwh ORDER BY CASE WHEN tlcave<> 0 THEN tlcave ELSE 999 END" );
		if($days>=1)
		{
		//$sql="select *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin  from " . DB::table ( 'golf_nd_baofen' ) . " where tlcave<999 and  cave_1>0 and cave_2>0  and cave_3>0  and cave_4>0  and cave_5>0  and cave_6>0  and cave_7>0  and cave_8>0  and cave_9>0  and cave_10>0  and cave_11>0  and cave_12>0  and cave_13>0  and cave_14>0  and cave_15>0  and cave_16>0  and cave_17>0  and cave_18>0    $strwh ORDER BY $orderby   isend desc,avcave,lin,cave_18,cave_17,cave_16";
		$sql="select *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin  from " . DB::table ( 'golf_nd_baofen' ) . " where tlcave<999 and  isend=1   and  fenz_id='".$fenz_id."'   ORDER BY $orderby   isend desc,avcave,lin,cave_18,cave_17,cave_16";
		}else{
		$sql="select *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin  from " . DB::table ( 'golf_nd_baofen' ) . " where fenz_id='".$fenz_id."'   ORDER BY $orderby    isend desc,avcave,lin,cave_18,cave_17,cave_16,team_num";
		
		//$sql="select uid,realname ,tlcavecave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18,avcave,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18) as lin,isend from ". DB::table('golf_nd_baofen')." where fenz_id='18' order by isend desc,avcave,lin";
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
	} else if($ac == 'ndtv') {
		$lib = 'ndbaotv';
	 	 
		$perpage = empty ( $_GET ['pg'] ) ? 13 : intval ( $_GET ['pg'] );
		$page = empty ( $_GET ['page'] ) ? 0 : intval ( $_GET ['page'] );
		if ($page < 1) {
			$page = 1;
		}
		
		$start = ($page - 1) * $perpage;
		ckstart ( $start, $perpage );
		
		$count = DB::result_first ( "select count(*) from " . DB::table ( 'golf_nd_baofen' ) . "    where 1=1  and  fenz_id='".$fenz_id."'     order by  tlcave asc" );
		
		$nbtvlist = array ();
		$pm = $_G [gp_pm] ? getgpc ( pm ) : 1;
		$pm = $pm > $count ? 1 : $pm;
		
		if ($count) {
			//$sql = "select  *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin from " . DB::table ( 'golf_nd_baofen' ) . " where sid=$sid and field_id=$field_id   and tlcave>0  ORDER BY CASE WHEN tlcave<> 0 THEN tlcave ELSE 999 END limit $start,$perpage";
			$sql = "select *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin  from " . DB::table ( 'golf_nd_baofen' ) . " where 1=1 and  fenz_id='".$fenz_id."'   ORDER BY $orderby  isend desc,avcave,lin,cave_18,cave_17,cave_16,team_num limit $start,$perpage";
		 
			$query = DB::query ( $sql );
			while ( $row = DB::fetch ( $query ) ) {
				$row ['pm'] = $pm ++;
				$nbtvlist [] = $row;
			
			}
		
		}
		
		if ($nbtvlist) {
			foreach ( $nbtvlist as $key => $value ) {
				
				$nbtvlist [$key] ['chj'] = $nbtvlist [$key] ['tlcave'] - $PTL;
				//$nbtvlist [$key] ['team_num'] = DB::result_first ( "select team_num from " . DB::table ( 'fenzu_members' ) . " where uid='" . $nbtvlist [$key] ['uid'] . "' and sid=$sid and fenz_id='tj57' and field_id='$field_id'  limit 1" );
				//$nbtvlist [$key] ['realname'] = DB::result_first ( "select realname from " . DB::table ( 'home_dazbm' ) . " where uid='" . $nbtvlist [$key] ['uid'] . "'  limit 1" );
			}
		}
		$realpages_x = @ceil ( $count / $perpage );
		if ($realpages_x <= getgpc ( 'page' )) {
			$page = 1;
		} else {
			$page ++;
		}
		
		$multi = multi ( $count, $perpage, $page, "/nd/baofen.php?ac=ndtv&field_id=".$field_id."&pg=".$perpage );
	
	}
	else {
		$lib = 'ndbaoled';
		
 
		$perpage = empty ( $_GET ['pg'] ) ? 30 : intval ( $_GET ['pg'] );
		$page = empty ( $_GET ['page'] ) ? 0 : intval ( $_GET ['page'] );
		if ($page < 1) {
			$page = 1;
		}
		
		$start = ($page - 1) * $perpage;
		ckstart ( $start, $perpage );
		
		$count = DB::result_first ( "select count(*) from " . DB::table ( 'golf_nd_baofen' ) . "    where 1=1  and  fenz_id='".$fenz_id."'    order by  tlcave asc" );
		
		$nbtvlist = array ();
		$pm = $_G [gp_pm] ? getgpc ( pm ) : 1;
		$pm = $pm > $count ? 1 : $pm;
		
		if ($count) {
			//$sql = "select  *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin from " . DB::table ( 'golf_nd_baofen' ) . " where sid=$sid and field_id=$field_id   and tlcave>0  ORDER BY CASE WHEN tlcave<> 0 THEN tlcave ELSE 999 END limit $start,$perpage";
			$sql = "select *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin  from " . DB::table ( 'golf_nd_baofen' ) . " where 1=1 and  fenz_id='".$fenz_id."'  ORDER BY $orderby  isend desc,avcave,lin,cave_18,cave_17,cave_16 limit $start,$perpage";
		 
			$query = DB::query ( $sql );
			while ( $row = DB::fetch ( $query ) ) {
				$row ['pm'] = $pm ++;
				$nbtvlist [] = $row;
			
			}
		
		}
		
		if ($nbtvlist) {
			foreach ( $nbtvlist as $key => $value ) {
				
				$nbtvlist [$key] ['chj'] = $nbtvlist [$key] ['tlcave'] - $PTL;
				//$nbtvlist [$key] ['team_num'] = DB::result_first ( "select team_num from " . DB::table ( 'fenzu_members' ) . " where uid='" . $nbtvlist [$key] ['uid'] . "' and sid=$sid and fenz_id='tj57' and field_id='$field_id'  limit 1" );
				//$nbtvlist [$key] ['realname'] = DB::result_first ( "select realname from " . DB::table ( 'home_dazbm' ) . " where uid='" . $nbtvlist [$key] ['uid'] . "'  limit 1" );
			}
		}
		$realpages_x = @ceil ( $count / $perpage );
		if ($realpages_x <= getgpc ( 'page' )) {
			$page = 1;
		} else {
			$page ++;
		}
		$multi = multi ( $count, $perpage, $page, "/nd/baofen.php?ac=ndled&field_id=$field_id" );
	
	}
	

}

//导入会员成绩卡
if ($ac == 'addd') {
	
	$qc_id=$_GET['qc_id']; 
	$sid=$_GET['sid'];
	$fz_id=$_GET['fz_id'];
	$lun=$_GET['lun'];
	$qc_par_result = DB::fetch_first ( " select `par` from " . DB::table ( "common_field" ) . " where uid='$qc_id'" );
	
	$fz_id = $_COOKIE ["fenz_id"];	
	
	$par = explode ( ',', $qc_par_result ['par'] );
	$POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
	$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
	$PTL = $POUT + $PIN;
	$arr ['par'] = $par [0] . '|' . $par [1] . '|' . $par [2] . '|' . $par [3] . '|' . $par [4] . '|' . $par [5] . '|' . $par [6] . '|' . $par [7] . '|' . $par [8] . '|' . $POUT . '|' . $par [9] . '|' . $par [10] . '|' . $par [11] . '|' . $par [12] . '|' . $par [13] . '|' . $par [14] . '|' . $par [15] . '|' . $par [16] . '|' . $par [17] . '|' . $PIN . '|' . $PTL;
		$i=0;
	
	$sql="select *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin  from " . DB::table ( 'golf_nd_baofen' ) . " where cave_1>0 and cave_2>0  and cave_3>0  and cave_4>0  and cave_5>0  and cave_6>0  and cave_7>0  and cave_8>0  and cave_9>0  and cave_10>0  and cave_11>0  and cave_12>0  and cave_13>0  and cave_14>0  and cave_15>0  and cave_16>0  and cave_17>0  and cave_18>0 and and  fenz_id='".$fz_id."'   and tlcave<1000";

 
	$bf = DB::query ( $sql );
	while ( $row = DB::fetch ( $bf ) )
	{
				$i=$i+1;
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
						$data [$i] = $row ['cave_' . ($i - 1)];
					} else {
						$data [$i] = $row ['cave_' . $i];
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
				$arr ['dateline'] = $row ['start_time'];
				$arr ['sais_id'] = $sid;
				$arr ['fuid'] = $field_id;
				$arr ['uid'] = $row ['uid'];
				$arr ['ismine'] = '0';
				$arr ['status'] = '2'; //状态通过
				$arr ['member'] = $tlmember;
				$arr ['lun'] = $lun;
				$arr ['fz_id'] = $fz_id;
				$arr ['addtime'] = time ();
				//$row = DB::insert('common_cave_', $arr);	  
					$uid=$row ['uid'];
					if($i<=40)
					{
						$jf=20+$jfarray[$i]; 
					}else{
					
						$jf=20;
					}
					

				$if_have=DB::fetch_first("select id from " . DB::table ( 'common_score' ) . " where fz_id='".$fz_id."' and uid='".$row ['uid']."' ");
				if(!$if_have['id']&&$arr ['uid'])
				{
					$row = DB::insert('common_score', $arr);
				}
				//$zong_score=DB::result_first("select sum(total_score) from ".DB::table("common_score")." where uid='".$row ['uid']."' and sais_id='".$sid."' ");
				//$user_up=DB::query("update ".DB::table("common_score")." set zong_score='".$zong_score."' where uid='".$row ['uid']."' and sais_id='".$sid."'  ");
				//$sql="update " . DB::table ( 'common_member_profile' ) . " set jifen=$jf where uid=".$row ['uid'] ;
				//$re=DB::query($sql);  	
	
	}
	
	//print_r($arr ); 
	$lib = 'ndbaotv';
		echo "<script language=javascript>alert('已经添加！');history.back(-1);</script>";
		exit;


}

 
//exit;
 
define ( 'CURMODULE', $mod );

runhooks ();
//距标准杆
function Gpar($cave, $par) {
	$option = $cave - $par;
	 
	return $option;
}

//距标准杆
function Getpar($cave, $par) {
	$option = $cave - $par;
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
	  
	 $sql=" select `cave_$dong` from " . DB::table ( "golf_nd_baofen" ) . " where nd_id='$nd_id' " ; 
	$cave= DB::result_first($sql);
 
	 
	if ($cave) {
		$dataInfo = $cave;	 
	
	} else {
		$dataInfo = 0;
	}
	
	return $dataInfo; 
}



function Getteamname($team_id) {
	 $teamname = DB::result_first( " select `team_name` from " . DB::table ( "golf_team" ) . " where team_id=$team_id" );
	return $teamname;
}

function Getusername($uid) {
	 $username = DB::result_first( " select `username` from " . DB::table ( "common_member" ) . " where uid=$uid" );
	return $username;
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

function Getcss($cave_, $par) {
	$option = $cave_ - $par;
	if ($cave_ == - 1) {
		//$dataInfo = " style=\"text-align:center;color:#FFffff;background:#089218\"";
		
		$dataInfo = " style=\"text-align:center;color:#FFffff;\"";
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
				$dataInfo = " style=\"text-align:center;color:#000000;background:#e9e9e9\"";
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
			//$dataInfo = " style=\"text-align:center;color:#FFffff;background:#FF00ff\"";
			$dataInfo = " style=\"text-align:center;color:#000000;\"";
	}
	return $dataInfo;
} 
if( $lib){
include_once template("diy:nd/" . $lib);
}
else
{
	echo '参数错误';
	}
?>