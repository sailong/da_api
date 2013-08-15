<?php
/*
*
* event_api.php
* by zhanglong 2013-05-21
* field app 赛事相关
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



$hot_2013district=array(
                 'tj511'=>'5/11天津',
                 'gz524'=>'5/24广州',
                 'sz531'=>'5/31深圳',
                 'hz615'=>'6/15杭州',
                 'sh621'=>'6/21上海',
                 'cs629'=>'6/29长沙',
                 'bj719'=>'7/19北京',
                 'dl726'=>'7/26大连',
                 'zz89'=>'8/9郑州',
                 'cd824'=>'8/24成都',
                 'sz830'=>'8/30苏州',
                 'fz97'=>'9/7福州',);





//echo time()+86400*15;


//赛事直播  选择赛事
if($ac=="select_event")
{
	$field_uid=$_G['gp_field_uid'];
	$list3=DB::query("select event_id,event_name,event_uid,event_is_zhutui,event_zhutui_pic,event_content,event_starttime,event_go_action,event_go_value from tbl_event where event_is_zhutui='Y' and field_uid='".$field_uid."'  order by event_sort desc limit 1 ");
	while($row3 = DB::fetch($list3))
	{
		if($row3['event_zhutui_pic'])
		{
			$row3['event_zhutui_pic']=$site_url."/".$row3['event_zhutui_pic'];
		}
		$row3['event_pic']=$site_url."/uc_server/avatar.php?uid=".$row['event_uid']."&size=big";
		$row3['uid']=$row3['event_uid'];
		$row3['event_starttime']=date("Y年m月d日",$row3['event_starttime']);
		$row3['event_content']=msubstr(cutstr_html($row3['event_content']),0,30);
		$list_data3[]=array_default_value($row3);
	}

	$list=DB::query("select event_id,event_name,event_uid,event_is_zhutui,event_content,event_starttime,event_go_action,event_go_value,event_logo from tbl_event where event_is_tj='Y' and field_uid='".$field_uid."' order by event_sort desc limit 100 ");
	while($row = DB::fetch($list))
	{
		$row['event_pic']=$site_url."/uc_server/avatar.php?uid=".$row['event_uid']."&size=big";
		if($row['event_logo'])
		{
			$row['event_logo']=$site_url."/".$row['event_logo'];
		}
		$row['uid']=$row['event_uid'];
		$row['event_starttime']=date("Y年m月d日",$row['event_starttime']);
		$row['event_content']=msubstr(cutstr_html($row['event_content']),0,30);
		$list_data[]=array_default_value($row);
	}


    if(empty($list_data3)) {
        $list_data3 = null;
    }
    if(empty($list_data)) {
        $list_data = null;
    }
	$data['title']		= "list_data";
	$data['data']=array(
	  'zhutui_list'=>$list_data3,
	  'ing_list'=>$list_data,
	);
	
	//print_r($data);
	api_json_result(1,0,$app_error['event']['10502'],$data);

	
}




//赛事报名   正在报名的比赛
if($ac=="apply_ing")
{
	$login_uid=$_G['gp_login_uid'];
	$field_uid=$_G['gp_field_uid'];
	
	$list=DB::query("select event_id,event_name,event_uid,event_is_zhutui,event_content,event_starttime,event_go_action,event_go_value,event_logo from tbl_event where event_baoming_starttime<=".time()." and event_baoming_endtime>=".time()." and field_uid='".$field_uid."' order by event_baoming_starttime desc  limit 100 ");
	while($row = DB::fetch($list))
	{
		if($login_uid)
		{
			$bm=DB::fetch_first("select event_apply_id,code_pic from tbl_event_apply where uid='".$login_uid."' and event_apply_state=1 ");
			//print_r($bm);
			if($bm['event_apply_id'])
			{
				$row['event_baoming_state']=$bm['bm_id'];
				if($bm['code_pic'])
				{
					$row['event_baoming_pic']=$site_url."".$bm['code_pic'];
				}
				else
				{
					/*
					//如果没有就生成二维码
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
					$txt_data=$bm['bm_id'];
					$pic_filename=$full_save_path.$bm['bm_id'].".png";
					$errorCorrectionLevel = "L";
					$matrixPointSize=9;
					$margin=1;
					QRcode::png($txt_data, $pic_filename, $errorCorrectionLevel, $matrixPointSize, $margin); 
					
					if(file_exists($pic_filename))
					{
						$row['event_baoming_pic']=$site_url."".$pic_filename;
						$res=DB::query("update  ".DB::table("home_dazbm")." set code_pic='".$pic_filename."' where  bm_id='".$bm['bm_id']."' ");
					}
					else
					{
						$row['event_baoming_pic']="";
						//echo "文件生成失败";
					}
					*/
					$row['event_baoming_pic']="";
					
				}


			}
			else
			{
				$row['event_baoming_state']="0";
				$row['event_baoming_pic']="";
			}

		}
	
		$row['event_pic']=$site_url."/uc_server/avatar.php?uid=".$row['event_uid']."&size=big";
		if($row['event_logo'])
		{
			$row['event_logo']=$site_url."/".$row['event_logo'];
		}
		$row['uid']=$row['event_uid'];
		$row['event_starttime']=date("Y年m月d日",$row['event_starttime']);
		$row['event_content']=msubstr(cutstr_html($row['event_content']),0,30);
		$list_data[]=array_default_value($row);
	}

    if(empty($list_data)) {
        $list_data = null;
    }
		$data['title']		= "list_data";
		$data['data']		= $list_data;
	if($list_data)
	{
		api_json_result(1,0,$app_error['event']['10502'],$data);
	}
	else
	{
		api_json_result(1,0,"no data",$data);
	}
}


//索取门票  选择所有赛事
if($ac=="select_event_all")
{
	$field_uid=$_G['gp_field_uid'];
	
	$list=DB::query("select event_id,event_name,event_uid,event_is_zhutui,event_content,event_starttime,event_logo from tbl_event where field_uid='".$field_uid."' order by event_sort desc limit 100 ");
	while($row = DB::fetch($list))
	{
		$row['event_pic']=$site_url."/".$row['event_logo'];
		$row['uid']=$row['event_uid'];
		$row['event_starttime']=date("Y年m月d日",$row['event_starttime']);
		$row['event_content']=msubstr(cutstr_html($row['event_content']),0,30);
		$list_data[]=array_default_value($row);
	}

	if($list_data)
	{
		$data['title']		= "list_data";
		$data['data']=array(
		  'all_list'=>$list_data,
		);
		//print_r($data);
		api_json_result(1,0,$app_error['event']['10502'],$data);
	}
	else
	{
		api_json_result(1,0,"no data",$data);
	}
	
	
}

//相关赛事的门票列表
if($ac=="event_ticket_list"){
	$event_id = $_G['gp_event_id'];
	if(empty($event_id))
	{
		api_json_result(1,1,"缺少参数event_id",$data);exit;
	}
	//,ticket_price,ticket_ren_num,ticket_num,ticket_pic,ticket_starttime,ticket_endtime,ticket_type,ticket_times,ticket_content,ticket_addtime
	$list=DB::query("select ticket_id,ticket_name,ticket_type from tbl_ticket where event_id='".$event_id."' order by ticket_id desc limit 100 ");
	while($row = DB::fetch($list))
	{
		/* $row['ticket_pic']=$site_url."/".$row['ticket_pic'];
		$row['ticket_starttime']=date("Y年m月d日",$row['ticket_starttime']);
		$row['ticket_endtime']=date("Y年m月d日",$row['ticket_endtime']);
		$row['ticket_content']=msubstr(cutstr_html($row['ticket_content']),0,30); */
		if(in_array($row['ticket_type'],array('VIP'))){
			$row['company_flag']='Y';
		}else{
			$row['company_flag']='N';
		}
		$list_data[]=array_default_value($row);
	}
	unset($list); 
	if($list_data)
	{
		$data['title'] = "ticket_list";
		$data['data']=$list_data;
		//print_r($data);
		api_json_result(1,0,'门票列表',$data);
	}
	else
	{
		api_json_result(1,0,"no data",$data);
	}
}

//我的门票列表
if($ac=="my_ticket_list")
{
	$uid = $_G['gp_uid'];
	if(empty($uid)){
		api_json_result(1,1,"缺少参数uid",$data);
	}
	$list=DB::query("select ticket_id,ticket_type,user_ticket_code,user_ticket_codepic,user_ticket_nums,user_ticket_realname,user_ticket_sex,user_ticket_age,user_ticket_address,user_ticket_cardtype,user_ticket_card,user_ticket_mobile,user_ticket_imei,user_ticket_company,user_ticket_company_post,user_ticket_status,user_ticket_addtime from tbl_user_ticket where uid='".$uid."' order by user_ticket_addtime desc limit 100 ");
	while($row = DB::fetch($list))
	{
		$row['user_ticket_codepic']=$site_url."/".$row['user_ticket_codepic'];
		$row['user_ticket_addtime']=date("Y年m月d日",$row['user_ticket_addtime']);
		$list_data[]=array_default_value($row);
	}
	unset($list);
	if($list_data)
	{
		$data['title'] = "list_data";
		$data['data']=array(
		  'all_list'=>$list_data,
		);
		//print_r($data);
		api_json_result(1,0,'我的门票列表',$data);
	}
	else
	{
		api_json_result(1,0,"no data",$data);
	}
}

//查看门票信息
if($ac=="ticket_detail")
{
	$ticket = $_G['gp_ticket_id'];
	if(empty($ticket)){
		api_json_result(1,1,"缺少参赛ticket_id",$data);exit;
	}
	$detail_data=DB::fetch_first("select ticket_id,ticket_name,ticket_price,ticket_ren_num,ticket_num,ticket_pic,ticket_starttime,ticket_endtime,ticket_type,ticket_times,ticket_content,ticket_addtime from tbl_ticket where ticket_id='".$ticket."'");
	$detail_data['ticket_pic']=$site_url."/".$detail_data['ticket_pic'];
	$detail_data['ticket_starttime']=date("Y年m月d日",$detail_data['ticket_starttime']);
	$detail_data['ticket_endtime']=date("Y年m月d日",$detail_data['ticket_endtime']);
	$detail_data['ticket_content']=msubstr(cutstr_html($detail_data['ticket_content']),0,30);
	if($detail_data)
	{
		$data['title'] = "data_detail";
		$data['data']=$detail_data;
		//print_r($data);
		api_json_result(1,0,'门票详情',$data);
	}
	else
	{
		api_json_result(1,0,"no data",$data);
	}
}


//赛事具体信息
if($ac=="event_detail")
{
	$event_id=$_G['gp_event_id'];
	if($event_id)
	{
		$detail_data=DB::fetch_first("select event_id,event_name,event_content,event_is_zhutui,event_uid from tbl_event where event_id='".$event_id."'  ");
		if($detail_data)
		{
			
			$detail_data['event_pic']=$site_url."/uc_server/avatar.php?uid=".$detail_data['event_uid']."&size=middle";
			$detail_data['event_content']=cutstr_html($detail_data['event_content']);
			$tag=$detail_data['event_name'];

			$total=DB::result_first("select count(tid) from jishigou_topic  where type<>'reply' and content like '%".$tag."%' ");
			$max_page=intval($total/$page_size);
			if($max_page<$total/$page_size)
			{
				$max_page=$max_page+1;
			}
			if($max_page>=$page)
			{

				$list=DB::query("select tid,uid,roottid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,replys,forwards,dateline,(select photo from jishigou_topic_image where tid=jishigou_topic.tid limit 1) as photo,voice,voice_timelong from jishigou_topic where type<>'reply' and content like '%".$tag."%' order by dateline desc limit $page_start,$page_size ");
				while($row = DB::fetch($list) )
				{
					if($row['photo'])
					{
						$row['photo_big']=$site_url."/weibo/".$row['photo'];
						$row['photo_small']=$site_url."/weibo/".str_replace("_o","_s",$row['photo']);
					}
					else
					{
						$row['photo_big']=null;
						$row['photo_small']=null;
					}
					unset($row['photo']);

					$row['content']=cutstr_html($row['content'].$row['content2']);
					$row['dateline']=date("Y-m-d G:i",$row['dateline']);
					
					$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";
					if($row['voice'])
					{
						$row['voice']=$site_url."/weibo/".$row['voice']."";
					}

					//根topic
					$root_topic=DB::fetch_first("select tid,uid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,replys,forwards,dateline,(select photo from jishigou_topic_image where tid=jishigou_topic.tid limit 1) as photo,voice,voice_timelong from jishigou_topic where tid='".$row['roottid']."' order by dateline asc ");
					if($root_topic)
					{
						if($root_topic['photo'])
						{
							$root_topic['photo_big']=$site_url."/weibo/".$root_topic['photo'];
							$root_topic['photo_small']=$site_url."/weibo/".str_replace("_o","_s",$root_topic['photo']);
						}
						else
						{
							$root_topic['photo_big']=null;
							$root_topic['photo_small']=null;
						}
						unset($root_topic['photo']);
						$root_topic['content']=cutstr_html($root_topic['content'].$root_topic['content2']);
						$root_topic['dateline']=date("Y-m-d",$root_topic['dateline']);
						$root_topic['touxiang']=$site_url."/uc_server/avatar.php?uid=".$root_topic['uid']."&size=small";
						if($root_topic['voice'])
						{
							$root_topic['voice']=$site_url."/weibo/".$root_topic['voice']."";
						}
						$row['root_topic']=empty($root_topic) ? null : array_default_value($root_topic);
                        $row['root_topic'] = check_field_to_relace($row['root_topic'],array('replys'=>'0','forwards'=>'0'));
					}
					else
					{
						$row['root_topic']="";
					}
                    
					$list_data[]=array_default_value($row);
				}

			}//end page
            
    		if(empty($detail_data)) {
                $detail_data = null;
            }
    		if(empty($list_data)) {
                $list_data = null;
            }
			$data['title']		= "detail_data";
			$data['data']=array(
				'detail_info'=>array_default_value($detail_data),
				'topic_list'=>$list_data,
			);
			
			api_json_result(1,0,$app_error['event']['10502'],$data);
		}
		else
		{
			api_json_result(1,1,"没有数据",$data);
		}

	}
}





















//赛事报名 页面接口
if($ac=="event_baoming")
{
	$event_id=$_G['gp_event_id'];
	$fenzhan=DB::query("select fenzhan_name,fenzhan_id from tbl_fenzhan where event_id='".$event_id."' ");
	while($row=DB::fetch($fenzhan))
	{
		$fenzhan_arr[]=$row['fenzhan_name'];
	}
	$fenzhan_arr_new=array_values($fenzhan_arr);
	//print_r($fenzhan_arr_new);
	
	
	$list_data[0]['name']="event_apply_realname";
	$list_data[0]['name_cn']="姓　　名";
	$list_data[0]['type']="input";
	$list_data[0]['max_size']="50";

	$list_data[1]['name']="event_apply_sex";
	$list_data[1]['name_cn']="姓　　别";
	$list_data[1]['type']="radio";
	$list_data[1]['type_more']=array('男','女');
	$list_data[1]['max_size']="50";

	
	$list_data[2]['name']="event_apply_card";
	$list_data[2]['name_cn']="身份证号";
	$list_data[2]['type']="input";
	$list_data[2]['max_size']="50";

	$list_data[3]['name']="event_apply_chadian";
	$list_data[3]['name_cn']="差　　点";
	$list_data[3]['type']="input";
	$list_data[3]['max_size']="50";
	
	/*
	$list_data[4]['name']="event_apply_fenzhan";
	$list_data[4]['name_cn']="分　　站";
	$list_data[4]['type']="radio";
	$list_data[4]['type_more']=$fenzhan_arr_new;
	$list_data[4]['max_size']="50";
	*/
	
	/*
	$list_data[1]['name']="event_apply_is_huang";
	$list_data[1]['name_cn']="是否车主";
	$list_data[1]['type']="radio";
	$list_data[1]['type_more']=array('是','否');
	$list_data[1]['max_size']="50";
	*/

/*
	$list_data[4]['name']="event_id";
	$list_data[4]['type']="hidden";
	$list_data[4]['max_size']="11";

	$list_data[5]['name']="uid";
	$list_data[5]['type']="hidden";
	$list_data[5]['max_size']="11";
*/
    if(empty($list_data)) {
        $list_data = null;
    }
	$data['title']="data";
	$data['data']=$list_data;
	//print_r($data);
	api_json_result(1,0,$app_error['event']['10502'],$data);
}



//赛事报名接口  ACTION
if($ac=="event_baoming_action")
{
	$fenzhan_name=$_G['gp_event_apply_fenzhan'];
	$event_id=$_G['gp_event_id'];
	$uid=$_G['gp_uid'];
	$field_uid=$_G['gp_field_uid'];
	$fenzhan_id=DB::result_first("select fenzhan_id from tbl_fenzhan where event_id='".$event_id."' order by fenzhan_id desc limit 1 ");
	
	
	if(urlencode($_G['gp_event_apply_sex'])=="男")
	{
		$sex=1;
	}
	else
	{
		$sex=2;
	}
	
	$arr['uid']=$uid;
	$arr['event_id']=$event_id;
	$arr['event_apply_realname']=urldecode($_G['gp_event_apply_realname']);
	$arr['event_apply_sex']=$sex;
	$arr['event_apply_card']=$_G['gp_event_apply_card'];     //证件号码
	$arr['event_apply_chadian']=$_G['gp_event_apply_chadian'];     //证件号码
	$arr['event_apply_state']=0;
	$arr['event_apply_addtime']=time();
	$arr['fenzhan_id']=$fenzhan_id;
	$arr['field_uid']=$field_uid;
	

	$res=DB::query("insert into tbl_event_apply (event_id,uid,event_apply_realname,event_apply_sex,event_apply_card,event_apply_chadian,event_apply_state,event_apply_addtime,fenzhan_id,field_uid) values ('".$arr['event_id']."','".$arr['uid']."','".$arr['event_apply_realname']."','".$arr['event_apply_sex']."','".$arr['event_apply_card']."','".$arr['event_apply_chadian']."','".$arr['event_apply_state']."','".$arr['event_apply_addtime']."','".$arr['fenzhan_id']."','".$arr['field_uid']."')");
	
	$new_id=DB::result_first("select event_apply_id from tbl_event_apply where uid='".$uid."' and event_id='".$event_id."' order by event_apply_id desc limit 1 ");
	
	//生成二维码图片
	include "../tool/phpqrcode/qrlib.php";
	$save_path="../upload/erweima/";
	$full_save_path=$save_path.date("Ymd",time())."/";
	if(!file_exists($save_path))
	{
		mkdir($save_path);
	}
	if(!file_exists($full_save_path))
	{
		mkdir($full_save_path);
	}

	$data=$new_id;
	$filename=$full_save_path.$new_id.".png";
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
		$filename=str_replace("../","/",$filename);
		$res=DB::query("update tbl_event_apply set code_pic='".$filename."' where  event_apply_id='".$new_id."' ");
	}

	
	
	api_json_result(1,0,"报名成功",$data);
	
}

/**
 * 检查一维数组并替换数组中的某一项元素
 * @param array $arr      将要替换的数组
 * @param array $fields   要替换的字段
 * $fields = array(
 * 				'key'=>'val'   //key:将要替换的字段；val:最终替换后的结果
 * 			);
 */
function check_field_to_relace($arr=array(), $fields=array()) {
    if(empty($arr) || empty($fields)) {
        return null;
    }
    
    
    foreach($arr as $key=>$val) {
        if(is_array($arr[$key])) {
           //$arr[$key] = current(array_map(__FUNCTION__,array($arr[$key]),array($fields)));//处理多维度
           //$arr[$key] = check_field_to_relace($arr[$key],$fields);//处理多维度
           continue;
        }
        if(isset($fields[$key])) {
            $arr[$key]=$fields[$key];
        }
    }

    
    return $arr;
}





?>