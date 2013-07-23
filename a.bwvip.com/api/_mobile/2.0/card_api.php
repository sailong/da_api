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
if($ac==='edit')
{
	
	$showtime = time();
	//$sql="update " . DB::table ( 'tmsg' ) . " set num=num+1,  num0=num0+1, dateline='$showtime'  where mobile='$mobile'";
	//$re=DB::query($sql); 

	api_json_result(1,0,$api_error['card']['10020'],$data);
	
}


//显示记录 
if($ac==='show')
{

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
					$res=DB::query("update  ".DB::table("home_dazbm")." set code_pic='".$filename."' where  bm_id='".$bm['bm_id']."' ");
				}
				*/
				$event_info['event_baoming_pic']="";	
				
				

			}

		}
		else
		{
			$event_info['event_baoming_state']="0";
			$event_info['event_baoming_pic']="";
		}
		

		
	}
	
		

	//print_r($event_info);
	if($event_info['event_logo'])
	{
		$event_info['event_logo']=$site_url."/".$event_info['event_logo'];
	}
	if($event_info['event_timepic'])
	{
		$event_info['event_timepic']=$site_url."/".$event_info['event_timepic'];
		list($width, $height, $type, $attr) = getimagesize($event_info['event_timepic']);
		$event_info['event_timepic_width']=$width;
		$event_info['event_timepic_height']=$height;
	}

	if($event_info['event_content'])
	{
		$event_info['event_content']=str_replace("http://192.168.1.151:806","",$event_info['event_content']);
	}
	if($pic_width)
	{
		$event_info['event_content']=str_replace("<img ","<img width=\"".$pic_width."\" ",$event_info['event_content']);
	}

	//微博列表
	$saishi_name=gettruename($sid);
	$list=DB::query("select tid,uid, (select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid)  as username,(select `longtext` from jishigou_topic_longtext where tid=jishigou_topic.tid limit 1) as `longtext`,imageid,content,replys,forwards,dateline,voice,voice_timelong from jishigou_topic where type<>'reply' and content like '%".$saishi_name."%' order by dateline desc limit 5 ");
	while($row = DB::fetch($list) )
	{
		$imageids_arr = explode(',',$row['imageid']);
					
		$pic_ids = implode("','",$imageids_arr);
		unset($imageids_arr);
		$topic_img_rs = DB::query("select id,photo from jishigou_topic_image where id in('{$pic_ids}')");
		unset($pic_ids);
		
		$pic_i=0;
		while($pic_row = DB::fetch($topic_img_rs) ){
			$pic_list[$pic_i]['photo_big'] = $site_url."/weibo/".$pic_row['photo'];
			$pic_list[$pic_i]['photo_small'] = $site_url."/weibo/".str_replace("_o","_s",$pic_row['photo']);
			$pic_i++;
		}
		unset($topic_img_rs,$pic_i,$pic_row);
		if(!empty($pic_list)) {
			$row['pic_list'] = $pic_list;
		}else{
			$row['pic_list'] = '';
		}
		$photo_pic = reset($pic_list);
		if($photo_pic)
		{
			$row['photo_big']=$photo_pic['photo_big'];
			$row['photo_small']=$photo_pic['photo_small'];
		}
		else
		{
			$row['photo_big']=null;
			$row['photo_small']=null;
		}
		unset($pic_list,$photo_pic);
		$content_tmp = cutstr_html($row['content']);
        $row['content']=cutstr_html($row['longtext']);
        if(empty($row['content']))
        {
            $row['content']=$content_tmp;
        }
		//$row['content']=cutstr_html($row['content']);
		$row['dateline']=date("Y-m-d G:i",$row['dateline']);

		$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=middle";
		if($row['voice'])
		{
			$row['voice']=$site_url."/weibo/".$row['voice']."";
		}
		$topic_list[]=$row;
	}


	if(!empty($event_info))
	{


			$sid=$event_info['event_uid'];
			$now_fz_id=$event_info['event_fenzhan_id'];
			

			//分站信息
			/*
			$fenzhan_list=DB::query("select fz_id,starttime,endtime from ".DB::table("fenzhan")." where sid='".$sid."' order by starttime asc ");
			while($row2=DB::fetch($fenzhan_list))
			{
				$fenzhan_data[]=$row2;
			}

			for($i=0; $i<count($fenzhan_data); $i++)
			{
				if($fenzhan_data[$i]['starttime']<time() && $fenzhan_data[$i+1]['starttime']>time())
				{
					$now_fz_id=$fenzhan_data[$i]['fz_id'];
				}
			}
			*/

			//echo $now_fz_id;

			if($now_fz_id)
			{
				$fenzhan=DB::fetch_first("select timepic,starttime,lun from pre_fenzhan where fz_id='".$now_fz_id."' limit 1 ");
				if($fenzhan['timepic'])
				{
					$event_info['event_timepic']=$site_url."/".$fenzhan['timepic'];
					list($width, $height, $type, $attr) = getimagesize($event_info['event_timepic']);
					$event_info['event_timepic_width']=$width;
					$event_info['event_timepic_height']=$height;
				}
				
				//比赛时间
				$days=(time()-$fenzhan['endtime']);

				//如果有分站信息
				//xyx20130614 分站 当前是第二轮是tlcave+tlcave1 as total_score  当前是第三轮是tlcave+tlcave1+tlcave2 as total_score
			
				if($fenzhan['lun']==2)
				{
				  $strlun="tlcave+tlcave1 as total_score";$lnorder=" avcave1+avcave,tlcave,tlcave1  ";
				}
				if($fenzhan['lun']==3)
				{
				  $strlun="tlcave+tlcave1+tlcave2 as total_score";$lnorder=" avcave1+avcave2+avcave,tlcave,tlcave2,tlcave1   ";
				}
				if($fenzhan['lun']==4)
				{
				  $strlun="tlcave+tlcave1+tlcave2+tlcave3 as total_score";$lnorder=" avcave1+avcave2+avcave3+avcave,tlcave,tlcave3,tlcave2,tlcave1   ";
				}
				if($fenzhan['lun']==0||$fenzhan['lun']==1)
				{
				  $strlun="tlcave as total_score";$lnorder=" isend desc,avcave,lin,cave_18,cave_17,cave_16  ";
				}
				 
				if($days>=1)
				{
					$list=DB::query("select uid,realname as username,tlcave as today_score,$strlun,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18,avcave,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18) as lin,isend from ". DB::table('golf_nd_baofen')." where fenz_id='".$now_fz_id."' order by  $lnorder,lin,cave_18,cave_17,cave_16 ");
				}
				else
				{
					$list=DB::query("select uid,realname as username,tlcave as today_score,$strlun,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18,avcave,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18) as lin,isend from ". DB::table('golf_nd_baofen')." where fenz_id='".$now_fz_id."' order by  $lnorder,lin,cave_18,cave_17,cave_16 ");
				}
				
				
				
				/*
				if($days>=1)
				{
					$list=DB::query("select uid,realname as username,tlcave as today_score,tlcave as total_score,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18,avcave,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18) as lin,isend from ". DB::table('golf_nd_baofen')." where fenz_id='".$fz_id."' and tlcave<999 and  cave_1>0 and cave_2>0  and cave_3>0  and cave_4>0  and cave_5>0  and cave_6>0  and cave_7>0  and cave_8>0  and cave_9>0  and cave_10>0  and cave_11>0  and cave_12>0  and cave_13>0  and cave_14>0  and cave_15>0  and cave_16>0  and cave_17>0  and cave_18>0  order by isend desc,avcave,lin,cave_18,cave_17,cave_16 ");
				}
				else
				{
					$list=DB::query("select uid,realname as username,tlcave as today_score,tlcave as total_score,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18,avcave,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18) as lin,isend from ". DB::table('golf_nd_baofen')." where fenz_id='".$fz_id."' order by isend desc,avcave,lin,cave_18,cave_17,cave_16 ");
				}
				*/
			
				
				while($row=DB::fetch($list))
				{
					//print_r($row);
					
					$row['id']="0";
					$row['tianshu']="-1";
					$row['lun_num']="1";

					$row['today_score']=$row['avcave'];
					if($row['today_score']==1000)
					{
						$row['today_score']='-';
					}
					$row['total_score']=Getstat($row['total_score']);
					$row['par']=str_replace(",","|","4,4,3,5,4,4,3,5,4,36,4,4,5,4,4,4,5,3,4,36,72");
					if($row['total_score']<1)
					{
						$row['score_status']="-";
					}
					else
					{
						$row['score_status']="F";
					}
					
					$out=$row['cave_1']+$row['cave_2']+$row['cave_3']+$row['cave_4']+$row['cave_5']+$row['cave_6']+$row['cave_7']+$row['cave_8']+$row['cave_9'];
					$in=$row['cave_10']+$row['cave_11']+$row['cave_12']+$row['cave_13']+$row['cave_14']+$row['cave_15']+$row['cave_16']+$row['cave_17']+$row['cave_18'];
					$total=$out+$in;

					if($row['cave_1']<0||$row['cave_2']<0||$row['cave_3']<0||$row['cave_4']<0||$row['cave_5']<0||$row['cave_6']<0||$row['cave_7']<0||$row['cave_8']<0||$row['cave_9']<0||$row['cave_10']<0||$row['cave_11']<0||$row['cave_12']<0||$row['cave_13']<0||$row['cave_14']<0||$row['cave_15']<0||$row['cave_16']<0||$row['cave_17']<0||$row['cave_18']<0)
					{
						$row['score']="0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0";
					}
					else
					{
						$row['score']=$row['cave_1']."|".$row['cave_2']."|".$row['cave_3']."|".$row['cave_4']."|".$row['cave_5']."|".$row['cave_6']."|".$row['cave_7']."|".$row['cave_8']."|".$row['cave_9']."|".$row['cave_10']."|".$row['cave_11']."|".$row['cave_12']."|".$row['cave_13']."|".$row['cave_14']."|".$row['cave_15']."|".$row['cave_16']."|".$row['cave_17']."|".$row['cave_18'];
					}
					$row['score_sub']=explode("|",$row['score']);
					$row['par_sub']=explode("|",$row['par']);

					unset($row['cave_1']);
					unset($row['cave_2']);
					unset($row['cave_3']);
					unset($row['cave_4']);
					unset($row['cave_5']);
					unset($row['cave_6']);
					unset($row['cave_7']);
					unset($row['cave_8']);
					unset($row['cave_9']);
					unset($row['cave_10']);
					unset($row['cave_11']);
					unset($row['cave_12']);
					unset($row['cave_13']);
					unset($row['cave_14']);
					unset($row['cave_15']);
					unset($row['cave_16']);
					unset($row['cave_17']);
					unset($row['cave_18']);
					
					$total_chengji=$total_chengji+$row['avcave'];
				
					$gscore[] = $row;
				}
				
				
				//print_r($now_fz_id);
				
				
				//如果成绩都为0，重新排序
				if(!$total_chengji)
				{
					$gscore=array_sort_by_field($gscore,'team_num',true);
				}
				
				
				
				
				
			}
			else
			{

				//则显示所有成绩卡

					//最大轮数
					$lun_num = DB::result_first("select max(lun) from ".DB::table('common_score')."  where sais_id=$sid and uid >0 and total_score>60  limit 1 ");
					//print_r($query);
				  
			
					 $query = DB::query(" SELECT id,uid,lun,total_score,zong_score,score,par,tianshu FROM (select id,uid,lun,total_score,zong_score,score,par,to_days(FROM_UNIXTIME(dateline))-to_days(now()) as tianshu from ".DB::table('common_score')." where sais_id =$sid and uid >0 and total_score>60 order by lun desc,zong_score asc ,tianshu asc) as t2 group by uid order by lun desc,zong_score asc ,tianshu asc  limit 0,$limit");
					 
					 

					$i=0;
					while($row = DB::fetch($query))
					{
						$zongbiaogan=0;
						for($j=1; $j<=$lun_num; $j++)
						{
			
							$lun_info = DB::fetch_first("select id,sais_id,uid,total_score,score,par, to_days(FROM_UNIXTIME(dateline))-to_days(now()) as tianshu from ".DB::table('common_score')." where sais_id=$sid and uid='".$row['uid']."' and lun='".$j."' and total_score>60 order by dateline asc   limit 1 ");
							$zongbiaogan=$zongbiaogan+(end(explode("|",$lun_info['par'])));

							//print_r($lun_info);
							if($j==1)
							{
								$lun_1=$lun_info['total_score'];
							}
							if($j==2)
							{
								$lun_2=$lun_info['total_score'];
							}
							if($j==3)
							{
								$lun_3=$lun_info['total_score'];
							}
							if($j==4)
							{
								$lun_4=$lun_info['total_score'];
							}

						}

						
						$row['zong_score']=($lun_1)+($lun_2)+($lun_3)+($lun_4);
						//$res=DB::query("update ".DB::table("common_score")." set zong_score='".$row['zong_score']."' where uid='".$row ['uid']."' and sais_id='".$row ['sais_id']."'  ");

						$row['lun_1']=$lun_1;
						$row['lun_2']=$lun_2;
						$row['lun_3']=$lun_3;
						$row['lun_4']=$lun_4;
						if(!$lun_1)
						{
							$row['lun_1']='-';
						}
						if(!$lun_2)
						{
							$row['lun_2']='-';
						}
						if(!$lun_3)
						{
							$row['lun_3']='-';
						}
						if(!$lun_4)
						{
							$row['lun_4']='-';
						}

						$row['today_score']='"'.(end(explode("|",$row['score']))-end(explode("|",$row['par']))).'"';
						$row['total_score']=(string)$row['zong_score'];
						if($row['total_score']==1000)
						{
							$row['total_score']='-';
						}
						//$row['score_status']="F/".$row['lun'];
						$row['score_status']="F";

						$s_arr=explode("|",$row['score']);
						unset($s_arr[9]);
						unset($s_arr[19]);
						unset($s_arr[20]);
						$str_new=implode("|",$s_arr);
						$arr_new=explode("|",$str_new);
						
						$row['score_sub']=$arr_new;
						
						$row['username'] =  gettruename($row['uid']);
						
						$gscore[] = $row; 
					}
					$i++;
					
				}

				//print_r($gscore);
				if($gscore)
				{
					$i=1;
					foreach ($gscore as $key => $value )
					{ 
						
						//$gscore [$key] ['order'] = '"'.$i++.'"';
						if($gscore [$key] ['lun']!=$lun_num)
						{
							$gscore [$key] ['order'] = "CUT";  	
						}
						else
						{
							$gscore [$key] ['order'] = "".$i++."";  
						}
						
					}
				}
						
						//重新排序
						//$gscore=array_sort_by_field($gscore, 'zong_score',true);
						
				if(empty($gscore))
				{
					$gscore=null;
				}
						
					
				/*接口返回的参数*/
				$response         = 0;
				$error_state      = 0;
				$data['title']    = "scorerank";
				$data['data'] = array(
									'sid'=>$event_info['event_uid'], 
									'realname'=>$event_info['event_name'], 
									'event_id'=>$event_info['event_id'],
									'event_fenzhan_id'=>$event_info['event_fenzhan_id'],
									'lun'=>$lun,
									'lun_num'=>$lun_num,
									'event_pic'=>$event_info['event_timepic'],
									'event_logo'=>$event_info['event_logo'],
									'event_pic_width'=>$event_info['event_timepic_width'],
									'event_pic_height'=>$event_info['event_timepic_height'],
									'event_content'=>str_replace('src="','src="'.$site_url.'/',$event_info['event_content']),
									'event_is_baoming'=>$event_info['event_is_baoming'],
									'event_baoming_state'=>$event_info['event_baoming_state'],
									'event_baoming_pic'=>$event_info['event_baoming_pic'],
									'score_list'=>$gscore,
									'topic_list'=>$topic_list,
									 );

				//print_r($data);
				api_json_result(1,0,$api_error['card']['10020'],$data);


		}//if no event_info
		else
		{
				$data['title']   = "scorerank";
				$data['data'] = null;
				api_json_result(1,1,"该赛事不存在或已被删除",$data);
		}

	
}


//分站详细页
if($ac=="fenzhan_detail")
{
	$fz_id=$_G['gp_fz_id'];
	$source=$_G['gp_source'];
	$login_uid=$_G['gp_login_uid'];
	$pic_width=$_G['gp_pic_width'];

	$fenzhan=DB::fetch_first("select * from ".DB::table("fenzhan")." where fz_id='".$fz_id."' ");
	$event_info=DB::fetch_first("select event_id,event_name,event_uid,event_logo,event_timepic,event_starttime,event_endtime,event_content,event_state,event_is_tj,event_is_baoming,event_addtime from tbl_event where event_uid='".$fenzhan['sid']."' order by event_addtime desc limit 1 ");
	if($event_info['event_logo'])
	{
		$event_info['event_logo']=$site_url."/".$event_info['event_logo'];
	}
	if($event_info['event_timepic'])
	{
		if($fenzhan['timepic'])
		{
			$event_info['event_timepic']=$fenzhan['timepic'];
		}
		$event_info['event_timepic']=$site_url."/".$event_info['event_timepic'];
		list($width, $height, $type, $attr) = getimagesize($event_info['event_timepic']);
		$event_info['event_timepic_width']=$width;
		$event_info['event_timepic_height']=$height;
	}

	if($event_info['event_content'])
	{
		$event_info['event_content']=str_replace("http://192.168.1.151:806","",$event_info['event_content']);
	}
	if($pic_width)
	{
		$event_info['event_content']=str_replace("<img ","<img width=\"".$pic_width."\" ",$event_info['event_content']);
	}
	
	
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
				$event_info['event_baoming_pic']="";	
			}
		}
		else
		{
			$event_info['event_baoming_state']="0";
			$event_info['event_baoming_pic']="";
		}
		
	}

	if($source=='waika')
	{
		$event_info['event_is_baoming']='N';
		//分站外卡成绩列表
		$lun_num = DB::result_first("select max(lun) from ".DB::table('common_score')."  where fz_id='$fz_id' and source='".$source."' and uid >0 and total_score>60  limit 1 ");
		$query = DB::query("select id,uid,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_score").".uid) as username,lun,total_score,score,par,to_days(FROM_UNIXTIME(dateline))-to_days(now()) as tianshu from ".DB::table('common_score')."  where fz_id='$fz_id' and source='".$source."' and uid >0 and total_score>60 group by uid order by lun desc,total_score asc,tianshu asc limit 0,$limit");

		$i=0;
		while($row = DB::fetch($query))
		{
			$zongbiaogan=0;
			for($j=1; $j<=$lun_num; $j++)
			{
				$lun_info = DB::fetch_first("select id,sais_id,uid,total_score,score,par, to_days(FROM_UNIXTIME(dateline))-to_days(now()) as tianshu from ".DB::table('common_score')." where fz_id='$fz_id' and source='".$source."' and uid='".$row['uid']."' and total_score>60  limit 1 ");
				$zongbiaogan=$zongbiaogan+(end(explode("|",$lun_info['par'])));

				if($j==1)
				{
					$lun_1=$lun_info['total_score'];
				}
				if($j==2)
				{
					$lun_2=$lun_info['total_score'];
				}
				if($j==3)
				{
					$lun_3=$lun_info['total_score'];
				}
				
			}

			
			$row['zong_score']=($lun_1)+($lun_2)+($lun_3)+($lun_4);

			$row['lun_1']=$lun_1;
			$row['lun_2']=$lun_2;
			$row['lun_3']=$lun_3;
			$row['lun_4']=$lun_4;

			$row['today_score']='"'.(end(explode("|",$row['score']))-end(explode("|",$row['par']))).'"';
			$row['total_score']=(string)$row['today_score'];
			$row['score_status']="F";

			$s_arr=explode("|",$row['score']);
			unset($s_arr[9]);
			unset($s_arr[19]);
			unset($s_arr[20]);
			$str_new=implode("|",$s_arr);
			$arr_new=explode("|",$str_new);
			
			$row['score_sub']=$arr_new;
			
			$gscore[] = $row; 
		}
		$i++;
		
	}
	else
	{
		$lun_num=1;
		
		//比赛时间
		$days=(time()-$fenzhan['starttime'])/(24*3600);
		if($days>=1)
		{
			$list=DB::query("select uid,realname as username,tlcave as today_score,tlcave as total_score,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18,avcave,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18) as lin,isend from ". DB::table('golf_nd_baofen')." where fenz_id='".$fz_id."' and tlcave<999 and  cave_1>0 and cave_2>0  and cave_3>0  and cave_4>0  and cave_5>0  and cave_6>0  and cave_7>0  and cave_8>0  and cave_9>0  and cave_10>0  and cave_11>0  and cave_12>0  and cave_13>0  and cave_14>0  and cave_15>0  and cave_16>0  and cave_17>0  and cave_18>0  order by isend desc,avcave,lin,cave_18,cave_17,cave_16 ");
		}
		else
		{
			$list=DB::query("select uid,realname as username,tlcave as today_score,tlcave as total_score,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18,avcave,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18) as lin,isend from ". DB::table('golf_nd_baofen')." where fenz_id='".$fz_id."' order by isend desc,avcave,lin,cave_18,cave_17,cave_16 ");
		}
		
		////分站N洞成绩列表
		$list=DB::query("select uid,realname as username,tlcave as today_score,tlcave as total_score,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18,avcave,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18) as lin,isend from ". DB::table('golf_nd_baofen')." where fenz_id='".$fz_id."' order by isend desc,avcave,lin,cave_18,cave_17,cave_16 ");
		while($row=DB::fetch($list))
		{
			$row['id']="0";
			$row['tianshu']="-1";
			$row['lun_num']="1";

			$row['today_score']=$row['avcave'];
			if($row['today_score']==1000)
			{
				$row['today_score']='-';
			}
			
			$row['total_score']=Getstat($row['total_score']);
			$row['par']=str_replace(",","|","4,4,3,5,4,4,3,5,4,36,4,4,5,4,4,4,5,3,4,36,72");
			$row['score_status']="F";
			$out=$row['cave_1']+$row['cave_2']+$row['cave_3']+$row['cave_4']+$row['cave_5']+$row['cave_6']+$row['cave_7']+$row['cave_8']+$row['cave_9'];
			$in=$row['cave_10']+$row['cave_11']+$row['cave_12']+$row['cave_13']+$row['cave_14']+$row['cave_15']+$row['cave_16']+$row['cave_17']+$row['cave_18'];
			$total=$out+$in;

			//$row['score']=$row['cave_1']."|".$row['cave_2']."|".$row['cave_3']."|".$row['cave_4']."|".$row['cave_5']."|".$row['cave_6']."|".$row['cave_7']."|".$row['cave_8']."|".$row['cave_9']."|".$out."|".$row['cave_10']."|".$row['cave_11']."|".$row['cave_12']."|".$row['cave_13']."|".$row['cave_14']."|".$row['cave_15']."|".$row['cave_16']."|".$row['cave_17']."|".$row['cave_18']."|".$in."|".$total;
			$if_dawan=0;
			if($row['cave_1']<0)
			{
				$if_dawan=$if_dawan-1;
				$row['cave_1']=0;
			}
			if($row['cave_2']<0)
			{
				$if_dawan=$if_dawan-1;
				$row['cave_2']=0;
			}
			if($row['cave_3']<0)
			{
				$if_dawan=$if_dawan-1;
				$row['cave_3']=0;
			}
			if($row['cave_4']<0)
			{
				$if_dawan=$if_dawan-1;
				$row['cave_4']=0;
			}
			if($row['cave_5']<0)
			{
				$if_dawan=$if_dawan-1;
				$row['cave_5']=0;
			}
			if($row['cave_6']<0)
			{
				$if_dawan=$if_dawan-1;
				$row['cave_6']=0;
			}
			if($row['cave_7']<0)
			{
				$if_dawan=$if_dawan-1;
				$row['cave_7']=0;
			}if($row['cave_8']<0)
			{
				$if_dawan=$if_dawan-1;
				$row['cave_8']=0;
			}
			if($row['cave_9']<0)
			{
				$if_dawan=$if_dawan-1;
				$row['cave_9']=0;
			}
			if($row['cave_10']<0)
			{
				$if_dawan=$if_dawan-1;
				$row['cave_10']=0;
			}
			if($row['cave_11']<0)
			{
				$if_dawan=$if_dawan-1;
				$row['cave_11']=0;
			}
			if($row['cave_12']<0)
			{
				$if_dawan=$if_dawan-1;
				$row['cave_12']=0;
			}
			if($row['cave_13']<0)
			{
				$if_dawan=$if_dawan-1;
				$row['cave_13']=0;
			}
			if($row['cave_14']<0)
			{
				$if_dawan=$if_dawan-1;
				$row['cave_14']=0;
			}
			if($row['cave_15']<0)
			{
				$if_dawan=$if_dawan-1;
				$row['cave_15']=0;
			}
			if($row['cave_16']<0)
			{
				$if_dawan=$if_dawan-1;
				$row['cave_16']=0;
			}
			if($row['cave_17']<0)
			{
				$if_dawan=$if_dawan-1;
				$row['cave_17']=0;
			}
			if($row['cave_18']<0)
			{
				$if_dawan=$if_dawan-1;
				$row['cave_18']=0;
			}
			if($if_dawan<0)
			{
				$row['today_score']="RTD";
			}
			
			$row['score']=$row['cave_1']."|".$row['cave_2']."|".$row['cave_3']."|".$row['cave_4']."|".$row['cave_5']."|".$row['cave_6']."|".$row['cave_7']."|".$row['cave_8']."|".$row['cave_9']."|".$row['cave_10']."|".$row['cave_11']."|".$row['cave_12']."|".$row['cave_13']."|".$row['cave_14']."|".$row['cave_15']."|".$row['cave_16']."|".$row['cave_17']."|".$row['cave_18'];

			$row['score_sub']=explode("|",$row['score']);
			$row['par_sub']=explode("|",$row['par']);

			unset($row['cave_1']);
			unset($row['cave_2']);
			unset($row['cave_3']);
			unset($row['cave_4']);
			unset($row['cave_5']);
			unset($row['cave_6']);
			unset($row['cave_7']);
			unset($row['cave_8']);
			unset($row['cave_9']);
			unset($row['cave_10']);
			unset($row['cave_11']);
			unset($row['cave_12']);
			unset($row['cave_13']);
			unset($row['cave_14']);
			unset($row['cave_15']);
			unset($row['cave_16']);
			unset($row['cave_17']);
			unset($row['cave_18']);
		
			$gscore[] = $row;
		}
	}

	/*
	$query = DB::query("select id,uid,total_score,score,par, to_days(FROM_UNIXTIME(dateline))-to_days(now()) as tianshu from ".DB::table('common_score')."  where fz_id ='".$fz_id."' and uid >0 and total_score>60 group by uid order by total_score asc limit 0,200");

	$i=0;
	while($row = DB::fetch($query))
	{
		
		$row['today_score']=(string)((end(explode("|",$row['score']))-end(explode("|",$row['par']))));
		$row['score_status']="F";

		$s_arr=explode("|",$row['score']);
		unset($s_arr[9]);
		unset($s_arr[19]);
		unset($s_arr[20]);
		$str_new=implode("|",$s_arr);
		$arr_new=explode("|",$str_new);
		
		$row['score_sub']=$arr_new;
		
		$gscore[] = $row; 
		$i++;
	}
	*/

	//print_r($gscore);
	if($gscore)
	{
		$i=1;
		foreach ($gscore as $key => $value )
		{ 
				//$gscore [$key] ['username'] =  gettruename($gscore [$key] ['uid']);
				//$gscore [$key] ['order'] = '"'.$i++.'"';
				$gscore [$key] ['order'] = "".$i++."";  
		}
	}
	
	
	//微博列表
	$saishi_name=$event_info['event_name'];
	$list=DB::query("select tid,uid, (select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid)  as username,(select `longtext` from jishigou_topic_longtext where tid=jishigou_topic.tid limit 1) as `longtext`,content,replys,forwards,dateline,imageid,voice,voice_timelong from jishigou_topic where type<>'reply' and content like '%".$saishi_name."%' order by dateline desc limit 5 ");
	while($row = DB::fetch($list) )
	{
		$imageids_arr = explode(',',$row['imageid']);
					
		$pic_ids = implode("','",$imageids_arr);
		unset($imageids_arr);
		$topic_img_rs = DB::query("select id,photo from jishigou_topic_image where id in('{$pic_ids}')");
		unset($pic_ids);
		
		$pic_i=0;
		while($pic_row = DB::fetch($topic_img_rs) ){
			$pic_list[$pic_i]['photo_big'] = $site_url."/weibo/".$pic_row['photo'];
			$pic_list[$pic_i]['photo_small'] = $site_url."/weibo/".str_replace("_o","_s",$pic_row['photo']);
			$pic_i++;
		}
		unset($topic_img_rs,$pic_i,$pic_row);
		if(!empty($pic_list)) {
			$row['pic_list'] = $pic_list;
		}else{
			$row['pic_list'] = '';
		}
		$photo_pic = reset($pic_list);
		if($photo_pic)
		{
			$row['photo_big']=$photo_pic['photo_big'];
			$row['photo_small']=$photo_pic['photo_small'];
		}
		else
		{
			$row['photo_big']=null;
			$row['photo_small']=null;
		}
		unset($pic_list,$photo_pic);
		$content_tmp = cutstr_html($row['content']);
        $row['content']=cutstr_html($row['longtext']);
        if(empty($row['content'])) 
        {
            $row['content']=$content_tmp;
        }
		//$row['content']=cutstr_html($row['content']);
		$row['dateline']=date("Y-m-d G:i",$row['dateline']);

		$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=middle";
		if($row['voice'])
		{
			$row['voice']=$site_url."/weibo/".$row['voice']."";
		}
		$topic_list[]=$row;
	}

	/*接口返回的参数*/
	$response         = 0;
	$error_state      = 0;
	$data['title']    = "scorerank";
	$data['data'] = array(
					'sid'=>$event_info['event_uid'], 
					'realname'=>$event_info['event_name'], 
					'event_id'=>$event_info['event_id'],
					'event_fenzhan_id'=>$event_info['event_fenzhan_id'],
					'lun'=>$lun,
					'lun_num'=>$lun_num,
					'event_pic'=>$event_info['event_timepic'],
					'event_logo'=>$event_info['event_logo'],
					'event_pic_width'=>$event_info['event_timepic_width'],
					'event_pic_height'=>$event_info['event_timepic_height'],
					'event_content'=>str_replace('src="','src="'.$site_url.'/',$event_info['event_content']),
					'event_is_baoming'=>$event_info['event_is_baoming'],
					'event_baoming_state'=>$event_info['event_baoming_state'],
					'event_baoming_pic'=>$event_info['event_baoming_pic'],
					'score_list'=>$gscore,
					'topic_list'=>$topic_list,
					 );

	//print_r($data);
	api_json_result(1,0,$api_error['card']['10020'],$data);
		

}



//分站列表
if($ac=="fenzhan_list")
{
	$sid=$_G['gp_sid'];
	$year=$_G['gp_year'];
	$list=DB::query("select fz_id,sid,fenz_name,starttime,endtime,field_id,(select fieldname from ".DB::table("common_field")." where uid=".DB::table("fenzhan").".field_id ) as field_name from ".DB::table("fenzhan")." where sid='".$sid."' and year='".$year."' ");
	$i=0;
	while($row=DB::fetch($list))
	{
		$i=$i+1;
		if($row['field_name']==null)
		{
			if($row['field_id']=="3802780")
			{
				$row['field_name']="大溪谷高尔夫俱乐部";
			}
			else
			{
				$row['field_name']="";
			}
			
		}

		$row['pic']=$site_url."/images/lx/q".$i.".png";
		$row['starttime']=date("Y-m-d",$row['starttime']);
		$row['endtime']=date("Y-m-d",$row['endtime']);
		$list_data[]=$row;
	}
	$data['title']="list_data";
	$data['data']=$list_data;
	if(!empty($list_data))
	{
		api_json_result(1,0,$api_error['card']['10020'],$data);
	}
	else
	{
		api_json_result(1,1,"还没有分站",$data);
	}

}

//显示DQ RTD
function Getstat($tlcave)
{
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
			$dataInfo = "";
			break;
	}
	
	return $dataInfo;
}
function gettruename($uuid)
{
	$username = DB::result_first( "select realname  from " . DB::table ( 'common_member_profile' ) . "  where uid='$uuid' ");
	return $username ;
}

?>