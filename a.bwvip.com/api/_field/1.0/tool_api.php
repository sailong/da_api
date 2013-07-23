<?php
/*
*
* tool_api.php
* by zhanglong 2013-05-21
* field app WAP页
*
*/
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}


$ac=$_G['gp_ac'];
$language=$_G['gp_language'];
$uid=$_G['gp_uid'];
$field_uid=$_G['gp_field_uid'];


//处理球场介绍缩略图
if($ac=='field_about_pic_small')
{
	$list=DB::query("select about_id,about_pic from tbl_field_about ");
	$i=0;
	while($row=DB::fetch($list))
	{
		if($row['about_pic'])
		{
		
			
			
			$file_url="../".$row['about_pic'];
			$extname=end(explode(".",$file_url));
			
			$small_file_url=$file_url."_small.".$extname;
			if($extname=="jpg")
			{
				$pic_source=imagecreatefromjpeg($file_url);
			}
			else
			{
				echo "文件类型不支持";
			}

			
			$filename_s = $file_url."_small";

			if($pic_source && $extname)
			{
				$aa=resizeImage($pic_source,163,163,$small_file_url);
				if(file_exists($file_url))
				{
					//$result=unlink($file_url);
				}
			}
			else
			{
				//api_json_result(1,1,"图片格式不支持",$data);
			}
			
				
			$i++;
		}
	}
	echo "共处理 ".$i." 张图片";
}

?>