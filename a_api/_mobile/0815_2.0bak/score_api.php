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



//更改城市
if($ac=="select_city")
{
	$upid=$_G['gp_upid'];
	if($upid)
	{
		$sql=" and upid='".$upid."' ";
	}
	else
	{
		$sql=" and upid='0' ";
	}
	
	$total=DB::result_first("select count(id) from ".DB::table("common_district")." where 1=1 ".$sql." ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{

			$list=DB::query("select id,name,(select count(id) from ".DB::table("common_field")." where province=".DB::table("common_district").".id ) as qiuchang_num  from ".DB::table("common_district")." where 1=1 ".$sql." order by id asc limit $page_start,$page_size ");
			while($row = DB::fetch($list) )
			{
				$list_data[]=$row;
			}

	}//end page

	$data['title']		= "list_data";
	$data['data']		= $list_data;

	//print_r($data);
	api_json_result(1,0,$app_error['event']['10502'],$data);
	
}


//选择球场
if($ac=="select_field")
{
	$city=$_G['gp_city'];
	$province=$_G['gp_province'];
	
	if($city)
	{
		$sql=" and city='".$city."' ";
	}
	if($province)
	{
		$sql=" and province='".$province."' ";
	}

	$map_x=$_G['gp_map_x'];
	$map_y=$_G['gp_map_y'];

	$k=$_G['gp_k'];
	if($k)
	{
		$k_sql=" and fieldname like '%".$k."%' ";
	}
	

	$total=DB::result_first("select count(id) from ".DB::table("common_field")." where 1=1 ".$sql." ".$k_sql." ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{

		$list=DB::query("select id,fieldid,uid,fieldname,map_x,map_y,round(6378.138*2*asin(sqrt(pow(sin( (map_x*pi()/180-".$map_x."*pi()/180)/2),2)+cos(map_x*pi()/180)*cos(".$map_x."*pi()/180)* pow(sin( (map_y*pi()/180-".$map_y."*pi()/180)/2),2)))*1000) as juli2 from ".DB::table("common_field")." where 1=1  and map_x>0 and map_y>0 ".$sql."  ".$k_sql." order by juli2 asc limit $page_start,$page_size ");

		while($row = DB::fetch($list) )
		{

			$row['icon']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=middle";
			
			$sub_list=DB::query("select coursetype,par from ".DB::table("common_course")." where uid='".$row['uid']."' group by coursetype order by coursetype asc");
			$i=0;
			while($row_sub=DB::fetch($sub_list))
			{
				
				$row_sub['par']=str_replace(",","|",$row_sub['par']);
				$row['chang'][]=$row_sub;
				$i++;
			}
			if($i==0)
			{
				$row['chang']="";
			}
			
			//$row['chang']=$sub_data;

			$row['dong']="18";
			//$juli=get_juli($row['map_x'],$row['map_y'],$map_x,$map_y);
			//$juli=intval($juli)%1000;
			$juli=round(($row['juli2']/1000),1);
			$row['juli']=$juli."公里";
			
			if($i>0)
			{
				$list_data[]=$row;
			}
			
		}

	}//end page

	for($i=0; $i<count($list_data); $i++)
	{
		if($list_data[$i]['chang']=="")
		{
			//unset($list_data[$i]);
		}
	}

	$data['title']		= "list_data";
	$data['data']		= $list_data;

	//print_r($data);
	api_json_result(1,0,$app_error['event']['10502'],$data);
	
}


//添加成功卡
if($ac=="score_add")
{
	$uid=$_G['gp_uid'];

	$field_uid=$_G['gp_field_uid'];
	$out=$_G['gp_out'];
	$out_par=$_G['gp_out_par'];
	$in=$_G['gp_in'];
	$in_par=$_G['gp_in_par'];

	$a=explode("|",$out_par);
	$n=0;
	for($i=0; $i<count($a); $i++)
	{
		$n=$i+1;
		$bzg +=$a[$i];
		$dong_names=$dong_names.$out.$n."|";
	}
	//echo $bzg;
	$b=explode("|",$in_par);
	$n=0;
	for($i=0; $i<count($b); $i++)
	{
		$n=$i+1;
		$bzg2 +=$b[$i];
		$dong_names=$dong_names.$in.$n."|";
	}
	$total_bzg=$bzg+$bzg2;
	$dong_names=substr($dong_names,0,strlen($dong_names)-1); 

	$par=$out_par."|".$bzg."|".$in_par."|".$bzg2."|".$total_bzg;
	$tee=$_G['gp_tee'];

	$res=DB::query(" insert into ".DB::table("common_score")." (uid,sais_id,tee,fuid,par,addtime,dateline,dong_names,source,is_edit) values ('".$uid."','0','".$tee."','".$field_uid."','".$par."','".time()."','".time()."','".$dong_names."','user','Y') ");
	//echo " insert into ".DB::table("common_score")." (uid,sais_id,tee,fuid,par,addtime,dateline,dong_names) values ('".$uid."','0','".$tee."','".$field_uid."','".$par."','".time()."','".time()."','".$dong_names."') ";

	$max_id=DB::result_first("select max(id) from ".DB::table("common_score")." where uid='".$uid."' and sais_id=0 ");
	$res2=DB::query(" update ".DB::table("common_score")." set group_id='".$max_id."' where id='".$max_id."'  ");

	api_json_result(1,0,$max_id,$data);

}


//成绩卡修改页
if($ac=="score_edit")
{
	$id=$_G["gp_id"];
	

	//个人信息
	$detail_info=DB::fetch_first("select id,uid,fuid,par,score,dong_names,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_score").".uid) as realname,tuigan from ".DB::table("common_score")." where id='".$id."' ");
	if($detail_info['id'])
	{
		
		if($detail_info['score'])
		{
			$arr=explode("|",$detail_info['score']);
			$detail_info['score_sub']=explode("|",$detail_info['score']);
			$tuigan=explode("|",$detail_info['tuigan']);
			unset($arr[9]);
			unset($arr[19]);
			unset($arr[20]);
			$c1=implode(",",$arr);
			$c_str=explode(",",$c1);
			//echo count($c_str);
			for($i=0; $i<count($c_str); $i++)
			{
				if($tuigan[$i])
				{
					$c_str[$i]=$c_str[$i]."/".$tuigan[$i];
				}
				else
				{
					$c_str[$i]=$c_str[$i]."/0";
				}
				
			}
			$detail_info['score']=$c_str;
		}

		if($detail_info['tuigan'])
		{
			$detail_info['tuigan']=explode("|",$detail_info['tuigan']);
			
			$detail_info['tuigan'][18]="0";
			$detail_info['tuigan'][19]="0";
			$detail_info['tuigan'][20]="0";

			for($i=0; $i<count($detail_info['tuigan']); $i++)
			{
				if($detail_info['tuigan'][$i]=="")
				{
					$detail_info['tuigan'][$i]="0";
				}
			}
			
		}

		if($detail_info['par'])
		{
			$ccc=str_replace("|",",",$detail_info['par']);
			$crr=explode(",",$ccc);
			unset($crr[9]);
			unset($crr[19]);
			unset($crr[20]);
			$c2=implode(",",$crr);
			$detail_info['par']=explode(",",$c2);
		}

		$brr=explode("|",$detail_info['dong_names']);
		$detail_info['dong_names']=$brr;
		$detail_data=$detail_info;
	}

	//同组
	$list=DB::query("select id,uid,fuid,par,score,dong_names,source,is_edit,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_score").".uid) as realname,tuigan from ".DB::table("common_score")." where group_id='".$id."' and id<>'".$id."' order by parent_id asc ");
	while($row=DB::fetch($list))
	{
		
		if($row['score'])
		{
			$arr=explode("|",$row['score']);
			$brr=explode("|",$row['tuigan']);
			unset($arr[9]);
			unset($arr[19]);
			unset($arr[20]);
			$c1=implode(",",$arr);
			$c_str=explode(",",$c1);
			for($i=0; $i<count($c_str); $i++)
			{
				if($brr[$i])
				{
					$c_str[$i]=$c_str[$i]."/".$brr[$i];
				}
				else
				{
					$c_str[$i]=$c_str[$i]."/0";
				}
			}
			$row['score']=$c_str;
		}

		if($row['tuigan'])
		{
			$row['tuigan']=explode("|",$row['tuigan']);
			
			$row['tuigan'][18]="0";
			$row['tuigan'][19]="0";
			$row['tuigan'][20]="0";

			for($i=0; $i<count($row['tuigan']); $i++)
			{
				if($row['tuigan'][$i]=="")
				{
					$row['tuigan'][$i]="0";
				}
			}
			
		}

		if($row['par'])
		{
			$ccc=str_replace("|",",",$row['par']);
			$crr=explode(",",$ccc);
			unset($crr[9]);
			unset($crr[19]);
			unset($crr[20]);
			$c2=implode(",",$crr);
			$row['par']=explode(",",$c2);
		}

		$brr=explode("|",$row['dong_names']);
		$row['dong_names']=$brr;
		$list_data[]=$row;
	}

	$data['title']="detail_data";
	$data['data']=array(
		'detail_data'=>$detail_data,	
		'list_data'=>$list_data
	);
	api_json_result(1,0,null,$data);

}


//成绩卡展示页
if($ac=="score_detail")
{
	$id=$_G["gp_id"];

	//单人成绩
	$detail_info=DB::fetch_first("select id,uid,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_score").".uid) as realname,fuid,par,pars,score,dong_names,sais_id,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_score").".sais_id) as sais_name,fuid,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_score").".fuid) as field_name,dateline,uploadimg,tuigan,is_edit,source from ".DB::table("common_score")." where id='".$id."' ");
	if($detail_info['id'])
	{
		$detail_info['tongzu_num']=DB::result_first("select count(id) from ".DB::table("common_score")." where group_id='".$detail_info['id']."' ");
		if($detail_info['tuigan'])
		{
			$detail_info['tuigan']=explode("|",$detail_info['tuigan']);
			$detail_info['tuigan'][18]="0";
			$detail_info['tuigan'][19]="0";
			$detail_info['tuigan'][20]="0";

			for($i=0; $i<count($detail_info['tuigan']); $i++)
			{
				if($detail_info['tuigan'][$i]=="")
				{
					$detail_info['tuigan'][$i]="0";
				}
			}
			$detail_info['tuigan']=implode("|",$detail_info['tuigan']);
			$detail_info['tuigan']=explode("|",$detail_info['tuigan']);
			
		}
		if($detail_info['uploadimg'])
		{
			$detail_info['uploadimg']=$site_url."/".$detail_info['uploadimg'];
			$detail_info['uploadimg_small']=$detail_info['uploadimg']."_small.jpg";
		}
		else
		{
			$detail_info['uploadimg']='';
			$detail_info['uploadimg_small']="";
		}

		if($detail_info['score'])
		{
			$detail_info['score']=explode("|",$detail_info['score']);
			for($i=0; $i<count($detail_info['score']); $i++)
			{
				if($detail_info['score'][$i]=="")
				{
					$detail_info['score'][$i]="0";
				}
			}
		}
		if($detail_info['par'])
		{
			$detail_info['par']=explode("|",$detail_info['par']);
		}
		if($detail_info['pars'])
		{
			$detail_info['pars']=explode("|",$detail_info['pars']);
		}
		if($detail_info['dong_names'])
		{
			$detail_info['dong_names']=explode("|",$detail_info['dong_names']);
		}

		$detail_info['dateline']=date("Y年m月d日",$detail_info['dateline']);
		$detail_data=$detail_info;
	}


	//同组成绩
	$list=DB::query("select id,uid,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_score").".uid )as realname,score from ".DB::table("common_score")." where group_id='".$id."' and id<>'".$id."' order by parent_id asc ");
	while($row=DB::fetch($list))
	{
	
		if($row['score'])
		{
			$row['score']=explode("|",$row['score']);
		}
	
		if($row['dong_names'])
		{
			$row['dong_names']=explode("|",$row['dong_names']);
		}
		
		$list_data[]=$row;
	}


	//评论列表 微博
	$tid=DB::result_first("select tid from jishigou_topic where score_id='".$id."' order by tid desc ");
	$detail_data['tid']=$tid;
	if(!$detail_data['tid'])
	{
		$detail_data['tid']=0;
	}

	if($tid)
	{
		$reply_list=DB::query("select tid,uid,
(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,replys,forwards,dateline,type,(select photo from jishigou_topic_image where tid=jishigou_topic.tid limit 1) as photo from jishigou_topic where totid='".$tid."'  order by dateline desc limit 10 ");
		while($row2 = DB::fetch($reply_list) )
		{
			$row2['content']=cutstr_html($row2['content']);
			$row2['dateline']=date("Y-m-d G:i",$row2['dateline']);
			$row2['roottid']=$tid;
			$row2['photo_big']="";
			$row2['photo_small']="";
			$row2['voice']="";
			$row2['voice_timelong']=0;
			$row2['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row2['uid']."&size=middle";
			$row2['root_topic']=null;
			$reply_data[]=$row2;
		}
	}
	

	$data['title']="detail_data";
	$data['data']=array(
		'detail_data'=>$detail_data,	
		'list_data'=>$list_data,
		'list_pinglun'=>$reply_data
	);
	//print_r($data);
	api_json_result(1,0,null,$data);

}






//修改 保存成绩卡
if($ac=="score_save")
{

	if($_G['gp_score'])
	{

		$str=$_G['gp_score'];
		$str=str_replace('\"','"',$str);
		$arr=json_decode($str,true);

		for($i=0; $i<count($arr); $i++)
		{
			$score=$arr[$i]['score'];

			$info==DB::fetch_first("select source,is_edit,uid,par from ".DB::table("common_score")." where id='".$arr[$i]['id']."'  ");
			$par=explode("|",$info['par']);
			//print_r($par);
			//echo "<br />";

			for($n=0; $n<count($score); $n++)
			{
					
				$fen = explode("/",$score[$n]);
				if($fen[0]=="")
				{
					$fen[0]="0";
				}
				if($fen[1]=="")
				{
					$fen[1]="0";
				}

				if($n<=8)
				{
					$score_left +=$fen[0];
				}
				if($n>8)
				{
					$score_right +=$fen[0];
				}
				$score_total =$score_right+$score_left;
				if($n==8)
				{
					$score_row .=$fen[0]."|".$score_left."|";	
				}
				else if($n==17)
				{
					$score_row .=$fen[0]."|".$score_right."|";	
				}
				else
				{
					$score_row .=$fen[0]."|";
				}

				if($n==17)
				{
					$tuigan .=$fen[1]."";
				}
				else
				{
					$tuigan .=$fen[1]."|";
				}

			}
			$score_row=$score_row."".$score_total;
			$score_row=str_replace('\"','"',$score_row);
			
			//pars
			$score_arr=explode("|",$score_row);
			for($j=0; $j<count($score_arr); $j++)
			{
				$pars_one=$score_arr[$j]-$par[$j];
				if($pars_one==0)
				{
					$pars_one="E";
				}
				else if($pars_one<0)
				{
					$pars_one="-".$pars_one;
				}
				else if($pars_one>0)
				{
					$pars_one="+".$pars_one;
				}
				else
				{
				}
				
				$pars_arr[]=$pars_one;
			}
			$pars=implode("|",$pars_arr);
			//echo $pars;
			//echo "<hr2>";



			
			if($arr[$i]['id'])
			{
				
				$is_edit=$info['is_edit'];
				$source=$info['source'];
				$uid=$info['uid'];
				if($source=="waika")
				{
					$res=DB::query("update ".DB::table("common_score")." set score='".$score_row."',pars='".$pars."',tuigan='".$tuigan."',is_edit='N' where id='".$arr[$i]['id']."' ");	
				}
				else
				{
					$res=DB::query("update ".DB::table("common_score")." set score='".$score_row."',pars='".$pars."',tuigan='".$tuigan."' where id='".$arr[$i]['id']."' ");
				}
				//echo "update ".DB::table("common_score")." set score='".$score_row."',pars='".$pars."',tuigan='".$tuigan."',is_edit='N' where id='".$arr[$i]['id']."' ";

				//添加微博 20130520
				if(!$result = DB::result_first(" select tid from ultrax.jishigou_topic where uid='".$uid."' and score_id=".$arr[$i]['id']))
				{
					$score_info = DB::fetch_first(" select cs.sais_id,cs.uid,cs.fuid,cs.id,cs.addtime,cmp.realname as sai_name ,cmp2.realname as qc_name from ".DB::table("common_score")." as cs LEFT JOIN ".DB::table("common_member_profile")." as cmp ON cmp.uid = cs.sais_id LEFT JOIN ".DB::table("common_member_profile")." as cmp2 ON cmp2.uid=cs.fuid where cs.id=".$arr[$i]['id']);

					$weibdata['uid']     = $score_info['uid'];
					$weibdata['fuid']    = $score_info['fuid'];
					$sais_username = DB::result_first(" select username from ".DB::table('common_member')." where uid=".$score_info['sais_id']);
					$qc_username   = DB::result_first(" select username from ".DB::table('common_member')." where uid=".$score_info['fuid']);
					$username      = DB::result_first(" select username from ".DB::table('common_member')." where uid=".$score_info['uid']);

					if($sais_username)
					{
						$weibdata['content'] ='我在'.date('Y-m-d',$score_info['addtime']).' 参加的 <M '.$qc_username.'>@'.$score_info['qc_name'].'<\/M> <M '.$sais_username.'>@'.$score_info['sai_name'].'<\/M> 比赛 成绩卡已经上传大正微博 <iframe src="/home.php?mod=space&do=common&op=score&uid='.$score_info['uid'].'&id='.$score_info['id'].'&weibo_tmp=1" width="353"  scrolling="no" frameborder="0" ><\/iframe>';
					}
					else
					{
						$weibdata['content'] ='我在'.date('Y-m-d',$score_info['addtime']).'  <M '.$qc_username.'>@'.$score_info['qc_name'].'<\/M> 的成绩卡已经上传大正微博 <iframe src="/home.php?mod=space&do=common&op=score&uid='.$score_info['uid'].'&id='.$score_info['id'].'&weibo_tmp=1" width="353"  scrolling="no" frameborder="0" ><\/iframe>';
					}
					

					DB::query(" insert into ultrax.jishigou_topic (uid,username,fuid,content,score_id,dateline,type) values ('".$weibdata['uid']."','".$username ."','".$weibdata['fuid']."','".$weibdata['content']."','".$score_info['id']."','".time()."' ,'first')  ");
				}
				
				//echo "update ".DB::table("common_score")." set score='".$par."',tuigan='".$tuigan."' where id='".$arr[$i]['id']."' ";
				//echo "<hr>";
			}

			$pars="";
			$score="";
			$score_row="";
			$score_left="";
			$score_right="";
			$tuigan="";
		}

		api_json_result(1,0,"保存成功",$data);
	}


}



//上传积分卡
if($ac=="up_pic")
{
	$id=$_G['gp_id'];	
	if($id)
	{
		if($_FILES["pic"]["error"]<=0 && $_FILES["pic"]["name"])
		{
			$save_path="./upload/score/";
			$full_save_path=$save_path.date("Ymd",time())."/";
			if(!file_exists($save_path))
			{
				mkdir($save_path);
			}
			if(!file_exists($full_save_path))
			{
				mkdir($full_save_path);
			}

			move_uploaded_file($_FILES["pic"]["tmp_name"], $full_save_path. time().$_FILES["pic"]["name"]);//将上传的文件存储到服务器
			
			$file_path="./upload/score/".date("Ymd",time())."/".time().$_FILES["pic"]["name"];
			$extname=end(explode(".",$file_path));
			if($extname=="jpg")
			{
				$pic_source=imagecreatefromjpeg($file_path);
			}

			$file_path2="./upload/score/".date("Ymd",time())."/".time().$_FILES["pic"]["name"]."_small";
			//echo $file_path2;
			if(file_exists($file_path))
			{
				$aa=resizeImage($pic_source,100,100,$file_path2,".".$extname);
				//print_r($aa);

				$res=DB::query("update ".DB::table("common_score")." set uploadimg='".$file_path."' where id='".$id."' ");	
				api_json_result(1,0,"保存成功",$data);
			}
			else
			{
				api_json_result(1,1,"保存失败",$data);
			}

		}
		else
		{
			api_json_result(1,2,"图片上传失败",$data);
		}
	}
}


//test
if($ac=="test")
{
	$file_path="/upload/score/20130508/1367994358intro_1.jpg";
	$extname="png";
	$bbb=resize_img($file_path, 125 ,75, false, 100, 0,"_thumb");
	print_r($bbb);
	//$aa=resizeImage($file_path,100,100,"aaaa",".".$extname);
?>	

<h1>1、上传成绩卡</h1>
<form method="post" action="/bw_api.php?mod=score&ac=up_pic" enctype="multipart/form-data">
ID：<input type="text" name="id" /><br />
本地图片：<input type="file" id="pic" name="pic" /><br />
<input type="submit" value="上传图片" />
</form>



<?php
}


//添加好友
if($ac=="add_friend")
{
	$from_uid=$_G['gp_from_uid'];
	$parent_id=$_G['gp_parent_id'];
	$source=$_G['gp_source'];
	$tee=$_G['gp_tee'];
	$mobile=$_G['gp_mobile'];
	

	$user=DB::fetch_first("select uid,sais_id,tee,fuid,par,dong_names from ".DB::table("common_score")." where id='".$parent_id."' limit 1 ");
	if($user['uid'])
	{

			if($source=="bwvip")
			{
				$res=DB::query(" insert into ".DB::table("common_score")." (uid,sais_id,tee,fuid,par,addtime,dateline,dong_names,parent_id,group_id,source,is_edit) values ('".$mobile."','0','".$tee."','".$user['fuid']."','".$user['par']."','".time()."','".time()."','".$user['dong_names']."','".$parent_id."','".$parent_id."','user','Y') ");
				
				$max_id=DB::result_first("select max(id) from ".DB::table("common_score")." where group_id='".$parent_id."' and uid='".$mobile."' limit 1 ");
				$data['title']="add_data";
				$data['data']=array(
						'message'=>	'添加成功',
						'id'=>	$max_id,
					);
				api_json_result(1,0,null,$data);
			}
			else
			{

				$member=DB::fetch_first("select uid from ".DB::table("common_member_profile")." where mobile='".$mobile."' limit 1 ");
				$member2=DB::fetch_first("select uid from ".DB::table("common_member")." where username='".$mobile."' limit 1 ");
				if($member['uid'] || $member2['uid'])
				{
					if($member['uid'])
					{
						$uid=$member['uid'];
					}
					else
					{
						if($member2['uid'])
						{
							$uid=$member2['uid'];
						}
					}

					$res=DB::query(" insert into ".DB::table("common_score")." (uid,sais_id,tee,fuid,par,addtime,dateline,dong_names,parent_id,group_id,source,is_edit) values ('".$uid."','0','".$tee."','".$user['fuid']."','".$user['par']."','".time()."','".time()."','".$user['dong_names']."','".$parent_id."','".$parent_id."','user','Y') ");

					$max_id=DB::result_first("select max(id) from ".DB::table("common_score")." where group_id='".$parent_id."' and uid='".$uid."' limit 1 ");
					$data['title']="add_data";
					$data['data']=array(
						'message'=>	'添加成功',
						'id'=>	$max_id,
					);
					api_json_result(1,0,null,$data);
				}
				else
				{
					//如果未注册,则增加新用户
					$t=time();
					$email=$_G['gp_email']?$_G['gp_email']:$t.'@bw.com';
					$username=$mobile;
					$password=$mobile;
					$realname=$_G['gp_realname'];

					if($username && $password &&  $email)
					{
						$uid = uc_user_register($username,$password, $email);
					}
					else
					{
						api_json_result(1,10018,$api_error['register']['10018'],null);
					}

					if($uid>0)
					{
						


						//处理用户信息
						//userlogin($username, $password);
						
						$post_string = "&username=".$username."&password=".$password."";
						$info = request_by_curl_new('".$site_url."/member.php?mod=logging&action=login&loginsubmit=yes',$post_string);
						
						DB::query("UPDATE ultrax.jishigou_members SET nickname='$realname',validate=1 WHERE ucuid='$uid'"); 
						DB::query("UPDATE ".DB::table('common_member_profile')."  SET realname='$realname',mobile='$mobile',cron_fensi_state=0  WHERE uid='$uid'"); 
						DB::query("UPDATE ".DB::table('common_member')."  SET groupid='10'  WHERE uid='$uid'"); 



						$res=DB::query("insert into ".DB::table("common_score")." (uid,sais_id,tee,fuid,par,addtime,dateline,dong_names,parent_id,group_id,source,is_edit) values ('".$uid."','0','".$tee."','".$user['fuid']."','".$user['par']."','".time()."','".time()."','".$user['dong_names']."','".$parent_id."','".$parent_id."','user','Y') ");
						$max_id=DB::result_first("select max(id) from ".DB::table("common_score")." where group_id='".$parent_id."' and uid='".$uid."' limit 1 ");

						$data['title']="add_data";
						$data['data']=array(
							'message'=>	'添加成功',
							'id'=>	$max_id,
						);

					}
					else
					{
						$data['title']="add_data";
						$data['data']=array(
							'message'=>	'添加失败',
							'id'=>	$max_id,
						);
					}

					
					
					api_json_result(1,0,null,$data);

				}
			}
		
	}//if_parent

	
}




//删除成绩卡
if($ac=="score_delete")
{
	$id=$_G['gp_id'];
	$res=DB::query("delete from ".DB::table("common_score")." where id='".$id."' ");
	$res2=DB::query("delete from jishigou_topic where score_id='".$id."' ");
	api_json_result(1,0,"删除成功",$data);
}



//邀请链接
if($ac=="score_link")
{
	$source=$_G['gp_source'];
	if($source=="bwvip")
	{
		$data['title']		= "data";
		$data['data']=array(
					  'link'=>"<通讯录名称>你好，你的好友<用户名>刚刚添加了一张和你一起打球的成绩卡。请下载大正客户端查看 ".$site_url."/app。",
					 );
	}
	else
	{
		$data['title']		= "data";
		$data['data']=array(
					  'link'=>"<受邀请者姓名>你好，你的好友<邀请者用户名>帮您注册了大正网，并邀您下载安装大正网手机客户端和他一起高球互动。用户名/密码都是<手机号码>。点击下载： ".$site_url."/app。",
					 );
	}
	
	api_json_result(1,0,$app_error['event']['10502'],$data);
}



//score_list
if($ac=="score_list")
{
	$uid=$_G['gp_uid'];
	$sid=$_G['gp_sid'];
	if($sid)
	{
		$sid_sql=" and sais_id='".$sid."' ";
	}
	$total=DB::result_first("select count(id)  from ".DB::table("common_score")." where uid='".$uid."' ".$sid_sql." ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{
		$list=DB::query("select id,uid,sais_id,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_score").".sais_id) as sais_name,fuid,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_score").".fuid) as field_name,par,score,pars,total_score,addtime,dong_names,uploadimg,is_edit from ".DB::table("common_score")." where uid='".$uid."' ".$sid_sql." order by addtime desc limit $page_start,$page_size  ");
		while($row =DB::fetch($list))
		{
			$row['par']=explode("|",$row['par']);
			$row['score']=explode("|",$row['score']);
			if($row['sais_name']==null)
			{
				$row['sais_name']="";
			}
			if($row['field_name']==null)
			{
				$row['field_name']="";
			}

			$row['icon']=$site_url."/uc_server/avatar.php?uid=".$row['fuid']."&size=middle";

			if($row['uploadimg'])
			{
				$row['uploadimg']=$site_url."/".$row['uploadimg'];
				$row['uploadimg_small']=$row['uploadimg']."_small.jpg";
			}
			else
			{
				$row['uploadimg']='';
				$row['uploadimg_small']="";
			}

			$row['addtime']=date("Y年m月d日",$row['addtime']);
			$list_data[]=$row;
		}
	}
	$data['title']='list_data';
	$data['data']=$list_data;
	api_json_result(1,0,$app_error['event']['10502'],$data);
}



//全站差点排名
if($ac=="chadian_rank")
{
	$uid=$_G['gp_uid'];
	if(!$_G['gp_page'])
	{
		$chadian=DB::result_first("select chadian from ".DB::table("common_member_profile")." where uid='".$uid."' ");
		if($chadian)
		{
			$my_rank1=DB::result_first("select count(uid) from ".DB::table("common_member_profile")." where chadian<'".$chadian."' and chadian>0 ");
			$page=intval($my_rank1/$page_size);
			$page_start=$page*$page_size;
		}
	}
	
	$total=DB::result_first("select count(uid) from ".DB::table("common_member_profile")." where chadian>0  ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{
		$list=DB::query("select uid,realname,chadian from ".DB::table("common_member_profile")." where chadian>0 order by chadian asc limit $page_start,$page_size ");
		while($row=DB::fetch($list))
		{
			$list_data[]=$row;
		}
	}

	$data['title']='list_data';
	$data['data']=$list_data;
	//print_r($data);
	api_json_result(1,0,$app_error['event']['10502'],$data);

}




//waika_list
if($ac=="waika_list")
{
	$uid=$_G['gp_uid'];
	$total=DB::result_first("select count(id)  from ".DB::table("common_score")." where uid='".$uid."' and source='waika' and score='' ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{
		$list=DB::query("select id,uid,sais_id,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_score").".sais_id) as sais_name,fuid,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_score").".fuid) as field_name,addtime from ".DB::table("common_score")." where uid='".$uid."' and source='waika' and score='' order by addtime desc limit $page_start,$page_size  ");
		while($row =DB::fetch($list))
		{
			if($row['sais_name']==null)
			{
				$row['sais_name']="";
			}
			if($row['field_name']==null)
			{
				$row['field_name']="";
			}

			$row['icon']=$site_url."/uc_server/avatar.php?uid=".$row['fuid']."&size=middle";

			$row['addtime']=date("Y年m月d日",$row['addtime']);
			$list_data[]=$row;
		}
	}

	//	
	$list=DB::query("select id,uid,sais_id,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_score").".sais_id) as sais_name,fuid,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_score").".fuid) as field_name,par,score,pars,total_score,addtime,dong_names,uploadimg,is_edit from ".DB::table("common_score")." where source='waika' and score<>'' and sais_id='1000333' order by addtime desc limit $page_start,$page_size  ");
	while($row =DB::fetch($list))
	{
		$row['par']=explode("|",$row['par']);
		$row['score']=explode("|",$row['score']);
		if($row['sais_name']==null)
		{
			$row['sais_name']="";
		}
		if($row['field_name']==null)
		{
			$row['field_name']="";
		}

		$row['icon']=$site_url."/uc_server/avatar.php?uid=".$row['fuid']."&size=middle";

		if($row['uploadimg'])
		{
			$row['uploadimg']=$site_url."/".$row['uploadimg'];
			$row['uploadimg_small']=$row['uploadimg']."_small.jpg";
		}
		else
		{
			$row['uploadimg']='';
			$row['uploadimg_small']="";
		}

		$row['addtime']=date("Y年m月d日",$row['addtime']);
		$list_data2[]=$row;
	}


	$data['title']='list_data';
	$data['data']=array(
		'score_list'=>$list_data,	
		'event_list'=>$list_data2	
	);
	api_json_result(1,0,$app_error['event']['10502'],$data);
}



//qiuyou_list  球友成绩卡
if($ac=="qiuyou_list")
{
	$uid=$_G['gp_uid'];
	$total=DB::result_first("select count(id)  from ".DB::table("common_score")." where uid in (select uid from (select buddyid from jishigou_buddys where  uid='".$uid."') as t2) and uid<>'".$uid."' ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{
		$list=DB::query("select id,uid,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_score").".uid) as realname,sais_id,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_score").".sais_id) as sais_name,fuid,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_score").".fuid) as field_name,addtime,uploadimg,lun from ".DB::table("common_score")." where uid in (select uid from (select buddyid from jishigou_buddys where  uid='".$uid."') as t2) and uid<>'".$uid."'  order by addtime desc limit $page_start,$page_size  ");
		while($row =DB::fetch($list))
		{
			
			if($row['sais_name']==null)
			{
				$row['sais_name']="";
			}
			if($row['field_name']==null)
			{
				$row['field_name']="";
			}

			$row['icon']=$site_url."/uc_server/avatar.php?uid=".$row['fuid']."&size=middle";

			if($row['uploadimg'])
			{
				$row['uploadimg']=$site_url."/".$row['uploadimg'];
				$row['uploadimg_small']=$row['uploadimg'];
			}
			else
			{
				$row['uploadimg']='';
				$row['uploadimg_small']="";
			}

			$row['addtime']=date("Y年m月d日",$row['addtime']);
			$list_data[]=$row;
		}
	}
	$data['title']='list_data';
	$data['data']=$list_data;
	api_json_result(1,0,$app_error['event']['10502'],$data);
}


//成绩分析  1890317
if($ac=="fenxi_index")
{
	$uid=$_G['gp_uid'];

	//差点和排行
	$chadian=DB::result_first("select chadian from ".DB::table("common_member_profile")." where uid='".$uid."' ");
	if($chadian)
	{
		$my_rank1=DB::result_first("select count(uid) from ".DB::table("common_member_profile")." where chadian<'".$chadian."' and chadian>0 ");
	}
	else
	{
		$my_rank1="暂无";
	}
	$detail_data['chadian']=$chadian;
	$detail_data['rank']=$my_rank1+1;


	//guoling_lv
	$total_guoling_lv=0;
	$list=DB::query("select par,score,tuigan,dateline from ".DB::table("common_score")." where uid='".$uid."' ");
	$n=0;
	while($row=DB::fetch($list))
	{
		$guoling_num=0;

		$tuigan=explode("|",$row['tuigan']);	
		$score=explode("|",$row['score']);
		unset($score[9]);
		unset($score[19]);
		unset($score[20]);
		$c1=implode(",",$score);
		$score=explode(",",$c1);

		$par=explode("|",$row['par']);
		unset($par[9]);
		unset($par[19]);
		unset($par[20]);
		$c1=implode(",",$par);
		$par=explode(",",$c1);

		for($i=0; $i<count($tuigan); $i++)
		{
			if($score[$i]-$tuigan[$i]==$par[$i]-2)
			{
				//如果上
				$guoling_num=$guoling_num+1;
			}
		}

		$row['guoling_lv']=round($guoling_num/18,2);

		$total_guoling_lv=$row['guoling_lv']+$total_guoling_lv;
		$n=$n+1;
	}

	$detail_data['guolinglv']=round(($total_guoling_lv/$n),2)*100;



	//pingjun_tuigan
	$total_pingjun_tuigan=0;
	$list=DB::query("select par,score,tuigan,dateline from ".DB::table("common_score")." where uid='".$uid."' ");
	$n=0;
	while($row=DB::fetch($list))
	{
		$total=0;
		$tuigan=explode("|",$row['tuigan']);	
		for($i=0; $i<count($tuigan); $i++)
		{
			$total =$total+$tuigan[$i];
		}
		$row['pingjun_tuigan']=round($pingjun_tuigan=$total/18,2);
		$total_pingjun_tuigan=$total_pingjun_tuigan+$row['pingjun_tuigan'];
		$n=$n+1;
	}
	$detail_data['pingjun_tuigan']=round($total_pingjun_tuigan/$n,2);

	$data['title']='detail_data';
	$data['data']=$detail_data;
	
	//print_r($data);
	api_json_result(1,0,$app_error['event']['10502'],$data);

}


//成绩分析  平均推杆 1890317
if($ac=="fenxi_tuigan")
{
	$uid=$_G['gp_uid'];
	if($uid)
	{
		$list=DB::query("select par,score,tuigan,dateline from ".DB::table("common_score")." where uid='".$uid."' ");
		while($row=DB::fetch($list))
		{
			$gan_0=0;
			$gan_1=0;
			$gan_2=0;
			$gan_3=0;
			$gan_4=0;
			$gan_5=0;
			$total=0;

				
			$tuigan=explode("|",$row['tuigan']);	
			$all_total=$all_total+count($tuigan);

			for($i=0; $i<count($tuigan); $i++)
			{
				$total =$total+$tuigan[$i];
				

				if($tuigan[$i]<=0)
				{
					$gan_0=$gan_0+1;
				}
				else if($tuigan[$i]==1)
				{
					$gan_1=$gan_1+1;
				}
				else if($tuigan[$i]==2)
				{
					$gan_2=$gan_2+1;
				}
				else if($tuigan[$i]==3)
				{
					$gan_3=$gan_3+1;
				}
				else if($tuigan[$i]==4)
				{
					$gan_4=$gan_4+1;
				}
				else
				{
					$gan_5=$gan_5+1;
				}

			}

			$row['dateline']=date("Y-m-d",$row['dateline']);
			$row['pingjun_tuigan']=round($pingjun_tuigan=$total/18,2);
			unset($row['par']);
			unset($row['score']);
			unset($row['tuigan']);
			$list_data[]=$row;

			
		}

		//$total=$gan_0+$gan_1+$gan_2+$gan_3+$gan_4+$gan_5;

		//echo $gan_0/$all_total;

		$total_data['gan_0_lv']=round($gan_0/$all_total,2)*100;
		$total_data['gan_1_lv']=round($gan_1/$all_total,2)*100;
		$total_data['gan_2_lv']=round($gan_2/$all_total,2)*100;
		$total_data['gan_3_lv']=round($gan_3/$all_total,2)*100;
		$total_data['gan_4_lv']=round($gan_4/$all_total,2)*100;
		$total_data['qita_lv']=100-$total_data['gan_0_lv']-$total_data['gan_1_lv']-$total_data['gan_2_lv']-$total_data['gan_3_lv']-$total_data['gan_4_lv'];

		$total_data['gan_0_num']=$gan_0;
		$total_data['gan_1_num']=$gan_1;
		$total_data['gan_2_num']=$gan_2;
		$total_data['gan_3_num']=$gan_3;
		$total_data['gan_4_num']=$gan_4;
		$total_data['qita_num']=$all_total-$gan_0-$gan_1-$gan_2-$gan_3-$gan_4;
		//$total_data['total']=$total;
		
	
		$data['title']="fenxi_data";
		$data['data']=array(
			'lv_list'=>$list_data,
			'total_data'=>$total_data,
		);
		
		//print_r($data);


	}// if uid


	api_json_result(1,0,$app_error['event']['10502'],$data);

}



//成绩分析  标准杆上果岭率 1890317
if($ac=="fenxi_guoling")
{
	$uid=$_G['gp_uid'];
	if($uid)
	{
		$list=DB::query("select par,score,tuigan,dateline from ".DB::table("common_score")." where uid='".$uid."' ");
		while($row=DB::fetch($list))
		{
			$gan_0=0;
			$gan_1=0;
			$gan_2=0;
			$gan_3=0;
			$gan_4=0;
			$total=0;
			$guoling_num=0;

			$tuigan=explode("|",$row['tuigan']);	
			$score=explode("|",$row['score']);
			unset($score[9]);
			unset($score[19]);
			unset($score[20]);
			$c1=implode(",",$score);
			$score=explode(",",$c1);

			$par=explode("|",$row['par']);
			unset($par[9]);
			unset($par[19]);
			unset($par[20]);
			$c1=implode(",",$par);
			$par=explode(",",$c1);

			for($i=0; $i<count($tuigan); $i++)
			{
				if($score[$i]-$tuigan[$i]==$par[$i]-2)
				{
					//如果上
					$guoling_num=$guoling_num+1;
				}
			}

			$row['guoling_lv']=round($guoling_num/18,2);
			$row['dateline']=date("Y-m-d",$row['dateline']);
			unset($row['par']);
			unset($row['score']);
			unset($row['tuigan']);
			$list_data[]=$row;

		}


		$data['title']="fenxi_data";
		$data['data']=array(
			'lv_list'=>$list_data,
		);
		
		//print_r($data);

	}// if uid


	api_json_result(1,0,$app_error['event']['10502'],$data);

}



//成绩分析  标准杆平均成绩 1890317
if($ac=="fenxi_chengji")
{
	$uid=$_G['gp_uid'];
	if($uid)
	{
		$list=DB::query("select par,score,tuigan,dateline from ".DB::table("common_score")." where uid='".$uid."' ");
		while($row=DB::fetch($list))
		{
			$gan_0=0;
			$gan_1=0;
			$gan_2=0;
			$gan_3=0;
			$gan_4=0;
			$total=0;

			$laoying_num=0;
			$xiaoniao_num=0;
			$biaozhungan_num=0;
			$boji_1_num=0;
			$boji_2_num=0;
			$boji_3_num=0;
			$qita_num=0;
				
			$score=explode("|",$row['score']);
			unset($score[9]);
			unset($score[19]);
			unset($score[20]);
			$c1=implode(",",$score);
			$score=explode(",",$c1);

			$par=explode("|",$row['par']);
			unset($par[9]);
			unset($par[19]);
			unset($par[20]);
			$c1=implode(",",$par);
			$par=explode(",",$c1);

			$all_total=$all_total+count($score);

			for($i=0; $i<count($score); $i++)
			{
				if($score[$i]-$par[$i]=='-2')
				{
					$laoying_num=$laoying_num+1;
				}
				else if($score[$i]-$par[$i]=='-1')
				{
					$xiaoniao_num=$xiaoniao_num+1;
				}
				else  if($score[$i]-$par[$i]==0)
				{
					$biaozhungan_num=$biaozhungan_num+1;
				}
				else  if($score[$i]-$par[$i]==1)
				{
					$boji_1_num=$boji_1_num+1;
				}
				else if($score[$i]-$par[$i]==2)
				{
					$boji_2_num=$boji_2_num+1;
				}
				else if($score[$i]-$par[$i]==3)
				{
					$boji_3_num=$boji_3_num+1;
				}
				else
				{
					$qita_num=$qita_num+1;
				}

			}

			$total=$laoying_num+$xiaoniao_num+$biaozhungan_num+$boji_1_num+$boji_2_num+$boji_3_num;

			$row['dateline']=date("Y-m-d",$row['dateline']);
			$row['pingjun_num']=round($total/18,2);
			
			unset($row['par']);
			unset($row['score']);
			unset($row['tuigan']);
		
			$list_data[]=$row;

		}

		$total_data['laoying_lv']=round($laoying_num/$all_total,2)*100;
		$total_data['xiaoniao_lv']=round($xiaoniao_num/$all_total,2)*100;
		$total_data['biaozhungan_lv']=round($biaozhungan_num/$all_total,2)*100;
		$total_data['boji_1_lv']=round($boji_1_num/$all_total,2)*100;
		$total_data['boji_2_lv']=round($boji_2_num/$all_total,2)*100;
		$total_data['boji_3_lv']=round($boji_3_num/$all_total,2)*100;
		$total_data['qita_lv']=100-$total_data['laoying_lv']-$total_data['xiaoniao_lv']-$total_data['biaozhungan_lv']-$total_data['boji_1_lv']-$total_data['boji_2_lv']-$total_data['boji_3_lv'];

		$total_data['laoying_num']=$laoying_num;
		$total_data['xiaoniao_num']=$xiaoniao_num;
		$total_data['biaozhungan_num']=$biaozhungan_num;
		$total_data['boji_1_num']=$boji_1_num;
		$total_data['boji_2_num']=$boji_2_num;
		$total_data['boji_3_num']=$boji_3_num;
		$total_data['qita_num']=$all_total-$laoying_num-$xiaoniao_num-$biaozhungan_num-$boji_1_num-$boji_2_num-$boji_3_num;
		
		$data['title']="fenxi_data";
		$data['data']=array(
			'lv_list'=>$list_data,
			'total_data'=>$total_data,
		);
		
		//print_r($data);


	}// if uid


	api_json_result(1,0,$app_error['event']['10502'],$data);

}




function get_juli($lng1,$lat1,$lng2,$lat2)//根据经纬度计算距离
{
	//将角度转为狐度
	$radLat1=deg2rad($lat1);
	$radLat2=deg2rad($lat2);
	$radLng1=deg2rad($lng1);
	$radLng2=deg2rad($lng2);
	$a=$radLat1-$radLat2;//两纬度之差,纬度<90
	$b=$radLng1-$radLng2;//两经度之差纬度<180
	$s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378.137;
	return round($s,1);
}


?>