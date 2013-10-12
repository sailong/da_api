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


//发送手机短信
function send_msg($mobile,$content)
{
	
$start=file_get_contents("msg.txt");
file_put_contents("msg.txt",$start+1);	
$flag = 0; 
        //要post的数据 
$argv = array( 
         'sn'=>'SDK-BBX-010-16801', ////替换成您自己的序列号
		 'pwd'=>strtoupper(md5('SDK-BBX-010-16801'.'f-_4ef-4')), //此处密码需要加密 加密方式为 md5(sn+password) 32位大写
		 'mobile'=>$mobile,//手机号 多个用英文的逗号隔开 post理论没有长度限制.推荐群发一次小于等于10000个手机号
		 'content'=>$content,//短信内容
		 'ext'=>'',		
		 'stime'=>date("Y-m-d H:i:s"),//定时时间 格式为2011-6-29 11:09:21
		 'rrid'=>''
		 ); 
//构造要post的字符串 
foreach ($argv as $key=>$value) { 
          if ($flag!=0) { 
                         $params .= "&"; 
                         $flag = 1; 
          } 
         $params.= $key."="; $params.= urlencode($value); 
         $flag = 1; 
          } 
         $length = strlen($params); 
                 //创建socket连接 
        $fp = fsockopen("sdk2.zucp.net",8060,$errno,$errstr,10) or exit($errstr."--->".$errno); 
         //构造post请求的头 
         $header = "POST /webservice.asmx/mt HTTP/1.1\r\n"; 
         $header .= "Host:sdk2.zucp.net\r\n"; 
         $header .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
         $header .= "Content-Length: ".$length."\r\n"; 
         $header .= "Connection: Close\r\n\r\n"; 
         //添加post的字符串 
         $header .= $params."\r\n"; 
         //发送post的数据 
         fputs($fp,$header); 
         $inheader = 1; 
          while (!feof($fp)) { 
                         $line = fgets($fp,1024); //去除请求包的头只显示页面的返回数据 
                         if ($inheader && ($line == "\n" || $line == "\r\n")) { 
                                 $inheader = 0; 
                          } 
                          if ($inheader == 0) { 
                                // echo $line; 
                          } 
          } 
		  //<string xmlns="http://tempuri.org/">-5</string>
	       $line=str_replace("<string xmlns=\"http://tempuri.org/\">","",$line);
	       $line=str_replace("</string>","",$line);
		   $result=explode("-",$line);
		  // echo $line."-------------";
		   
		    if(count($result)>1)
			return $line;
			else
			return '0#1';
}




function get_number($length=8, $chars='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',$str='')
{

	// 密码字符集，可任意添加你需要的字符 
	$password = '';  
	for($i=0; $i<$length; $i++)  
	{  
		// 这里提供两种字符获取方式  
		// 第一种是使用 substr 截取$chars中的任意一位字符；  
		// 第二种是取字符数组 $chars 的任意元素  
		// $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);  
		$password .= $chars[ mt_rand(0, strlen($chars) - 1) ];  
	}

    return $password;  
}


?>