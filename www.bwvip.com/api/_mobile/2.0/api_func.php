<?php
function get_thru($event_id,$fenzhan_id,$uid,$event_user_id,$fenzhan_ing_status=0,$lun,$fenzhan_num)
{
	if($event_id)
	{
		$sql .=" and event_id='".$event_id."' ";
	}
	
	if($fenzhan_id)
	{
		$sql .=" and fenzhan_id='".$fenzhan_id."' ";
	}
	
	$user_sql="";
	if($uid)
	{
		$user_sql .=" and uid='".$uid."' ";
	}
	else
	{
		$user_sql .=" and event_user_id='".$event_user_id."' ";
	}
	
	
	if($fenzhan_id)
	{
		$score_info=DB::fetch_first("select total_score,score,status,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18 from tbl_baofen where source='ndong' and fenzhan_id='".$fenzhan_id."' ".$user_sql." order by lun desc limit 1 ");
		$total_score=$score_info['total_score'];

		if($score_info['cave_1'] && !$score_info['cave_2'])
		{
			$next_dong=1;
		}
		else if($score_info['cave_2'] && !$score_info['cave_3'])
		{
			$next_dong=2;
		}
		else if($score_info['cave_3'] && !$score_info['cave_4'])
		{
			$next_dong=3;
		}
		else if($score_info['cave_4'] && !$score_info['cave_5'])
		{
			$next_dong=4;
		}
		else if($score_info['cave_5'] && !$score_info['cave_6'])
		{
			$next_dong=5;
		}
		else if($score_info['cave_6'] && !$score_info['cave_7'])
		{
			$next_dong=6;
		}
		else if($score_info['cave_7'] && !$score_info['cave_8'])
		{
			$next_dong=7;
		}
		else if($score_info['cave_8'] && !$score_info['cave_9'])
		{
			$next_dong=8;
		}
		else if($score_info['cave_9'] && !$score_info['cave_10'])
		{
			$next_dong=9;
		}
		else if($score_info['cave_10'] && !$score_info['cave_11'])
		{
			$next_dong=10;
		}
		else if($score_info['cave_11'] && !$score_info['cave_12'])
		{
			$next_dong=11;
		}
		else if($score_info['cave_12'] && !$score_info['cave_13'])
		{
			$next_dong=12;
		}
		else if($score_info['cave_13'] && !$score_info['cave_14'])
		{
			$next_dong=13;
		}
		else if($score_info['cave_14'] && !$score_info['cave_15'])
		{
			$next_dong=14;
		}
		else if($score_info['cave_15'] && !$score_info['cave_16'])
		{
			$next_dong=15;
		}
		else if($score_info['cave_16'] && !$score_info['cave_17'])
		{
			$next_dong=16;
		}
		else if($score_info['cave_17'] && !$score_info['cave_18'])
		{
			$next_dong=17;
		}
		else if($score_info['cave_18'] && !$score_info['cave_1'])
		{
			$next_dong='F';
		}
		else
		{
			$next_dong='F';
		}
		
		/*

		$s_arr=explode("|",$score_info['score']);
		unset($s_arr[9]);
		unset($s_arr[19]);
		unset($s_arr[20]);
		$str_new=implode("|",$s_arr);
		$arr_new=explode("|",$str_new);

		$dong_num=0;
		for($i=0; $i<count($arr_new); $i++)
		{
			if($arr_new[$i]>0)	
			{
				$dong_num=$dong_num+1;
			}
		}

		//$dong_num=count($arr_new);
		//$score_info['score']=$arr_new;
		*/
		
		if($fenzhan_ing_status==0 || $total_score<=0)
		{
			$str=DB::result_first("select start_time from tbl_baofen where source='ndong' and fenzhan_id='".$fenzhan_id."' ".$user_sql." order by lun desc limit 1 ");
			$str=date("G:i",$str);
		}
		else if($fenzhan_ing_status==1)
		{
			//$dong_num=DB::result_first("select count(baofen_id) as num from tbl_baofen where source='ndong' and fenzhan_id='".$fenzhan_id."' ".$user_sql." ");
			$tee=DB::result_first("select tee from tbl_baofen where source='ndong' and fenzhan_id='".$fenzhan_id."' ".$user_sql." order by lun desc limit 1 ");
			if($tee==1)
			{
				$str=$dong_num+$tee;
			}
			else if($tee==10)
			{
				if($dong_num<10)
				{
					$str=$dong_num+$tee;
				}
				else
				{
					$str=$dong_num+$tee-9;
				}
				
			}
			else
			{
				$str="-";
			}


			$str=$next_dong;

			//$str="-";
			
		}
		else
		{
			$str="-";
		}
		
		if($score_info['status']<0)
		{
			$str="-";
		}
	}
	else
	{
		$str="-";
	}
	
	return (string)$str;
	
}


function get_ju_par_total_sort($ju_1,$ju_2,$ju_3,$ju_4,$ju_5=0)
{
	if($ju_1>900)
	{
		$ju_1=0;
	}
	if($ju_2>900)
	{
		$ju_2=0;
	}
	if($ju_3>900)
	{
		$ju_3=0;
	}
	if($ju_4>900)
	{
		$ju_4=0;
	}
	if($ju_5>900)
	{
		$ju_5=0;
	}
	
	$total=$ju_1+$ju_2+$ju_3+$ju_4+$ju_5;
	return $total;
}


function get_ju_par_total_view($ju_1,$ju_2,$ju_3,$ju_4,$ju_5=0)
{

	if($ju_1>900)
	{
		$ju_1=0;
	}
	if($ju_2>900)
	{
		$ju_2=0;
	}
	if($ju_3>900)
	{
		$ju_3=0;
	}
	if($ju_4>900)
	{
		$ju_4=0;
	}
	if($ju_5>900)
	{
		$ju_5=0;
	}

	$total=$ju_1+$ju_2+$ju_3+$ju_4+$ju_5;
	return $total;

}

function get_zong_score_sort($lun_1,$lun_2,$lun_3,$lun_4,$lun_5=0)
{
	$total=$lun_1+$lun_2+$lun_3+$lun_4+$lun_5;
	return $total;
}


function get_zong_score_view($lun_1,$lun_2,$lun_3,$lun_4,$lun_5=0)
{
	if($lun_1>900)
	{
		$lun_1=0;
	}
	if($lun_2>900)
	{
		$lun_2=0;
	}
	if($lun_3>900)
	{
		$lun_3=0;
	}
	if($lun_4>900)
	{
		$lun_4=0;
	}
	if($lun_5>900)
	{
		$lun_5=0;
	}

	$total=$lun_1+$lun_2+$lun_3+$lun_4+$lun_5;
	return $total;

}


function get_small_pic($url)
{
	if(strpos($url,"data/attachment"))
	{
		$str=$url.".thumb.jpg";
	}
	else
	{
		$arr=end(explode("/",$url));
		$file_name="s_".$arr;
		$str=str_replace($arr,$file_name,$url);
	}
	
	return $str;
}

function dong_color($s_arr,$p_arr)
{
	/*
	print_r($s_arr);
	echo "<hr>";
	print_r($p_arr);
	echo "<hr>";
	echo "------------------------------------------------";
	echo "<hr>";
	*/
	
	if(count($s_arr) == count($p_arr))
	{
		for($i=0; $i<count($s_arr); $i++)
		{
			if(intval($p_arr[$i])>0 && intval($s_arr[$i])>0)
			{
				if($s_arr[$i]-$p_arr[$i]>=3)
				{
					$c_arr[$i]=1;
				}
				else if($s_arr[$i]-$p_arr[$i]==2)
				{
					$c_arr[$i]=2;
				}
				else if($s_arr[$i]-$p_arr[$i]==1)
				{
					$c_arr[$i]=3;
				}
				else if($s_arr[$i]-$p_arr[$i]==0)
				{
					$c_arr[$i]=4;
				}
				else if($s_arr[$i]-$p_arr[$i]==-1)
				{
					$c_arr[$i]=5;
				}
				else if($s_arr[$i]-$p_arr[$i]==-2 || $s_arr[$i]-$p_arr[$i]==-3)
				{
					$c_arr[$i]=6;
				}
				/*
				else if($s_arr[$i]-$p_arr[$i]==-3)
				{
					$c_arr[$i]=7;
				}
				*/
				else
				{
					$c_arr[$i]=0;
					//$c_arr[$i]=$s_arr[$i]-$p_arr[$i];
				}
			}
			else
			{
				$c_arr[$i]=0;
			}
			
		}
	
	}
	else
	{
		$c_arr=null;
	}
	return $c_arr;
}

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



/*  if(data=='0#1')
{alert('发送成功！');}
else if(data=='10109')
{alert('已经注册！');}
else if(data=='-1')
{alert('手机号错误！');}
else if(data=='10103')
{alert('此手机号注册次数过多！');} */
//改demo的功能是群发短信和发单条短信。（传一个手机号就是发单条，多个手机号既是群发）

//您把序列号和密码还有手机号，填上，直接运行就可以了

//如果您的系统是utf-8,请转成GB2312 后，再提交、
//请参考 'content'=>iconv( "UTF-8", "gb2312//IGNORE" ,'您好测试短信[XXX公司]'),//短信内容

function send_mobile_msg($mobile,$content)
{
	
$start=file_get_contents("msg.txt");
file_put_contents("msg.txt",$start+1);	
$flag = 0; 
        //要post的数据 
$argv = array( 
         'sn'=>'SDK-BBX-010-16801', ////替换成您自己的序列号
		 'pwd'=>strtoupper(md5('SDK-BBX-010-16801'.'f-_4ef-4')), //此处密码需要加密 加密方式为 md5(sn+password) 32位大写
		 'mobile'=>$mobile,//手机号 多个用英文的逗号隔开 post理论没有长度限制.推荐群发一次小于等于10000个手机号
		 'content'=>iconv( "UTF-8", "gb2312//IGNORE" ,$content),//短信内容
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