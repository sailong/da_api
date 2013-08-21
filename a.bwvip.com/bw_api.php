<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: member.php 20112 2011-02-15 07:10:53Z monkey $
 */

define('APPTYPEID', 0);
define('CURSCRIPT', 'member');
require './source/class/class_core.php';
$discuz = & discuz_core::instance();

$discuz->init();
/*接口版本*/
$versions = $_G['gp_versions'] ? $_G['gp_versions'] : '2.0';

/*接口mod 对应的 接口文件*/
$modarray = array('register', 'login','card','blog','weibo','mb','event','club','score','ad','user','team');
$mod = !in_array($discuz->var['mod'], $modarray) ? 'error' : $discuz->var['mod'];
if($mod=='error') api_json_result(0,99999,'你访问的接口不存在 或者 参数mod值不匹配',null);

//不同设备调用不同接口
$userAgent = $_SERVER['HTTP_USER_AGENT'];
/*
if(strpos($userAgent,"iPhone") || strpos($userAgent,"iPad") || strpos($userAgent,"iPod") || strpos($userAgent,"iOS"))
{
	$versions="1.0";
}
else
{
	
}
*/
$ac=$_G['gp_ac'];
$no_token=$_G['gp_no_token'];
if(!$no_token && $ac<>"app_version")
{
	$token=$_G['gp_token'];
	if(!yanzheng_token($token))
	{
		api_json_result(0,88888,'token error！请尝试修改成正确的系统时间',null);
	}
}



//tj_start
if(strpos($_SERVER['HTTP_USER_AGENT'],"iPhone"))
{
	$userAgent="iPhone";
}
else if(strpos($_SERVER['HTTP_USER_AGENT'],"iPad"))
{
	$userAgent="iPad";
}
else if(strpos($_SERVER['HTTP_USER_AGENT'],"iPod"))
{
	$userAgent="iPod";
}
else if(strpos($_SERVER['HTTP_USER_AGENT'],"iOS"))
{
	$userAgent="iOS";
}
else if(strpos($_SERVER['HTTP_USER_AGENT'],"Android"))
{
	$userAgent="Android";
}
else
{
	$userAgent='other';
}

if($_G['gp_uid'])
{
	$log_uid=$_G['gp_uid'];
}
else
{
	$log_uid=0;
}
if($_G['gp_field_uid'])
{
	$log_field_uid=$_G['gp_field_uid'];
}
else
{
	$log_field_uid=0;
}

$tj_sql .=" insert into tbl_app_log ( ";
$tj_sql .=" uid, ";
$tj_sql .=" field_uid, ";
$tj_sql .=" app_log_mod, ";
$tj_sql .=" ac, ";
$tj_sql .=" ip, ";
$tj_sql .=" province, ";
$tj_sql .=" user_agent, ";
$tj_sql .=" url, ";
$tj_sql .=" app_log_addtime ";
$tj_sql .=" ) values( ";
$tj_sql .=" '".$log_uid."', ";
$tj_sql .=" '".$log_field_uid."', ";
$tj_sql .=" '".$mod."', ";
$tj_sql .=" '".$ac."', ";
$tj_sql .=" '".get_real_ip()."', ";
$tj_sql .=" '".$province."', ";
$tj_sql .=" '".$userAgent."', ";
$tj_sql .=" '".$_SERVER['REQUEST_URI']."', ";
$tj_sql .=" '".time()."' ";
$tj_sql .=" ) ";
$tj_up=DB::query($tj_sql);
//tj_end




define('BWAPIMODULE', $mod);
require libfile('function/member');
require libfile('class/member');
loaducenter();


//引入接口需要装载的文件
include 'api/_mobile/'.$versions.'/api_config.php';
include 'api/_mobile/'.$versions.'/api_func.php';
include 'api/_mobile/'.$versions.'/api_error.php';
include 'api/_mobile/'.$versions.'/'.$mod.'_api.php';

echo 'api/_mobile/'.$versions.'/'.$mod.'_api.php';
/*mobile api josn_result*/
function api_json_result($response=0,$error='',$message='',$data=''){
    $result = array(
                  'response'      => $response,
                  'error'         => $error,
                  'message'       => $message,
                  $data['title']  => $data['data']
                );
    exit(json_encode($result));
}



function xml_serialize($arr, $htmlon = FALSE, $isnormal = FALSE, $level = 1)
{
	$s = $level == 1 ? "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\r\n<root>\r\n" : '';
	$space = str_repeat("\t", $level);
	foreach($arr as $k => $v) {
		if(!is_array($v)) {
			$s .= $space."<item id=\"$k\">".($htmlon ? '' : '').$v.($htmlon ? '' : '')."</item>\r\n";
		} else {
			$s .= $space."<item id=\"$k\">\r\n".xml_serialize($v, $htmlon, $isnormal, $level + 1).$space."</item>\r\n";
		}
	}
	$s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
	return $level == 1 ? $s."</root>" : $s;
}



function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
{
    if(function_exists("mb_substr")){
            if ($suffix && strlen($str)>$length)
                return mb_substr($str, $start, $length, $charset)."...";
        else
                 return mb_substr($str, $start, $length, $charset);
    }
    elseif(function_exists('iconv_substr')) {
            if ($suffix && strlen($str)>$length)
                return iconv_substr($str,$start,$length,$charset)."...";
        else
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


function cutstr_html($string, $sublen)    
{
	$string = strip_tags($string);
	$string = preg_replace ('/\n/is', '', $string);
	$string = preg_replace ('/ |　/is', '', $string);
	$string = preg_replace ('/&nbsp;/is', '', $string);

	preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $t_string);   
	if(count($t_string[0]) - 0 > $sublen) $string = join('', array_slice($t_string[0], 0, $sublen))."";   
	else $string = join('', array_slice($t_string[0], 0, $sublen));

	$string = str_replace("\r","",$string);
	$string = str_replace("\n","",$string);

	return $string;
}



function yanzheng_token($token)
{
	global $token_timelong;
	$code=substr($token,0,32);
	$uid=substr($token,32,(strlen($token)-32));
	/*
	echo "<hr>";
	echo $time;
	echo "<hr>";
	echo $code;
	echo "<hr>";
	*/
	if($code<>md5(date("Ymd",time())."bwvip.com"))
	{
		return false;
	}
	else
	{
		if($uid)
		{
			define("TOKEN_UID",$uid);
			//echo $uid;
		}
		return true;
	}

}


//获取所在城市
function get_real_ip()
{
	$ip=false;
	if(!empty($_SERVER["HTTP_CLIENT_IP"])){
	$ip = $_SERVER["HTTP_CLIENT_IP"];
	}
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
	if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
	for ($i = 0; $i < count($ips); $i++) {
	if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])) {
	$ip = $ips[$i];
	break;
	}
	}
	}
	return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}
 
function getCity($ip)
{
	$url="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
	$ip=json_decode(file_get_contents($url));
	if((string)$ip->code=='1'){
	  return false;
	  }
	  $data = (array)$ip->data;
	return $data;
}

?>








