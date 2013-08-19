<?php
function get_token()
{
	$token=time().md5("bwvip.com");
	return $token;
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



/*

*生成缩略图

*$imgPath（图片路径）, $maxWidth（宽）, $maxHeight（高）, $directOutput = true（是否在页面输出）, $quality = 90, $verbose,$imageType（图片类型）

*

*

*/
function resize_img($imgPath, $maxWidth, $maxHeight, $directOutput = true, $quality = 90, $verbose,$imageType)
{
	$size = getimagesize($imgPath);

	//print_r($size);exit;
	 // break and return false if failed to read image infos
	if(!$size){
	  if($verbose && !$directOutput)echo "<br />Not able to read image infos.<br />";
	  
	  return false;
	}

	 // relation: width/height
	$relation = $size[0]/$size[1];
	 // maximal size (if parameter == false, no resizing will be made)
	$maxSize = array($maxWidth?$maxWidth:$size[0],$maxHeight?$maxHeight:$size[1]);
	 // declaring array for new size (initial value = original size)
	$newSize = $size;
	 // width/height relation
	$relation = array($size[1]/$size[0], $size[0]/$size[1]);
	//print_r($size);
	//echo "<br>";
	//print_r($relation);exit;

	if(($newSize[0] > $maxWidth))
	{
	$newSize[0]=$maxSize[0];
	$newSize[1]=$newSize[0]*$relation[0];
	}
	  
	if(($newSize[1] > $maxHeight))
	{
	$newSize[1]=$maxSize[1];
	$newSize[0]=$newSize[1]*$relation[1];
	}


	// create image
	  switch($size[2])
	  {
	 case 1:
	   if(function_exists("imagecreatefromgif"))
	   {
	  $originalImage = imagecreatefromgif($imgPath);
	   }else{
	  if($verbose && !$directOutput)echo "<br />No GIF support in this php installation, sorry.<br />";
	  return false;
	   }
	   break;
	 case 2: $originalImage = imagecreatefromjpeg($imgPath); break;
	 case 3: $originalImage = imagecreatefrompng($imgPath); break;
	 default:
	   if($verbose && !$directOutput)echo "<br />No valid image type.<br />";
	   return false;
	  }

	// create new image

	  $resizedImage = imagecreatetruecolor($newSize[0], $newSize[1]); 

	  imagecopyresampled($resizedImage, $originalImage,0, 0, 0, 0,$newSize[0], $newSize[1], $size[0], $size[1]);

	$rz=$imgPath;

	// output or save
	  if($directOutput)
	{
	 imagejpeg($resizedImage);
	 }
	 else
	{
	 
	 $exp=explode(".",$imgPath);
	 $extension=end($exp);//$exp[count($exp)-1];
	 $newimage=$imageType.".".$extension;
	 $rz=preg_replace("//.([a-zA-Z]{3,4})$/",$newimage,$imgPath);
		imagejpeg($resizedImage, $rz, $quality);
	 }
	// return true if successfull
	  return $rz;
} // End function Resize Image 

 


 /**  
 *   
 * 二维数组按指定列排序  
 * @param $arr_data 原数组  
 * @param $field 指定列  
 * @param $descending 是否降顺（默认升顺）  
 * @return 排列好的数组  

 //测试：  
$arr = array (  
  array (’s’ => ’aaa’, ’i’ => 3),  
  array (’s’ => ’bbb’, ’i’ => 2),  
  array (’s’ => ’ccc’, ’i’ => 4),  
  array (’s’ => ’ddd’, ’i’ => 1),  
);  

print_r(array_sort_by_field($arr, ’i’));  
print_r(array_sort_by_field($arr, ’i’, true));  

**/  
function array_sort_by_field($arr_data, $field, $descending = false)  
{  
	$arrSort = array();  
	foreach ( $arr_data as $key => $value ) 
	{  
		$arrSort[$key] = $value[$field];
	}  

	if($descending)
	{  
		arsort($arrSort);  
	}
	else
	{  
		asort($arrSort);  
	}  

	$resultArr = array();  
	foreach ($arrSort as $key => $value )
	{  
		$resultArr[$key] = $arr_data[$key];  
	}  

	return $resultArr;
}  



?>