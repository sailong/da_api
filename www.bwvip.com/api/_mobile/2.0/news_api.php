<?php
/*
*
* bwvip.com
* 新闻相关
*

【修改记录】
1、自2013-12-20日起，所有新闻相关接口均转移至该文件，除评论外。




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
$page2=$_G['gp_page'];
if(!$page2)
{
	$page2=1;
}
$page_size2=$_G['gp_page_size'];
if(!$page_size2)
{
	$page_size2=9;
}
if($page2==1)
{
	$page_start2=0;
}
else
{
	$page_start2=($page2-1)*($page_size2);
}

$root_path = dirname(dirname(dirname(dirname(__FILE__))));



//分享统计
if($ac == 'share_count')
{
	if(strpos($_SERVER['HTTP_USER_AGENT'],"iPhone"))
	{
		$agent="iPhone";
	}
	else if(strpos($_SERVER['HTTP_USER_AGENT'],"iPad"))
	{
		$agent="iPad";
	}
	else if(strpos($_SERVER['HTTP_USER_AGENT'],"iPod"))
	{
		$agent="iPod";
	}
	else if(strpos($_SERVER['HTTP_USER_AGENT'],"iOS"))
	{
		$agent="iOS";
	}
	else if(strpos($_SERVER['HTTP_USER_AGENT'],"Android"))
	{
		$agent="Android";
	}
	else
	{
		$agent='other';
	}

	$type=$_G['gp_type'];
	$arc_id=$_G['gp_arc_id'];
	$uid=$_G['gp_uid'];
	$ip = get_real_ip();
	if($type && $arc_id)
	{
		$rs=DB::query("update tbl_arc set ".$type."=".$type."+1 where arc_id='".$arc_id."'  ");
		$now_time = time();
		DB::query("insert into tbl_share_log(uid,arc_id,type,agent,ip,addtime) values('{$uid}','{$arc_id}','{$type}','{$agent}','{$ip}','{$now_time}')");
		api_json_result(1,0,'分享成功',$data);
	}
	else
	{
		api_json_result(1,0,'参数不完整',$data);
	}
	
}



//添加新闻
if($ac=="news_add")
{
	$uid=$_G['gp_uid'];
	$arc_name=$_G['gp_arc_name'];
	$arc_content=$_G['gp_arc_content'];
	
	
	
	if($uid && $arc_name && $arc_content)
	{
		DB::query(" insert into tbl_arc (uid,arc_name,field_uid,arc_type,arctype_id,arc_content,arc_state,arc_addtime) values ('".$uid."','".$arc_name."','0','U','0','".addslashes($arc_content)."','0','".time()."') ");
		
		$new_id=DB::result_first("select arc_id from tbl_arc where uid='".$uid."' order by arc_id desc limit 1 ");
		
		if($new_id)
		{
			$res=DB::query("insert into pre_home_blog (blogid,uid,subject,replynum,dateline) values ('".$new_id."','".$uid."','".$arc_name."','0','".time()."')");
			$res2=DB::query("insert into pre_home_blogfield (blogid,uid,message,pic) values ('".$new_id."','".$uid."','".addslashes($arc_content)."','')");
			
			$table_info=DB::fetch_first("show table status where name ='tbl_arc'");
			$up=DB::query("ALTER TABLE `pre_home_blog` AUTO_INCREMENT=".$table_info['Auto_increment']." ");
			
			$data['title']='detail_data';
			$data['data']=array(
				'msg'=>'发布成功',
				'new_arc_id'=>$new_id
			);
			
			api_json_result(1,0,$app_error['event']['10502'],$data);
			
		}
		
		
	}
	else
	{
		api_json_result(1,1,'标题和内容必须填写',$data);
		
	}
	
	
}




//添加新闻图片
if($ac=="news_add_pic")
{
	$arc_id=$_G['gp_arc_id'];
	$uid=$_G['gp_uid'];
	$pic_order=$_G['gp_pic_order'];
	
	if($uid && $arc_id && $pic_order!="")
	{
		if($_FILES["pic"]["error"]<=0 && $_FILES["pic"]["name"])
		{
			$full_save_path="./upload/arc_user/";
			if(!file_exists($full_save_path))
			{
				mkdir($full_save_path);
			}
			$up_res=move_uploaded_file($_FILES["pic"]["tmp_name"], $full_save_path.time().$_FILES["pic"]["name"]);//将上传的文件存储到服务器
			if($up_res)
			{
				
				$file_url=$full_save_path.time().$_FILES["pic"]["name"];

				// echo $file_url;
				// echo "<hr>";
				$extname=end(explode(".",$file_url));
				if($extname=="jpg")
				{
					$pic_source=imagecreatefromjpeg($file_url);
				}
				else if($extname=="gif")
				{
					$pic_source=imagecreatefromgif($file_url);
				}
				else if($extname=="png")
				{
					$pic_source=imagecreatefrompng($file_url);
				}
				else
				{
					api_json_result(1,1,"文件类型不支持",$data);
				}

				
				$filename_s = str_replace(".".$extname,'_s.'.$extname,$file_url);
				// echo $filename_s;
				// echo "<hr>";
				if($pic_source && $extname)
				{
					$aa=resizeImage($pic_source,160,160,$filename_s,".".$extname);
				}
				else
				{
					api_json_result(1,1,"图片格式不支持",$data);
				}
			
			}
			else
			{
				api_json_result(1,1,"文件上传失败",$data);
			}
			
			
			if($file_url)
			{
				$pic_str=addslashes('<p><img src="'.$file_url.'"></p>');
				if($pic_order==1)
				{
					$sql =", arc_pic='".$filename_s."'  ";
					$sql2 =", pic='".$filename_s."'  ";
				}
				
				// echo "update tbl_arc set arc_content=REPLACE(arc_content,'{img".$pic_order."}', '".$pic_str."') ".$sql." where arc_id=".$arc_id."; ";
				
				$up=DB::query("update tbl_arc set arc_content=REPLACE(arc_content,'{img".$pic_order."}', '".$pic_str."') ".$sql." where arc_id=".$arc_id."; ");
				
				$up=DB::query("update pre_home_blogfield set message=REPLACE(message,'{img".$pic_order."}', '".$pic_str."') ".$sql2." where blogid=".$arc_id."; ");
				
			}
			
			

		}
		
		api_json_result(1,0,"上传成功",$data);
	}
	else
	{
		api_json_result(1,1,"新闻ID不能为空",$data);
	}
	
	
}









//专家讲堂 用户发布的新闻列表
if($ac=="user_list")
{
	$field_uid=$_G['gp_field_uid'];
	$uid=$_G['gp_uid'];
	if($uid)
	{
		$sql .=" and uid='".$uid."' ";
	}
	
	
	
	
	$total=DB::result_first("select count(arc_id) from tbl_arc where arc_model='arc' and arc_type='U' ".$sql." ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{

		$list=DB::query("select arc_id as blogid,arc_name as subject,arc_replynum as replynum,arc_viewtype as view_type,arc_pic as pic ,arc_addtime as dateline,arc_content as content,FROM_UNIXTIME(arc_addtime, '%Y%m%d') as today,uid, (select realname from pre_common_member_profile where uid=tbl_arc.uid ) as realname from tbl_arc where arc_model='arc' and arc_type='U'  ".$sql." $language_sql  order by today desc,arc_sort desc,arc_addtime desc limit $page_start,$page_size");
		
		$i=0;
		while($row = DB::fetch($list))
		{
		
			$row['replynum']="".$row['replynum'];
			if($row['pic'])
			{
				$row['pic']="".$site_url."/".$row['pic'];
			}
			$row['dateline']=date("Y-m-d G:i:s",$row['dateline']);
			$row['content']=msubstr(cutstr_html($row['content']),0,30);
			$row = array_default_value($row);
			
			$list_data[]=$row;
			$i++;
		}

			
	}
	
    if(empty($list_data)) {
        $list_data = null;
    }
	$data['title']="list_data";
	$data['data']=$list_data;
	api_json_result(1,0,$app_error['event']['10502'],$data);

}




//全部资讯     大正行业资讯 + 球场新闻
if($ac=="all_list")
{
	$field_uid=$_G['gp_field_uid'];
	$page_size=9;
	
	if($page==1)
	{
		$page_start=0;
	}
	else
	{
		$page_start=($page-1)*($page_size);
	}


	$total=DB::result_first("select count(arc_id) from tbl_arc where arc_model='arc' and arc_state=1 and arc_viewstatus=1  and (arctype_id=2 or arc_type='Q') and arc_viewtype='normal' $language_sql ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{

		$list=DB::query("select field_uid,arc_id as blogid,arc_name as subject,arc_replynum as replynum,arc_viewtype as view_type,arc_pic as pic ,arc_addtime as dateline,arc_content as content,FROM_UNIXTIME(arc_addtime,'%Y%m%d') as today,arc_top from tbl_arc where  arc_model='arc' and arc_state=1 and arc_viewtype='normal' and arc_state=1 and arc_viewstatus=1 and (arctype_id=2 or arc_type='Q')  $language_sql order by arc_top desc,today desc,arc_sort desc limit $page_start,$page_size");
		$i=0;
		while($row = DB::fetch($list))
		{
			$row['uid']=0;
			$row['replynum']="".$row['replynum'];
			if($row['pic'])
			{
				$row['pic']="".$site_url."/".$row['pic'];
			}
			$row['dateline']=date("Y-m-d G:i:s",$row['dateline']);
			$row['content']=msubstr(cutstr_html($row['content']),0,30);
			
			if($row['field_uid']==1186)
			{
				$row['replynum']=$row['replynum']." - 来自美兰湖球场";
			}
			else if($row['field_uid']==1160)
			{
				$row['replynum']=$row['replynum']." - 来自南山球会";
			}
			else
			{
				
			}
			
			$row = array_default_value($row);
			//$row = check_field_to_relace($row, array('replynum'=>'0'));
			$list_data[]=$row;
			
			$i++;
		}
			
	}

	//处理
	$normal_1=array_slice($list_data,0,3,true);
	$normal_2=array_slice($list_data,3,3,true);
	$normal_3=array_slice($list_data,6,3,true);


	$page_size=3;
	
	if($page==1)
	{
		$page_start=0;
	}
	else
	{
		$page_start=($page-1)*($page_size);
	}

	
	
	$total=DB::result_first("select count(arc_id) from tbl_arc where arc_model='arc' and arc_state=1 and arc_viewstatus=1 and arc_viewtype='pic' and arc_state=1 and (arctype_id=2 or arc_type='Q')  ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{
		$list=DB::query("select field_uid,arc_id as blogid,arc_name as subject,arc_replynum as replynum,arc_viewtype as view_type,arc_pic as pic ,arc_addtime as dateline,arc_content as content,FROM_UNIXTIME(arc_addtime,'%Y%m%d') as today,arc_top from tbl_arc where  arc_model='arc' and arc_state=1 and arc_viewstatus=1  and arc_viewtype='pic' and (arctype_id=2 or arc_type='Q') $language_sql order by arc_top desc,today desc,arc_sort desc limit $page_start,$page_size");
		$i=0;
		while($row = DB::fetch($list))
		{
			$row['uid']=0;
			$row['replynum']="".$row['replynum'];
			if($row['pic'])
			{
				$row['pic']="".$site_url."/".$row['pic'];
			}
			$row['dateline']=date("Y-m-d G:i:s",$row['dateline']);
			$row['content']=msubstr(cutstr_html($row['content']),0,30);
			if($row['field_uid']==1186)
			{
				$row['replynum']=$row['replynum']." - 来自美兰湖球场";
			}
			else if($row['field_uid']==1160)
			{
				$row['replynum']=$row['replynum']." - 来自南山球会";
			}
			else
			{
				
			}
			
			$row = array_default_value($row);
			//$row = check_field_to_relace($row, array('replynum'=>'0'));
			$pic_list[]=$row;
			$i++;
		}
	}

	$pic_1=array_slice($pic_list,0,1,true);
	$pic_2=array_slice($pic_list,1,1,true);
	$pic_3=array_slice($pic_list,2,1,true);

	$list_data=array_merge($pic_1,$normal_1,$pic_2,$normal_2,$pic_3,$normal_3);

    if(empty($list_data)) 
	{
        $list_data = null;
    }

	$data['title']="list_data";
	$data['data']=$list_data;

	//print_r($data);
	api_json_result(1,0,$app_error['event']['10502'],$data);

}






//新闻 详细
if($ac=="news_detail")
{
	
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
			//$detail_data['arc_video_pic_info']=getimagesize($detail_data['arc_video_pic']);
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
			if(file_exists($pic_arr[$n]['pic_info']))
			{
				$pic_arr[$n]['pic_info']=getimagesize($pic_url);
			}
			else
			{
				$pic_arr[$n]['pic_info']=null;
			}
			
			
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
		
		$list=DB::query("select arc_id as blogid,arc_name as subject,arc_addtime as dateline from tbl_arc where arc_state=1 and arc_type='".$detail_data['arc_type']."' and arc_id<>'".$blogid."' order by arc_addtime desc limit 2");
		while($row = DB::fetch($list))
		{
			if($row['dateline'])
			{
				$row['dateline']=date("Y-m-d G:i",$row['dateline']);
			}
			$list_data[]=$row;
		}

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






//新闻 评论列表
if($ac=="comment_list")
{

	$ecname=getecprefix();
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
if($ac=="comment_add")
{

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
		
		$res2=DB::query("insert into jishigou_topic (uid,username,content,type,totid,roottid,dateline) values ('".$authorid."','".$author."','".$weibo_text."','first','0','0','".time()."') ");
		
	}

	api_json_result(1,"0","发送成功",$res2);

}



//新闻 删除评论
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
			$res2=DB::query("update tbl_arc set arc_replynum=arc_replynum-1 where arc_id='".$blogid."' ");
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



?>