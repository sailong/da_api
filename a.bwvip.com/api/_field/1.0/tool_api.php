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
//微博缩略图处理
if($ac=='topic_image_small')
{
	
	$page = 1;
	$limit = 200;
	$i = 0;
	while(true) {
		$offset = ($page-1)*$limit;
		$list=DB::query("select photo from jishigou_topic_image order by id asc limit {$offset},{$limit}");
		if(!$list) {
			break;
		}
		$page++;
		while($row=DB::fetch($list))
		{
			$file_url=dirname(dirname(dirname(dirname(__FILE__)))).'/weibo/'.$row['photo'];
			
			if(file_exists($file_url)) {
				$extname=end(explode(".",$file_url));
				
				$pre_pic_path = reset(explode("_o.",$file_url));
				
				$pic_name_o_path=$pre_pic_path.'_o.'.$extname;
				$pic_name_p_path=$pre_pic_path.'_p.'.$extname;
				$pic_name_s_path=$pre_pic_path.'_s.'.$extname;
				//move_uploaded_file($file_val['tmp_name'], $pic_name_o_path);
				$image_file = $pic_name_o_path;
				$image_file_small = $pic_name_s_path;
				$image_file_photo = $pic_name_p_path;
			
				//@copy($image_file, $image_file);
				
				list($image_width,$image_height,$image_type,$image_attr) = getimagesize($image_file);
				//生成小图
				$iw = $image_width;
				$ih = $image_height;
				
				/*$image_width_s = 180;
				if($iw > $image_width_s) {
					$s_width = $image_width_s;
					$s_height = round(($ih*$image_width_s)/$iw);
				}else{
					$s_width=$iw;
					$s_height=$ih;
				}
				$result = makethumb($image_file, $image_file_small, $s_width, $s_height, 0, 0, 0, 0, 0, 0, 0, 100); */
				if(!file_exists($image_file_small)) {
					$src_x = $src_y = 0;
					$src_w = $src_h = min($iw, $ih);
					if($iw > $ih) {
						$src_x = round(($iw - $ih) / 2);
					} else {
						$src_y = round(($ih - $iw) / 2);
					}
					$result = makethumb($image_file, $image_file_small, 180, 180, 0, 0, $src_x, $src_y, $src_w, $src_h, 0, 100);
					clearstatcache();
					if (!$result && !is_file($image_file_small)) {
						@copy($image_file, $image_file_small);
					}
				}
				if(!file_exists($image_file_photo)) {
					//生成中图
					$image_width_p = 300;
					if($iw > $image_width_p) {
						$p_width = $image_width_p;
						$p_height = round(($ih*$image_width_p)/$iw);
						$result = makethumb($image_file, $image_file_photo, $p_width, $p_height, 0, 0, 0, 0, 0, 0, 0, 100);
					}
					clearstatcache();
					if($iw <= $image_width_p || (!$result && !is_file($image_file_photo))) {
						@copy($image_file, $image_file_photo);
					}
				}
				$i++;
				echo $i.'<br/>';
			}
		} 
	}
}

function makethumb($srcfile,$dstfile,$thumbwidth,$thumbheight,$maxthumbwidth=0,$maxthumbheight=0,$src_x=0,$src_y=0,$src_w=0,$src_h=0, $thumb_cut_type=0, $thumb_quality = 100) {
		if (!is_file($srcfile)) {
		return '';
	}

	$tow = (int) $thumbwidth;
	$toh = (int) $thumbheight;
	if($tow < 30) {
		$tow = 30;
	}
	if($toh < 30) {
		$toh = 30;
	}

	$make_max = 0;
	$maxtow = (int) $maxthumbwidth;
	$maxtoh = (int) $maxthumbheight;
	if($maxtow >= 300 && $maxtoh >= 300)
	{
		$make_max = 1;
	}

	$im = '';
	if(false != ($data = getimagesize($srcfile))) {
		if($data[2] == 1) {
			$make_max = 0;			if(function_exists("imagecreatefromgif")) {
				$im = imagecreatefromgif($srcfile);
			}
		} elseif($data[2] == 2) {
			if(function_exists("imagecreatefromjpeg")) {
				$im = imagecreatefromjpeg($srcfile);
			}
		} elseif($data[2] == 3) {
			if(function_exists("imagecreatefrompng")) {
				$im = imagecreatefrompng($srcfile);
			}
		}
	}
	if(!$im) return '';

	$srcw = ($src_w ? $src_w : imagesx($im));
	$srch = ($src_h ? $src_h : imagesy($im));

	$towh = $tow/$toh;
	$srcwh = $srcw/$srch;
	if($towh <= $srcwh) {
		$ftow = $tow;
		$ftoh = round($ftow*($srch/$srcw),2);
	} else {
		$ftoh = $toh;
		$ftow = round($ftoh*($srcw/$srch),2);
	}


	if($make_max) {
		$maxtowh = $maxtow/$maxtoh;
		if($maxtowh <= $srcwh) {
			$fmaxtow = $maxtow;
			$fmaxtoh = round($fmaxtow*($srch/$srcw),2);
		} else {
			$fmaxtoh = $maxtoh;
			$fmaxtow = round($fmaxtoh*($srcw/$srch),2);
		}

		if($srcw <= $maxtow && $srch <= $maxtoh) {
			$make_max = 0;		
		}
	}


	$maxni = '';
	$thumb_quality = (int) $thumb_quality;
	if($thumb_quality < 1 || $thumb_quality > 100) {
		$thumb_quality = 100;
	}
	if($srcw >= $tow || $srch >= $toh) {
		if(function_exists("imagecreatetruecolor") && function_exists("imagecopyresampled") && ($ni = imagecreatetruecolor($ftow, $ftoh))) {
			imagecopyresampled($ni, $im, 0, 0, $src_x, $src_y, $ftow, $ftoh, $srcw, $srch);
						if($make_max && ($maxni = imagecreatetruecolor($fmaxtow, $fmaxtoh))) {
				imagecopyresampled($maxni, $im, 0, 0, $src_x, $src_y, $fmaxtow, $fmaxtoh, $srcw, $srch);
			}
		} elseif(function_exists("imagecreate") && function_exists("imagecopyresized") && ($ni = imagecreate($ftow, $ftoh))) {
			imagecopyresized($ni, $im, 0, 0, $src_x, $src_y, $ftow, $ftoh, $srcw, $srch);
						if($make_max && ($maxni = imagecreate($fmaxtow, $fmaxtoh))) {
				imagecopyresized($maxni, $im, 0, 0, $src_x, $src_y, $fmaxtow, $fmaxtoh, $srcw, $srch);
			}
		} else {
			return '';
		}
		if(function_exists('imagejpeg')) {
			imagejpeg($ni, $dstfile, $thumb_quality);
						if($make_max && $maxni) {
				imagejpeg($maxni, $srcfile, $thumb_quality);
			}
		} elseif(function_exists('imagepng')) {
			imagepng($ni, $dstfile);
						if($make_max && $maxni) {
				imagepng($maxni, $srcfile);
			}
		}
		imagedestroy($ni);
		if($make_max && $maxni) {
			imagedestroy($maxni);
		}
	}
	imagedestroy($im);

	if(!is_file($dstfile)) {
		return '';
	} else {
		return $dstfile;
	}
}
?>