<?php

/*******************************************************************

 * [JishiGou] (C)2005 - 2099 Cenwor Inc.

 *

 * This is NOT a freeware, use is subject to license terms

 *

 * @Package JishiGou $

 *

 * @Filename global.func.php $

 *

 * @Author http://www.jishigou.net $

 *

 * @Date 2011-09-28 19:16:47 69744671 1872182410 83688 $

 *******************************************************************/




if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

define('JISHIGOU_GLOBAL_FUNCTION', true);


if(!function_exists('jaddslashes'))
{
	function jaddslashes($string, $force = 0, $strip = FALSE) {
		if(!MAGIC_QUOTES_GPC || $force) {
			if(is_array($string)) {
				foreach($string as $key => $val) {
					$string[$key] = jaddslashes($val, $force, $strip);
				}
			} else {
				$string = (is_numeric($string) ? $string : addslashes($strip ? stripslashes($string) : $string));
			}
		}
		return $string;
	}
}
if(!function_exists('jstripslashes'))
{
	function jstripslashes($string) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = jstripslashes($val);
			}
		} else {
			$string = stripslashes($string);
		}
		return $string;
	}
}
if(!function_exists('daddslashes'))
{
    function daddslashes($string, $force = 0, $strip = FALSE)
    {
        return jaddslashes($string, $force, $strip);
    }
}
if(!function_exists('jstripslashes'))
{
    function dstripslashes($string)
    {
        return jstripslashes($string);
    }
}

if(!function_exists('file_put_contents')) {

	!defined('FILE_APPEND') && define('FILE_APPEND', 8);

	function file_put_contents($filename, $data, $flag = false) {
		$mode = ($flag == FILE_APPEND || strtoupper ( $flag ) == 'FILE_APPEND') ? 'ab' : 'wb';
		$f = @fopen ( $filename, $mode );
		if ($f === false) {
			return 0;
		} else {
			if ( is_array ( $data )){
				$data = implode ( '', $data );
			}
			$bytes_written = @fwrite ( $f, $data );
			@fclose ($f);
			return $bytes_written;
		}
	}
}
if(!function_exists('jfsockopen'))
{
	function jfsockopen($hostname, $port, $errno, $errstr, $timeout)
	{
		$fp = false;

		if(function_exists('fsockopen'))
		{
			@$fp = fsockopen($hostname, $port, $errno, $errstr, $timeout);
		}
		elseif(function_exists('pfsockopen'))
		{
			@$fp = pfsockopen($hostname, $port, $errno, $errstr, $timeout);
		}
		
		return $fp;
	}
}

if (!function_exists('gzdecode')){
    function gzdecode ($data){
        $flags = ord(substr($data, 3, 1));
        $headerlen = 10;
        $extralen = 0;
        $filenamelen = 0;
        if ($flags & 4){
            $extralen = unpack('v' , substr($data, 10, 2));
            $extralen = $extralen[1];
            $headerlen += 2 + $extralen;
            }
        if ($flags & 8)             $headerlen = strpos($data, chr(0), $headerlen) + 1;
        if ($flags & 16)             $headerlen = strpos($data, chr(0), $headerlen) + 1;
        if ($flags & 2)             $headerlen += 2;
        $unpacked = @gzinflate(substr($data, $headerlen));
        if ($unpacked === FALSE)
            $unpacked = $data;
        return $unpacked;
    }
}

if(!function_exists('jstrpos'))
{
	function jstrpos($haystack, $needle, $offset = null)
	{
		$jstrpos = false;
		
		if(function_exists('mb_strpos'))
		{
			$sys_config = ConfigHandler::get();
			
			$jstrpos = mb_strpos($haystack, $needle, $offset, $sys_config['charset']);
		}
		elseif(function_exists('strpos'))
		{
			$jstrpos = strpos($haystack, $needle, $offset);
		}
		
		return $jstrpos;
	}
}

function get_param($var=null, $ifnullval=null, $P=null, $G=null, $R=null) {
	$P = (isset($P) ? $P : $_POST);
	$G = (isset($G) ? $G : $_GET);
	$R = (isset($R) ? $R : $_REQUEST);
	
	if(null === $var)
	{
		return array_merge((array) $P, (array) $G, (array) $R);
	}
	
	if(isset($P[$var]))
	{
		return $P[$var];
	}
	
	if(isset($G[$var]))
	{
		return $G[$var];
	}
	
	if(isset($R[$var]))
	{
		return $R[$var];
	}
	
	return $ifnullval;
}



function cache($name,$lifetime=null,$only_get=false)
{
	static $S_filelist=null, $S_lastfile=null, $S_file=null, $S_caches=null;

	$path = (defined('TEMPLATE_ROOT_PATH') ? TEMPLATE_ROOT_PATH : ROOT_PATH) . "cache/";

	if($lifetime!==null)
	{
		if($S_file!==null)$S_lastfile=$S_file;
		$S_file=$path.$name.'.cache.php';
		$S_filelist[$S_file]=$S_lastfile;
		$file=$S_file;
		if($only_get)$S_file=null;
		if ($lifetime===0) return @unlink($file);
		if($S_caches[$name.$lifetime]!==null)return $S_caches[$name.$lifetime];
		$cache = null;
		@$cache_exists=include($file);
		if($cache_exists && ($lifetime===-1 || @filemtime($file)+$lifetime>time()))return $S_caches[$name.$lifetime]=$cache;
	}
	else
	{
		if($S_file===null)if($S_lastfile===null)return false;else $S_lastfile=$S_filelist[$S_file=$S_lastfile];
		if(is_writeable($path)===false && is_dir($path))return trigger_error("缓存目录 $path 不可写",E_USER_WARNING);
		if(is_dir($cache_dir=dirname($S_file))==false)
		{
			@$dir_list=explode("/",$cache_dir);
			foreach($dir_list as $dir)
			{
				$dirs .= $dir . "/";
				if(!@is_dir($dirs)) {
					@mkdir($dirs, 0777);
				}
			}
		}

		$data=var_export($name,true);

		$data="<?php if(!defined('IN_JISHIGOU')) exit('invalid request'); \r\n\$cache=$data;\r\n?>";
		$fp = @fopen($S_file,"wb");
		flock($fp, LOCK_EX);
		@$len=fwrite($fp,$data);
		flock($fp, LOCK_UN);
		fclose($fp);
		@chmod($S_file, 0777);
		$S_file=null;
		return $len;
	}
	return false;
}

function clearcache()
{
	return cacheclear(true);
}
function cacheclear($handle=true)
{
	if(true === $handle)
	{
		Load::lib('io');
		$IoHandler = new IoHandler();

		$ret = @$IoHandler->ClearDir(ROOT_PATH . 'cache/');
		$ret = @$IoHandler->ClearDir(ROOT_PATH . 'wap/cache/');

		return $ret;
	}
	return true;
}


function &Tag($type)
{
	include_once(ROOT_PATH . 'include/logic/tag.logic.php');
	
	return new TagLogic($type);
}

function order($order_by_list,$query_link='',$config=array())
{
	include_once(ROOT_PATH . 'include/function/order.func.php');

	return __order($order_by_list,$query_link,$config);
}

function pre($string)
{
	$string=nl2br($string);
    $string = str_replace(array("&amp;","&gt;","&lt;","&quot;","&#39;","\s","\t",),
    array("&", ">","<","\"","'","&nbsp;","&nbsp;&nbsp;&nbsp;&nbsp;",),  $string);
	return $string;
}

if(false == function_exists('http_build_query'))
{
	
	function http_build_query($form_data, $numeric_prefix = null)
	{
		static $_query = '';

		if(is_array($form_data)==false)Return false;
		foreach($form_data as $key => $values)
		{
			if(is_array($values))
			{
				$_query = http_build_query($values, isset($numeric_prefix)?sprintf('%s[%s]', $numeric_prefix, urlencode($key)):$key);
			}
			else
			{
				$key = isset($numeric_prefix)?sprintf('%s[%s]', $numeric_prefix, urlencode($key)):$key;
				$_query .= (isset($_query) ? '&' : null) . $key . '=' . urlencode(stripslashes($values));
			}
		}
		Return $_query;
	}

}
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;
	if(!$key)
    {
		$sys_config = ConfigHandler::get();
		$key = $sys_config['auth_key'];
	}
	$key = md5($key);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}

function random($length, $numeric = 0) {
	mt_srand((double)microtime() * 1000000);
	if($numeric) {
		$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
	} else {
		$hash = '';
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
		$max = strlen($chars) - 1;
		for($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
	}
	return $hash;
}



function build_like_query($fields,$keywords,$binary=false)
{
	if(trim($keywords)==false)Return '';
	$binary = ($binary ?' binary ' : '');
	$keywords=preg_replace('~[\t\s　]+and[\t\s　]+~i','\%',$keywords);
	$keyword_list=preg_split('~([\t\s　]+or[\t\s　]+)|\|~i',$keywords);
	if(count($keyword_list)>1 and $fields==false)die("搜索多个关键字其中部分，必须指定参数\$field");
	
	foreach($keyword_list as $key=>$keyword)
	{
						$keyword = addcslashes($keyword, '_%"\'\\');
				$keyword = str_replace(array('*', ), array('%', ), $keyword);
		$temp_list[] = $keyword;
	}
	
	$keywords = '';
	if(strpos($fields,',')!==false)
	{
		$field_list=explode(',',$fields);
		foreach($field_list as $field)
		{
			$keywords_list[]=$binary." ".$field.'  like "%'.implode("%\" OR \r\n".$binary.' '.$field.'  like "%',$temp_list)."%\"";
		}
		$keywords='(('.implode(') or (',$keywords_list).'))';
	}
	else
	{
		$keywords=$binary." ".$fields.' like "%'.implode("%\" OR \r\n".$binary.' '.$fields.' like "%',$temp_list)."%\"";
	}
	$keywords=preg_replace("~[%]+~",'%',$keywords);
	
	return $keywords;
}


function response_text($response)
{
	ob_clean();

	echo $response; exit;
}


function debug($mixed,$halt=true)
{
	static $num=1;
	if (function_exists("debug_backtrace"))
	{
		$debug=debug_backtrace();
		echo "<div style='background:#FF6666;color:#fff;margin-top:5px;padding:5px'>".$num++.".debug position: {$debug[0]['file']}({$debug[0]['line']})</div>";
	}
	echo "<div style='border:1px solid #ff6666;background:#fff;padding:10px'>";
	if (is_array($mixed) or is_object($mixed))
	{
		echo str_replace(array("&lt;?php","?&gt;"),'',highlight_string("<?php\r\n".var_export($mixed,true).";\r\n?>",true));
	}
	else
	{
		var_dump($mixed);
	}
	echo "</div>";
	$halt && exit;
}

if (function_exists('iconv')==false)
{
	
	function iconv($in_charset,$out_charset,$str)
	{
		if($str && strtoupper($in_charset)!=strtoupper($out_charset))
		{
			if(false!==strpos($out_charset,'/'.'/'))
			{
				$out_charset = str_replace(array('/'.'/IGNORE','/'.'/TRANSLIT'),'',strtoupper($out_charset));
			}
			
			include_once(ROOT_PATH . 'include/encoding/chinese.class.php');
			
			$CharEncoding=new Chinese($in_charset,$out_charset);
			
			return $CharEncoding->Convert($str);
		}
		return $str;
	}
}


function array_iconv($in_charset,$out_charset,$array)
{
	if($array && strtoupper($in_charset)!=strtoupper($out_charset) && (function_exists('mb_convert_encoding') || function_exists('iconv')))
    {
		if(is_array($array))
        {
			foreach($array as $key=>$val)
			{
				$key = lconv($in_charset, $out_charset, $key);
				$array[$key] = array_iconv($in_charset,$out_charset,$val);
			}
		}
        else
        {
        	$array = lconv($in_charset,$out_charset,$array);
        }
	}
	return $array;
}
function lconv($in_charset,$out_charset,$string)
{
    $return = '';

    if($string)
    {
        if (!is_numeric($string) && !is_bool($string) && is_string($string))
        {
            if(function_exists('mb_convert_encoding'))
            {
    			$return = mb_convert_encoding($string, $out_charset, $in_charset);
    		}
            elseif (function_exists('iconv'))
            {
    			$return = iconv($in_charset,$out_charset . (false!==strpos($out_charset,'/'.'/') ? '' : "/"."/TRANSLIT"), $string);
    		}
        }
        else
        {
            $return = $string;
        }
    }

    if(!$return)
    {
        $return = $string;
    }

	return $return;
}



function referer($default = '?') {
	$DOMAIN = preg_replace("~^www\.~",'',strtolower(getenv('HTTP_HOST') ? getenv('HTTP_HOST') : $_SERVER['HTTP_HOST']));
	$referer=$_POST['referer']?$_POST['referer']:$_GET['referer'];
	if($referer=='')$referer=$_SERVER['HTTP_REFERER'];
	if($referer=="" || strpos($referer,'code=register')!==false || strpos($referer,'mod=login')!==false || (strpos($referer,":/"."/")!==false && strpos($referer,$DOMAIN)===false))
	{
		global $rewriteHandler;
		if($rewriteHandler) $default = $rewriteHandler->formatURL($default,false);

		return $default;
	}
	return $referer;
}



function my_date_format($timestamp,$format="Y-m-d H:i:s")
{
	$SystemConfig = ConfigHandler::get();

	$timezone=$SystemConfig['timezone'];

	Return gmdate($format,($timestamp+$timezone*3600));
}

function cut_str($string, $length, $dot = ' ...')
{
	if(strlen($string) <= $length)
    {
		return $string;
	}

	
	$strcut = '';
    $sys_config = ConfigHandler::get();
	if(strtolower($sys_config['charset']) == 'utf-8')
    {
		$n = $tn = $noc = 0;
		while($n < strlen($string))
        {
			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t < 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}

			if($noc >= $length)
            {
				break;
			}
		}
		if($noc > $length)
        {
			$n -= $tn;
		}

		$strcut = substr($string, 0, $n);
	}
    else
    {
		for($i = 0; $i < $length; $i++)
        {
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}

	
	return $strcut.$dot;
}
function cutstr($string,$length,$dot=''){Return cut_str($string,$length,$dot);};

function strip_selected_tags(&$str,$disallowable="<script><iframe><style><link>")
{
	$disallowable=trim(str_replace(array(">","<"),array("","|"),$disallowable),'|');
	$str=str_replace(array('&lt;', '&gt;'),array('<', '>'),$str);
	$str=preg_replace("~<({$disallowable})[^>]*>(.*?<\s*\/(\\1)[^>]*>)?~is",'',$str);
	return $str;
}

function page($total_record, $per_page_num,$url='', $_config = array(), $per_page_nums = "")
{
	global $rewriteHandler;
    $SystemConfig = ConfigHandler::get();
        if(true === IN_JISHIGOU_ADMIN && isset($SystemConfig['total_page_default']))
    {
    	unset($SystemConfig['total_page_default']);
    }

	$result = array();

	$total_record = intval($total_record);
	$per_page_num = intval($per_page_num);
	if($per_page_num < 1) $per_page_num = 10;
	$config['total_page'] = max(0,(int) (isset($_config['total_page']) ? $_config['total_page'] : $SystemConfig['total_page_default']));	$config['page_display'] = isset($_config['page_display']) ? (int) $_config['page_display'] : 5;	$config['char'] = isset($_config['char']) ? (string) $_config['char'] : ' ';	$config['url_postfix'] = isset($_config['url_postfix']) ? (string) $_config['url_postfix'] : '';	$config['extra'] = isset($_config['extra']) ? (string) $_config['extra'] : '';	$config['idencode'] = (bool) $_config['idencode'];	$config['var'] = isset($_config['var']) ? (string) $_config['var'] : 'page';	$config['return'] = isset($_config['return']) ? (string) $_config['return'] : 'html';	
	extract($config);

	$total_page = ceil($total_record / $per_page_num);
	if($config['total_page']>1 && $total_page > $config['total_page'])
	{
		$total_page = $config['total_page'];
	}

	$result['total_page'] = $total_page;
	$current_page=$_GET[$var]?$_GET[$var]:$_POST[$var];
	$current_page = max(1,(int) ((true == $idencode) ? iddecode($current_page) :$current_page));
	$current_page = ($total_page > 0 && $current_page > $total_page) ? $total_page : $current_page;
	$result['current_page'] = $current_page;
	$result['title_postfix'] = $current_page > 1 ? "_第{$current_page}页" : "";
	$result['offset'] = (int) (($current_page - 1) * $per_page_num);

	$result['limit'] = " LIMIT ".$result['offset'].",{$per_page_num} ";

	if(isset($result[$return])) return $result[$return];

	if('' == $url)
	{
		$request = count($_POST) ? array_merge($_GET,$_POST) : $_GET;
		$query_string = '';
		foreach($request as $_var => $_val)
		{
			if(is_string($_val) && $var!==$_var) $query_string .= "&{$_var}=" . urlencode($_val);
		}
		$url = '?'.($result['query_string'] = trim($query_string,'&'));
	}

	$p_val = "V01001page10010V";
	if('/#'!=$url) {
		$url = ('' == $url) ? "?$var={$p_val}" : (($url_no_page = (false !== strpos($url,"&{$var}=") ? preg_replace("/\&?{$var}\=[^\&]*/i",'',$url) : $url)) . "&{$var}={$p_val}");
		if($rewriteHandler)
		{
			$url_no_page = $rewriteHandler->formatURL($url_no_page,false);
			$url=$rewriteHandler->formatURL($url,false);
		}
	} else {
		$url_no_page = $url;
	}
	$result['url'] = $url;

	if(isset($result[$return])) return $result[$return];

	$html = '';
	if($total_record > $per_page_num)
	{
		$halfper = (int) ($config['page_display'] / 2);

		$html=($current_page - 1 >= 1) ? "\n<a href='{$url_no_page}{$url_postfix}' title=1 {$extra}>首页</a>{$char}\n<a href='".(1 == ($previous_page = ($current_page - 1)) ? $url_no_page : str_replace($p_val,(true===$idencode?idencode($previous_page):$previous_page),$url))."{$url_postfix}' title=$previous_page {$extra}>上一页</a>{$char}" : "首页{$char}上一页{$char}";

		for ($i=$current_page-$halfper,$i>0 or $i=1,$j=$current_page + $halfper,$j<$total_page or $j=$total_page;$i<=$j;$i++) {
			$html.=($i==$current_page)?"\n<B>".($i)."</B>{$char}":"\n<a href='".(1 == $i ? $url_no_page : str_replace($p_val,(true===$idencode?idencode($i):$i),$url))."{$url_postfix}' title=$i {$extra}>".($i)."</a>{$char}";
		}

		$html.=(($next_page=($current_page + 1)) > $total_page)?"下一页{$char}尾页":"\n<a href='".str_replace($p_val,(true===$idencode?idencode($next_page):$next_page),$url)."{$url_postfix}' title=$next_page {$extra}>下一页</a>{$char}\n<a href='".str_replace($p_val,(true===$idencode?idencode($total_page):$total_page),$url)."{$url_postfix}' title=$total_page {$extra}>尾页</a>";

		if(!empty($per_page_nums))
		{
			$per_page_num_list=is_array($per_page_nums)?$per_page_nums:explode(" ",$per_page_nums);
			$current_url=str_replace($p_val,(true===$idencode?idencode($current_page):$current_page),$url).$url_postfix;
			$pn_postfix=$rewriteHandler?$rewriteHandler->argSeparator."pn".$rewriteHandler->varSeparator:"&pn=";
			$per_page_num_select="<select name='per_page_num' onchange=\"window.location='{$current_url}{$pn_postfix}'+this.value\">";
			foreach ($per_page_num_list as $_per_page_num)
			{
				$selected=$_per_page_num==$per_page_num?"selected":"";
				$per_page_num_select.="<option value={$_per_page_num} $selected>{$_per_page_num}";
			}
			$per_page_num_select.="</select>";
		}
		else {
			$per_page_num_select="<i>{$per_page_num}</i>";
		}

		$html ="<div id='page'> 当前<i>{$current_page}</i>/共<i>{$total_page}</i>页 {$html} 每页显示${per_page_num_select}条/共<i>{$total_record}</i>条</div>";
	}
	$result['html'] = $html;

	if(isset($result[$return])) return $result[$return];

	return $result;
}


function strexists($haystack, $needle)
{
	return !(strpos($haystack, $needle) === FALSE);
}


function makethumb($srcfile,$dstfile,$thumbwidth,$thumbheight,$maxthumbwidth=0,$maxthumbheight=0,$src_x=0,$src_y=0,$src_w=0,$src_h=0) {
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
	if($towh <= $srcwh)
    {
		$ftow = $tow;
		$ftoh = round($ftow*($srch/$srcw),2);
	}
    else
    {
		$ftoh = $toh;
		$ftow = round($ftoh*($srcw/$srch),2);
	}


    if($make_max)
    {
        $maxtowh = $maxtow/$maxtoh;
        if($maxtowh <= $srcwh)
        {
            $fmaxtow = $maxtow;
    		$fmaxtoh = round($fmaxtow*($srch/$srcw),2);
        }
        else
        {
            $fmaxtoh = $maxtoh;
    		$fmaxtow = round($fmaxtoh*($srcw/$srch),2);
        }

    	if($srcw <= $maxtow && $srch <= $maxtoh)
        {
    		$make_max = 0;    	}
    }


    $maxni = '';
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
			imagejpeg($ni, $dstfile, 100);
						if($make_max && $maxni) {
				imagejpeg($maxni, $srcfile, 100);
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

function remove_xss($val) {
   $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
   $search = 'abcdefghijklmnopqrstuvwxyz';
   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $search .= '1234567890!@#$%^&*()';
   $search .= '~`";:?+/={}[]-_|\'\\';
   for ($i = 0; $i < strlen($search); $i++) {
      $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val);       $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val);    }
   $ra1 = ConfigHandler::get("xss",'tag');
   $ra2 = ConfigHandler::get("xss",'attribute');
   $ra = array_merge($ra1, $ra2);

   $found = true;
   while ($found == true) {
      $val_before = $val;
      for ($i = 0; $i < sizeof($ra); $i++) {
         $pattern = '/';
         for ($j = 0; $j < strlen($ra[$i]); $j++) {
            if ($j > 0) {
               $pattern .= '(';
               $pattern .= '(&#[xX]0{0,8}([9ab]);)';
               $pattern .= '|';
               $pattern .= '|(&#0{0,8}([9|10|13]);)';
               $pattern .= ')*';
            }
            $pattern .= $ra[$i][$j];
         }
         $pattern .= '/i';
         $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2);
         $val = preg_replace($pattern, $replacement, $val);
         if ($val_before == $val) {
            $found = false;
         }
      }
   }
   return $val;
}


function filter(&$string)
{
	static $filter = null, $filter_keyword_list = null;
		$string=trim($string);
	if($string) {
		if(false!==strpos($string,'<')) {
			$string=strip_selected_tags($string,"<script><iframe><style><link><meta>");
			$string=remove_xss($string);
		}

		if($filter===null) {
			$filter = (array) ConfigHandler::get('filter');
		}

		if(!$filter['enable']) {
			return false;
		}

				if(!empty($filter['keywords']))
		{
			if($filter_keyword_list===null)
			{
				$filter_keyword_list=explode("|",str_replace(array("\r\n","\r","\n","\t","\\|"),"|",trim($filter['keywords'])));
			}
			$sys_config = ConfigHandler::get();
			foreach ($filter_keyword_list as $keyword)
			{				
				$strpos = jstrpos($string, $keyword);
				
				if($strpos!==false)
				{
					$keyword_len=strlen($keyword);
					if($keyword_len>2 && $keyword_len<40)
					{
						$statistic['filter_type']='keyword';
						$statistic['keyword'] = $keyword;
												return "含有禁止发布的内容".($filter['keyword_disable'] ? "" : " {$keyword} ")."，请修改后重新发布！";
					}
				}
			}
		}
	}

	return false;
}

function request($action, $post=array(), &$error) {
	settype($post,"array");
	$post['system_env'] = ($post['system_env'] ? array_merge((array) $post['system_env'],(array) get_system_env()) : (array) get_system_env());
	    $aclData = upsCtrl()->Account();
    $post['__acl__']['account'] = $aclData['account'];
    $post['__acl__']['token'] = $aclData['token'];
        $data='_POST='.urlencode(base64_encode(serialize($post)));
    include ROOT_PATH.'setting/settings.php';
    $charset = strtolower(str_replace('-', '', $config['charset']));
    $version = urlencode(SYS_VERSION);
	$server_url = base64_decode('aHR0cDovL3VwZGF0ZS5jZW53b3IuY29tL3NlcnZlci5yZXF1ZXN0LnBocA==')."?do=$action&pid=2&charset=$charset&iver=$version";
	$response=dfopen($server_url,5000000,$data);
		$error_msg=array(1=>"error_nodata",2=>"error_format",);
	if($response == "") {
		$result = $error_msg[($error = 1)];
    }else{
		$int = preg_match("/<DATA>(.*)<\/DATA>/s", $response, $m);
		if($int < 1){
			$result = $error_msg[($error = 2)];
		}else{
						if(false!==strpos($m[1],"\n")) {
				$m[1] = preg_replace('~\s+\w{1,10}\s+~','',$m[1]);
			}
			$response = unserialize(base64_decode($m[1]));
			$result = $response['data'];
			if($response['type']) {
				$error = 3;
			}
		}
    }

	return $result;
}
function get_system_env( )
{
	$e = array();
	$e['time'] = gmdate( "Y-m-d", time( ) );
	$e['os'] = PHP_OS;
	$e['ip'] = gethostbyname($_SERVER['SERVER_NAME']) or ($e['ip'] = getenv( "SERVER_ADDR" )) or ($e['ip'] = getenv('LOCAL_ADDR'));
	$e['sapi'] = php_sapi_name( );
	$e['host'] = strtolower(getenv('HTTP_HOST') ? getenv('HTTP_HOST') : $_SERVER['HTTP_HOST']);
	$e['path'] = substr(dirname(__FILE__),0,-17);
	$e['cpu'] = $_ENV['PROCESSOR_IDENTIFIER']."/".$_ENV['PROCESSOR_REVISION'];
	$e['name'] = $_ENV['COMPUTERNAME'];
	if(defined('SYS_VERSION')) $e['sys_version']=SYS_VERSION;
	if(defined('SYS_BUILD')) $e['sys_build']=SYS_BUILD;
	$sys_conf = ConfigHandler::get();
	if($sys_conf['site_name']) $e['sys_name'] = $sys_conf['site_name'];
	if($sys_conf['site_admin_email']) $e['sys_email'] = $sys_conf['site_admin_email'];
	if($sys_conf['site_url']) $e['sys_url'] = $sys_conf['site_url'];
    if($sys_conf['charset']) $e['sys_charset'] = $sys_conf['charset'];

	return $e;
}

function dfopen($url, $limit = 10485760 , $post = '', $cookie = '', $bysocket = false,$timeout=5,$agent="") {
	if(ini_get('allow_url_fopen') && !$bysocket && !$post) {
		$fp = @fopen($url, 'r');
		$s = $t = '';
		if($fp) {
			while ($t=@fread($fp,2048)) {
				$s.=$t;
			}
			fclose($fp);
		}
		if($s) {
			return $s;
		}
	}

	$return = '';
	$agent=$agent?$agent:"Mozilla/5.0 (compatible; Googlebot/2.1; +http:/"."/www.google.com/bot.html)";
	$matches = parse_url($url);
	$host = $matches['host'];
	$script = $matches['path'].($matches['query'] ? '?'.$matches['query'] : '').($matches['fragment'] ? '#'.$matches['fragment'] : '');
	$script = $script ? $script : '/';
	$port = !empty($matches['port']) ? $matches['port'] : 80;
	if($post) {
		$out = "POST $script HTTP/1.1\r\n";
		$out .= "Accept: */"."*\r\n";
		$out .= "Referer: $url\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Accept-Encoding: none\r\n";
		$out .= "User-Agent: $agent\r\n";
		$out .= "Host: $host\r\n";
		$out .= 'Content-Length: '.strlen($post)."\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cache-Control: no-cache\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
		$out .= $post;
	} else {
		$out = "GET $script HTTP/1.1\r\n";
		$out .= "Accept: */"."*\r\n";
		$out .= "Referer: $url\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Accept-Encoding: none\r\n";
		$out .= "User-Agent: $agent\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
	}
	
	$fp = jfsockopen($host, $port, $errno, $errstr, $timeout);

	if(!$fp) {
		return false;
	} else {
		fwrite($fp, $out);
		$return = '';
		while(!feof($fp) && $limit > -1) {
			$limit -= 8192;
			$return .= @fread($fp, 8192);
			if(!isset($status)) {
				preg_match("|^HTTP/[^\s]*\s(.*?)\s|",$return, $status);
				$status=$status[1];
				if($status!=200) {
					return false;
				}
			}
		}
		fclose($fp);
				preg_match("/^Location: ([^\r\n]+)/m",$return,$match);
		if(!empty($match[1]) && $location=$match[1]) {
			if(strpos($location,":/"."/")===false) {
				$location=dirname($url).'/'.$location;
			}
			$args=func_get_args();
			$args[0]=$location;
			return call_user_func_array("dfopen",$args);
		}
		if(false!==($strpos = strpos($return, "\r\n\r\n"))) {
			$return = substr($return,$strpos);
			$return = preg_replace("~^\r\n\r\n(?:[\w\d]{1,8}\r\n)?~","",$return);
			if("\r\n\r\n"==substr($return,-4)) {
				$return = preg_replace("~(?:\r\n[\w\d]{1,8})?\r\n\r\n$~","",$return);
			}
		}

		return $return;
	}
}

function str_exists($haystack,$needle)
{
	$arg_list = func_get_args();
	while(($needle=$arg_list[++$i])!==null)
	{
		if(strpos($haystack,$needle)!==false)return true;
	}
	return false;
}

function client_ip()
{
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$onlineip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}

	preg_match('/[\d\.]{7,15}/', $onlineip, $onlineipmatches);
	$onlineip = ($onlineipmatches[0] ? $onlineipmatches[0] : 'unknown');

	return $onlineip;
}

function face_path($uid) {
	$key = "ww"."w.jis"."higo"."u.c"."om"; 	$hash = md5($key."\t".$uid."\t".strlen($uid)."\t".$uid % 10);
	$path = $hash{$uid % 32} . "/" . abs(crc32($hash) % 100) . "/";

	return $path;
}
function jsg_uc_face_path($uid, $size = 'middle', $type = '') {
	$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'middle';
	$uid = abs(intval($uid));
	$uid = sprintf("%09d", $uid);
	$dir1 = substr($uid, 0, 3);
	$dir2 = substr($uid, 3, 2);
	$dir3 = substr($uid, 5, 2);
	$typeadd = $type == 'real' ? '_real' : '';
	return $dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).$typeadd."_avatar_$size.jpg";
}
function face_get($users=array(),$type='small') 
{
    if(is_numeric($users))
    {
        $users = DB::fetch_first("select * from ".TABLE_PREFIX."members where `uid`='$users'");
    }
    
    if(is_array($users))
    {
        $uid = $users['uid'];
        $ucuid = $users['ucuid'];
        $face_url = $users['face_url'];
        $face = $users['face'];
            
        unset($users);
    }
    
    
    $sys_config = ConfigHandler::get();
    
    
    	$file = $sys_config['site_url'] . '/images/no.gif';
    if($uid < 1)
    {
        return $file;
    }
    
    
        if(true === UCENTER_FACE)
	{
        if(null === $ucuid)
        {
            $ucuid = DB::result_first("select `ucuid` from ".TABLE_PREFIX."members where `uid`='$uid'");
        }		      
	  
		if($ucuid > 0)
        {             
			if('small'!=$type) 
            {
                $type = 'middle';
            }
            
                        $mods = array('share'=>1, 'show'=>1, );
            if(!isset($mods[$_GET['mod']]) && (TRUE===IN_JISHIGOU_INDEX || TRUE===IN_JISHIGOU_AJAX))
            {
            	$file = UC_API . '/data/avatar/' . jsg_uc_face_path($ucuid, $type, 'virtual');
	            if($uid == MEMBER_ID) $file .= "?".date("YmdHi");
            }
            else 
            {
            	$file = UC_API . "/avatar.php?uid={$ucuid}&type=virtual&size={$type}";
				if($uid == MEMBER_ID) $file .= "&".date("YmdHi");
            }
            
            return $file;
        }
	}
    
    
    	$type = ('small' == $type ? 's' : 'b');
	$file = 'images/face/' . face_path($uid) . $uid . "_{$type}.jpg";
    
    
        if($sys_config['ftp_on'] && $face)
    {
        if(null === $face_url)
        {
            $face_url = DB::result_first("select `face_url` from ".TABLE_PREFIX."members where `uid`='$uid'");
        }
    }
    else
    {
        if(!is_file(RELATIVE_ROOT_PATH . $file))
        {
            $file = 'images/no.gif';
        }
    }

    
        if($uid==MEMBER_ID && $file != 'images/no.gif')
    {
        $file .= "?".date('YmdHi');
    }
        

    if(!$face_url)
    {
        $face_url = $sys_config['site_url'];
    }
        
    
    $file = ($face_url . "/" . $file);
    

	return $file;
}

/*
function topic_image($id,$type='small',$relative=true) 
{
	$type = ('small' == $type ? 's' : 'o');
	$file = 'images/topic/' . face_path($id) . $id . "_{$type}.jpg";
    if($relative)
    {
        $file = RELATIVE_ROOT_PATH . $file;
    }
    else
    {
        $sys_config = ConfigHandler::get();        
        
                if($sys_config['ftp_on'])
        {
            if(!($site_url = $GLOBALS['ftp_site_urls'][$id]))
            {
                $site_url = DB::result_first("select `site_url` from " . TABLE_PREFIX . "topic_image where `id`='$id'");
                
                $GLOBALS['ftp_site_urls'][$id] = $site_url;
            }
        }
        if(!$site_url)
        {
            $site_url = $sys_config['site_url'];
        }
        
        $file = $site_url . '/' . $file;
    }

	return $file;
}
*/

function topic_image($id,$type='small',$relative=true)
{
	$type = ('photo' == $type ? 'p' : ('small' == $type ? 's' : 'o'));
	//$file = 'images/topic/' . face_path($id) . $id . "_{$type}.jpg";
	$photo_url = DB::result_first("select `photo` from " . TABLE_PREFIX . "topic_image where `id`='$id'");
	$file="".str_replace("_o","_{$type}",$photo_url);
	
	if($relative)
	{
		$file = RELATIVE_ROOT_PATH . $file;
	}
	else
	{
		static $sys_config=null;
		if(is_null($sys_config)) {
			$sys_config = ConfigHandler::get();
		}

				if($sys_config['ftp_on'])
				{
					if(!($site_url = $GLOBALS['ftp_site_urls'][$id]))
					{
						$site_url = DB::result_first("select `site_url` from " . TABLE_PREFIX . "topic_image where `id`='$id'");
						$GLOBALS['ftp_site_urls'][$id] = $site_url;
					}
				}
		if(!$site_url)
		{
			$site_url = $sys_config['site_url'];
		}

		//jack edit 0308
		$file = $site_url . '/' . $file;
		//$file = '/weibo1/' . $file;
	}
	
	//jack add  0308
	//$file="/weibo/".$file;

	return $file;
}




function medal_image($id,$type='small') 
{
	$type = ('small' == $type ? 'o' : 's');
	
	$file = RELATIVE_ROOT_PATH . 'images/medal/' . face_path($id) . $id . "_{$type}.jpg";

	return $file;
}


function get_safe_code($value) { return getSafeCode($value); }
function getSafeCode($value) 
{
	if(preg_match('~^[\x01-\x7f]+$~',$value)) return $value;
	
	$is_utf8 = 0;
	if(preg_match('~^([\x01-\x7f]|[\xc0-\xdf][\xa0-\xbf])+$~',$value))
	{
		;
	}
	else
	{
		if(preg_match('~^([\x01-\x7f]|[\xc0-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3}|[\xf8-\xfb][\x80-\xbf]{4}|[\xfc-\xfd][\x80-\xbf]{5})+$~',$value))
		{
			$is_utf8 = 1;
		}
	}


	$sys_config = ConfigHandler::get();    

	if('utf-8'==strtolower($sys_config['charset']))
	{
		return ($is_utf8 ? $value : array_iconv("gbk", "utf-8", $value));
		
		
	}
	else
	{
		return ($is_utf8 ? array_iconv("utf-8", "gbk", $value) : $value);

		
	}
}
function follow_html($uid,$follow=0,$addhtml=true) {

	$html = "";
	if(MEMBER_ID>0 && MEMBER_ID!=$uid) {
		if ($follow) {
					$html = "<a href='javascript:void(0)' onclick=\"follow({$uid},'follow_{$uid}','');return false;\"><img src='./templates/default/images/accept_2.gif' /></a>";

		} else {
					$html = "<a href='javascript:void(0)' onclick=\"follow({$uid},'follow_{$uid}','add');return false;\"><img src='./templates/default/images/add_2.gif' /></a>";
		}

		if($addhtml) $html = "<span id='follow_{$uid}'>{$html}</span>";
	}

	return $html;
}

function follow_html2($uid,$follow=0,$addhtml=true) {

	$html = "";
	if(MEMBER_ID>0 && MEMBER_ID!=$uid) {
		if ($follow) {
					$html = "<a href='javascript:void(0)' class=\"follow_html2_1\" onclick=\"follow({$uid},'follow_{$uid}','','xiao');return false;\">已关注</a>";

		} else {
					$html = "<a href='javascript:void(0)' class=\"follow_html2_1\" onclick=\"follow({$uid},'follow_{$uid}','add','xiao');return false;\">+关注</a>";
		}

		if($addhtml) $html = "<span id='follow_{$uid}'>{$html}</span>";
	}

	return $html;
}



function user_exp($user_level=0,$user_credits=0) 
{
				$experience = ConfigHandler::get('experience');
		$exp_list = $experience['list'];
		
        		$my_exp = $user_level;
		
				$my_credits = $user_credits;
		
				$next_exp = $my_exp + 1;
		
				$next_exp_credits = $exp_list[$next_exp]['start_credits'];
			
				$percent = round($my_credits/$next_exp_credits, 2);
		
				$exp_width = round($percent * 100);
		
				$liter_exp  = $next_exp_credits - $my_credits;
		
		$exp_arr = array(
								'exp_width' => $exp_width,
		
								'nex_exp_credit' => $liter_exp,
				
				'nex_exp_level' => $next_exp ,
		
		);
		
		
	
	return $exp_arr;
}

function weather_html($city,$province='',$tpl='weather.inc.1') {
	return ;

	include(ROOT_PATH . 'include/./function/weather.func.php');
	return __weather_html($city,$province,$tpl);
}

function my_date_format2($time,$format='m月d日 H时i分')
{
	$now = time();

	$t = $now - $time;

    if($t >= 3600)
    {
        $time = my_date_format($time,$format);
    }
    
    elseif ($t < 3600 && $t >= 60)
    {
		$time = floor($t / 60) . "分钟前";
	}
	else
    {
		$time = "刚刚";
	}

	return $time;
}

function url_key($id,$op="ENCODE") {
	$index = 'z6OmlGsC9xqLPpN7iw8UDAb4HIBXfgEjJnrKZSeuV2Rt3yFcMWhakQT1oY5v0d';
	$base = 62;

	$out = "";
	if('ENCODE' == $op) {
	   for ( $t = floor( log10( $id ) / log10( $base ) ); $t >= 0; $t-- ) {
	       $a = floor( $id / pow( $base, $t ) );
	       $out = $out . substr( $index, $a, 1 );
	       $id = $id - ( $a * pow( $base, $t ) );
	   }
	} elseif ('DECODE' == $op) {
		;
	}

   return $out;
}

function get_url_key($url,$title='',$description='') 
{
	$DatabaseHandler = Obj::registry('DatabaseHandler');
	$url_hash = md5($url);
	$sql = "select * from `".TABLE_PREFIX."url` where `url_hash`='{$url_hash}'";
	$query = $DatabaseHandler->Query($sql);
	$url_info = $query->GetRow();

	if (!$url_info)
    {
		$url_info = array();
		$url_info['url'] = addslashes($url);
        if($title) $url_info['title'] = addslashes($title);
        if($description) $url_info['description'] = addslashes($description);
		$url_info['url_hash'] = $url_hash;
		$url_info['dateline'] = time();
		$sql = "insert into `".TABLE_PREFIX."url` (`".implode("`,`",array_keys($url_info))."`) values ('".implode("','",$url_info)."')";
		$query = $DatabaseHandler->Query($sql,'SKIP_ERROR');
		if(false===$query)
		{
			$DatabaseHandler->Query("delete from `".TABLE_PREFIX."url` where `key`=''");

			$DatabaseHandler->Query($sql);
		}
		$url_info['id'] = $DatabaseHandler->Insert_ID();
	}

    if ($url_info['id'] < 1)
    {
		return false;
	}

    $url_key = url_key($url_info['id'],'ENCODE');
    if($url_key && $url_key != $url_info['key'])
    {
        $url_info['key'] = $url_key;
		$sql = "update `".TABLE_PREFIX."url` set `key`='{$url_info['key']}' where `id`='{$url_info['id']}'";
		$query = $DatabaseHandler->Query($sql,'SKIP_ERROR');
        if(false===$query && 1062==$DatabaseHandler->GetLastErrorNo())
        {
            $sys_config = ConfigHandler::get();
            $db_charset = str_replace(array('-',),'',$sys_config['charset']);
            
            $DatabaseHandler->Query("ALTER TABLE {$sys_config['db_table_prefix']}url CHANGE `key` `key` VARCHAR(10) CHARACTER SET $db_charset COLLATE {$db_charset}_bin DEFAULT '' NOT NULL");

			$DatabaseHandler->Query($sql);
        }
    }

	if(!$url_info['key'])
	{
    	$DatabaseHandler->Query("delete from `".TABLE_PREFIX."url` where `key`=''");
	}

	return $url_info;
}

function update_my_fans_follow_count($uid=0)
{
    $uid = max(0, (int) $uid);
    if($uid < 1)
    {
        return false;
    }

    $DatabaseHandler = Obj::registry('DatabaseHandler');
    $member = $DatabaseHandler->FetchFirst("select * from `".TABLE_PREFIX."members` where `uid`='{$uid}'");
    if(!$member)
    {
        return false;
    }
    $member['follow_count'] = max(0, (int) $member['follow_count']);
    $member['fans_count'] = max(0, (int) $member['fans_count']);

	$row = $DatabaseHandler->FetchFirst("select count(*) as follow_count from `".TABLE_PREFIX."buddys` where `uid`='{$uid}'");
    $row['follow_count'] = max(0, (int) $row['follow_count']);
	if($row['follow_count']!=$member['follow_count'])
	{
		$DatabaseHandler->Query("update `".TABLE_PREFIX."members` set `follow_count`='{$row['follow_count']}' where `uid`='{$uid}'");
	}

	$row = $DatabaseHandler->FetchFirst("select count(*) as fans_count from `".TABLE_PREFIX."buddys` where `buddyid`='{$uid}'");
    $row['fans_count'] = max(0, (int) $row['fans_count']);
	if($row['fans_count']!=$member['fans_count'])
	{
        $fans_new_update = '';
        $fans_new = 0;
        if($row['fans_count'] > $member['fans_count'])
        {
            $fans_new = max(0, (int) (($row['fans_count']-$member['fans_count'])));

            if($fans_new > 0)
            {
                $fans_new_update = " , `fans_new` = `fans_new` + '{$fans_new}'";
            }
        }

		$DatabaseHandler->Query("update `".TABLE_PREFIX."members` set `fans_count`='{$row['fans_count']}' {$fans_new_update} where `uid`='{$uid}'");
	}

    return true;
}

function buddy_add($buddyid,$uid=0)
{
	$SystemConfig = ConfigHandler::get();

	$timestamp = time();
	$buddyid = (int) $buddyid;
	$uid = (int) ($uid>0 ? $uid : MEMBER_ID);
	if($uid<1 || $buddyid<1 || $uid==$buddyid) return false;

	$DatabaseHandler = Obj::registry('DatabaseHandler');
	$sql = "select `uid`,`username` from `".TABLE_PREFIX."members` where `uid` in ('{$uid}','{$buddyid}')";
	$query = $DatabaseHandler->Query($sql);
	$num_rows = $query->GetNumRows();
	if($num_rows<2) return false;

	while ($row = $query->GetRow())
	{
		$members[$row['uid']] = $row;
	}

	$sql = "select * from `".TABLE_PREFIX."buddys` where `uid`='{$uid}' and `buddyid`='{$buddyid}'";
	$query = $DatabaseHandler->Query($sql);
	$row = $query->GetRow();
	if (!$row)
    {
		$sql = "insert into `".TABLE_PREFIX."buddys` (`uid`,`buddyid`,`dateline`,`buddy_lastuptime`) values ('{$uid}','{$buddyid}','{$timestamp}','{$timestamp}')";
		$DatabaseHandler->Query($sql);

		update_my_fans_follow_count($uid);
        update_my_fans_follow_count($buddyid);

		if($SystemConfig['extcredits_enable'] && $uid>0)
		{
			
			$update_credits = false;
			if($members[$buddyid]['username'])
			{
				$update_credits = update_credits_by_action(("_U".crc32($members[$buddyid]['username'])),$uid);
			}

			if(!$update_credits)
			{
				
				update_credits_by_action('buddy',$uid);
			}
		}

		if($SystemConfig['imjiqiren_enable'] && imjiqiren_init($SystemConfig))
		{
			imjiqiren_send_message($members[$buddyid],'f');
		}

		if($SystemConfig['sms_enable'] && sms_init($SystemConfig))
		{
			sms_send_message($members[$buddyid],'f');
		}
	}
}


function check_BlackList($touid=0,$uid=0)
{
    $uid = ($uid ? $uid : MEMBER_ID);
    
	$DatabaseHandler = Obj::registry('DatabaseHandler');
	$sql = "select * from `".TABLE_PREFIX."blacklist` where `uid` ='$uid' and `touid` = '{$touid}'";
	$query = $DatabaseHandler->Query($sql);
	$blackList = $query->GetRow();

	$return = ($blackList ? '0' : '1');

	return $return;
}

function get_host($str){
	$list=array(
	"sina.com.cn",
	"youku.com",
	"tudou.com",
	"ku6.com",
	"sohu.com",
	"mofile.com",
	);
	foreach($list as $v){

		if( strpos($str,$v)>0){
			$re= substr($str,strpos($str,$v),100);
			break;
		}
	}
	return $re;
}

if(!function_exists('swritefile'))
{
    function swritefile($filename, $writetext, $openmod='w') {
    
    	if($fp = @fopen($filename, $openmod)) {
    		flock($fp, 2);
    		fwrite($fp, $writetext);
    		fclose($fp);
    		return true;
    	} else {
    		return false;
    	}    
    }
}    


function is_image($filename,$allow_types=array('gif'=>1,'jpg'=>1,'png'=>1,'bmp'=>1,'jpeg'=>1)) {
	if(!is_file($filename)) {
		return false;
	}

	$imagetypes = array('1'=>'gif','2'=>'jpg','3'=>'png','4'=>'swf','5'=>'psd','6'=>'bmp','7'=>'tiff','8'=>'tiff','9'=>'jpc','10'=>'jp2','11'=>'jpx','12'=>'jb2','13'=>'swc','14'=>'iff','15'=>'wbmp','16'=>'xbm',);
	if(!$allow_types) {
		$allow_types = array('gif'=>1,'jpg'=>1,'png'=>1,'bmp'=>1,'jpeg'=>1);
	}
	$typeid = 0;
	$imagetype = '';
	if(function_exists('exif_imagetype')) {
		$typeid = exif_imagetype($filename);
	} elseif (function_exists('getimagesize')) {
		$_tmps = getimagesize($filename);
		$typeid = (int) $_tmps[2];
	} else {
		if(($fh = @fopen($filename, "rb"))) {
			$strInfo = unpack("C2chars", fread($fh,2));
			fclose($fh);
			$fileTypes = array(7790=>'exe',7784=>'midi',8297=>'rar',255216=>'jpg',7173=>'gif',6677=>'bmp',13780=>'png',);
			$imagetype = $fileTypes[intval($strInfo['chars1'] . $strInfo['chars2'])];
		}
	}
	$file_ext = strtolower(trim(substr(strrchr($filename, '.'), 1)));
	if($typeid > 0) {
		$imagetype = $imagetypes[$typeid];
	} else {
		if(!$imagetype) {
			$imagetype = $file_ext;
		}
	}

	if($allow_types && $file_ext && $imagetype && isset($allow_types[$file_ext]) && isset($allow_types[$imagetype])) {
		return true;
	}

	return false;
}

function get_full_url($site_url='',$url='',$rewrite_url_postfix='')
{
    global $rewriteHandler;

    if(!$site_url)
    {
        $sys_config = ConfigHandler::get();

        $site_url = $sys_config['site_url'];
    }
    else
    {
        if('/'==substr($site_url,-1))
        {
            $site_url = rtrim($site_url,'/');
        }
    }


    $full_url = "{$site_url}/{$url}";

	if($rewriteHandler && $url)
    {
		$url = ltrim($rewriteHandler->formatURL($url),'/');

		$full_url = (((false!==($_tmp_pos = strpos($site_url,'/',10))) ? substr($site_url,0,$_tmp_pos) : $site_url) . '/' . $url) . $rewrite_url_postfix;
	}

	return $full_url;
}

function get_invite_url($url='',$site_url='')
{
    return get_full_url($site_url,$url,'/');
}

function grayJpeg($imgname)
{ 
    $im = @imagecreatefromjpeg($imgname);
   
    if(!$im)
    {
        $im  = imagecreatetruecolor(150, 30);
        $bgc = imagecolorallocate($im, 255, 255, 255);
        $tc  = imagecolorallocate($im, 0, 0, 0);
 
        imagefilledrectangle($im, 0, 0, 150, 30, $bgc);
        imagestring($im, 10, 5, 5, 'Error loading ' . $imgname, $tc);
    }
		else{
			$img_width = ImageSX($im);
			$img_height = ImageSY($im);
			for ($y = 0; $y <$img_height; $y++) { 
			  for ($x = 0; $x <$img_width; $x++) {
					$gray = (ImageColorAt($im, $x, $y) >> 8) & 0xFF;
					imagesetpixel ($im, $x, $y, ImageColorAllocate ($im, $gray,$gray,$gray));
			  }
			}
		}
    return $im;
}





class Load
{
	function functions($name)
	{
		return @include_once(ROOT_PATH . 'include/function/' .$name.'.func.php');
	}
	function logic($name)
	{
		return @include_once(ROOT_PATH . 'include/logic/' .$name.'.logic.php');
	}
	function lib($name)
	{
		return @include_once(ROOT_PATH . 'include/lib/' .$name.'.han.php');
	}
}




class Obj
{
	function &Obj($name=null)
	{
		Return Obj::_share($name,$null,'get');
	}

	function &_share($name=null,&$mixed,$type='set')
	{
		static $_register=array();

		if($name==null)
		{
			Return $_register;
		}

		if('get' == $type)
		{
			if(isset($_register[$name]))
			{
				Return $_register[$name];
			}

			return null;
		}

		if('set' == $type)
		{
			$_register[$name]=&$mixed;
		}

		return true;
	}
	
	function register($name,&$obj)
	{
		Obj::_share($name,$obj,"set");
	}
	
	function &registry($name=null)
	{
		Return Obj::_share($name,$null,'get');
	}
	
	function isRegistered($name)
	{
		Return isset($_register[$name]);
	}
}

/**
 *[JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 配置的读取及写入
 *
 * @author 狐狸<foxis@qq.com>
 * @package www.jishigou.net
 */

class ConfigHandler
{
	function ConfigHandler()
	{
		;
	}
	function file($type=null)
	{
		if($type) $type = str_replace(array('.','\\','/',),'',$type);

		return ROOT_PATH . 'setting/' .($type===null?'settings':$type).'.php';
	}

	
	function get()
	{
		$func_num_args=func_num_args();
		if($func_num_args===0)
		{
            if(!$GLOBALS['SystemConfigCache'])
            {
                if(Obj::isRegistered('config'))
                {
                    $GLOBALS['SystemConfigCache'] = Obj::registry('config');
                }
                else
                {
                    include(ConfigHandler::file());

                    $GLOBALS['SystemConfigCache'] = $config;
                }
            }

			Return $GLOBALS['SystemConfigCache'];
		}
		else
		{
			$func_args=func_get_args();
			$type=$func_args[0];
			if(isset($GLOBALS['ConfigHandlerCaches'][$type])===false)
			{
				if(!@include(ConfigHandler::file($type)))
				{
					return null;
				}

				if(isset($config[$type]))
				{
					$GLOBALS['ConfigHandlerCaches'][$type]=$config[$type];
				}
				else
				{
					$config=isset(${$type})?${$type}:
					$GLOBALS['ConfigHandlerCaches'][$type]=${$type};
				}
			}

			if($func_num_args===1)
			{
				Return $GLOBALS['ConfigHandlerCaches'][$type];
			}

			foreach($func_args as $arg)
			{
				$path_str.="['$arg']";
			}
			$config=eval('return $GLOBALS["ConfigHandlerCaches"]'.$path_str.";");
			Return $config;
		}
	}

	
	function set()
	{
		$func_num_args=func_num_args();
		$func_args=func_get_args();
		$value=array_pop($func_args);
		$type=array_shift($func_args);

        ConfigHandler::backup($type);

		$file=ConfigHandler::file($type);
		if($type===null)
		{
			$data="<?php \r\n  \r\n \$config=".var_export($value,true)."; \r\n ?>";
		}
		else
		{
			if(($config=$GLOBALS['ConfigHandlerCaches'][$type])===null)
			{
				$config=array();
				@include($file);
				$config=$config[$type];
			}
			foreach($func_args as $arg)
			{
				$path_str.="['$arg']";
			}
			eval($value===null?'unset($config'.$path_str.');':'$config'.$path_str.'=$value;');
			$data="<?php \r\n  \r\n\$config['$type']=".var_export($config,true).";\r\n?>";
		}       
       
		$fp = @fopen($file,'wb');
		if(!$fp)die($file."文件无法写入,请检查是否有可写权限。");
		$len=fwrite($fp, $data);
		fclose($fp);

		if($len)
		{
			if($type)
			{
				$GLOBALS['ConfigHandlerCaches'][$type]=$config;
			}
			elseif($value)
			{
				$GLOBALS['SystemConfigCache'] = $value;
			}
		}

		return $len;
	}

    //修改配置前备份一个
    function backup($type=null)
    {
        $dir = ROOT_PATH . 'backup/setting/';
        
        if(!is_dir($dir))
        {
            jmkdir($dir);
        }
        
        $config = array();
        
        if(null===$type)
        {
            $config = ConfigHandler::get();
        }
        else
        {
            $config = ConfigHandler::get($type);
        }
        
        if($config)
        {
            return file_put_contents(($dir . (null===$type ? 'settings' : $type) . '.php'),'<?php $config'.(null===$type ? '' : "['$type']").' = '.var_export($config,true).'; ?>');
        }
    }
}








function update_credits_by_action($action,$uid=0,$coef=1)
{
	static $CreditsLogic = null;
	if (!$CreditsLogic)
	{
		Load::logic('credits');
		$CreditsLogic = new CreditsLogic();
	}

	return $CreditsLogic->ExecuteRule($action,$uid,$coef);
}


function sina_weibo_init($sys_config=array())
{
	return init_item_func($sys_config, 'sina');
}


function qqwb_init($sys_config=array())
{
	return init_item_func($sys_config, 'qqwb');
}


function yy_init($sys_config=array())
{
	return init_item_func($sys_config, 'yy');
}


function renren_init($sys_config=array())
{
	return init_item_func($sys_config, 'renren');
}


function kaixin_init($sys_config=array())
{
	return init_item_func($sys_config, 'kaixin');
}


function imjiqiren_init($sys_config=array())
{
	return init_item_func($sys_config, 'imjiqiren');
}


function sms_init($sys_config=array())
{
    return init_item_func($sys_config, 'sms');
}


function init_item_func($sys_config = array(), $item)
{
	$func = "{$item}_enable";
	if(!function_exists($func))
	{
		Load::functions($item);
		
		clearstatcache();
		
		if(function_exists($func))
		{
			return $func($sys_config);
		}
	}
	else 
	{
		return $func($sys_config);
	}
	
	return false;
}


function js_alert_output($alert_msg,$halt=true)
{
    	echo "<script language='javascript'>MessageBox('notice', '{$alert_msg}');</script>";
    
    if($halt) exit;
}


function js_alert_showmsg($alert_msg)
{
    echo "<script language='Javascript'>";
	echo "show_message('{$alert_msg}');";
	echo "</script>";
	exit;
}



function jsg_setcookie($var, $value, $life = 0, $prefix = 1)
{
    $sys_config = ConfigHandler::get();

    $expire = 0;

    if($life)
    {
        $expire = time() + $life;
    }

	@setcookie(($prefix ? $sys_config['cookie_prefix'] : '').$var, $value,
		$expire, ($sys_config['cookie_path'] ? $sys_config['cookie_path'] : '/'),
		($sys_config['cookie_domain'] ? $sys_config['cookie_domain'] : ''), ($_SERVER['SERVER_PORT'] == 443 ? 1 : 0));
}
function jsg_getcookie($var, $prefix = 1)
{
    if($prefix)
    {
        $sys_config = ConfigHandler::get();
        
        $var = $sys_config['cookie_prefix'] . $var;
    }
    
    if(isset($_COOKIE[$var]))
    {
        return $_COOKIE[$var];
    }
    else
    {
        return false;
    }
}

function jsg_is_qq($n)
{
	$ret = 0;
	
	if(is_numeric($n) && $n > 10000)
	{
		$nl = strlen((string) $n);
		if($nl < 11)
		{
			$ret = 1;
			
			if(10 == $nl && (((int) substr((string) $n, 0, 1)) > 2))
			{
				$ret = 0;
			}
		}
	}
	
	return $ret;
}

function jsg_is_mobile($num)
{
    $return = false;
    
    if($num && is_numeric($num))
    {
        settype($num,'string');
        
        $num_len = strlen($num);
        
        if(11==$num_len || 12==$num_len)
        {
            $return = preg_match('~^((?:13|15|18)\d{9}|0(?:10|2\d|[3-9]\d{2})[1-9]\d{6,7})$~',$num);
        }
    }        
    
    return $return;
}

function jsg_is_email($email)
{
	$return = false;
	
	if($email && false !== strpos($email,'@'))
	{
		return preg_match('~^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+([a-z]{2,4})|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$~i', $email);
	}
	
	return $return;
}

function jsg_schedule($vars=array(),$type='')
{
    if(!function_exists('schedule_add'))
    {
        Load::functions('schedule');
    }
    
    if($vars)
    {
        return schedule_add($vars,$type);
    }
    else
    {
        return schedule_html();
    }
}

function ftpcmd($cmd, $arg1 = '', $arg2 = '') {
	static $ftp = null;
	$ftpon = ConfigHandler::get('ftp','on');
	if(!$ftpon) {
		return $cmd == 'error' ? -101 : 0;
	} elseif($ftp === null) {
	    Load::lib('ftp');
		$ftp = FtpHandler::instance();
	}
	if(!$ftp->enabled) {
		if('error' != $cmd)
        {
            return 0;
        }
	} elseif($ftp->enabled && !$ftp->connectid) {
		$ftp->connect();
	}
	switch ($cmd) {
		case 'upload' : return $ftp->upload(ROOT_PATH . $arg1, $arg2 ? $arg2 : $arg1); break;
		case 'delete' : return $ftp->ftp_delete($arg1); break;
		case 'close'  : return $ftp->ftp_close(); break;
		case 'error'  : return $ftp->error(); break;
		case 'object' : return $ftp; break;
		default       : return false;
	}    
}



define("QUERY_SAFE", true);

class DB
{

	
	function table($table)
	{
		$table_name = TABLE_PREFIX.$table;
		return $table_name;
	}

	
	function delete($table, $condition, $limit = 0, $unbuffered = true)
	{
		if(empty($condition)) {
			$where = '1';
		} elseif(is_array($condition)) {
			$where = DB::implode_field_value($condition, ' AND ');
		} else {
			$where = $condition;
		}
		$sql = "DELETE FROM ".DB::table($table)." WHERE $where ".($limit ? "LIMIT $limit" : '');
		return DB::query($sql, ($unbuffered ? 'UNBUFFERED' : ''));
	}

	
	function insert($table, $data, $return_insert_id = false, $replace = false, $silent = false)
	{

		$sql = DB::implode_field_value($data);

		$cmd = $replace ? 'REPLACE INTO' : 'INSERT INTO';

		$table = DB::table($table);
		$silent = $silent ? 'SILENT' : '';

		$return = DB::query("$cmd $table SET $sql", $silent);

		return $return_insert_id ? DB::insert_id() : $return;

	}

	
	function update($table, $data, $condition, $unbuffered = false, $low_priority = false)
	{
		$sql = DB::implode_field_value($data);
		$cmd = "UPDATE ".($low_priority ? 'LOW_PRIORITY' : '');
		$table = DB::table($table);
		$where = '';
		if(empty($condition)) {
			$where = '1';
		} elseif(is_array($condition)) {
			$where = DB::implode_field_value($condition, ' AND ');
		} else {
			$where = $condition;
		}
		$res = DB::query("$cmd $table SET $sql WHERE $where", $unbuffered ? 'UNBUFFERED' : '');
		return $res;
	}

	
	function implode_field_value($array, $glue = ',')
	{
		$sql = $comma = '';
		foreach ($array as $k => $v) {
			$sql .= $comma."`$k`='$v'";
			$comma = $glue;
		}
		return $sql;
	}

		function insert_id()
	{
				return DB::_execute('Insert_ID');
	}

	
	function fetch($resourceid, $type = 'assoc')
	{
		return DB::_execute('GetRow', $resourceid, $type);
	}

	
	function fetch_first($sql)
	{
		DB::checkquery($sql);
		return DB::_execute('fetch_first', $sql);
	}

	function result($resourceid, $row = 0)
	{
		return DB::_execute('result', $resourceid, $row);
	}

	function result_first($sql)
	{
		DB::checkquery($sql);
		$query = DB::query($sql);
		return DB::result($query);
	}

	function query($sql, $type = '')
	{
		DB::checkquery($sql);
		return DB::_execute('Query', $sql, $type);
	}

	function num_rows($resourceid)
	{
		return DB::_execute('GetNumRows', $resourceid);
	}

	function affected_rows()
	{
		return DB::_execute('AffectedRows');
	}

	function free_result($query)
	{
		return DB::_execute('_freeResult', $query);
	}
	
	function error()
	{
		return DB::_execute('GetLastErrorString');
	}

	function errno() {
		return DB::_execute('GetLastErrorNo');
	}

	function _execute($cmd , $arg1 = '', $arg2 = '') {
		static $db;
		if(empty($db)) $db = & DB::object();
		if ($cmd == 'GetRow') {
			$res = $arg1->GetRow($arg2);
		} else if ($cmd == 'result') {
			$res = $arg1->result($arg2);
		} else if ($cmd == 'GetNumRows') {
			$res = $arg1->GetNumRows();
		} else if ($cmd == '_freeResult') {
			$res = $arg1->_freeResult();
		} else {
			$res = $db->$cmd($arg1, $arg2);
		}
		return $res;
	}

	function &object() {
		static $db=null;
		if(empty($db)) {
			$db = Obj::registry('DatabaseHandler');
			if (empty($db)) {
				//exit("Database init fail!");
				//数据库资源初始化
				$sys_config = ConfigHandler::get();
				include_once ROOT_PATH . 'include/db/database.db.php';
				include_once ROOT_PATH . 'include/db/mysql.db.php';
				$db = new MySqlHandler($sys_config['db_host'],$sys_config['db_port']);
				$db->Charset($sys_config['charset']);
				$db->DoConnect($sys_config['db_user'],$sys_config['db_pass'],$sys_config['db_name'],$sys_config['db_persist']);
				Obj::register('DatabaseHandler',$db);
			}
		}
		return $db;
	}

	
	function checkquery($sql) {
		return DB::_execute('CheckQuery');
	}
}


function template($tpl_name)
{
	static $tpl;
	if(empty($tpl)) {
		$tpl = Obj::registry('TemplateHandler');
		if (empty($tpl)) {
			exit("Template init fail!");
		}
	}
	$path = $tpl->Template($tpl_name);
	return $path;
}


function get_list($table_name, $parma)
{
	$where_sql = ' 1 ';
	$order_sql = ' ';
	$limit_sql = ' ';
	
	if (empty($parma['field'])) {
		$field = ' * ';	
	} else {
		$field = $parma['field'];
	}
	
	if (!empty($parma['where'])) {
		$where_sql .= " AND {$parma['where']} ";
	}
	
	if (!empty($parma['order'])) {
		$order_sql = " ORDER BY {$parma['order']} ";
	}
	
	if (!empty($parma['limit'])) {
		$limit_sql = " LIMIT {$parma['limit']} ";
	}
	
	$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table($table_name)." WHERE {$where_sql}");
	$keyword_ary = array();
	if ($count) {
				if ($parma['page']) {
			$_config = array(
				'return' => 'array',
			);
			$page_ary = page($count, $parma['perpage'], $parma['page_url'], $_config);
			$limit_sql = $page_ary['limit'];
		}
		$query = DB::query("SELECT {$field} FROM ".DB::table($table_name)." WHERE {$where_sql} {$order_sql} {$limit_sql}");
		while ($value = DB::fetch($query)) {
			$keyword_ary[] = $value;
		}
		$r = array('list' => $keyword_ary, 'count' => $count);
		
		if ($parma['page']) {
			$r['page'] = $page_ary['html'];	
		}
		return $r;
	}
	return false;
}




function unfilterHtmlChars($str)
{
	return str_replace(array('&lt;', '&gt;'), array('<', '>'), $str);
}


function getstr($string, $length, $in_slashes=0, $out_slashes=0,  $html=0) 
{
	$string = trim($string);
	if($in_slashes) {
		$string = jstripslashes($string);
	}
	if($html < 0) {
		$string = preg_replace("/(\<[^\<]*\>|\r|\n|\s|\[.+?\])/is", ' ', $string);
	} elseif ($html == 0) {
		$string = htmlspecialchars($string);
	}

	if($length) {
		$string = cut_str($string, $length);
	}
	filter($string);
	if($out_slashes) {
		$string = jaddslashes($string);
	}
	return trim($string);
}

function jstrtotime($string)
{
	$time = '';
	if($string) {
		$time = strtotime($string);
		$sys_config = ConfigHandler::get();
		$timezone = $sys_config['timezone'];
		if(gmdate('H:i', TIMESTAMP + $timezone * 3600) != date('H:i', TIMESTAMP)) {
			$time = $time - $timezone * 3600;
		}
	}
	return $time;
}

function url_implode($gets)
{
	$arr = array();
	foreach ($gets as $key => $value) {
		if($value) {
			$arr[] = $key.'='.urlencode(jstripslashes($value));
		}
	}
	return implode('&', $arr);
}

function jimplode($array)
{
	if(!empty($array)) {
		return "'".implode("','", is_array($array) ? $array : array($array))."'";
	} else {
		return 0;
	}
}



function chk_follow($a, $b)
{
	$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('buddys')." WHERE uid='{$a}' AND buddyid='{$b}'");
	return $count;
}


function mk_time_select($type = 'hour', $def_val = false,$name='')
{
	$html = '';
	$time = 0;
	if (defined(TIMESTAMP)) {
		$time = TIMESTAMP;
	} else {
		$time = time();
	}
	
	if ($type == 'hour') {
		$range = 24;
		if ($def_val === false) {
			$def_val = my_date_format($time, 'H');
		}
	} else if ($type == 'min') {
		$range = 60;
		if ($def_val === false) {
			$def_val = my_date_format($time, 'i');
		}
	} else {
		return '';
	}
	
	$name = $name ? $name : $type;
	$html = "<select name=\"{$name}\" id=\"{$name}\" defaultvalue=\"{$def_val}\">";
	for ($i=0;$i<$range;++$i) {
		$selected = '';
		$value = $i;
		if (strlen($value) < 2) {
			$value = '0'.$value;
		}
		if ($value == $def_val) {
			$selected = 'selected="selected"';	
		}
		$html .= " <option value=\"{$value}\" {$selected} >{$value}</option>";
	}
	$html .= '</select>';
	return $html;
}


function get_buddyids($uid)
{
	$buddyids = array();
	$query = DB::query("SELECT `buddyid`  
						FROM ".DB::table("buddys")." 
						WHERE `uid`='{$uid}'");
	while ($value = DB::fetch($query)) {
		$buddyids[] = $value['buddyid'];
	}
	return $buddyids;
}


function table_exists($table_name)
{
	$row = DB::fetch_first("SHOW TABLES LIKE '".DB::table($table_name)."'");
	if (empty($row)) {
		return false;
	}
	return true;
}



function jsg_json_encode($value)
{
    if(!class_exists('servicesJSON')) {
    	Load::lib('servicesJSON');
    }
	$json = new servicesJSON(0, false);
	return $json->encode($value);
}

function jsg_json_decode($value)
{
	if(!class_exists('servicesJSON')) {
    	Load::lib('servicesJSON');
    }
	$json = new servicesJSON(0, false);
	return $json->decode($value);
}


function json_error ($msg = '', $retval = null, $jqremote = false)
{
	$result = array("done" => false , "msg" => $msg);
	if (isset($retval)) $result["retval"] = $retval;

	json_header();
	$json = jsg_json_encode($result);
	if ($jqremote === false) {
		$jqremote = isset($_GET['jsoncallback']) ? trim($_GET['jsoncallback']) : false;
	}
	if ($jqremote) {
		$json = $jqremote . '(' . $json . ')';
	}
	echo $json;
	exit;
}

function js_show_login($msg='')
{
	echo "<script language='Javascript'>";
	echo "show_message('{$msg}',1);";
	echo "ShowLoginDialog();";
	echo "</script>";
	exit;
}

function json_result ($msg = '', $retval = '', $jqremote = false)
{
	json_header();
	$json = jsg_json_encode(array("done" => true , "msg" => $msg , "retval" => $retval));
	if ($jqremote === false) {
		$jqremote = isset($_GET['jsoncallback']) ? trim($_GET['jsoncallback']) : false;
	}
	if ($jqremote) {
		$json = $jqremote . '(' . $json . ')';
	}
	echo $json;
	exit;
}


function json_header()
{
	ob_clean();
	
	@header("Cache-Control: no-cache, must-revalidate");
	@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
}


function ajax_page($count, $perpage, $page, $js_code, $parma = '')
{
	$multi = '';
	$str_parma = '{}';
	if (!empty($parma)) {
		$str_parma = jsg_json_encode($parma);
	}
	
	if ($count > $perpage) {
		if ($page > 1) {
			$prev = $page - 1;
			$multi .= '<a href=\'javascript:;\' onclick=\''.$js_code.'('.$prev.','.$str_parma.')\'>上一页</a>';
		}
		
		if ($page * $perpage < $count) {
			$next = $page + 1;
			$multi .= '&nbsp;&nbsp;<a href=\'javascript:;\' onclick=\''.$js_code.'('.$next.','.$str_parma.')\'>下一页</a>';
		}
	}
	return $multi;
}


function autothumbnail($src_img, $dst_img, $w, $h)
{
	Load::lib('image');	
	$im = new image();
    return $im->Thumb($src_img, $dst_img, $w, $h, 'fixwr');
}


 
if(!function_exists('json_encode'))
{
    function json_encode($value)
    {
    	if(!class_exists('servicesJSON'))
    	{
    		Load::lib('servicesJSON');
    	}
        $json = new servicesJSON();
        return $json->encode($value);
    }
}
 
if(!function_exists('json_decode'))
{
    function json_decode($json_value,$bool = false)
    {
    	if(!class_exists('servicesJSON'))
    	{
    		Load::lib('servicesJSON');
    	}
        $assoc = ($bool) ? 16 : 32;
        $json = new servicesJSON($assoc);
        return $json->decode($json_value);
    }
}


function topic_type()
{
	$types = array(
		'first', 
		'forward', 
		'both',
	);
	return $types;	
}


function get_topic_type($type = '')
{
	$topic_types = array(
		'first', 
		'forward', 
		'both',
	);
	
	$not_visible_topic_types = array(
		'reply',
		'qun',
		'vote',

	);
	
	if ($type == 'personal') {
		$topic_types[] = 'personal';
	} else if ($type == 'forward') {
		$topic_types[] = 'reply';
		$topic_types[] = 'qun';
	} else if ($type == 'sys_not_visible') {
		$topic_types = $not_visible_topic_types;
	}
	return $topic_types;	
}


function get_def_follow_group()
{
	$g = array(
	    1 => '现同事',
	    2 => '好友',
	    3 => '同行',
	    4 => '其他',
	);
	return $g;
}







function mkseccode()
{
	$seccode = random(6, 1);
	$s = sprintf('%04s', base_convert($seccode, 10, 24));
	$seccode = '';
	$seccodeunits = 'BCEFGHJKMPQRTVWXY2346789';
	for($i = 0; $i < 4; $i++) {
		$unit = ord($s{$i});
		$seccode .= ($unit >= 0x30 && $unit <= 0x39) ? $seccodeunits[$unit - 0x30] : $seccodeunits[$unit - 0x57];
	}
	return $seccode;
}

function ckseccode($seccode)
{
	$check = true;
	$cookie = Obj::registry('CookieHandler');
	$c = $cookie->getVar("seccode");
	$cookie_seccode = empty($c)?'':authcode($c, 'DECODE');
	if(empty($cookie_seccode) || strtolower($cookie_seccode) != strtolower($seccode)) {
		$check = false;
	}
	return $check;
}

function email_url($email='')
{
 	$url = "";
 
 	$email_array = explode("@",$email);
 
 	$email_value = $email_array[1];
 
	 switch($email_value)
	 {
		  case "163.com":
		   $url = "mail.163.com";
		   break;
		  case "vip.163.com":
		   $url = "vip.163.com/?b08abh1";
		   break;
		  case "sina.com":
		   $url = "mail.sina.com.cn";
		   break;
		  case "sina.cn":
		   $url = "mail.sina.com.cn/cnmail/index.html";
		   break; 
		  case "vip.sina.com":
		   $url = "vip.sina.com.cn";
		   break; 
		  case "2008.sina.com":
		   $url = "mail.2008.sina.com.cn";
		   break; 
		  case "sohu.com":
		   $url = "mail.sohu.com";
		   break; 
		  case "vip.sohu.com":
		   $url = "vip.sohu.com";
		   break;
		  case "tom.com":
		   $url = "mail.tom.com";
		   break; 
		  case "vip.sina.com":
		   $url = "vip.tom.com";
		   break; 
		  case "sogou.com":
		   $url = "mail.sogou.com";
		   break;
		  case "126.com":
		   $url = "www.126.com";
		   break;
		  case "vip.126.com":
		   $url = "vip.126.com/?b09abh1";
		   break; 
		  case "139.com":
		   $url = "mail.10086.cn";
		   break; 
		  case "gmail.com":
		   $url = "www.google.com/accounts/ServiceLogin?service=mail";
		   break; 
		  case "hotmail.com":
		   $url = "www.hotmail.com";
		   break;
		  case "189.cn":
		   $url = "webmail2.189.cn/webmail/";
		   break;
		  case "qq.com":
		   $url = "mail.qq.com/cgi-bin/loginpage";
		   break;
		  case "yahoo.com":
		   $url = "mail.cn.yahoo.com";
		   break; 
		  case "yahoo.cn":
		   $url = "mail.cn.yahoo.com";
		   break; 
		  case "yahoo.com.cn":
		   $url = "mail.cn.yahoo.com";
		   break; 
		  case "21cn.com":
		   $url = "mail.21cn.com";
		   break; 
		  case "eyou.com":
		   $url = "www.eyou.com";
		   break; 
		  case "188.com":
		   $url = "www.188.com";
		   break;
		  case "yeah.net":
		   $url = "www.yeah.net";
		   break; 
		  case "foxmail.com":
		   $url = "mail.qq.com/cgi-bin/loginpage?t=fox_loginpage";
		   break; 
		  case "wo.com.cn":
		   $url = "mail.wo.com.cn/smsmail/login.html";
		   break; 
		  case "263.net":
		   $url = "www.263.net";
		   break; 
		  case "x263.net":
		   $url = "www.263.net";
		   break;
		  case "263.net.cn":
		   $url = "www.263.net";
		   break; 
		  default:
		   $url = "";            
	 } 
	 if($url)
	 {
	 	return $url; 		
	 } 
	 else
	 {		
	 	return false;
	 }
} 


$__TMP_OBJ_OF_UPS_CTRL = null;
function upsCtrl()
{
    global $__TMP_OBJ_OF_UPS_CTRL;
    if (is_null($__TMP_OBJ_OF_UPS_CTRL))
    {
        include_once(ROOT_PATH.'include/logic/ups.ctrl.moyo.php');
        $__TMP_OBJ_OF_UPS_CTRL = new xUpdateControlLogic();
    }
    return $__TMP_OBJ_OF_UPS_CTRL;
}

function jmkdir($dir, $mode = 0777, $makeindex = TRUE)
{
	if(!is_dir($dir)) {
		clearstatcache();
		jmkdir(dirname($dir));
		@mkdir($dir, $mode);
		if(!empty($makeindex)) {
			@touch($dir.'/index.html'); @chmod($dir.'/index.html', 0777);
		}
	}
	return true;
}

function process_url($content)
{
	if(false != strpos($content, ':/'.'/'))
	{
		$pattern = '~((?:https?\:\/\/)(?:[A-Za-z0-9\_\-]+\.)+[A-Za-z0-9]{1,4}(?:\:\d{1,6})?(?:\/[\w\d\/=\?%\-\&_\~\`\:\+\#\.]*(?:[^\;\@\[\]\<\>\'\"\n\r\t\s\x7f-\xff])*)?)~i';
		$replacement = '<a target="_blank" href="\\1">\\1</a>';
		
		$content = preg_replace($pattern, $replacement, $content);
	}
	
	return $content;
}

function sys_version($v = null)
{
	$v = ($v ? $v : SYS_VERSION);
	
	$srp = strrpos($v, '.');
	if(false !== $srp)
	{
		$v = substr($v, 0, $srp);
	}
	
	return $v;
}




function curPageURL() 
{
    $pageURL = 'http';

    if ($_SERVER["HTTPS"] == "on") 
    {
        $pageURL .= "s";
    }
    $pageURL .= "://";

    if ($_SERVER["SERVER_PORT"] != "80") 
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } 
    else 
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}
?>