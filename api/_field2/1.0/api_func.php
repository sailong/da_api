<?php
function array_default_value($arr,$other_arr,$default_value="")
{

	foreach($arr as $key=>$value)
	{
		
		if(is_array($value))
		{
			/*
			foreach($value as $key2=>$value2)
			{
				if(strlen($value2)==0 || $value2==null || $value2=="")
				{
					$value2=$default_value;
				}
				if(!$value2)
				{
					$value2=$default_value;
				}
				$value2=trim($value2);
				$value2=(string)$value2;
				$value[$key2]=$value2;
			}
			*/
		}
		else
		{
			if(!in_array($key,$other_arr))
			{
				if(strlen($value)==0 || $value==null || $value=="")
				{
					$value=$default_value;
				}
				/*
				if(!$value)
				{
					$value=$default_value;
				}
				*/
				$value=trim($value);
				$value=(string)$value;
			}
		}
		
		$arr[$key]=$value;
	}
	
	return $arr;
}



/** 
* Curl版本 
* 使用方法： 
* $post_string = "app=request&version=beta"; 
* request_by_curl('http://facebook.cn/restServer.php',$post_string); 
*/ 
function request_by_curl_new($remote_server,$post_string)
{ 
    $ch = curl_init(); 
    curl_setopt($ch,CURLOPT_URL,$remote_server); 
    curl_setopt($ch,CURLOPT_POSTFIELDS,'mypost='.$post_string); 
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true); 
    curl_setopt($ch,CURLOPT_USERAGENT,"Jimmy's CURL Example beta"); 
    $data = curl_exec($ch); 
    curl_close($ch); 
     return $data; 
} 


function resizeImage($im,$maxwidth,$maxheight,$name,$filetype)
{
    $pic_width = imagesx($im);
    $pic_height = imagesy($im);

    if(($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight))
    {
        if($maxwidth && $pic_width>$maxwidth)
        {
            $widthratio = $maxwidth/$pic_width;
            $resizewidth_tag = true;
        }

        if($maxheight && $pic_height>$maxheight)
        {
            $heightratio = $maxheight/$pic_height;
            $resizeheight_tag = true;
        }

        if($resizewidth_tag && $resizeheight_tag)
        {
            if($widthratio<$heightratio)
                $ratio = $widthratio;
            else
                $ratio = $heightratio;
        }

        if($resizewidth_tag && !$resizeheight_tag)
            $ratio = $widthratio;
        if($resizeheight_tag && !$resizewidth_tag)
            $ratio = $heightratio;

        $newwidth = $pic_width * $ratio;
        $newheight = $pic_height * $ratio;

        if(function_exists("imagecopyresampled"))
        {
            $newim = imagecreatetruecolor($newwidth,$newheight);
           imagecopyresampled($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
        }
        else
        {
            $newim = imagecreate($newwidth,$newheight);
           imagecopyresized($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
        }

        $name = $name.$filetype;
        imagejpeg($newim,$name);
        imagedestroy($newim);
    }
    else
    {
        $name = $name.$filetype;
        imagejpeg($im,$name);
    }           
}



function get_avatar($uid, $size = 'middle', $type = '',$out_type='')
{
	$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'middle';
	$uid = abs(intval($uid));
	$uid = sprintf("%09d", $uid);
	$dir1 = substr($uid, 0, 3);
	$dir2 = substr($uid, 3, 2);
	$dir3 = substr($uid, 5, 2);
	$typeadd = $type == 'real' ? '_real' : '';

	if($out_type=="filename")
	{
		return $dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).$typeadd."_avatar_$size";
	}
	else if($out_type=="dir")
	{
		return $dir1.'/'.$dir2.'/'.$dir3.'/';
	}
	else
	{
		return $dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).$typeadd."_avatar_$size.jpg";
	}
	
}





function get_content_links($document)
{
	preg_match_all("'<\s*U\s.*?ab\s*\>\s*([\"\'])?(?(1)(.*?)\\1|([^\s\>]+))[^>]*</U>'isx",$document,$links);
	while(list($key,$val) = each($links[2]))
	{
		if(!empty($val))
		{
			$arr=explode("-",$val);
			$len=(strlen($arr[2]))-5;
			$tid=substr($arr[2],0,$len);
			if(!$tid)
			{
				$tid="0";
			}

			$match['link'][$key]['url'] = $val;
			$match['link'][$key]['tid'] = $tid; 
		}
	}

	while(list($key,$val) = each($links[3]))
	{
		if(!empty($val))
		{
			$arr=explode("-",$val);
			$len=(strlen($arr[2]))-5;
			$tid=substr($arr[2],0,$len);
			if(!$tid)
			{
				$tid="0";
			}

			$match['link'][$key]['url'] = $val;
			$match['link'][$key]['tid'] = $tid; 
		}
	}
	/*

	while(list($key,$val) = each($links[4])) {
	if(!empty($val))
		$match['content'][] = $val;
	}
	while(list($key,$val) = each($links[0])) {
	if(!empty($val))
		$match['all'][] = $val;
	}
	*/
	return $match;
}




?>