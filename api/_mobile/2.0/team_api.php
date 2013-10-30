<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}


/**
 * 团队赛成绩直播
 * 
 */


$ac=$_G['gp_ac'];
//赛事ID
$event_id=$_G['gp_sid'];
$orderby=" fenzu_id asc,is_out desc";
if($order=="1")
{ 
	$strwh=$strwh." and is_end=1 ";
}
else
{
	$orderby=" is_end desc,ttlcave,lin,cave_18,cave_17,cave_16";
}





//竖版
if($ac=="shuban")
{
	$event_info=DB::fetch_first("select event_id,event_name,event_uid,event_fenzhan_id,event_logo,event_left,event_left_flag,event_left_intro,event_left_pic,event_right,event_right_flag,event_right_intro,event_right_pic,event_timepic,event_starttime,event_endtime,event_content,event_state,event_is_tj,event_is_baoming,event_addtime from tbl_event where 1=1 and event_id='".$event_id."' order by event_addtime desc limit 1 ");
	//echo '<pre>';
	//var_dump($event_info);die;
	$list_info['event_name']=$event_info['event_name'];
	$list_info['event_left']=$event_info['event_left'];//'中国';
	$list_info['event_left_intro']=$event_info['event_left_intro'];//'CHINA';
	$list_info['event_left_pic']=$site_url.'/'.$event_info['event_left_pic'];//"/nd/images/big-guoqi.png";
	$list_info['event_right']=$event_info['event_right'];//'韩国';
	$list_info['event_right_intro']=$event_info['event_right_intro'];//'SOUTH KOREA';
	
	$list_info['event_right_pic']=$site_url.'/'.$event_info['event_right_pic'];//"/nd/images/big-hanguo.png";
	$list_info['event_logo']=$site_url."/".$event_info['event_logo'];
	$list_info['event_logo_info']=getimagesize($list_info['event_logo']);
	
	$big_where= " and is_end=1 ";
	if($event_info['event_fenzhan_id'])
	{
		$big_where .=" and fenzhan_id='".$event_info['event_fenzhan_id']."' ";
	}
	
	$sql="select baofen_id,sid,fenzhan_id,event_apply_id,uid,realname,total_ju_par as tlcave,start_time,fenzu_id,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin,total_ju_par+total_ju_par1 as ttlcave from tbl_baofen where 1=1 $big_where ORDER BY $orderby "; 
	//echo $sql;die;
	$bf = DB::query ($sql); 
	while($row=DB::fetch($bf))
	{
		$row['start_time']=date("Y年m月d日",$row['start_time']);
		$apply_info = DB::fetch_first("select tuanti_flag from tbl_event_apply where event_apply_id='{$row['event_apply_id']}'");
		$row['country']=$apply_info['tuanti_flag'];
		$nblist[]=array_default_value($row,array('duiyuan'));
	} 
	//die;
	$pm = 1;
	if($nblist)
	{
		foreach($nblist as $key => $value )
		{
			$nblist[$key]['pm'] = $pm ++;
			$nblist[$key]['chj'] = $nblist [$key] ['tlcave'] - $PTL; 
		}
	}	
	
	$data['title']='list_data';
	$data['data']=array(
		'list_info'=>$list_info,
		'list'=>$nblist
	);
	api_json_result(1,0,$app_error['event']['10502'],$data);

}





//横版 heng
if($ac=="heng")
{
	

	$event_info=DB::fetch_first("select event_id,event_name,event_uid,event_fenzhan_id,event_logo,event_left,event_left_flag,event_left_intro,event_left_pic,event_right,event_right_flag,event_right_intro,event_right_pic,event_timepic,event_starttime,event_endtime,event_content,event_state,event_is_tj,event_is_baoming,event_addtime,event_team_level from tbl_event where 1=1 and event_id='".$event_id."' order by event_addtime desc limit 1 ");
	//echo '<pre>';
	//var_dump($event_info);die;
	$list_info['event_name']=$event_info['event_name'];
	$list_info['event_left']=$event_info['event_left'];//'中国';
	$list_info['event_left_intro']=$event_info['event_left_intro'];//'CHINA';
	$list_info['event_left_pic']=$site_url.'/'.$event_info['event_left_pic'];//"/nd/images/big-guoqi.png";
	$list_info['event_right']=$event_info['event_right'];//'韩国';
	$list_info['event_right_intro']=$event_info['event_right_intro'];//'SOUTH KOREA';
	
	$list_info['event_right_pic']=$site_url.'/'.$event_info['event_right_pic'];//"/nd/images/big-hanguo.png";
	$list_info['event_logo']=$site_url."/".$event_info['event_logo'];
	$list_info['event_logo_info']=getimagesize($list_info['event_logo']);
	
	//$big_where= " and is_end=1 ";
	if($event_info['event_fenzhan_id'])
	{
		$big_where .=" and fenzhan_id='".$event_info['event_fenzhan_id']."' ";
	}
	
	
	$fenzhan_info=DB::fetch_first("select * from tbl_fenzhan where fenzhan_id='".$event_info['event_fenzhan_id']."' ");
	$fenzhan_rule=$fenzhan_info['fenzhan_rule'];

	//球场标准杆
	$qc_par_result = DB::fetch_first ( " select `par` from " . DB::table ( "common_field" ) . " where uid='$qc_id'" );
	$par = explode( ',',$fenzhan_info['fenzhan_a'].",".$fenzhan_info['fenzhan_b'] );  
	$POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
	$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
	$PTL = $POUT + $PIN;
	
	//取分组列表
	$sql="select fenzu_id,total_ju_par,total_ju_par as tlcave from tbl_baofen where 1=1 $big_where group by fenzu_id ORDER BY fenzu_id asc "; 
	$bf = DB::query ($sql); 
	while($row=DB::fetch($bf))
	{
		$row['par']=$par;
		$row['team_num']=$row['fenzu_id'];
		
		
		//取成员
		if($fenzhan_rule==11)
		{
			//个人对抗
			$sub_list=DB::query("select baofen_id,sid,event_apply_id,fenzhan_id,uid,realname,total_ju_par,total_ju_par as tlcave,start_time,fenzu_id,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin,total_ju_par+total_ju_par1 as ttlcave,event_user_id,event_user_team,event_apply_parent_id from tbl_baofen where 1=1 and fenzu_id='".$row['fenzu_id']."' $big_where order by event_user_team desc  ");
			
		}
		else if($fenzhan_rule==42)
		{
			//4人2球
			$sub_list=DB::query("select baofen_id,sid,event_apply_id,fenzhan_id,uid,realname,total_ju_par,total_ju_par as tlcave,start_time,fenzu_id,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin,total_ju_par+total_ju_par1 as ttlcave,event_user_id,event_user_team,event_apply_parent_id from tbl_baofen where 1=1 and fenzu_id='".$row['fenzu_id']."' $big_where order by event_user_team desc  ");
			
		}
		else
		{
			$sub_list=DB::query("select baofen_id,sid,event_apply_id,fenzhan_id,uid,realname,total_ju_par,total_ju_par as tlcave,start_time,fenzu_id,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin,total_ju_par+total_ju_par1 as ttlcave,event_user_id,event_user_team,event_apply_parent_id from tbl_baofen where 1=1 and fenzu_id='".$row['fenzu_id']."' $big_where group by event_user_team order by event_user_team desc  ");
		}
		
		
		$row_sub=array();
		$duiyuan = array();
		while($row_sub_arr=DB::fetch($sub_list))
		{
			
			//两个人
			$event_apply_info=DB::fetch_first("select event_apply_id,event_user_id,event_apply_realname,parent_id,parent_id as event_apply_parent_id from tbl_event_apply where event_apply_id='".$row_sub_arr['event_apply_id']."' ");
			
			if($fenzhan_rule==11)
			{
				//个人对抗
				if($event_apply_info['event_apply_parent_id']>0)
				{
					$event_apply_info_parent=DB::fetch_first("select event_apply_realname from tbl_event_apply where event_apply_id='".$event_apply_info['event_apply_parent_id']."' ");

					$user_sub_list=DB::query("select event_apply_realname from tbl_event_apply where 1=1 and event_user_parent_id='".$event_apply_info['event_apply_parent_id']."' and fenzhan_id='".$fenzhan_info['fenzhan_id']."' ");
					$user_sub_list_array=array();
					while($user_sub_list_arr=DB::fetch($user_sub_list))
					{
						$user_sub_list_array[]=$user_sub_list_arr['event_apply_realname'];
					}
					$row_sub_arr['realname']=$event_apply_info_parent['event_apply_realname'];
				}
				else
				{
					$user_sub_list_array=array($event_apply_info['event_apply_realname']);
					$row_sub_arr['realname']="";
				}
			}
			else if($fenzhan_rule==42)
			{
				//4人2球
				$row_sub_arr['realname']=$event_apply_info['event_apply_realname'];
				
				$user_sub_list=DB::query("select event_apply_realname from tbl_event_apply where 1=1 and parent_id='".$event_apply_info['event_apply_id']."' and fenzhan_id='".$fenzhan_info['fenzhan_id']."' ");
				$user_sub_list_array=array();
				while($user_sub_list_arr=DB::fetch($user_sub_list))
				{
					$user_sub_list_array[]=$user_sub_list_arr['event_apply_realname'];
				}
				if(count($user_sub_list_array)<1)
				{
					$user_sub_list_array=array();
				}
				
			}
			else if($fenzhan_rule==44)
			{
				//4人4球
				$event_apply_info_parent=DB::fetch_first("select event_apply_realname from tbl_event_apply where event_apply_id='".$event_apply_info['event_apply_parent_id']."' ");
				$user_sub_list=DB::query("select event_apply_realname from tbl_event_apply where 1=1 and parent_id='".$event_apply_info['event_apply_parent_id']."' and fenzhan_id='".$fenzhan_info['fenzhan_id']."' ");
				$user_sub_list_array=array();
				while($user_sub_list_arr=DB::fetch($user_sub_list))
				{
					$user_sub_list_array[]=$user_sub_list_arr['event_apply_realname'];
				}
				$row_sub_arr['realname']=$event_apply_info_parent['event_apply_realname'];
			}
			else
			{
				$user_sub_list_array=array($event_apply_info['event_apply_realname']);
				$row_sub_arr['realname']="";
				
			}
		
		
			$row_sub_arr['start_time']=date("Y年m月d日",$row_sub_arr['start_time']);
			$row_sub_arr['country']=$row_sub_arr['event_user_team'];
			$row_sub_arr['duiyuan']=implode(",",$user_sub_list_array);
			
			$row_sub_arr['chj'] = $row_sub_arr['tlcave']-$PTL; 
			//$row_sub_arr['defen'] = (string)1;
			
			if($fenzhan_rule==44)
			{
				$sub_score_list_1=DB::fetch_first("select baofen_id,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18 from tbl_baofen where event_apply_parent_id='".$event_apply_info['event_apply_parent_id']."' and fenzhan_id='".$fenzhan_info['fenzhan_id']."'  order by baofen_id desc limit 1 ");
				$sub_score_list_2=DB::fetch_first("select baofen_id,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18 from tbl_baofen where event_apply_parent_id='".$event_apply_info['event_apply_parent_id']."' and fenzhan_id='".$fenzhan_info['fenzhan_id']."'   order by baofen_id desc limit 1 ");
				$sub_score_list=get_score_44($sub_score_list_1,$sub_score_list_2);
				
				$row_sub_arr['tlcave']=array_sum($sub_score_list)-array_sum($par);
				$row_sub_arr['tlcave_new']=array_sum($sub_score_list)-array_sum($par);
				
				$row_sub_arr['color_1']=get_dong_color($sub_score_list[0],$par[0]);
				$row_sub_arr['color_2']=get_dong_color($sub_score_list[1],$par[1]);
				$row_sub_arr['color_3']=get_dong_color($sub_score_list[2],$par[2]);
				$row_sub_arr['color_4']=get_dong_color($sub_score_list[3],$par[3]);
				$row_sub_arr['color_5']=get_dong_color($sub_score_list[4],$par[4]);
				$row_sub_arr['color_6']=get_dong_color($sub_score_list[5],$par[5]);
				$row_sub_arr['color_7']=get_dong_color($sub_score_list[6],$par[6]);
				$row_sub_arr['color_8']=get_dong_color($sub_score_list[7],$par[7]);
				$row_sub_arr['color_9']=get_dong_color($sub_score_list[8],$par[8]);
				$row_sub_arr['color_10']=get_dong_color($sub_score_list[9],$par[9]);
				$row_sub_arr['color_11']=get_dong_color($sub_score_list[10],$par[10]);
				$row_sub_arr['color_12']=get_dong_color($sub_score_list[11],$par[11]);
				$row_sub_arr['color_13']=get_dong_color($sub_score_list[12],$par[12]);
				$row_sub_arr['color_14']=get_dong_color($sub_score_list[13],$par[13]);
				$row_sub_arr['color_15']=get_dong_color($sub_score_list[14],$par[14]);
				$row_sub_arr['color_16']=get_dong_color($sub_score_list[15],$par[15]);
				$row_sub_arr['color_17']=get_dong_color($sub_score_list[16],$par[16]);
				$row_sub_arr['color_18']=get_dong_color($sub_score_list[17],$par[17]);
				
			}
			else
			{
				$row_sub_arr['color_1']=get_dong_color($row_sub_arr['cave_1'],$par[0]);
				$row_sub_arr['color_2']=get_dong_color($row_sub_arr['cave_2'],$par[1]);
				$row_sub_arr['color_3']=get_dong_color($row_sub_arr['cave_3'],$par[2]);
				$row_sub_arr['color_4']=get_dong_color($row_sub_arr['cave_4'],$par[3]);
				$row_sub_arr['color_5']=get_dong_color($row_sub_arr['cave_5'],$par[4]);
				$row_sub_arr['color_6']=get_dong_color($row_sub_arr['cave_6'],$par[5]);
				$row_sub_arr['color_7']=get_dong_color($row_sub_arr['cave_7'],$par[6]);
				$row_sub_arr['color_8']=get_dong_color($row_sub_arr['cave_8'],$par[7]);
				$row_sub_arr['color_9']=get_dong_color($row_sub_arr['cave_9'],$par[8]);
				$row_sub_arr['color_10']=get_dong_color($row_sub_arr['cave_10'],$par[9]);
				$row_sub_arr['color_11']=get_dong_color($row_sub_arr['cave_11'],$par[10]);
				$row_sub_arr['color_12']=get_dong_color($row_sub_arr['cave_12'],$par[11]);
				$row_sub_arr['color_13']=get_dong_color($row_sub_arr['cave_13'],$par[12]);
				$row_sub_arr['color_14']=get_dong_color($row_sub_arr['cave_14'],$par[13]);
				$row_sub_arr['color_15']=get_dong_color($row_sub_arr['cave_15'],$par[14]);
				$row_sub_arr['color_16']=get_dong_color($row_sub_arr['cave_16'],$par[15]);
				$row_sub_arr['color_17']=get_dong_color($row_sub_arr['cave_17'],$par[16]);
				$row_sub_arr['color_18']=get_dong_color($row_sub_arr['cave_18'],$par[17]);
			}

			$row_sub[]=array_default_value($row_sub_arr,array('par'));
			$i++;
		}
		
		if($row_sub[0]['tlcave'] < $row_sub[1]['tlcave'])
		{
			$row['win']='left';
			$row_sub[0]['defen']=1;
			$row_sub[1]['defen']=0;
			
			//$row['left_score']=$row['left_score']+1;
		}
		else if($row_sub[0]['tlcave'] = $row_sub[1]['tlcave'])
		{
			if($row_sub[0]['ttlcave']<$row_sub[1]['ttlcave'])
			{
				$row['win']='left';
				$row_sub[0]['defen']=1;
				$row_sub[1]['defen']=0;
			}
			else
			{
				$row['win']='right';
				$row_sub[0]['defen']=0;
				$row_sub[1]['defen']=1;
			}
		}
		else
		{
			$row['win']='right';
			$row_sub[0]['defen']=0;
			$row_sub[1]['defen']=1;
		}
		
		$list_info['event_left_score']=(string)($list_info['event_left_score']+$row_sub[0]['defen']);
		$list_info['event_right_score']=(string)($list_info['event_right_score']+$row_sub[1]['defen']);
		
		$row['sub_member']=$row_sub;

		$nblist[]=array_default_value($row,array('duiyuan','sub_member'));
	} 
	$pm = 1;
	if($nblist)
	{
		foreach($nblist as $key => $value )
		{
			$nblist[$key]['pm'] = $pm ++;
		}
	}	
	
	$data['title']='list_data';
	$data['data']=array(
		'list_info'=>$list_info,
		'list'=>$nblist
	);
	api_json_result(1,0,$app_error['event']['10502'],$data);

}





//坚版 SMALL
if($ac=="small")
{

	$event_info=DB::fetch_first("select event_id,event_name,event_uid,event_fenzhan_id,event_logo,event_left,event_left_flag,event_left_intro,event_left_pic,event_right,event_right_flag,event_right_intro,event_right_pic,event_timepic,event_starttime,event_endtime,event_content,event_state,event_is_tj,event_is_baoming,event_addtime,event_team_level from tbl_event where 1=1 and event_id='".$event_id."' order by event_addtime desc limit 1 ");
	//echo '<pre>';
	//var_dump($event_info);die;
	$list_info['event_name']=$event_info['event_name'];
	$list_info['event_left']=$event_info['event_left'];//'中国';
	$list_info['event_left_intro']=$event_info['event_left_intro'];//'CHINA';
	$list_info['event_left_pic']=$site_url.'/'.$event_info['event_left_pic'];//"/nd/images/big-guoqi.png";
	$list_info['event_right']=$event_info['event_right'];//'韩国';
	$list_info['event_right_intro']=$event_info['event_right_intro'];//'SOUTH KOREA';
	
	$list_info['event_right_pic']=$site_url.'/'.$event_info['event_right_pic'];//"/nd/images/big-hanguo.png";
	$list_info['event_logo']=$site_url."/".$event_info['event_logo'];
	$list_info['event_logo_info']=getimagesize($list_info['event_logo']);
	
	
	//$big_where= " and is_end=1 ";
	if($event_info['event_fenzhan_id'])
	{
		$big_where .=" and fenzhan_id='".$event_info['event_fenzhan_id']."' ";
	}
	$fenzhan_info=DB::fetch_first("select * from tbl_fenzhan where fenzhan_id='".$event_info['event_fenzhan_id']."' ");
	$fenzhan_rule=$fenzhan_info['fenzhan_rule'];
	
	
	//分组列表
	$sql="select fenzu_id,total_ju_par as tlcave,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin,total_ju_par+total_ju_par1 as ttlcave,event_user_id,event_user_team from tbl_baofen where 1=1 $big_where group by fenzu_id ORDER BY fenzu_id asc "; 
	$bf = DB::query ($sql); 
	while($row=DB::fetch($bf))
	{
		$row['team_num']=$row['fenzu_id'];
		
		//取成员
		if($fenzhan_rule==11)
		{
			//个人对抗
			$sub_list=DB::query("select baofen_id,event_user_id,event_user_team,event_apply_id,sid,fenzhan_id,uid,realname,total_ju_par,total_ju_par as tlcave,start_time,fenzu_id,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin,total_ju_par+total_ju_par1 as ttlcave,event_apply_parent_id from tbl_baofen where 1=1 and fenzu_id='".$row['fenzu_id']."' $big_where order by event_user_team desc ");
		}
		else if($fenzhan_rule==42)
		{
			//4人2球
			$sub_list=DB::query("select baofen_id,event_user_id,event_user_team,event_apply_id,sid,fenzhan_id,uid,realname,total_ju_par,total_ju_par as tlcave,start_time,fenzu_id,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin,total_ju_par+total_ju_par1 as ttlcave,event_apply_parent_id from tbl_baofen where 1=1 and fenzu_id='".$row['fenzu_id']."' $big_where order by event_user_team desc ");
		}
		else
		{
			$sub_list=DB::query("select baofen_id,event_user_id,event_user_team,event_apply_id,sid,fenzhan_id,uid,realname,total_ju_par,total_ju_par as tlcave,start_time,fenzu_id,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin,total_ju_par+total_ju_par1 as ttlcave,event_apply_parent_id from tbl_baofen where 1=1 and fenzu_id='".$row['fenzu_id']."' $big_where group by event_user_team order by event_user_team desc ");
		}
		$row_sub=array();
		$duiyuan = array();
		while($row_sub_arr=DB::fetch($sub_list))
		{
			
			//两个人
			$event_apply_info=DB::fetch_first("select event_apply_id,event_user_id,event_apply_realname,parent_id,parent_id as event_apply_parent_id from tbl_event_apply where event_apply_id='".$row_sub_arr['event_apply_id']."' ");
			
			if($fenzhan_rule==11)
			{
				//个人对抗
				if($event_apply_info['event_apply_parent_id']>0)
				{
					$event_apply_info_parent=DB::fetch_first("select event_apply_realname from tbl_event_apply where event_apply_id='".$event_apply_info['event_apply_parent_id']."' ");

					$user_sub_list=DB::query("select event_apply_realname from tbl_event_apply where 1=1 and event_user_parent_id='".$event_apply_info['event_apply_parent_id']."' and fenzhan_id='".$fenzhan_info['fenzhan_id']."' ");
					$user_sub_list_array=array();
					while($user_sub_list_arr=DB::fetch($user_sub_list))
					{
						$user_sub_list_array[]=$user_sub_list_arr['event_apply_realname'];
					}
					$row_sub_arr['realname']=$event_apply_info_parent['event_apply_realname'];
				}
				else
				{
					$user_sub_list_array=array($event_apply_info['event_apply_realname']);
					$row_sub_arr['realname']="";
				}
			}
			else if($fenzhan_rule==42)
			{
				//4人2球
				$row_sub_arr['realname']=$event_apply_info['event_apply_realname'];
			
				$user_sub_list=DB::query("select event_apply_realname from tbl_event_apply where 1=1 and parent_id='".$event_apply_info['event_apply_id']."' and fenzhan_id='".$fenzhan_info['fenzhan_id']."'  ");
				
				$user_sub_list_array=array();
				while($user_sub_list_arr=DB::fetch($user_sub_list))
				{
					$user_sub_list_array[]=$user_sub_list_arr['event_apply_realname'];
				}
				if(count($user_sub_list_array)<1)
				{
					$user_sub_list_array=array();
				}
				
			}
			else if($fenzhan_rule==44)
			{
				//4人4球
				$event_apply_info_parent=DB::fetch_first("select event_apply_realname from tbl_event_apply where event_apply_id='".$event_apply_info['event_apply_parent_id']."' ");
				$user_sub_list=DB::query("select event_apply_realname from tbl_event_apply where 1=1 and parent_id='".$event_apply_info['event_apply_parent_id']."' and fenzhan_id='".$fenzhan_info['fenzhan_id']."' ");
				$user_sub_list_array=array();
				while($user_sub_list_arr=DB::fetch($user_sub_list))
				{
					$user_sub_list_array[]=$user_sub_list_arr['event_apply_realname'];
				}
				$row_sub_arr['realname']=$event_apply_info_parent['event_apply_realname'];
			}
			else
			{
				$user_sub_list_array=array($event_apply_info['event_apply_realname']);
				$row_sub_arr['realname']="";
				
			}
		
			$row_sub_arr['start_time']=date("Y年m月d日",$row_sub_arr['start_time']);
			$row_sub_arr['country']=$row_sub_arr['event_user_team'];
			$row_sub_arr['duiyuan']=$user_sub_list_array;
			
			
			
			
			if($fenzhan_rule==44)
			{
				$sub_score_list_1=DB::fetch_first("select baofen_id,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18 from tbl_baofen where event_apply_parent_id='".$event_apply_info['event_apply_parent_id']."' and fenzhan_id='".$fenzhan_info['fenzhan_id']."'  order by baofen_id desc limit 1 ");
				$sub_score_list_2=DB::fetch_first("select baofen_id,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18 from tbl_baofen where event_apply_parent_id='".$event_apply_info['event_apply_parent_id']."' and fenzhan_id='".$fenzhan_info['fenzhan_id']."'   order by baofen_id desc limit 1 ");
				$sub_score_list=get_score_44($sub_score_list_1,$sub_score_list_2);
				
				$row_sub_arr['tlcave']=array_sum($sub_score_list)-array_sum($par);
				$row_sub_arr['tlcave_new']=array_sum($sub_score_list)-array_sum($par);
				
				$row_sub_arr['color_1']=get_dong_color($sub_score_list[0],$par[0]);
				$row_sub_arr['color_2']=get_dong_color($sub_score_list[1],$par[1]);
				$row_sub_arr['color_3']=get_dong_color($sub_score_list[2],$par[2]);
				$row_sub_arr['color_4']=get_dong_color($sub_score_list[3],$par[3]);
				$row_sub_arr['color_5']=get_dong_color($sub_score_list[4],$par[4]);
				$row_sub_arr['color_6']=get_dong_color($sub_score_list[5],$par[5]);
				$row_sub_arr['color_7']=get_dong_color($sub_score_list[6],$par[6]);
				$row_sub_arr['color_8']=get_dong_color($sub_score_list[7],$par[7]);
				$row_sub_arr['color_9']=get_dong_color($sub_score_list[8],$par[8]);
				$row_sub_arr['color_10']=get_dong_color($sub_score_list[9],$par[9]);
				$row_sub_arr['color_11']=get_dong_color($sub_score_list[10],$par[10]);
				$row_sub_arr['color_12']=get_dong_color($sub_score_list[11],$par[11]);
				$row_sub_arr['color_13']=get_dong_color($sub_score_list[12],$par[12]);
				$row_sub_arr['color_14']=get_dong_color($sub_score_list[13],$par[13]);
				$row_sub_arr['color_15']=get_dong_color($sub_score_list[14],$par[14]);
				$row_sub_arr['color_16']=get_dong_color($sub_score_list[15],$par[15]);
				$row_sub_arr['color_17']=get_dong_color($sub_score_list[16],$par[16]);
				$row_sub_arr['color_18']=get_dong_color($sub_score_list[17],$par[17]);
				
			}
			else
			{
				$row_sub_arr['color_1']=get_dong_color($row_sub_arr['cave_1'],$par[0]);
				$row_sub_arr['color_2']=get_dong_color($row_sub_arr['cave_2'],$par[1]);
				$row_sub_arr['color_3']=get_dong_color($row_sub_arr['cave_3'],$par[2]);
				$row_sub_arr['color_4']=get_dong_color($row_sub_arr['cave_4'],$par[3]);
				$row_sub_arr['color_5']=get_dong_color($row_sub_arr['cave_5'],$par[4]);
				$row_sub_arr['color_6']=get_dong_color($row_sub_arr['cave_6'],$par[5]);
				$row_sub_arr['color_7']=get_dong_color($row_sub_arr['cave_7'],$par[6]);
				$row_sub_arr['color_8']=get_dong_color($row_sub_arr['cave_8'],$par[7]);
				$row_sub_arr['color_9']=get_dong_color($row_sub_arr['cave_9'],$par[8]);
				$row_sub_arr['color_10']=get_dong_color($row_sub_arr['cave_10'],$par[9]);
				$row_sub_arr['color_11']=get_dong_color($row_sub_arr['cave_11'],$par[10]);
				$row_sub_arr['color_12']=get_dong_color($row_sub_arr['cave_12'],$par[11]);
				$row_sub_arr['color_13']=get_dong_color($row_sub_arr['cave_13'],$par[12]);
				$row_sub_arr['color_14']=get_dong_color($row_sub_arr['cave_14'],$par[13]);
				$row_sub_arr['color_15']=get_dong_color($row_sub_arr['cave_15'],$par[14]);
				$row_sub_arr['color_16']=get_dong_color($row_sub_arr['cave_16'],$par[15]);
				$row_sub_arr['color_17']=get_dong_color($row_sub_arr['cave_17'],$par[16]);
				$row_sub_arr['color_18']=get_dong_color($row_sub_arr['cave_18'],$par[17]);
			}

			
			

			$row_sub[]=array_default_value($row_sub_arr);
		}
		
		//var_dump($row_sub);
		
		//$row['sub_member']=(array_sort_by_field($row_sub,'guoqi',true));
		
		if($row_sub[0]['tlcave'] < $row_sub[1]['tlcave'])
		{
			$row['win']='left';
			$row_sub[0]['defen']=1;
			$row_sub[1]['defen']=0;
			
			//$row['left_score']=$row['left_score']+1;
		}
		else if($row_sub[0]['tlcave'] = $row_sub[1]['tlcave'])
		{
			if($row_sub[0]['ttlcave']<$row_sub[1]['ttlcave'])
			{
				$row['win']='left';
				$row_sub[0]['defen']=1;
				$row_sub[1]['defen']=0;
			}
			else
			{
				$row['win']='right';
				$row_sub[0]['defen']=0;
				$row_sub[1]['defen']=1;
			}
		}
		else
		{
			$row['win']='right';
			$row_sub[0]['defen']=0;
			$row_sub[1]['defen']=1;
		}
		
		$list_info['event_left_score']=(string)($list_info['event_left_score']+$row_sub[0]['defen']);
		$list_info['event_right_score']=(string)($list_info['event_right_score']+$row_sub[1]['defen']);
		
		$row['sub_member']=$row_sub;
		
		

		$nblist[]=array_default_value($row,array('duiyuan','sub_member'));
	} 
	$pm = 1;
	if($nblist)
	{
		foreach($nblist as $key => $value )
		{
			$nblist[$key]['pm'] = $pm ++;
			$nblist[$key]['chj'] = $nblist[$key]['tlcave']-$PTL; 
		}
	}	
	
	$data['title']='list_data';
	$data['data']=array(
		'list_info'=>$list_info,
		'list'=>$nblist
	);
	api_json_result(1,0,$app_error['event']['10502'],$data);

}


//function ||


	
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

function Getdefen($score1,$score2,$is_end)
{
	if($is_end==1&&$score1<200&&$score2<200)
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
 
function Getwin($score1,$score2,$is_end,$type)
{
	
	if($is_end==1&&$score1<200&&$score2<200)
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


 
function get_dong_color($score,$par)
{
	if($score-$par==3)
	{
		$color=1;
	}
	else if($score-$par==2)
	{
		$color=2;
	}
	else if($score-$par==1)
	{
		$color=3;
	}
	else if($score-$par==0)
	{
		$color=4;
	}
	else if($score-$par==-1)
	{
		$color=5;
	}
	else if($score-$par==-2)
	{
		$color=6;
	}
	else if($score-$par==-3)
	{
		$color=7;
	}
	else
	{
		$color=0;
	}
	
	return $color;
}
	

function sort_by_country($a,$b)
{
	
	if ($a == $b) return 0;
	return ($a > $b) ? -1 : 1;

}	


?>

