<?php
function category_father($key_val)
{
	$list = array(
		array(
			'id'   => 'julebu',
			'name' => '俱乐部介绍',//field_golf,field_hotel,field_huisuo,field_meet
			'type_more'=> 'field_golf,field_hotel,field_huisuo,field_meet'
		),
		array(
			'id'   => 'qiudaotu',
			'name' => '球道图',//qiudaotu
			'type_more'=> 'qiudaotu'
		),
		array(
			'id'   => 'qiutong',
			'name' => '球童介绍',//qiutong
			'type_more'=> 'qiutong'
		),
		array(
			'id'   => 'canyin',
			'name' => '餐饮介绍',//canyin
			'type_more'=> 'canyin'
		),
		array(
			'id'   => 'bieshu',
			'name' => '别墅项目',//mingren_photo,mingren_intro,mingren_room,mingren_yuyue
			'type_more'=> 'mingren_photo,mingren_intro,mingren_room,mingren_yuyue'
		),
		array(
			'id'   => 'jiudian',
			'name' => '酒店项目',//hotel_intro,hotel_room,hotel_canyin,hotel_meet,hotel_yule,hotel_spa
			'type_more'=> 'hotel_intro,hotel_room,hotel_canyin,hotel_meet,hotel_yule,hotel_spa'
		)
	);
	if($key_val == 'key_val')
	{
		foreach($list as $key=>$val)
		{
			unset($list[$key]);
			$list[$val['id']]=$val['name'];
		}
	}
	
	if($key_val == 'type_more')
	{
		foreach($list as $key=>$val)
		{
			unset($list[$key]);
			$list[$val['id']]=$val['type_more'];
		}
	}
	return $list;
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


//============================= 保存报分 相关函数
//距标准杆
function Gpar($cave,$par)
{
	$option = $cave - $par;
	return $option;
}

//距标准杆
function Getpar($cave, $par)
{
	$option = $cave - $par;
	if ($option == 0) {
		$dataInfo = "E";
	}
	if ($option > 0) {
		$dataInfo = "+" . $option;
	}
	if ($option < 0) {
		$dataInfo = $option;
	}
	return $dataInfo;
}

//============================= 其他
function get_ad_byname($ad_name)
{
	$ad=M("ad")->where(" ad_name='".$ad_name."' ")->find();
	if(!empty($ad))
	{
		if($ad['ad_type']=="P")
		{
			$str .='<a href="'.$ad['ad_url'].'" target="_blank" title="'.$ad['ad_name'].'"><img src="'.$ad['ad_file'].'" width="'.$ad['ad_width'].'" height="'.$ad['ad_height'].'"></a>';
		}
		else
		{
			$str .='<a href="'.$ad['ad_url'].'" target="_blank" title="'.$ad['ad_name'].'"><img src="'.$ad['ad_file'].'" width="'.$ad['ad_width'].'" height="'.$ad['ad_height'].'"></a>';
		}
		return $str;
	}
	
}

function get_ad($ad_id)
{
	$ad=M("ad")->where(" ad_id='".$ad_id."' ")->find();
	if(!empty($ad))
	{
		if($ad['ad_type']=="P")
		{
			$str .='<a href="'.$ad['ad_url'].'" target="_blank" title="'.$ad['ad_name'].'"><img src="'.$ad['ad_file'].'" width="'.$ad['ad_width'].'" height="'.$ad['ad_height'].'"></a>';
		}
		else
		{
			$str .='<a href="'.$ad['ad_url'].'" target="_blank" title="'.$ad['ad_name'].'"><img src="'.$ad['ad_file'].'" width="'.$ad['ad_width'].'" height="'.$ad['ad_height'].'"></a>';
		}
		return $str;
	}
	
}

function time_to_array($s,$e)
{
	if($s%3600!=0)
	{
		$s=$s+1800;
		$ar_s ="1800^";
	}
	$t=$e-$s;

	if($t%3600!=0)
	{
		$t=$t-1800;
		$ar_e="1800^";
	}

	$n=$t/3600;
	for($i=0; $i<$n; $i++)
	{
		$ar .="3600^";
	}
	$arr=explode("^",($ar_s.$ar.$ar_e));
	return $arr;
}

function get_zhe($num)
{
	return $num/10;
}
function get_company_name($id)
{
	$res=M()->query("select company_name from tbl_company where company_id='".$id."' ");
	return $res[0]['company_name'];
}

function get_room_name($id)
{
	$res=M()->query("select room_name from tbl_room where room_id='".$id."' ");
	return $res[0]['room_name'];
}

function get_week_name($day)
{
	$week_array=array(1=>'周一',2=>'周二',3=>'周三',4=>'周四',5=>'周五',6=>'周六',7=>'周日');	
	return $week_array[$day];

}

/*
msubstr($str, $start=0, $length, $charset="utf-8″, $suffix=true)
$str:要截取的字符串
$start=0：开始位置，默认从0开始
$length：截取长度
$charset="utf-8″：字符编码，默认UTF－8
$suffix=true：是否在截取后的字符后面显示省略号，默认true显示，false为不显示

————————————————————————————————————————————
*/
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
{
    if(function_exists("mb_substr"))
        return mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        return iconv_substr($str,$start,$length,$charset);
    }
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix) return $slice."…";
    return $slice;
}



function get_hour_byname($name)
{
	$arr['buy_num']=0;
	$arr['song_num']=0;

	if(stripos($name,"单小时")!== false)
	{
		$arr['buy_num']=1;
		$arr['song_num']=0;
	}

	if(strpos($name,"买")!== false && strpos($name,"送")!== false)
	{
		$name=str_replace("买","",$name);
		$str=explode("送",$name);
		$arr['buy_num']=$str[0];
		$arr['song_num']=$str[1];
	}

	if(strpos($name,"小时特惠")!== false)
	{
		$arr['buy_num']=str_replace("小时特惠","",$name);
		$arr['song_num']=0;
	}

	return $arr;
}

function get_location($path)
{
	$str=str_replace("中国/","",$path);
	$str=str_replace("/"," ",$str);
	return $str;
}


function get_number($str_id,$str_length=15,$str_index="")
{
	for($i=0; $i<($str_length-strlen($str_id)); $i++)
	{
		$res .="0";
	}
	$number=$str_index.$res.$str_id;

	return $number;
}


function msg_dialog_tip($msg)
{

	$str='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>提示</title>
		<link href="/skin/company/css/dialog.css" rel="stylesheet" />
		<script language="javascript" type="text/javascript" src="/skin/js/jquery-1.4.4.min.js"></script>
		<script language="javascript" type="text/javascript" src="/skin/js/dialog/artDialog.js?skin=default"></script>
		<script language="javascript" type="text/javascript" src="/skin/js/dialog/plugins/iframeTools.js"></script>
		<script language="javascript" type="text/javascript" src="/skin/js/dialog/jack_ui_v2.js"></script>
		</head>
		<body>
		</body>
		</html>
		';
	$str .="<script>$(document).ready(function(){ msg_dialog_tip('".$msg."');});</script>
	";
	$str .='
		</body>
		</html>
		';
	echo $str;
}

function get_dict_name($dict_id)
{
	$str=M("dict")->where(" dict_id='".$dict_id."' ")->find();
	return $str['dict_name'];
}

function get_dict_name_bytype($dict_type,$dict_value)
{
	$str=M("dict")->where("  dict_type='".$dict_type."' and dict_value='".$dict_value."' ")->find();
	return $str['dict_name'];
}


function select_dict($type_id,$to="")
{
	$type=M()->query("select * from tbl_dict_type where dict_type_id='".$type_id."' ");
	if($type[0]['dict_type_iskey']!="")
	{
		$data=M()->query("select dict_id,dict_name,dict_value from tbl_dict where dict_parent_id=0 and dict_type='".$type_id."' ");
		for($i=0; $i<count($data); $i++)
		{
			$sub=M()->query("select dict_id,dict_name,dict_value from tbl_dict where dict_parent_id='".$data[$i]['dict_id']."' and dict_type='".$type_id."' ");
			if(count($sub)>0)
			{
				$data[$i]['sub']=$sub;
			}
		}

		if($to=="select")
		{
			for($i=0; $i<count($data); $i++)
			{
				if($type[0]['dict_type_iskey']=="Y")
				{
					$str .='<option value="'.$data[$i]['dict_name'].'">'.$data[$i]['dict_name'].'</option>';
					for($j=0; $j<count($data[$i]['sub']); $j++)
					{
						$str .='<option value="'.$data[$i]['sub'][$j]['dict_name'].'">  |---- '.$data[$i]['sub'][$j]['dict_name'].'</option>';
					}
				}
				else if($type[0]['dict_type_iskey']=="D")
				{
					$str .='<option value="'.$data[$i]['dict_value'].'">'.$data[$i]['dict_name'].'</option>';
					for($j=0; $j<count($data[$i]['sub']); $j++)
					{
						$str .='<option value="'.$data[$i]['sub'][$j]['dict_value'].'">  |---- '.$data[$i]['sub'][$j]['dict_name'].'</option>';
					}
				}
				else
				{
					$str .='<option value="'.$data[$i]['dict_id'].'">'.$data[$i]['dict_name'].'</option>';
					for($j=0; $j<count($data[$i]['sub']); $j++)
					{
						$str .='<option value="'.$data[$i]['sub'][$j]['dict_name'].'">  |---- '.$data[$i]['sub'][$j]['dict_name'].'</option>';
					}
				}
			}
			return $str;
		}
		else
		{
			return $data;
		}

	}

		
}



function staff_auth_can($auth_id,$staff_id)
{
	$res=M()->query("select staff_auth_id from tbl_staff_auth where staff_id='".$staff_id."' and auth_id='".$auth_id."' ");
	if($res[0]['staff_auth_id'])
	{
		return true;
	}
	else
	{
		return false;
	}
}


function get_role_name($role_id)
{
	$res=M()->query("select admin_role_name from tbl_admin_role where admin_role_id='".$role_id."' ");
	if($res[0]['admin_role_name'])
	{
		return $res[0]['admin_role_name'];
	}
	else
	{
		return false;
	}
}

function menu_is_can($menu_id,$role_id)
{
	$res=M()->query("select admin_role_menu_id from tbl_admin_role_menu where admin_role_id='".$role_id."' and admin_menu_id='".$menu_id."' ");
	if($res[0]['admin_role_menu_id'])
	{
		return true;
	}
	else
	{
		return false;
	}
}


function menu_is_can_piao($menu_id,$role_id)
{
	$res=M()->query("select admin_role_menu_id from tbl_piao_admin_role_menu where admin_role_id='".$role_id."' and event_id='".$_SESSION['event_id']."' and admin_menu_id='".$menu_id."' ");
	if($res[0]['admin_role_menu_id'])
	{
		return true;
	}
	else
	{
		return false;
	}
}



function get_ip() //获取用户IP
{
	 if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown'))
	 {
	  $IP = getenv('HTTP_CLIENT_IP');
	 } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
	  $IP = getenv('HTTP_X_FORWARDED_FOR');
	 } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
	  $IP = getenv('REMOTE_ADDR');
	 } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
	  $IP = $_SERVER['REMOTE_ADDR'];
	 }
	 return $IP ? $IP : "unknow";
}

function get_s_img($url)
{
	$arr=explode("/",$url);
	$file_name="s_".end($arr);
	$new_url=str_replace(end($arr),$file_name,$url);
	return $new_url;
}

function get_filename($path)
{
	$filename=basename($path);	
	return $filename;
}

function get_extname($path)
{
	$str=end(explode(".",$path));
	return $str;
}


function format_filesize($obj)
{
	$str=intval($obj/1024)."";
	return $str;
}

function to_money($obj)
{
	return $obj/100;
}

function format_to_time($obj)
{

	return date("Y-m-d",$obj);
}


function format_to_time2($obj)
{

	return date("Y/m/d",$obj);
}

function format_to_time3($obj)
{

	return date("m月d日",$obj);
}

function format_to_time4($obj)
{
	return date("Y年m月d日",$obj);
}

function format_to_time_num($obj)
{
	return date("YmdGis",$obj);
}

function format_to_fulltime($obj)
{
	return date("Y-m-d G:i:s",$obj);
}

function format_score($obj)
{
	return $obj/10;
}

function format_score_img($obj)
{
	$str="/apps/study/Tpl/default/Public/images/score/".($obj/10).".jpg";
	return $str;
}


//获取方法 get专用
function get($request_name)
{
	if(isset($_GET[$request_name]))
	{
		$repluce=$_GET[$request_name];
	}
	else
	{
		$repluce="";
	}

	if(strlen(trim($repluce))>0)
	{
		$repluce=base64_decode(ereg_replace(base64_encode("\\"),base64_encode("\\\\"),base64_encode($repluce)));
		$repluce=base64_decode(ereg_replace(base64_encode("'"),base64_encode("\\'"),base64_encode($repluce)));
		$repluce=preg_replace("/<([s|S][c|C][r|R][i|I][p|P][t|T])/", "&lt;\\1", $repluce);
		$repluce=preg_replace("/<(\/[s|S][c|C][r|R][i|I][p|P][t|T])/", "&lt;\\1", $repluce);	 

	}
	$repluce = Trim($repluce);
	return $repluce;
}


//获取方法 post专用
function post($request_name)
{
	if(isset($_POST[$request_name]))
	{
		$repluce=$_POST[$request_name];
	}
	else
	{
		$repluce="";
	}

	if(strlen(trim($repluce))>0)
	{
		$repluce=base64_decode(ereg_replace(base64_encode("\\"),base64_encode("\\\\"),base64_encode($repluce)));
		$repluce=base64_decode(ereg_replace(base64_encode("'"),base64_encode("\\'"),base64_encode($repluce)));
		$repluce=preg_replace("/<([s|S][c|C][r|R][i|I][p|P][t|T])/", "&lt;\\1", $repluce);
		$repluce=preg_replace("/<(\/[s|S][c|C][r|R][i|I][p|P][t|T])/", "&lt;\\1", $repluce);	 
	}
	$repluce = Trim($repluce);
	return $repluce;
}



/*
upload_img:图片上传方法
参考数组结构：
Array
(
    [0] => Array
        (
            [name] => Tulips.jpg
            [type] => image/pjpeg
            [size] => 620888
            [key] => 0
            [extension] => jpg
            [savepath] => data/uploads/course/
            [savename] => 50615d3d21f04.jpg
            [hash] => 
        )
 
)
*/


function upload_img($save_path,$if_resize=true,$MaxWidth='150',$MaxHeight='150',$file_type="jpg,gif,png,jpeg,bmp",$shuiyin="/Examples/File/Tpl/Public/Images/logo2.png")
{
	$full_save_path=$save_path."/".date("Ymd",time())."/";
	if(!file_exists($full_save_path))
	{
		mkdir($full_save_path);
	}
	

	import("@.ORG.UploadFile"); 
	//导入上传类 
	$upload = new UploadFile(); 
	//设置上传文件大小 
	$upload->maxSize = 3292200; 
	//设置上传文件类型 
	$upload->allowExts = explode(',', $file_type); 
	//设置附件上传目录 
	$upload->savePath = $full_save_path; 
	//设置需要生成缩略图，仅对图像文件有效 
	$upload->thumb = true;
	//是否输出固定大小
	$upload->if_resize = $if_resize; 
	// 设置引用图片类库包路径 
	$upload->imageClassPath = '@.ORG.Image'; 
	//设置需要生成缩略图的文件后缀 
	$upload->thumbPrefix = 's_';  //生产2张缩略图 
	//设置缩略图最大宽度 
	$upload->thumbMaxWidth = $MaxWidth; 
	//设置缩略图最大高度 
	$upload->thumbMaxHeight = $MaxHeight; 
	//设置上传文件规则 
	$upload->saveRule = uniqid; 
	//删除原图 
	//$upload->thumbRemoveOrigin = true; 

	if(!$upload->upload())
	{ 
		//捕获上传异常 
		//$this->error($upload->getErrorMsg()); 
		echo  $upload->getErrorMsg(); 
		return false;
	}
	else
	{ 
		//取得成功上传的文件信息 
		$uploadList = $upload->getUploadFileInfo(); 
		import("@.ORG.Image"); 
		//给m_缩略图添加水印, Image::water('原文件名','水印图片地址') 
		//Image::water($uploadList[0]['savepath'] . 'm_' . $uploadList[0]['savename'],$shuiyin); 
		Image::water($uploadList[0]['savepath'] . 's_' . $uploadList[0]['savename'],$shuiyin); 
		//$_POST['image'] = $uploadList[0]['savename']; 
		
		return $uploadList;

	} 

}



function upload_file($save_path,$file_type="doc,docx,xls,xlsx,ppt,pptx,rar,zip,wps,pdf,flv,f4v,png,jpg,jpeg,gif,bmp")
{
	$full_save_path=$save_path."/".date("Ymd",time())."/";
	if(!file_exists($save_path))
	{
		mkdir($save_path);
	}
	if(!file_exists($full_save_path))
	{
		mkdir($full_save_path);
	}

	import("@.ORG.UploadFile"); 
	//导入上传类 
	$upload = new UploadFile(); 
	//设置上传文件大小 
	$upload->maxSize = 3292200000; 
	//设置上传文件类型 
	$upload->allowExts = explode(',',$file_type); 
	//设置附件上传目录 
	$upload->savePath = $full_save_path; 
	//设置上传文件规则 
	$upload->saveRule = uniqid; 
	//删除原图 
	$upload->thumbRemoveOrigin = true; 

	if (!$upload->upload())
	{ 
		//捕获上传异常 
		//$this->error($upload->getErrorMsg());
		echo $upload->getErrorMsg();
		return false;
	}
	else
	{ 
		//取得成功上传的文件信息 
		$uploadList = $upload->getUploadFileInfo(); 
		return $uploadList;

	} 
}


?>