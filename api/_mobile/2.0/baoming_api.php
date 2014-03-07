<?php
/*
*
* bwvip.com
* 赛事报名
*
*/
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



//参考  zimeiti_api.php

//报名页面
if($ac=="baoming_add")
{
	//当前文件所在路径
	$current_path = dirname(__FILE__); 
	$event_id=$_G['gp_event_id'];
	if($event_id)
	{
		$event_info=DB::fetch_first("select event_baoming_top_pic from tbl_event where event_id='".$event_id."' ");
		$list_data=include($current_path.'/data/tbl_event_baoming_'.$event_id.'_array_data.php');
		$baoming_top_pic='';
		if(!empty($event_info['event_baoming_top_pic'])){
			$baoming_top_pic = $site_url.'/'.$event_info['event_baoming_top_pic'];
		}
		$data['title']="data";
		$data['data']=array('tip_info'=>array('baoming_top_pic'=>$baoming_top_pic),'list'=>$list_data);
		api_json_result(1,0,$app_error['event']['10502'],$data);
	}
	else
	{
		api_json_result(1,1,$app_error['event']['10502'],null);
	}
}

//报名保存页面
if($ac=="baoming_add_action")
{
	$event_id=$_G['gp_event_id'];
	$uid=$_G['gp_uid'];

	//2013城市挑战赛
	if($event_id==25)
	{
	
		$fenzhan=array_search(urldecode($_G['gp_event_apply_fenzhan']),$hot_2013district);
		
		//api_json_result(1,1,"该比赛已结束，不能报名",$data);
		$bm=DB::fetch_first("select bm_id from pre_home_dazbm where uid='".$_G['gp_uid']."' and hot_district='".$fenzhan."' and year='2014' ");
		if(!$bm['bm_id'])
		{
			/* if(urlencode($_G['gp_event_apply_sex'])=="男")
			{
				$sex=1;
			}
			else
			{
				$sex=2;
			}
			if(urlencode($_G['gp_event_apply_is_huang'])=="是")
			{
				$is_huang=1;
			}
			else
			{
				$is_huang=0;
			} */
			
			
			$mobile=DB::result_first("select mobile from ".DB::table("common_member_profile")." where uid='".$_G['gp_uid']."' ");
			
			$data_bm['uid']=$_G['gp_uid'];
			$data_bm['realname']   = urldecode($_G['gp_event_apply_realname']);         //真实姓名
			$data_bm['gender']     = $_G['gp_event_apply_sex'];            //1 男 2 女
			$data_bm['credentials_num']    = $_G['gp_event_apply_card'];     //证件号码
			$data_bm['hot_district']    = $fenzhan;

			$data_bm['cahdian']    = !empty ( $_G['gp_event_apply_chadian'] ) ? $_G['gp_event_apply_chadian'] : '';              //差点
			$data_bm['moblie']             = $mobile;
			$data_bm['is_huang']             = $_G['gp_event_apply_is_huang']; //是否车主
			$data_bm['nationality']='中国';     //国籍
			//xyx 20130615增加字段，方便客服查询
			$data_bm['addtime']= time(); 
			$data_bm['game_s_type']= 1000333; 
			$data_bm['year']= '2014'; 
			
			DB::insert('home_dazbm',$data_bm,true);
			api_json_result(1,0,"报名成功",$data);
			
		}
		else
		{
			api_json_result(1,1,"不能重复报名",$data);
		}
	}
	
	
	//2014城市挑战赛
	if($event_id==65)
	{
		$baoming_info=DB::fetch_first("select baoming_id from tbl_baoming where uid='".$_G['gp_uid']."' and event_id='".$event_id."' ");
		if(!$baoming_info['baoming_id'])
		{
			//$fenzhan_ids=implode(",",$_POST['fenzhan_names']);
			$fenzhan_ids = $_G['gp_fenzhan_names'];
			$sql="insert into tbl_baoming (event_id,uid,baoming_realname,baoming_sex,baoming_is_huang,baoming_chadian,fenzhan_ids,baoming_source,baoming_addtime) values('".$event_id."','".$uid."','".urldecode($_G['gp_baoming_realname'])."','".urldecode($_G['gp_baoming_sex'])."','".$_G['baoming_is_huang']."','".$_G['gp_baoming_chadian']."','".$fenzhan_ids."','app','".time()."') ";
			DB::query($sql);
			
			api_json_result(1,0,"您的报名信息已受理，详询4008109966。",$data);

		}
		else
		{
			api_json_result(1,1,"不能重复报名",$data);
		}
	}
	
	//亚运会
	if($event_id==66)
	{
		$baoming_info=DB::fetch_first("select baoming_id from tbl_baoming where uid='".$_G['gp_uid']."' and event_id='".$event_id."' ");
		if(!$baoming_info['baoming_id'])
		{
		
			
			
			/* var_dump($_G['gp_fenzhan_names']);
			$fenzhan_ids=implode(",",$_POST['fenzhan_names']);//
			var_dump($fenzhan_ids);die; */
			/*$event_ids = $_G['gp_fenzhan_names'];
			$event_id_arr=explode(",",$event_ids);
			
			
			for($i=0; $i<count($event_id_arr); $i++ )
			{
				$list=DB::query("select fenzhan_id from tbl_fenzhan where event_id='".$event_id_arr[$i]."' ");
				while($row=DB::fetch($list))
				{
					$fenzhan_id_arr[]=$row['fenzhan_id'];
				}
				
			}
			$fenzhan_ids=implode(",",$fenzhan_id_arr); */
			
			$fenzhan_ids = $_G['gp_fenzhan_names'];
			$sql="insert into tbl_baoming (event_id,uid,baoming_realname,baoming_sex,baoming_card,baoming_mobile,baoming_email,baoming_chadian,baoming_zige,baoming_is_zidai_qiutong,fenzhan_ids,baoming_source,baoming_addtime) values('".$event_id."','".$uid."','".urldecode($_G['gp_baoming_realname'])."','".urldecode($_G['gp_baoming_sex'])."','".$_G['gp_baoming_card']."','".$_G['gp_baoming_mobile']."','".urldecode($_G['gp_baoming_email'])."','".$_G['gp_baoming_chadian']."','".urldecode($_G['gp_baoming_zige'])."','".$_G['gp_baoming_is_zidai_qiutong']."','".$fenzhan_ids."','app','".time()."') ";
			DB::query($sql);
			
			api_json_result(1,0,"您的报名信息已受理，详询4008109966。",$data);
		
		}
		else
		{
			api_json_result(1,1,"不能重复报名",$data);
		}
		
	
	}
	//云信联盟杯
	if($event_id==80)
	{
		$baoming_info=DB::fetch_first("select baoming_id from tbl_baoming where uid='".$_G['gp_uid']."' and event_id='".$event_id."' ");
		if(!$baoming_info['baoming_id'])
		{
			/* var_dump($_G['gp_fenzhan_names']);
			$fenzhan_ids=implode(",",$_POST['fenzhan_names']);//
			var_dump($fenzhan_ids);die; */
			/*$event_ids = $_G['gp_fenzhan_names'];
			$event_id_arr=explode(",",$event_ids);
			
			
			for($i=0; $i<count($event_id_arr); $i++ )
			{
				$list=DB::query("select fenzhan_id from tbl_fenzhan where event_id='".$event_id_arr[$i]."' ");
				while($row=DB::fetch($list))
				{
					$fenzhan_id_arr[]=$row['fenzhan_id'];
				}
				
			}
			$fenzhan_ids=implode(",",$fenzhan_id_arr); */
			
			$fenzhan_ids = $_G['gp_fenzhan_names'];
			$sql="insert into tbl_baoming (event_id,uid,baoming_realname,baoming_sex,baoming_card,baoming_mobile,baoming_email,baoming_chadian,baoming_zige,baoming_is_zidai_qiutong,fenzhan_ids,baoming_source,baoming_addtime) values('".$event_id."','".$uid."','".urldecode($_G['gp_baoming_realname'])."','".urldecode($_G['gp_baoming_sex'])."','".$_G['gp_baoming_card']."','".$_G['gp_baoming_mobile']."','".urldecode($_G['gp_baoming_email'])."','".$_G['gp_baoming_chadian']."','".urldecode($_G['gp_baoming_zige'])."','".$_G['gp_baoming_is_zidai_qiutong']."','".$fenzhan_ids."','app','".time()."') ";
			DB::query($sql);
			
			api_json_result(1,0,"您的报名信息已受理，详询4008109966。",$data);
		
		}
		else
		{
			api_json_result(1,1,"不能重复报名",$data);
		}
		
	
	}
}


?>