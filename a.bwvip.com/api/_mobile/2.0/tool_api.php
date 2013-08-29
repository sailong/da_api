<?php
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}


$ac=$_G['gp_ac'];


//图片处理
if($ac=="import_photo_form_dz")
{
	$page = $_G['gp_page'];
	$page_size = $_G['gp_page_size'];
	if(empty($page)){
		$page = 1;
		$page_size = 100;
	}
	$offset = ($page-1)*$page_size;
	$list=DB::query("select albumid,albumname,uid,updatetime from pre_home_album where uid='1000333' order by albumid asc limit {$offset},{$page_size}");
	
	while($row=DB::fetch($list))
	{
		$album_id=DB::result_first("select album_id from tbl_album where albumid='".$row['albumid']."' ");
		if(!$album_id)
		{
			//添加相册
			$res=DB::query("insert into tbl_album (albumid,uid,album_name,album_addtime) values ('".$row['albumid']."','".$row['uid']."','".$row['albumname']."','".$row['updatetime']."') ");
			
			
			$album_id=DB::result_first("select album_id from tbl_album where albumid='".$row['albumid']."' ");
		}
		else
		{
			continue;
		}
		$pic_list=DB::query("select picid,albumid,uid,title,dateline,filepath from pre_home_pic where albumid='".$row['albumid']."' ");
		
		while($row_pic=DB::fetch($pic_list))
		{
			//echo "相册".$row_pic['albumid'].'---相片'.$row_pic['picid'].'<br>';
			$file_url=dirname(dirname(dirname(dirname(__FILE__)))).'/data/attachment/album/'.$row_pic['filepath'];
			
			$filepath_small = '';
			$filepath = '';
			if(file_exists($file_url))
			{
				$extname=end(explode(".",$file_url));
				$image_file_small = $file_url.'_small.'.$extname;
				$filepath="/data/attachment/album/".$row_pic['filepath'];
				$filepath_small=$filepath.'_small.'.$extname;
				if(!file_exists($image_file_small))
				{
					list($image_width,$image_height,$image_type,$image_attr) = getimagesize($file_url);
					$iw = $image_width;
					$ih = $image_height;
					$image_width_p = 300;//140;
					$image_height_p = 200;//94
					if($iw > $image_width_p || $ih > $image_height_p) {
						$p_width = $image_width_p;
						$p_height = round(($ih*$image_width_p)/$iw);
						if($p_height > $image_height_p){
							$p_height = $image_height_p;
						} 
					}
					$result = makethumb($file_url, $image_file_small, $p_width, $p_height, 0, 0, 0, 0, 0, 0, 0, 100);
					clearstatcache();
					if(!$result && !is_file($image_file_small)) {
						@copy($file_url, $image_file_small);
					}
					clearstatcache();
					unset($file_url,$image_file_small);
				}
			}
			
			$photo_info=DB::fetch_first("select photo_id,photo_url_small from tbl_photo where picid='".$row_pic['picid']."'");
			//var_dump($photo_info);
			if(!empty($photo_info))
			{
				DB::query("update tbl_photo set photo_url='".$filepath."' and photo_url_small='".$filepath_small."' where photo_id='".$photo_info['photo_id']."'");
			}
			else
			{
				DB::query("insert into tbl_photo (uid,album_id,picid,photo_name,photo_url,photo_url_small,photo_addtime) values ('".$row_pic['uid']."','".$album_id."','".$row_pic['picid']."','".$row_pic['title']."','".$filepath."','".$filepath_small."','".$row_pic['dateline']."')");
				
			}
			unset($row_pic);
		}
		
			//echo $image_file_small.'<br>'.$res.'<br>';die;
			unset($row);
	}

	echo "{$page}处理完成";

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