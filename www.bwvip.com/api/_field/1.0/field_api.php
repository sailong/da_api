<?php
/*
*
* field_api.php
* by zhanglong 2013-05-21
* field app 新闻相关和球场介绍
*
*/

if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}


$ac=$_G['gp_ac'];
$language=$_G['gp_language'];
if(!$language)
{
	$language='cn';
	$language_sql=" and language='cn' ";
}
else
{
	$language_sql=" and language='".$language."' ";
}


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

//球场介绍  field_golf field_hotel field_huisuo field_meet field_other
if($ac=="field_intro")
{
	$field_uid=$_G['gp_field_uid'];
	$type=$_G['gp_type'];
	if($field_uid && $type)
	{
		$detail_data=DB::fetch_first("select about_id,about_id as blogid,about_name as arc_name,about_replynum as arc_replynum,about_addtime as dateline,about_content as content,about_tel as tel,about_tel2 from tbl_field_about where about_type='".$type."' and field_uid='".$field_uid."' ".$language_sql." order by about_id desc limit 1 ");
		
		$detail_data['content']=strip_tags($detail_data['content'],"");
		
		//$detail_data['content'] = "<div style='font-size:18px; line-height:180%; width:100%; bakcground:red; '>".$detail_data['content'];
		//$detail_data['content'] = $detail_data['content']."</div>";
		
		if($detail_data['dateline'])
		{
			$detail_data['dateline']=date("Y-m-d G:i",$detail_data['dateline']);
		}
    	if(empty($detail_data)) {
            $detail_data = null;
        }
        else{
            $pic_res=DB::query("select pic_id,pic_url from tbl_field_about_pic where about_id='{$detail_data['about_id']}' order by pic_id desc limit 0,10");
    		while($row=DB::fetch($pic_res))
    		{
    			if(!empty($row['pic_url'])) {
    			    $pic_list[]=$site_url."/".$row['pic_url'];
    			}
    		}
    		$detail_data['about_pic_list'] = $pic_list;
        }
        
		$data['title']='detail_data';
		$data['data']=$detail_data;
		//print_r($data);
		if($detail_data['id'])
		{
			api_json_result(1,0,$app_error['event']['10502'],$data);
		}
		else
		{
			//$data['data']=null;
			api_json_result(1,0,"没有数据",$data);
		}
	
	}
	else
	{
		api_json_result(1,0,"参数不完整",$data);
	}
	
}


//球场介绍 field_golf field_hotel field_huisuo field_meet field_other
if($ac=="field_intro_list")
{
	$field_uid=$_G['gp_field_uid'];
	$type=$_G['gp_type'];
	if($field_uid && $type)
	{
		$list=DB::query("select about_id,about_id as blogid,about_name as arc_name,about_replynum as arc_replynum,about_addtime as dateline,about_content as content,about_tel as tel,about_tel2,about_pic,about_more from tbl_field_about where about_type='".$type."' and field_uid='".$field_uid."' ".$language_sql." ");
		while($row=DB::fetch($list))
		{
			$row['content']=strip_tags($row['content'],"");
			if($row['dateline'])
			{
				$row['dateline']=date("Y-m-d G:i",$row['dateline']);
			}
			
			$row['about_pic_small']="";
			$row['about_pic_info']=array();
			if($row['about_pic'])
			{
				$row['about_pic']=$site_url."/".$row['about_pic'];
				$extname=end(explode(".",$row['about_pic']));
				$row['about_pic_small']=$row['about_pic']."_small.".$extname;
				$row['about_pic_info']=getimagesize($row['about_pic']);
			}
			$list_data[]=array_default_value($row);
		}
		
		$data['title']='list';
		$data['data']=$list_data;
		
		api_json_result(1,0,$app_error['event']['10502'],$data);
	
	}
	else
	{
		api_json_result(1,0,"参数不完整",$data);
	}
}


//球场介绍评论列表
if($ac=="field_intro_comment")
{

	$ecname=getecprefix();
	$blogid=$_G['gp_blogid'];
	$total=DB::result_first("select count(cid) from tbl_comment where idtype='field_about_id' and id='".$blogid."'  ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{
		$list=DB::query("select cid,uid,id,idtype,authorid,author,dateline,message from tbl_comment where idtype='field_about_id' and id='".$blogid."' order by dateline desc  limit $page_start,$page_size ");
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

			$row['touxiang']="".$site_url."/uc_server/avatar.php?uid=".$row['authorid']."&size=small";
			$row['dateline']=date("Y-m-d G:i:s",$row['dateline']);
			$list_data[]=array_default_value($row);
		}
	}
    if(empty($list_data)) {
        $list_data = null;
    }
	$data['title']="list";
	$data['data']=$list_data;
	api_json_result(1,0,$app_error['event']['10502'],$data);
	

}



//球场介绍评论   添加评论
if($ac=="field_intro_comment_add")
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
		$weibo_text=cutstr_html($arr[1]);
	}
	else
	{
		$weibo_text=cutstr_html($arr[0]);
	}
	
	
	$res=DB::query("insert into tbl_comment (parent_cid,uid,id,idtype,authorid,author,dateline,message) values ('".$parent_cid."','".$uid."','".$blogid."','field_about_id','".$authorid."','".$author."','".time()."','".$message."') ");
	$res2=DB::query("update tbl_field_about set about_replynum=about_replynum+1 where about_id='".$blogid."' ");
	
	//echo "update tbl_field_about set about_replynum=about_replynum+1 where about_id='".$blogid."' ";
	//echo "<hr>";

	if($is_feed)
	{
		//weibo_add
		//$res3=DB::query("INSERT INTO `pre_home_feed` ( `appid`, `icon`, `uid`, `username`, `dateline`, `friend`, `hash_template`, `hash_data`, `title_template`, `title_data`, `body_template`, `body_data`, `body_general`, `image_1`, `image_1_link`, `image_2`, `image_2_link`, `image_3`, `image_3_link`, `image_4`, `image_4_link`, `target_ids`, `id`, `idtype`, `hot`) VALUES ( 0, 'comment', '".$authorid."', '".$author."', '".time()."', 0, '', '".$authorid."', '<a href=\"home.php?mod=space&uid=".$authorid."\">".$author."</a> 评论了 <a href=\"home.php?mod=space&uid=".$authorid."\">".$blog_username."</a> 的日志 <a href=\"home.php?mod=space&uid=".$authorid."&do=blog&id=".$blogid."\">".$blog_title."</a>', 'a:3:{s:6:\"touser\";s:55:\"<a href=\"home.php?mod=space&uid=".$authorid."\">".$author."</a>\";s:4:\"blog\";s:126:\"<a href=\"home.php?mod=space&uid=".$blog_uid."&do=blog&id=".$blogid."\">".$blog_title."</a>\";s:9:\"hash_data\";s:11:\"".$author."\";}', '', 'a:0:{}', '', '', '', '', '', '', '', '', '', '', 0, '', 0);");
		
		$res2=DB::query("insert into jishigou_topic (uid,username,content,type,totid,roottid,dateline) values ('".$authorid."','".$author."','".$weibo_text."','first','0','0','".time()."') ");
		
	}

	api_json_result(1,"0","发送成功",$res2);

}



//删除评论 球场介绍评论
if($ac=="field_intro_comment_delete")
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
			$res2=DB::query("update tbl_field_about set about_replynum=about_replynum-1 where about_id='".$blogid."' ");
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




//全部资讯 
if($ac=="all_news")
{
	$field_uid=$_G['gp_field_uid'];
	$page_size=9;
	$total=DB::result_first("select count(arc_id) from tbl_arc where arc_model='arc' and arc_state=1 and (arc_type='B' or (arc_type='Q' and field_uid='".$field_uid."')) and arc_viewtype='normal' $language_sql ");
	//echo "select count(arc_id) from tbl_arc where arc_model='arc' and arc_state=1 and (arc_type='B' or (arc_type='Q' and field_uid='".$field_uid."')) and arc_viewtype='normal'  ";
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{

		$list=DB::query("select arc_id as blogid,arc_name as subject,arc_replynum as replynum,arc_viewtype as view_type,arc_pic as pic ,arc_addtime as dateline,arc_content as content from tbl_arc where  arc_model='arc' and arc_state=1 and arc_viewtype='normal' and (arc_type='B' or (arc_type='Q' and field_uid='".$field_uid."')) $language_sql order by arc_addtime desc limit $page_start,$page_size");
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
			$row = array_default_value($row);
			$row = check_field_to_relace($row, array('replynum'=>'0'));
			$list_data[]=$row;
			
			$i++;
		}
			
	}

	//处理
	$normal_1=array_slice($list_data,0,3,true);
	$normal_2=array_slice($list_data,3,3,true);
	$normal_3=array_slice($list_data,6,3,true);


	$page_size=3;
	$total=DB::result_first("select count(arc_id) from tbl_arc where arc_model='arc' and arc_state=1 and arc_viewtype='pic' and (arc_type='B' or (arc_type='Q' and field_uid='".$field_uid."')) ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{
		$list=DB::query("select arc_id as blogid,arc_name as subject,arc_replynum as replynum,arc_viewtype as view_type,arc_pic as pic ,arc_addtime as dateline,arc_content as content from tbl_arc where  arc_model='arc' and arc_state=1  and arc_viewtype='pic' and (arc_type='B' or (arc_type='Q' and field_uid='".$field_uid."')) $language_sql order by arc_addtime desc  limit $page_start,$page_size");
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
			$row = array_default_value($row);
			$row = check_field_to_relace($row, array('replynum'=>'0'));
			$pic_list[]=$row;
			$i++;
		}
	}

	$pic_1=array_slice($pic_list,0,1,true);
	$pic_2=array_slice($pic_list,1,1,true);
	$pic_3=array_slice($pic_list,2,1,true);

	$list_data=array_merge($pic_1,$normal_1,$pic_2,$normal_2,$pic_3,$normal_3);

    if(empty($list_data)) {
        $list_data = null;
    }

	$data['title']="list_data";
	$data['data']=$list_data;

	//print_r($data);
	api_json_result(1,0,$app_error['event']['10502'],$data);

}



//行业资讯 arctype_id=2
/*
if($ac=="golf_news")
{
	
	$total=DB::result_first("select count(arc_id) from tbl_arc where arc_model='arc' and arctype_id=2 ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{

			$list=DB::query("select arc_id as blogid,arc_name as subject,arc_replynum as replynum,arc_viewtype as view_type,arc_pic as pic ,arc_addtime as dateline,arc_content as content from tbl_arc where  arc_model='arc' and arc_state=1 and arctype_id=2  order by arc_addtime desc  limit $page_start,$page_size");
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
				$list_data[]=$row;
				$i++;
			}

			
	}	
			$data['title']="list_data";
			$data['data']=$list_data;
			api_json_result(1,0,$app_error['event']['10502'],$data);


}
*/



//球场资讯 arc_type=Q
if($ac=="field_news")
{
	$field_uid=$_G['gp_field_uid'];
	$total=DB::result_first("select count(arc_id) from tbl_arc where arc_model='arc' and arc_type='Q' and field_uid='".$field_uid."'  $language_sql ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{

		$list=DB::query("select arc_id as blogid,arc_name as subject,arc_replynum as replynum,arc_viewtype as view_type,arc_pic as pic ,arc_addtime as dateline,arc_content as content,FROM_UNIXTIME(arc_addtime, '%Y%m%d') as today from tbl_arc where  arc_model='arc' and arc_state=1 and arc_type='Q' and field_uid='".$field_uid."'  $language_sql order by today desc,arc_sort desc,arc_addtime desc limit $page_start,$page_size");
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
			$row = array_default_value($row);
			$row = check_field_to_relace($row, array('replynum'=>'0'));
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



//赛事报道 详细
if($ac=="news_detail")
{

	$blogid=$_G['gp_blogid'];
	$pic_width=$_G['gp_pic_width'];
	if($blogid)
	{
		$detail_data=DB::fetch_first("select uid,arc_type,arc_id as blogid,arc_name as subject,arc_replynum as replynum,arc_viewtype as view_type,arc_pic as pic ,arc_addtime as dateline,arc_content as content from tbl_arc where arc_id='".$blogid."'  ");
		$detail_data['username']="";
		
		
		$detail_data['content']=strip_tags($detail_data['content'],"<p><img><br><div>");
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
			$detail_data['pic']=str_replace("data/attachment/album/upload/","upload/",$detail_data['pic']);
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
		}
		
		//$list=DB::query("select arc_id as blogid,arc_name as subject,arc_addtime as dateline from tbl_arc where arc_id<>'".$blogid."' order by arc_addtime desc limit 2");
		$list=DB::query("select arc_id as blogid,arc_name as subject,arc_addtime as dateline from tbl_arc where arc_state=1 and arc_type='".$detail_data['arc_type']."'  order by arc_addtime desc limit 2");
		while($row = DB::fetch($list))
		{
			if($row['dateline'])
			{
				$row['dateline']=date("Y-m-d G:i",$row['dateline']);
			}
			$list_data[]=array_default_value($row);
		}

		//print_r($list_data);
        
	    if(empty($detail_data)) {
            $detail_data = null;
        }
	    if(empty($list_data)) {
            $list_data = null;
        }
		if($detail_data)
		{
		    $detail_data = array_default_value($detail_data);
		    $detail_data = check_field_to_relace($detail_data,array('replynum'=>'0'));
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
if($ac=="news_detail_comment")
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

		$row['touxiang']="".$site_url."/uc_server/avatar.php?uid=".$row['authorid']."&size=small";
		$row['dateline']=date("Y-m-d G:i:s",$row['dateline']);
		$list_data[]=array_default_value($row);
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
if($ac=="news_detail_comment_add")
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
		$weibo_text=cutstr_html($arr[1])." ".$site_url."/arc/".$blogid.".html";
	}
	else
	{
		$weibo_text=cutstr_html($arr[0])." ".$site_url."/arc/".$blogid.".html";
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
if($ac=="news_comment_delete")
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
        if(isset($fields[$key]) && empty($arr[$key])) {
            $arr[$key]=$fields[$key];
        }
    }

    
    return $arr;
}





?>