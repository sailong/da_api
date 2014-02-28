<?php
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}

$ac=$_G['gp_ac'];

//page 1
$page=$_G['gp_page'];
if(!$page)
{
	$page=1;
}
$page_size=$_G['gp_page_size'];
if(!$page_size)
{
	$page_size=10;
}
if($page==1)
{
	$page_start=0;
}
else
{
	$page_start=($page-1)*($page_size);
}

//page 2
$page2=$_G['gp_page2'];
if(!$page2)
{
	$page2=1;
}
$page_size2=$_G['gp_page_size2'];
if(!$page_size2)
{
	$page_size2=10;
}
if($page2==1)
{
	$page_start2=0;
}
else
{
	$page_start2=($page2-1)*($page_size2);
}

if($ac = 'score_list'){
	$nd_id=$_G['gp_ndid'];
	//获取赛事ID
	$score_info=DB::fetch_first("select uid,event_id,field_id,fenzhan_id,total_score,start_time,realname from tbl_baofen where baofen_id='".$nd_id."' ");//result_first

	$uid=$score_info['uid']; 
	$event_id=$score_info['event_id'];
	$field_id=$score_info['field_id']; 
	$fenzhan_id=$score_info['fenzhan_id'];  
	$zong_fen=$score_info['total_score'];
	$realname=$score_info['realname'];
	$event_info=DB::fetch_first("select event_name,event_starttime,event_endtime from tbl_event where event_id='".$event_id."' ");
	$start_date = date('Y.m.d',$event_info['event_starttime']).' — '.date('Y.m.d',$event_info['event_endtime']);

	$score_list_arr = array();
	if($nd_id > 0)
	{ 
		$row1 = array(
					'球洞(RND)',
					'1',
					'2',
					'3',
					'4',
					'5',
					'6',
					'7',
					'8',
					'9',
					'OUT',
					'10',
					'11',
					'12',
					'13',
					'14',
					'15',
					'16',
					'17',
					'18',
					'IN',
					'TOT');
	
		$score_list_arr[] = $row1;
		$ct= DB::result_first("SELECT lun  FROM tbl_baofen WHERE event_id=".$event_id." and uid=".$uid."  order by lun desc limit 1");
		
		if($ct>1)
		{
		
			if($fenzhan_id){
				if($event_id==27){
					$qc_par_result = DB::fetch_first ( " select `fenzhan_a`,fenzhan_b,sid from tbl_fenzhan where fenzhan_id='115' " );
				}else{
					$qc_par_result = DB::fetch_first ( " select `fenzhan_a`,fenzhan_b,sid from tbl_fenzhan where fenzhan_id='".$fenzhan_id."' " );
				}
				$par = explode(',',$qc_par_result['fenzhan_a'].','.$qc_par_result['fenzhan_b'] );  

				$sid=$qc_par_result['sid']; 	
				$POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
				$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
				$PTL = $POUT + $PIN;
			}
			
			$row2[]='标准杆(PAR)';
			$i=0;
			foreach($par as $key=>$val){
				if(($i+1)%10==0){
					$row2[] = "'".$POUT."'";
				}
				$row2[] = $val;
				$i++;
			}
			
			$row2[] = "'".$PIN."'";
			$row2[] = "'".$PTL."'";
			$score_list_arr[] = $row2;
			
			if($event_id==27){
				$jdt=DB::query("SELECT uid,realname,lun,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18,  (cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18) as lin,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9+cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18) as lto,total_ju_par,pars   FROM tbl_baofen WHERE event_id=".$event_id." and uid=".$uid." and fenzhan_id in(115,116,117) order by is_end desc,lun");  
			}else{
				$jdt=DB::query("SELECT uid,realname,lun,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18,  (cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18) as lin,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9+cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18) as lto,total_ju_par,pars   FROM tbl_baofen WHERE event_id=".$event_id." and uid=".$uid."  order by is_end desc,lun");  
			}
			//echo '<pre>';
			$row3 = array();
			$totle_score = 0;
			while($lib=DB::fetch($jdt)){
				//var_dump($lib);
				unset($tmp_row,$tmp_color);
				
				$tmp_row[] = 'R'.$lib['lun'];
				$tmp_color[] = Getcolor();
				for($i=0;$i<=17;$i++){
					$score=Getchj($lib["cave_".($i+1)]);
					if(($i+1)%10==0){
						$tmp_row[] = $lib['lout'];
						$tmp_color[] = Getcolor();
					}
					$tmp_row[] = $score;
					$tmp_color[] = Getcolor($score,$par[$i]);
				}
				$tmp_row[] = $lib['lin'];
				$tmp_color[] = Getcolor();
				if($lib['lto']){
					$tmp_row[] = $lib['lto'];
				}else{
					$tmp_row[] = '-';
				}
				$totle_score+=$lib['lto'];
				$tmp_color[] = Getcolor();
				$tmp_score_list['score'] = $tmp_row;
				$tmp_score_list['color'] = $tmp_color;
				$row3[]=$tmp_score_list;
			}
			
			
		}
	}
	
	$data['title']='detail_data';
	$data['data']=array(
		//'list_info'=>$list_info,
			'uid'=>$uid,
			'realname'=>$realname,
			'event_name'=>$event_info['event_name'],
			'fuid'=>$field_id,
			'totle_score'=>"{$totle_score}",
			'start_date'=>$start_date,
			'par_title'=>$row1,
			'par'=>$row2,
			'score_list'=>$row3//$score_list_arr
	);
	
	api_json_result(1,0,$app_error['event']['10502'],$data);
}









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
	if ($avscore == 1000) {
			$avscore = '-';
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

function Getchj($score)
{
	if($score){
		$dataInfo=$score;
	}else
    {
		$dataInfo='-';
	}
	
	return $dataInfo;
}
function Getcolor($score,$par){
	$color='0';
	if($score!="" )
	{
		if($score-$par==3)
		{
			$color='1';
		}
		else if($score-$par==2)
		{
			$color='2';
		}
		else if($score-$par==1)
		{
			$color='3';
		}
		else if($score-$par==0)
		{
			$color='4';
		}
		else if($score-$par==-1)
		{
			$color='5';
		}
		else if($score-$par==-2)
		{
			$color='6';
		}
		else if($score-$par==-3)
		{
			$color='7';
		}
		else
		{
			$color='0';
		}
	}
	
	return $color;
}


?>