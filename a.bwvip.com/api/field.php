<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: meilanhu.php  2013-05-02 11:55:01 jack $
 */

define('APPTYPEID', 0);
define('CURSCRIPT', 'member');
require '../source/class/class_core.php';
$discuz = & discuz_core::instance();
$discuz->init();

/*uc*/
include '../config/config_ucenter.php';
include '../uc_client/client.php';

/*接口版本*/
$versions = $_G['gp_versions'] ? $_G['gp_versions'] : '1.0';


/*接口mod 对应的 接口文件*/
$modarray = array('register', 'login','field','ad','club','system','user','finance','message','score','event','manage_event','manage_event1','wap','go','reg_activate','card','tool');
$mod = !in_array($discuz->var['mod'], $modarray) ? 'error' : $discuz->var['mod'];
if($mod=='error') api_json_result(0,99999,'你访问的接口不存在 或者 参数mod值不匹配',null);



//token口令
$no_token=$_G['gp_no_token'];
if(!$no_token)
{
	$token=$_G['gp_token'];
	if(!yanzheng_token($token))
	{
		api_json_result(0,88888,'token error！请尝试修改正确的系统时间',null);
	}
}


define('BWAPIMODULE', $mod);
require libfile('function/member');
require libfile('class/member');
loaducenter();

include('../weibo/api/file.class.php');


//引入接口需要装载的文件
include '_field/'.$versions.'/api_config.php';
include '_field/'.$versions.'/api_func.php';
include '_field/'.$versions.'/api_error.php';
include '_field/'.$versions.'/'.$mod.'_api.php';


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

?>