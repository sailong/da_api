<?php
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}


$ac=$_G['gp_ac'];


//图片处理
if($ac=="import_photo_form_dz")
{
	$list=DB::query("select albumid,albumname,uid from pre_home_album where uid='1000333' ");
	$i=0;
	while($row=DB::fetch($list))
	{
		$album_id=DB::result_first("select album_id from tbl_album where albumid='".$row['albumid']."' ");
		if(!$album_id)
		{
			//添加相册
			$res=DB::query("insert into tbl_album (albumid,uid,album_name,album_addtime) values ('".$row['albumid']."','".$row['uid']."','".$row['albumname']."','".time()."') ");
			$i++;
			
			$album_id=DB::result_first("select album_id from tbl_album where albumid='".$row['albumid']."' ");
		}
		
		$pic_list=DB::query("select picid,albumid,uid,title,dateline,filepath from pre_home_pic where albumid='".$row['albumid']."' ");
		while($row_pic=DB::fetch($pic_list))
		{
			if($row_pic['filepath'])
			{
				$filepath="/data/attachment/album/".$row_pic['filepath'];
				$photo_info=DB::fetch_first("select photo_id,photo_url_small from tbl_photo where picid='".$row_pic['picid']."' ");

				if($photo_info['photo_id'])
				{
					$res=DB::query("update tbl_photo set photo_url='".$filepath."' ".$up_sql." where photo_id='".$photo_info['photo_id']."' ");
				}
				else
				{
					$res=DB::query("insert into tbl_photo (uid,album_id,picid,photo_name,photo_url,photo_url_small,photo_addtime) values ('".$row_pic['uid']."','".$album_id."','".$row_pic['album_id']."','".$row_pic['title']."','".$filepath."','".$filepath.".thumb.jpg','".$row_pic['dateline']."') ");
					
					/*
					echo "insert into tbl_photo (uid,album_id,picid,photo_name,photo_url,photo_url_small,photo_addtime) values ('".$row_pic['uid']."','".$album_id."','".$row_pic['album_id']."','".$row_pic['title']."','".$filepath."','".$small_file_url."','".$row_pic['dateline']."') ";
					echo "<hr>";
					*/
				}
			}
		}
	}
	
	echo "处理完成";

}







?>