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
//选择 赛事
if($ac=="select_event")
{
	$list3=DB::query("select event_id,event_name,event_uid,event_is_zhutui,event_zhutui_pic,event_content,event_url,event_type,event_logo from tbl_event where event_is_zhutui='Y' and field_uid=0 order by event_sort desc limit 1 ");
	while($row3 = DB::fetch($list3))
	{
		if($row3['event_zhutui_pic'])
		{
			$row3['event_zhutui_pic']=$site_url."/".$row3['event_zhutui_pic'];
		}
		if(!$row3['event_url'])
		{
			$row3['event_url']=null;
		}
		$row3['event_pic']=$site_url."/uc_server/avatar.php?uid=".$row['event_uid']."&size=middle";
		$row3['uid']=$row3['event_id'];
		$row3['event_content']=msubstr(cutstr_html($row3['event_content']),0,30);
		$list_data3[]=$row3;
	}

	$list=DB::query("select event_id,event_name,event_id as event_uid,event_is_zhutui,event_content,event_url,event_type,event_logo,event_starttime,event_endtime from tbl_event where event_is_tj='Y' and (event_viewtype='B' or event_viewtype='A' or event_viewtype='S') order by event_sort desc  ");
	while($row = DB::fetch($list))
	{
		
		if($row['event_logo'])
		{
			$row['event_logo']=$site_url."/".$row['event_logo'];
		}
		$row['event_pic']=$row['event_logo'];
		$row['uid']=$row['event_uid'];
		$row['event_content']=msubstr(cutstr_html($row['event_content']),0,30);
		
		$row['event_content']=date('Y年m月d日',$row['event_starttime'])." ~ ".date('m月d日',$row['event_endtime']);
		
		if(!$row['event_url'])
		{
			$row['event_url']=null;
		}
		$list_data[]=$row;
	}
	
	

	if($list_data)
	{
		$data['title']		= "list_data";
		$data['data']=array(
							  'zhutui_list'=>$list_data3,
							  'ing_list'=>$list_data,
							  'apply_ing_list'=>$list_data2,
							 );
		//print_r($data);
		api_json_result(1,0,$app_error['event']['10502'],$data);
	}
	else
	{
		api_json_result(1,0,"no data",$data);
	}
	
	
}




//正在报名的比赛
if($ac=="apply_ing")
{
	$login_uid=$_G['gp_login_uid'];

	$list=DB::query("select event_id,field_uid,event_name,event_uid,event_is_zhutui,event_content,event_url,event_type,event_logo from tbl_event where event_baoming_starttime<=".time()." and event_baoming_endtime>=".time()." and (event_viewtype='B' or (field_uid=0) or event_viewtype='S') and event_is_baoming='Y' order by event_baoming_starttime desc  limit 100 ");
	while($row = DB::fetch($list))
	{
		if($login_uid)
		{
			$bm=DB::fetch_first("select bm_id,code_pic from ".DB::table("home_dazbm")." where  uid='".$login_uid."' and pay_status=1 ");
			//print_r($bm);
			if($bm['bm_id'])
			{
				$row['event_baoming_state']=$bm['bm_id'];
				if($bm['code_pic'])
				{
					$row['event_baoming_pic']=$site_url."".$bm['code_pic'];
				}
				else
				{
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

				}


			}
			else
			{
				$row['event_baoming_state']="0";
				$row['event_baoming_pic']="";
			}

		}
		
		if(!$row['event_url'])
		{
			$row['event_url']=null;
		}
		
		//$row['event_pic']=$site_url."/uc_server/avatar.php?uid=".$row['event_uid']."&size=middle";
		$row['event_pic']=$site_url."/".$row['event_logo'];
		$row['uid']=$row['event_uid'];
		$row['event_content']=msubstr(cutstr_html($row['event_content']),0,30);
		$list_data[]=$row;
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


//ADD 添加报名
if($ac=="apply_insert")
{
	$arr['event_id']=$_G['gp_event_id'];
	$arr['uid']=$_G['gp_uid'];
	$arr['event_apply_realname']=$_G['gp_event_apply_realname'];
	$arr['event_apply_sex']=$_G['gp_event_apply_sex'];
	$arr['event_apply_card']=$_G['gp_event_apply_card'];
	$arr['event_apply_chadian']=$_G['gp_event_apply_chadian'];
	$arr['event_apply_state']=0;
	$arr['event_apply_addtime']=time();

	$res=DB::insert('dz_event_apply',$arr);
	api_json_result(1,0,$api_error['event']['10501'],$arr);
}


//赛事报道 arctype_id=3
if($ac=="event_blog")
{

	//微博列表 分页
	$total=DB::result_first("select count(arc_id) from tbl_arc where  arc_model='arc' and arctype_id=3 ");
	//echo $total;
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{

		$list=DB::query("select arc_id as blogid,arc_name as subject,arc_replynum as replynum,arc_viewtype as view_type,arc_pic as pic ,arc_addtime as dateline,arc_content as content,FROM_UNIXTIME(arc_addtime, '%Y%m%d') as today from tbl_arc where arc_model='arc' and arc_state=1 and arc_viewstatus=1 and arctype_id=3 order by today desc,arc_sort desc  limit $page_start,$page_size");
		$i=0;
		while($row = DB::fetch($list))
		{
			$row['uid']=0;
			$row['replynum']="评论：".$row['replynum'];
			if($row['pic'])
			{
				$row['pic']=$site_url."/".$row['pic'];
			}
			$row['dateline']=date("Y-m-d G:i:s",$row['dateline']);
			$row['content']=msubstr(cutstr_html($row['content']),0,30);
			$list_data[]=$row;
			$i++;
		}
		
		
	}
		$data['title']="list_data";
		$data['data']=$list_data;
		api_json_result(1,0,$app_error['event']['10502'],$data);
		/*
		if($list_data)
		{
			
		}
		else
		{
			api_json_result(1,0,"没有新闻了",$data);
		}
		*/
}



//新闻 详细
if($ac=="event_blog_detail")
{
	//print_r(getimagesize("".$site_url."/data/attachment/album/201303/19/114243utqdpvtp9vipq2gd.jpg.thumb.jpg"));

	$blogid=$_G['gp_blogid'];
	$pic_width=$_G['gp_pic_width'];
	if($blogid)
	{
		$detail_data=DB::fetch_first("select uid,field_uid,arc_type,arc_id as blogid,arc_name as subject,arc_replynum as replynum,arc_viewtype as view_type,arc_pic as pic ,arc_addtime as dateline,arc_content as content,is_video,is_span,(arc_share_qq+arc_share_sina+arc_share_other) as arc_share_total,arc_share_qq,arc_share_sina,arc_video_pic,arc_video_url from tbl_arc where arc_id='".$blogid."' ");
		$detail_data['username']="";
		$detail_data['uid']="0";
		
		//video about
		if($detail_data['arc_video_pic'])
		{
			$detail_data['arc_video_pic']=$site_url."/".$detail_data['arc_video_pic'];
			$detail_data['arc_video_pic_info']=getimagesize($detail_data['arc_video_pic']);
		}
		else
		{
			$detail_data['arc_video_pic_info']=null;
		}
		
		if($detail_data['arc_video_url'])
		{
			$detail_data['arc_video_url']=$site_url."/".$detail_data['arc_video_url'];
		}

		
		if($detail_data['is_video']=="Y")
		{
			if($detail_data['is_span']=="Y")
			{
				$detail_data['content']=strip_tags($detail_data['content'],"<p><img><a><span><strong><br><div>");
			}
			else
			{
				$detail_data['content']=strip_tags($detail_data['content'],"<p><img><a><br><div>");
			}
		}
		else
		{
			if($detail_data['is_span']=="Y")
			{
				$detail_data['content']=strip_tags($detail_data['content'],"<p><img><span><strong><br><div>");
			}
			else
			{
				$detail_data['content']=strip_tags($detail_data['content'],"<p><img><br><div>");
			}

		}
		
		$detail_data['content']=str_replace("<img ","<div style=\"text-align:center; width:100%; \"><a href=\"http://www.bwvip.com/news_detail_pic\"><changsailong><img ",$detail_data['content']);
		
		/**
		 * 添加图片编号
		 * start
		 */
		$find_str = "<div style=\"text-align:center; width:100%; \"><a href=\"http://www.bwvip.com/news_detail_pic";
		$content_arr = explode("<changsailong>",$detail_data['content']);
		$i=1;
		$str = '';
		foreach($content_arr as $key=>&$val)
		{
		    $str .= str_replace($find_str,$find_str.$i,$val);
		    $i++;
		}
		$detail_data['content']=$str;
		if($_G['gp_test'] == 1) {
			echo '<pre>';
			var_dump($content_arr);
			var_dump($str);die;
			//var_dump($find_str);
		}
		unset($str,$content_arr,$find_str);
		
		/**
		 * 添加图片编号
		 * end
		 */
		
		$detail_data['content']=str_replace("jpg\">","jpg\"></a></div>",$detail_data['content']);
		$detail_data['content']=str_replace("jpg\" />","jpg\" /></a></div>",$detail_data['content']);
		$detail_data['content']=str_replace("jpg\" alt=\"\" />","jpg\" /></a></div>",$detail_data['content']);

		if($detail_data['content'])
		{
			$detail_data['content']=str_replace(".=\"uchome-message-pic\"","",$detail_data['content']);
			$detail_data['content']=str_replace("src=\"data/attachment/","src=\"".$site_url."/data/attachment/",$detail_data['content']);
			$detail_data['content']=str_replace("src=\"/Public/editor/attached/image","src=\"".$site_url."/Public/editor/attached/image",$detail_data['content']);
			
			
			$detail_data['content'] = "<div style='font-size:18px; line-height:180%; width:100%; bakcground:red; '>".$detail_data['content'];
			$detail_data['content'] = $detail_data['content']."</div>";
		}

		if($pic_width)
		{
			$detail_data['content']=str_replace("<img ","<img style=\"width:".$pic_width."px; margin:0 auto;\" ",$detail_data['content']);
		}
		
		//获取图片数组
		$img_reg = "/<img[^>]*src=\"(http:\/\/(.+)\/(.+)\.(jpg|gif|bmp|bnp))\"/isU";
		preg_match_all($img_reg, $detail_data['content'], $img_array, PREG_PATTERN_ORDER);
		$pic_list = array_unique($img_array[1]);
		
	
		for($n=0; $n<count($pic_list); $n++)
		{
			$pic_url=$pic_list[$n];
			
			$pic_arr[$n]['pic']=$pic_url;
			$pic_arr[$n]['pic_info']=getimagesize($pic_url);
			/*
			if(file_exists($pic_arr[$n]['pic']))
			{
				$pic_arr[$n]['pic_info']=getimagesize($pic_url);
			}
			else
			{
				$pic_arr[$n]['pic_info']=null;
			}
			*/

		}
		
		
		$detail_data['pic_list']=$pic_arr;
		
	
		if($detail_data['pic'])
		{
			$detail_data['pic']	="".$site_url."/data/attachment/album/".$detail_data['pic'];
			$detail_data['pic']=str_replace("/data/attachment/album/upload/arc","/upload/arc",$detail_data['pic']);
			if(file_exists($detail_data['pic']))
			{
				$detail_data['pic_info']=getimagesize($detail_data['pic']);
			}
			else
			{
				$detail_data['pic_info']=null;
			}
			
		}
		
		if($detail_data['dateline'])
		{
			$detail_data['dateline']=date("Y-m-d G:i",$detail_data['dateline']);
			
			if($detail_data['field_uid']==1186)
			{
				$detail_data['dateline']="来自美兰湖球场 ".$detail_data['dateline'];
			}
			else if($detail_data['field_uid']==1160)
			{
				$detail_data['dateline']="来自南山球会 ".$detail_data['dateline'];
			}
			else
			{
				$detail_data['dateline']="".$detail_data['dateline'];
			}
			
		}
		
		//$list=DB::query("select arc_id as blogid,arc_name as subject,arc_addtime as dateline from tbl_arc where arc_id<>'".$blogid."' order by arc_addtime desc limit 2");
		$list=DB::query("select arc_id as blogid,arc_name as subject,arc_addtime as dateline from tbl_arc where arc_state=1 and arc_type='".$detail_data['arc_type']."' and arc_id<>'".$blogid."' order by arc_addtime desc limit 2");
		while($row = DB::fetch($list))
		{
			if($row['dateline'])
			{
				$row['dateline']=date("Y-m-d G:i",$row['dateline']);
			}
			$list_data[]=$row;
		}
		//print_r($list_data);

		if($detail_data)
		{
		
			$detail_data=array_default_value($detail_data,array('arc_video_pic_info'));
			
			$data['title']="detail_data";
			$data['data']=array(
							  'detail_info'=>$detail_data,
							  'blog_list'=>$list_data,
							 );
			
			//print_r($data);
			api_json_result(1,0,$app_error['event']['10502'],$data);
		}
	}
	
	
}




//赛事报道评论列表
if($ac=="event_blog_detail_comment")
{

	 $ecname=getecprefix();
	 //print_r($ecname);
	$blogid=$_G['gp_blogid'];

	$list=DB::query("select cid,uid,id,idtype,authorid,author,dateline,message from tbl_comment where idtype='blogid' and id='".$blogid."' order by dateline desc  limit 10 ");
	while($row=DB::fetch($list))
	{
		$arr=explode("</blockquote>",$row['message']);
		if(count($arr)>1)
		{
			$row['realname_parent'] = DB::result_first( "select realname  from " . DB::table ( 'common_member_profile' ) . "  where uid='".$row['uid']."' ");
			$row['message_parent']=cutstr_html($arr[0]);
			$row['message']=cutstr_html($arr[1]);
		}
		else
		{
			$row['realname_parent']="";
			$row['message_parent']="";
			$row['message']=cutstr_html($row['message']);
		}

		$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['authorid']."&size=small";
		$row['dateline']=date("Y-m-d G:i:s",$row['dateline']);
		$list_data[]=$row;
	}

	$data['title']="list";
	if($list_data)
	{
		
		$data['data']=$list_data;
		//print_r($data);
		api_json_result(1,0,$app_error['event']['10502'],$data);
	}
	else
	{
		api_json_result(1,1,"还没有评论...",$data);
	}

}



//赛事报道评论   添加评论
if($ac=="event_blog_detail_comment_add")
{

	//print_r($_POST);
	$blogid=$_G["gp_blogid"];
	$parent_cid=$_G["gp_parent_cid"];
	$uid=$_G["gp_uid"];		//评论发布者 ID
	$authorid=$_G["gp_authorid"];		//回复者用户ID
	$author=urldecode($_G["gp_author"]);		//回复者用户名
	$message=urldecode($_G["gp_message"]);		//评论内容
	$is_feed=$_G["gp_is_feed"];		//是否发布为动态
	$blog_title=urldecode($_G["gp_blog_title"]);		//博客标题
	$blog_username=urldecode($_G["gp_blog_username"]);		//博客发布者 用户名
	$blog_uid=$_G["gp_blog_uid"];		//博客发布者 用户ID

	$arr=explode("</blockquote>",$message);
	if(count($arr)>1)
	{
		$weibo_text=cutstr_html($arr[1])." ".$site_url."/blog-".$blog_uid."-".$blogid.".html";
	}
	else
	{
		$weibo_text=cutstr_html($arr[0])." ".$site_url."/blog-".$blog_uid."-".$blogid.".html";
	}
	
	$res=DB::query("insert into tbl_comment (parent_cid,uid,id,idtype,authorid,author,dateline,message) values ('".$parent_cid."','".$uid."','".$blogid."','blogid','".$authorid."','".$author."','".time()."','".$message."') ");
	$res2=DB::query("update tbl_arc set arc_replynum=arc_replynum+1 where arc_id='".$blogid."' ");

	if($is_feed)
	{
		//weibo_add
		//$res3=DB::query("INSERT INTO `pre_home_feed` ( `appid`, `icon`, `uid`, `username`, `dateline`, `friend`, `hash_template`, `hash_data`, `title_template`, `title_data`, `body_template`, `body_data`, `body_general`, `image_1`, `image_1_link`, `image_2`, `image_2_link`, `image_3`, `image_3_link`, `image_4`, `image_4_link`, `target_ids`, `id`, `idtype`, `hot`) VALUES ( 0, 'comment', '".$authorid."', '".$author."', '".time()."', 0, '', '".$authorid."', '<a href=\"home.php?mod=space&uid=".$authorid."\">".$author."</a> 评论了 <a href=\"home.php?mod=space&uid=".$authorid."\">".$blog_username."</a> 的日志 <a href=\"home.php?mod=space&uid=".$authorid."&do=blog&id=".$blogid."\">".$blog_title."</a>', 'a:3:{s:6:\"touser\";s:55:\"<a href=\"home.php?mod=space&uid=".$authorid."\">".$author."</a>\";s:4:\"blog\";s:126:\"<a href=\"home.php?mod=space&uid=".$blog_uid."&do=blog&id=".$blogid."\">".$blog_title."</a>\";s:9:\"hash_data\";s:11:\"".$author."\";}', '', 'a:0:{}', '', '', '', '', '', '', '', '', '', '', 0, '', 0);");
		
		$res2=DB::query("insert into jishigou_topic (uid,username,content,type,totid,roottid,dateline) values ('".$authorid."','".$author."','".$weibo_text."','first','0','0','".time()."') ");
		
	}

	api_json_result(1,"0","发送成功",$res2);



}



//删除评论
if($ac=="delete_comment")
{
	$cid=$_G['gp_cid'];
	$uid=$_G['gp_uid'];//当前登录uid
	$blogid=$_G['gp_blogid'];
	if($cid && $uid && $blogid)
	{
		$res=DB::query("delete from tbl_comment where cid='".$cid."' and uid='".$uid."' ");
		$data['title']="list";
		if($res)
		{
			$res2=DB::query("update tbl_arc  set arc_replynum=arc_replynum-1 where arc_id='".$blogid."' ");
			api_json_result(1,0,"删除成功",$data);
		}
		else
		{
			api_json_result(1,1,"没有权限",$data);
		}
		
	}
	else
	{
		api_json_result(1,1,"删除失败",$data);
	}
	
}





//赛事聊天室
if($ac=="event_room")
{

	$list=DB::query("select event_id,event_uid,event_logo,event_name,event_content,event_starttime,event_endtime from tbl_event where event_is_bbs='Y' and (event_viewtype='B' or event_viewtype='A' or event_viewtype='S') order by event_sort desc,event_addtime desc ");
	while($row = DB::fetch($list))
	{
		
		$row['event_pic']="http://www.bwvip.com/".$row['event_logo'];
		$row['uid']=$row['event_uid'];
		$row['event_content']=msubstr(cutstr_html($row['event_content']),0,70);
		$row['event_content']=str_replace("\r","",$row['event_content']);
		$row['event_content']=str_replace("\n","",$row['event_content']);
		
		$row['event_content']=date('Y年m月d日',$row['event_starttime'])." ~ ".date('m月d日',$row['event_endtime']);
		
		$list_data[]=$row;
	}
	if($list_data)
	{
		$data['title']		= "list_data";
		$data['data']		= $list_data;
		api_json_result(1,0,$app_error['event']['10502'],$data);
	}
}



//赛事具体信息
if($ac=="event_detail")
{
	$event_id=$_G['gp_event_id'];
	$sid=$_G['gp_sid'];
	if($event_id || $sid!='')
	{
		if($event_id)
		{
			$detail_data=DB::fetch_first("select event_id,event_name,event_uid,event_logo as event_pic,event_content,event_is_zhutui,event_starttime,event_endtime from tbl_event where event_id='".$event_id."'  ");
		}
		else
		{
			$detail_data=DB::fetch_first("select event_id,event_name,event_uid,event_logo as event_pic,event_content,event_is_zhutui,event_starttime,event_endtime from tbl_event where event_uid='".$sid."' ");
		}
		
		if($detail_data)
		{
			$detail_data['uid']=$detail_data['event_uid'];
			$detail_data['event_pic']=$site_url."/".$detail_data['event_pic'];
			$detail_data['event_content']=cutstr_html($detail_data['event_content']);
			
			
			$detail_data['event_content']=date('Y年m月d日',$detail_data['event_starttime'])." ~ ".date('m月d日',$detail_data['event_endtime']);
			
			$total=DB::result_first("select count(tid) from jishigou_topic where type<>'reply' and content like '%".$tag."%' ");
			$max_page=intval($total/$page_size);
			if($max_page<$total/$page_size)
			{
				$max_page=$max_page+1;
			}
			if($max_page>=$page)
			{

				$tag=$detail_data['event_name'];
				$list=DB::query("select tid,uid,roottid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,(select `longtext` from jishigou_topic_longtext where tid=jishigou_topic.tid) as full_content,replys,forwards,dateline,imageid,voice,voice_timelong from jishigou_topic where type<>'reply' and content like '%".$tag."%' order by dateline desc limit $page_start,$page_size ");
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
						$pic_list[$pic_i]['photo_mibble'] = $site_url."/weibo/".str_replace("_o","_p",$pic_row['photo']);
						$pic_list[$pic_i]['photo_small'] = $site_url."/weibo/".str_replace("_o","_s",$pic_row['photo']);
						$pic_i++;
					}
					unset($topic_img_rs,$pic_i,$pic_row);
					if(!empty($pic_list)) {
						$row['pic_list'] = $pic_list;
					}else{
						$row['pic_list'] = null;
					}
					$photo_pic = reset($pic_list);
					if($photo_pic)
					{
						$row['photo_big']=$photo_pic['photo_big'];
						$row['photo_mibble']=$photo_pic['photo_mibble'];
						$row['photo_small']=$photo_pic['photo_small'];
					}
					else
					{
						$row['photo_big']=null;
						$row['photo_small']=null;
					}
					unset($pic_list,$photo_pic);
					$content_tmp = cutstr_html($row['content'].$row['content2']);
					//$row['content']=cutstr_html($row['content'].$row['content2']);
					$row['content'] = cutstr_html($row['full_content']);
					if(empty($row['content'])) {
						$row['content'] = $content_tmp;
					}
					$row['dateline']=date("Y-m-d G:i",$row['dateline']);
					
					$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";
					if($row['voice'])
					{
						$row['voice']=$site_url."/weibo/".$row['voice']."";
					}

					//根topic
					$root_topic=DB::fetch_first("select tid,uid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,(select `longtext` from jishigou_topic_longtext where tid=jishigou_topic.tid) as full_content,replys,forwards,dateline,imageid,voice,voice_timelong from jishigou_topic where tid='".$row['roottid']."' order by dateline asc ");
					if($root_topic)
					{
						$imageids_arr = explode(',',$root_topic['imageid']);
						$pic_ids = implode("','",imageids_arr);
						$root_topic_img_rs =  DB::query("select photo from jishigou_topic_image where id in ('{$pic_ids}')");
						unset($imageids_arr,$pic_ids);
						
						$pic_i=0;
						while($pic_row = DB::fetch($root_topic_img_rs) ){
							$pic_list[$pic_i]['photo_big'] = $site_url."/weibo/".$pic_row['photo'];
							$pic_list[$pic_i]['photo_mibble'] = $site_url."/weibo/".str_replace("_o","_p",$pic_row['photo']);
							$pic_list[$pic_i]['photo_small'] = $site_url."/weibo/".str_replace("_o","_s",$pic_row['photo']);
							$pic_i++;
						}
						unset($root_topic_img_rs,$pic_i,$pic_row);
						if(!empty($pic_list)) {
							$root_topic['pic_list'] = $pic_list;
						}else{
							$root_topic['pic_list'] = null;
						}
						$photo_pic = reset($pic_list);
						if($photo_pic)
						{
							$root_topic['photo_big']=$photo_pic['photo_big'];
							$root_topic['photo_mibble']=$photo_pic['photo_mibble'];
							$root_topic['photo_small']=$photo_pic['photo_small'];
						}
						else
						{
							$root_topic['photo_big']=null;
							$root_topic['photo_small']=null;
						}
						unset($pic_list,$photo_pic);
						//$root_topic['content']=cutstr_html($root_topic['content']);
						$content_tmp = cutstr_html($root_topic['content']);
						$root_topic['content']=cutstr_html($root_topic['full_content']);
						if(empty($root_topic['content'])) {
							$root_topic['content'] = $content_tmp;
						}
						$root_topic['dateline']=date("Y-m-d",$root_topic['dateline']);
						$root_topic['touxiang']=$site_url."/uc_server/avatar.php?uid=".$root_topic['uid']."&size=small";
						if($root_topic['voice'])
						{
							$root_topic['voice']=$site_url."/weibo/".$root_topic['voice']."";
						}
						$row['root_topic']=$root_topic;
					}
					else
					{
						$row['root_topic']="";
					}

					$list_data[]=$row;
				}

			}//end page

			$data['title']		= "detail_data";
			$data['data']=array(
							  'detail_info'=>$detail_data,
							  'topic_list'=>$list_data,
							 );
			//var_dump($data);
			api_json_result(1,0,$app_error['event']['10502'],$data);
		}
		else
		{
			api_json_result(1,1,"没有数据",$data);
		}

	}
}




//规则江湖
if($ac=="rule")
{
	
	$detail_data=DB::fetch_first("select rule_tag_id,rule_tag_name,rule_tag_logo,rule_tag_content from tbl_rule_tag limit 1 ");
	if($detail_data)
	{
		$detail_data['rule_tag_logo']=$site_url."/".$detail_data['rule_tag_logo']."";
		
		//page start
		$total=DB::result_first("select count(tid) from jishigou_topic where type<>'reply' and fuid='1899466'  ");
		$max_page=intval($total/$page_size);
		if($max_page<$total/$page_size)
		{
			$max_page=$max_page+1;
		}
		if($max_page>=$page)
		{

			$tag=$detail_data['event_name'];
			$list=DB::query("select tid,uid,roottid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,(select `longtext` from jishigou_topic_longtext where tid=jishigou_topic.tid) as full_content,replys,forwards,dateline,imageid,voice,voice_timelong from jishigou_topic where type<>'reply' and fuid='1899466' order by dateline desc limit ".$page_start.",".$page_size." ");
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
					$pic_list[$pic_i]['photo_mibble'] = $site_url."/weibo/".str_replace("_o","_p",$pic_row['photo']);
					$pic_list[$pic_i]['photo_small'] = $site_url."/weibo/".str_replace("_o","_s",$pic_row['photo']);
					$pic_i++;
				}
				unset($topic_img_rs,$pic_i,$pic_row);
				if(!empty($pic_list)) {
					$row['pic_list'] = $pic_list;
				}else{
					$row['pic_list'] = null;
				}
				$photo_pic = reset($pic_list);
				if($photo_pic)
				{
					$row['photo_big']=$photo_pic['photo_big'];
					$row['photo_mibble']=$photo_pic['photo_mibble'];
					$row['photo_small']=$photo_pic['photo_small'];
				}
				else
				{
					$row['photo_big']=null;
					$row['photo_small']=null;
				}
				unset($pic_list,$photo_pic);
				$content_tmp = cutstr_html($row['content']);
				$row['content']=cutstr_html($row['full_content']);
				if(empty($row['content'])) {
					$row['content'] = $content_tmp;
				}
				$row['dateline']=date("Y-m-d G:i",$row['dateline']);
				
				$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";
				if($row['voice'])
				{
					$row['voice']=$site_url."/weibo/".$row['voice']."";
				}

				$root_topic=DB::fetch_first("select tid,uid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,(select `longtext` from jishigou_topic_longtext where tid=jishigou_topic.tid) as full_content,replys,forwards,dateline,imageid,voice,voice_timelong from jishigou_topic where tid='".$row['roottid']."' order by dateline asc ");
				if($root_topic)
				{
					$imageids_arr = explode(',',$root_topic['imageid']);
					$pic_ids = implode("','",$imageids_arr);
					
					$root_topic_img_rs =  DB::query("select photo from jishigou_topic_image where id in ('{$pic_ids}')");
					unset($imageids_arr,$pic_ids);
					
					$pic_i=0;
					while($pic_row = DB::fetch($root_topic_img_rs) ){
						$pic_list[$pic_i]['photo_big'] = $site_url."/weibo/".$pic_row['photo'];
						$pic_list[$pic_i]['photo_mibble'] = $site_url."/weibo/".str_replace("_o","_p",$pic_row['photo']);
						$pic_list[$pic_i]['photo_small'] = $site_url."/weibo/".str_replace("_o","_s",$pic_row['photo']);
						$pic_i++;
					}
					unset($root_topic_img_rs,$pic_i,$pic_row);
					if(!empty($pic_list)) {
						$root_topic['pic_list'] = $pic_list;
					}else{
						$root_topic['pic_list'] = null;
					}
					$photo_pic = reset($pic_list);
					if($photo_pic)
					{
						$root_topic['photo_big']=$photo_pic['photo_big'];
						$root_topic['photo_mibble']=$photo_pic['photo_mibble'];
						$root_topic['photo_small']=$photo_pic['photo_small'];
					}
					else
					{
						$root_topic['photo_big']=null;
						$root_topic['photo_small']=null;
					}
					unset($pic_list,$photo_pic);
					//$root_topic['content']=cutstr_html($root_topic['content']);
					$content_tmp = cutstr_html($root_topic['content']);
					$root_topic['content']=cutstr_html($root_topic['full_content']);
					if(empty($root_topic['content'])) {
						$root_topic['content'] = $content_tmp;
					}
					$root_topic['dateline']=date("Y-m-d",$root_topic['dateline']);
					$root_topic['touxiang']=$site_url."/uc_server/avatar.php?uid=".$root_topic['uid']."&size=small";
					if($root_topic['voice'])
					{
						$root_topic['voice']=$site_url."/weibo/".$root_topic['voice']."";
					}
					$row['root_topic']=$root_topic;
				}
				else
				{
					$row['root_topic']="";
				}

				$list_data[]=$row;
			}

		}//end page

		$data['title']		= "detail_data";
		$data['data']=array(
						  'detail_info'=>$detail_data,
						  'topic_list'=>$list_data,
						 );
		//var_dump($data);
		api_json_result(1,0,$app_error['event']['10502'],$data);
	}
	else
	{
		api_json_result(1,0,"没有数据",$data);
	}

}


//规则江湖
if($ac=="rule_user")
{
	//$uid=$_G['gp_uid'];
	$uid='1899466';
	//page start
	$total=DB::result_first("select count(userid) from ".DB::table("guanxi")."  where compid='".$uid."' and iscomp=1 ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{
		$list=DB::query("select userid,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("guanxi").".userid) as username from ".DB::table("guanxi")." where compid='".$uid."' and iscomp=1 limit ".$page_start.",".$page_size."  ");
		while($row = DB::fetch($list))
		{
			$row['uid']=$row['userid'];
			unset($row['userid']);

			$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=middle";
			if($row['uid']>0)
			{
				$list_data[]=$row;
			}
		}

	}
	$data['title']		= "list";
		$data['data']		=array(
			'list_info'=>$list_data,
		);

	api_json_result(1,0,$app_error['event']['10502'],$data);

}




//回复我的评论
if($ac=="blog_comment_me")
{

	$uid=$_G['gp_uid'];//登录uid
	$res=DB::query("update jishigou_members set comment_new=0 where uid='".$uid."' ");
	if($_G['gp_is_hulue'])
	{
		api_json_result(1,0,"成功了！",$data);
	}
	else
	{

		$list=DB::query("select cid,uid,id,idtype,authorid,author,dateline,message from ".DB::table("home_comment")." where idtype='blogid' and uid='".$uid."'  and uid<>authorid order by dateline desc  limit 10 ");
		while($row=DB::fetch($list))
		{
			$blog=DB::fetch_first("select subject,username from ".DB::table("home_blog")." where blogid='".$row['id']."' ");
			$row['blog_title']=$blog['subject'];
			$row['blog_username']=$blog['username'];

			$arr=explode("</blockquote>",$row['message']);
			if(count($arr)>1)
			{
				$row['message_parent']=cutstr_html($arr[0]);
				$row['message']=cutstr_html($arr[1]);
			}
			else
			{
				$row['message_parent']="";
				$row['message']=cutstr_html($row['message']);
			}

			$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['authorid']."&size=small";

			$row['dateline']=date("Y-m-d G:i:s",$row['dateline']);
			$list_data[]=$row;
		}

		if($list_data)
		{
			$data['title']="list";
			$data['data']=$list_data;
			api_json_result(1,0,$app_error['event']['10502'],$data);
		}
		else
		{
			api_json_result(1,0,"没有数据",$data);
		}

	}

}



//赛事报名接口
if($ac=="event_baoming")
{



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

	$list_data[4]['name']="event_apply_fenzhan";
	$list_data[4]['name_cn']="分　　站";
	$list_data[4]['type']="radio";
	$list_data[4]['type_more']=array('5/11天津','5/24广州','5/31深圳','6/15杭州','6/21上海','6/29长沙','7/19北京','7/26大连','8/9郑州','8/24成都','8/30苏州','9/7福州');
	$list_data[4]['max_size']="50";

	$list_data[1]['name']="event_apply_is_huang";
	$list_data[1]['name_cn']="是否车主";
	$list_data[1]['type']="radio";
	$list_data[1]['type_more']=array('是','否');
	$list_data[1]['max_size']="50";

/*
	$list_data[4]['name']="event_id";
	$list_data[4]['type']="hidden";
	$list_data[4]['max_size']="11";

	$list_data[5]['name']="uid";
	$list_data[5]['type']="hidden";
	$list_data[5]['max_size']="11";
*/
	$data['title']="data";
	$data['data']=$list_data;
	//print_r($data);
	api_json_result(1,0,$app_error['event']['10502'],$data);
}



//赛事报名接口  ACTION
if($ac=="event_baoming_action")
{
	$fenzhan=array_search(urldecode($_G['gp_event_apply_fenzhan']),$hot_2013district);

	$bm=DB::fetch_first("select bm_id from pre_home_dazbm where uid='".$_G['gp_uid']."' and hot_district='".$fenzhan."' ");
	if(!$bm['bm_id'])
	{
		if(urlencode($_G['gp_event_apply_sex'])=="男")
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
		}
		//$fenzhan=urldecode($_G['gp_event_apply_fenzhan']);
	
		$mobile=DB::result_first("select mobile from ".DB::table("common_member_profile")." where uid='".$_G['gp_uid']."' ");
		
		$data_bm['uid']=$_G['gp_uid'];
		$data_bm['realname']   = urldecode($_G['gp_event_apply_realname']);         //真实姓名
		$data_bm['gender']     = $sex;            //1 男 2 女
		$data_bm['credentials_num']    = $_G['gp_event_apply_card'];     //证件号码
		$data_bm['hot_district']    = $fenzhan;

		$data_bm['cahdian']    = !empty ( $_G['gp_event_apply_chadian'] ) ? $_G['gp_event_apply_chadian'] : '';              //差点
		$data_bm['moblie']             = $mobile;
		$data_bm['is_huang']             = $is_huang; //是否车主
		$data_bm['nationality']='中国';     //国籍
		//xyx 20130615增加字段，方便客服查询
		$data_bm['addtime']= time(); 
		$data_bm['game_s_type']= 1000333; 

		
		DB::insert('home_dazbm',$data_bm,true);
		api_json_result(1,0,"报名成功",$data);
		
		//print_r($_POST);
		//echo urldecode($arr['event_apply_realname']);
		/*
		$res=DB::query("insert into tbl_event_apply (event_id,uid,event_apply_realname,event_apply_sex,event_apply_card,event_apply_chadian,event_apply_state,event_apply_addtime) values ('".$arr['event_id']."','".$arr['uid']."','".$arr['event_apply_realname']."','".$arr['event_apply_sex']."','".$arr['event_apply_card']."','".$arr['event_apply_chadian']."','".$arr['event_apply_state']."','".$arr['event_apply_addtime']."')");
		api_json_result(1,0,"报名成功",$data);
		*/

	}
	else
	{
		api_json_result(1,1,"不能重复报名",$data);
	}

	
}


//索取门票
if($ac=='dz_ticket_event_list')
{
	$field_uid = $_G['gp_field_uid'];
	if($field_uid)
	{
		$big_where=" and (event_viewtype='B' or (event_viewtype='A' and field_uid='".$field_uid."') or (event_viewtype='Q' and field_uid='".$field_uid."'))  and event_is_ticket='Y' and event_is_ticket_bwvip='N' ";
	}
	else
	{
		$big_where=" and (event_viewtype='B' or event_viewtype='A' or event_viewtype='S') and event_is_ticket='Y' ";
	}
	
	$sql = "select event_id,event_name,field_uid,event_logo,event_starttime,event_endtime,event_ticket_status,event_ticket_wapurl from tbl_event where 1 ".$big_where." order by event_sort desc ";
	
	$list=DB::query($sql);
	$event_list = array();
	while($row = DB::fetch($list))
	{
		$row['event_logo'] = $site_url.'/'.$row['event_logo'];
		$y_s=date('m',$row['event_starttime']);
		$d_s=date('d',$row['event_starttime']);
		$y_e=date('m',$row['event_endtime']);
		$d_e=date('d',$row['event_endtime']);
		if($y_s==$y_e)
		{
			$row['event_starttime']=$y_s."月".$d_s."日-".$d_e."日";
		}
		else
		{
			$row['event_starttime']=$y_s."月".$d_s."日-".$y_e."月".$d_e."日";
		}
		/*
		$row['event_starttime'] = date('Y年m月d日',$row['event_starttime']);
		$row['event_starttime'] = $row['event_starttime']." - ".date('Y年m月d日',$row['event_endtime']);
		*/
		$row['wab_url'] = $row['event_ticket_wapurl'];
		
		
		$row2 = DB::fetch_first("select ad_url,ad_file,ad_file_iphone4,ad_file_iphone5,ad_width,ad_height from tbl_ad where event_id='".$row['event_id']."' and ad_page='ticket' order by ad_sort desc limit 1");
		
		$arr=explode("|",$row2['ad_url']);
		if(count($arr)>1)
		{
			$row2['ad_action']=$arr[0];
			$row2['ad_action_id']=$arr[1];
			$row2['ad_action_text']=$arr[2];
			$row2['event_url']=$arr[3];
		}
	
		if($row2['ad_file'])
		{
			$row2['ad_file']="".$site_url."/".$row2['ad_file'];
		}
		if($row2['ad_file_iphone4'])
		{
			$row2['ad_file_iphone4']="".$site_url."/".$row2['ad_file_iphone4'];
		}
		if($row2['ad_file_iphone5'])
		{
			$row2['ad_file_iphone5']="".$site_url."/".$row2['ad_file_iphone5'];
		}
		
		if(!empty($row2))
		{
			$row['ad_list']=$row2;
		}
		else
		{
			$row['ad_list']=null;
		}
		$event_list[] = $row;
	}
	if(empty($event_list))
	{
		$event_list = null;
	}
	$data['title'] = 'event_list';
	$data['data'] = $event_list;
	
	api_json_result(1,0,"成功",$data);
}






?>