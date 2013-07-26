<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: function_core.php 24580 2011-09-27 05:38:22Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

define('DISCUZ_CORE_FUNCTION', true);

function system_error($message, $show = true, $save = true, $halt = true) {
	require_once libfile('class/error');
	discuz_error::system_error($message, $show, $save, $halt);
}

function updatesession($force = false) {

	global $_G;
	static $updated = false;

	if(!$updated) {
		if($_G['uid']) {
			if($_G['cookie']['ulastactivity']) {
				$ulastactivity = authcode($_G['cookie']['ulastactivity'], 'DECODE');
			} else {
				$ulastactivity = getuserprofile('lastactivity');
				dsetcookie('ulastactivity', authcode($ulastactivity, 'ENCODE'), 31536000);
			}
		}
		$discuz = & discuz_core::instance();
		$oltimespan = $_G['setting']['oltimespan'];
		$lastolupdate = $discuz->session->var['lastolupdate'];
		if($_G['uid'] && $oltimespan && TIMESTAMP - ($lastolupdate ? $lastolupdate : $ulastactivity) > $oltimespan * 60) {
			DB::query("UPDATE ".DB::table('common_onlinetime')."
				SET total=total+'$oltimespan', thismonth=thismonth+'$oltimespan', lastupdate='" . TIMESTAMP . "'
				WHERE uid='{$_G['uid']}'");
			if(!DB::affected_rows()) {
				DB::insert('common_onlinetime', array(
					'uid' => $_G['uid'],
					'thismonth' => $oltimespan,
					'total' => $oltimespan,
					'lastupdate' => TIMESTAMP,
				));
			}
			$discuz->session->set('lastolupdate', TIMESTAMP);
		}
		foreach($discuz->session->var as $k => $v) {
			if(isset($_G['member'][$k]) && $k != 'lastactivity') {
				$discuz->session->set($k, $_G['member'][$k]);
			}
		}

		foreach($_G['action'] as $k => $v) {
			$discuz->session->set($k, $v);
		}

		$discuz->session->update();

		$updated = true;

		if($_G['uid'] && TIMESTAMP - $ulastactivity > 21600) {
			if($oltimespan && TIMESTAMP - $ulastactivity > 43200) {
				$total = DB::result_first("SELECT total FROM ".DB::table('common_onlinetime')." WHERE uid='$_G[uid]'");
				DB::update('common_member_count', array('oltime' => round(intval($total) / 60)), "uid='$_G[uid]'", 1);
			}
			dsetcookie('ulastactivity', authcode(TIMESTAMP, 'ENCODE'), 31536000);
			DB::update('common_member_status', array('lastip' => $_G['clientip'], 'lastactivity' => TIMESTAMP, 'lastvisit' => TIMESTAMP), "uid='$_G[uid]'", 1);
		}
	}
	return $updated;
}

function dmicrotime() {
	return array_sum(explode(' ', microtime()));
}

function setglobal($key , $value, $group = null) {
	global $_G;
	$k = explode('/', $group === null ? $key : $group.'/'.$key);
	switch (count($k)) {
		case 1: $_G[$k[0]] = $value; break;
		case 2: $_G[$k[0]][$k[1]] = $value; break;
		case 3: $_G[$k[0]][$k[1]][$k[2]] = $value; break;
		case 4: $_G[$k[0]][$k[1]][$k[2]][$k[3]] = $value; break;
		case 5: $_G[$k[0]][$k[1]][$k[2]][$k[3]][$k[4]] =$value; break;
	}
	return true;
}

function getglobal($key, $group = null) {
	global $_G;
	$k = explode('/', $group === null ? $key : $group.'/'.$key);
	switch (count($k)) {
		case 1: return isset($_G[$k[0]]) ? $_G[$k[0]] : null; break;
		case 2: return isset($_G[$k[0]][$k[1]]) ? $_G[$k[0]][$k[1]] : null; break;
		case 3: return isset($_G[$k[0]][$k[1]][$k[2]]) ? $_G[$k[0]][$k[1]][$k[2]] : null; break;
		case 4: return isset($_G[$k[0]][$k[1]][$k[2]][$k[3]]) ? $_G[$k[0]][$k[1]][$k[2]][$k[3]] : null; break;
		case 5: return isset($_G[$k[0]][$k[1]][$k[2]][$k[3]][$k[4]]) ? $_G[$k[0]][$k[1]][$k[2]][$k[3]][$k[4]] : null; break;
	}
	return null;
}

function getgpc($k, $type='GP') {
	$type = strtoupper($type);
	switch($type) {
		case 'G': $var = &$_GET; break;
		case 'P': $var = &$_POST; break;
		case 'C': $var = &$_COOKIE; break;
		default:
			if(isset($_GET[$k])) {
				$var = &$_GET;
			} else {
				$var = &$_POST;
			}
			break;
	}

	return isset($var[$k]) ? $var[$k] : NULL;

}

function getuserbyuid($uid) {
	static $users = array();
	if(empty($users[$uid])) {
		$users[$uid] = DB::fetch_first("SELECT * FROM ".DB::table('common_member')." WHERE uid='$uid'");
	}
	return $users[$uid];
}

function getuserprofile($field) {
	global $_G;
	if(isset($_G['member'][$field])) {
		return $_G['member'][$field];
	}
	static $tablefields = array(
		'count'		=> array('extcredits1','extcredits2','extcredits3','extcredits4','extcredits5','extcredits6','extcredits7','extcredits8','friends','posts','threads','digestposts','doings','blogs','albums','sharings','attachsize','views','oltime','todayattachs','todayattachsize'),
		'status'	=> array('regip','lastip','lastvisit','lastactivity','lastpost','lastsendmail','invisible','buyercredit','sellercredit','favtimes','sharetimes','profileprogress'),
		'field_forum'	=> array('publishfeed','customshow','customstatus','medals','sightml','groupterms','authstr','groups','attentiongroup'),
		'field_home'	=> array('videophoto','spacename','spacedescription','domain','addsize','addfriend','menunum','theme','spacecss','blockposition','recentnote','spacenote','privacy','feedfriend','acceptemail','magicgift','stickblogs'),
		'profile'	=> array('realname','gender','birthyear','birthmonth','birthday','constellation','zodiac','telephone','mobile','idcardtype','idcard','address','zipcode','nationality','birthprovince','birthcity','resideprovince','residecity','residedist','residecommunity','residesuite','graduateschool','company','education','occupation','position','revenue','affectivestatus','lookingfor','bloodtype','height','weight','alipay','icq','qq','yahoo','msn','taobao','site','bio','interest','field1','field2','field3','field4','field5','field6','field7','field8'),
		'verify'	=> array('verify1', 'verify2', 'verify3', 'verify4', 'verify5', 'verify6', 'verify7'),
	);
	$profiletable = '';
	foreach($tablefields as $table => $fields) {
		if(in_array($field, $fields)) {
			$profiletable = $table;
			break;
		}
	}
	if($profiletable) {
		$data = array();
		if($_G['uid']) {
			$data = DB::fetch_first("SELECT ".implode(', ', $tablefields[$profiletable])." FROM ".DB::table('common_member_'.$profiletable)." WHERE uid='$_G[uid]'");
		}
		if(!$data) {
			foreach($tablefields[$profiletable] as $k) {
				$data[$k] = '';
			}
		}
		$_G['member'] = array_merge(is_array($_G['member']) ? $_G['member'] : array(), $data);
		return $_G['member'][$field];
	}
}

function daddslashes($string, $force = 1) {
	if(is_array($string)) {
		$keys = array_keys($string);
		foreach($keys as $key) {
			$val = $string[$key];
			unset($string[$key]);
			$string[addslashes($key)] = daddslashes($val, $force);
		}
	} else {
		$string = addslashes($string);
	}
	return $string;
}

function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;
	$key = md5($key != '' ? $key : getglobal('authkey'));
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

function dfsockopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
	require_once libfile('function/filesock');
	return _dfsockopen($url, $limit, $post, $cookie, $bysocket, $ip, $timeout, $block);
}

function dhtmlspecialchars($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = dhtmlspecialchars($val);
		}
	} else {
		$string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
		if(strpos($string, '&amp;#') !== false) {
			$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string);
		}
	}
	return $string;
}

function dexit($message = '') {
	echo $message;
	output();
	exit();
}

function dheader($string, $replace = true, $http_response_code = 0) {
	$islocation = substr(strtolower(trim($string)), 0, 8) == 'location';
	if(defined('IN_MOBILE') && strpos($string, 'mobile') === false && $islocation) {
		if (strpos($string, '?') === false) {
			$string = $string.'?mobile=yes';
		} else {
			if(strpos($string, '#') === false) {
				$string = $string.'&mobile=yes';
			} else {
				$str_arr = explode('#', $string);
				$str_arr[0] = $str_arr[0].'&mobile=yes';
				$string = implode('#', $str_arr);
			}
		}
	}
	$string = str_replace(array("\r", "\n"), array('', ''), $string);
	if(empty($http_response_code) || PHP_VERSION < '4.3' ) {
		@header($string, $replace);
	} else {
		@header($string, $replace, $http_response_code);
	}
	if($islocation) {
		exit();
	}
}

function dsetcookie($var, $value = '', $life = 0, $prefix = 1, $httponly = false) {

	global $_G;

	$config = $_G['config']['cookie'];

	$_G['cookie'][$var] = $value;
	$var = ($prefix ? $config['cookiepre'] : '').$var;
	$_COOKIE[$var] = $value;

	if($value == '' || $life < 0) {
		$value = '';
		$life = -1;
	}

	if(defined('IN_MOBILE')) {
		$httponly = false;
	}

	$life = $life > 0 ? getglobal('timestamp') + $life : ($life < 0 ? getglobal('timestamp') - 31536000 : 0);
	$path = $httponly && PHP_VERSION < '5.2.0' ? $config['cookiepath'].'; HttpOnly' : $config['cookiepath'];

	$secure = $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
	if(PHP_VERSION < '5.2.0') {
		setcookie($var, $value, $life, $path, $config['cookiedomain'], $secure);
	} else {
		setcookie($var, $value, $life, $path, $config['cookiedomain'], $secure, $httponly);
	}
}

function getcookie($key) {
	global $_G;
	return isset($_G['cookie'][$key]) ? $_G['cookie'][$key] : '';
}

function fileext($filename) {
	return addslashes(trim(substr(strrchr($filename, '.'), 1, 10)));
}

function formhash($specialadd = '') {
	global $_G;
	$hashadd = defined('IN_ADMINCP') ? 'Only For Discuz! Admin Control Panel' : '';
	return substr(md5(substr($_G['timestamp'], 0, -7).$_G['username'].$_G['uid'].$_G['authkey'].$hashadd.$specialadd), 8, 8);
}

function checkrobot($useragent = '') {
	static $kw_spiders = array('bot', 'crawl', 'spider' ,'slurp', 'sohu-search', 'lycos', 'robozilla');
	static $kw_browsers = array('msie', 'netscape', 'opera', 'konqueror', 'mozilla');

	$useragent = strtolower(empty($useragent) ? $_SERVER['HTTP_USER_AGENT'] : $useragent);
	if(strpos($useragent, 'http://') === false && dstrpos($useragent, $kw_browsers)) return false;
	if(dstrpos($useragent, $kw_spiders)) return true;
	return false;
}
function checkmobile() {
	global $_G;
	$mobile = array();
	static $mobilebrowser_list =array('iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi', 'opera mini',
				'ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod', 'nokia', 'samsung',
				'palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma', 'docomo', 'up.browser',
				'up.link', 'blazer', 'helio', 'hosin', 'huawei', 'novarra', 'coolpad', 'webos', 'techfaith', 'palmsource',
				'alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 'bunjalloo', 'maui', 'smartphone',
				'iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech', 'gionee', 'portalmmm', 'jig browser', 'hiptop',
				'benq', 'haier', '^lct', '320x320', '240x320', '176x220');
	$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
	if(($v = dstrpos($useragent, $mobilebrowser_list, true))) {
		$_G['mobile'] = $v;
		return true;
	}
	$brower = array('mozilla', 'chrome', 'safari', 'opera', 'm3gate', 'winwap', 'openwave', 'myop');
	if(dstrpos($useragent, $brower)) return false;

	$_G['mobile'] = 'unknown';
	if($_GET['mobile'] === 'yes') {
		return true;
	} else {
		return false;
	}
}

function dstrpos($string, &$arr, $returnvalue = false) {
	if(empty($string)) return false;
	foreach((array)$arr as $v) {
		if(strpos($string, $v) !== false) {
			$return = $returnvalue ? $v : true;
			return $return;
		}
	}
	return false;
}

function isemail($email) {
	return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}

function quescrypt($questionid, $answer) {
	return $questionid > 0 && $answer != '' ? substr(md5($answer.md5($questionid)), 16, 8) : '';
}

function random($length, $numeric = 0) {
	$seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
	$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
	$hash = '';
	$max = strlen($seed) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $seed{mt_rand(0, $max)};
	}
	return $hash;
}

function strexists($string, $find) {
	return !(strpos($string, $find) === FALSE);
}

function avatar($uid, $size = 'middle', $returnsrc = FALSE, $real = FALSE, $static = FALSE, $ucenterurl = '') {
	global $_G;
	static $staticavatar;
	if($staticavatar === null) {
		$staticavatar = $_G['setting']['avatarmethod'];
	}

	$ucenterurl = empty($ucenterurl) ? $_G['setting']['ucenterurl'] : $ucenterurl;
	$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'middle';
	$uid = abs(intval($uid));
	if(!$staticavatar && !$static) {
		return $returnsrc ? $ucenterurl.'/avatar.php?uid='.$uid.'&size='.$size : '<img src="'.$ucenterurl.'/avatar.php?uid='.$uid.'&size='.$size.($real ? '&type=real' : '').'" />';
	} else {
		$uid = sprintf("%09d", $uid);
		$dir1 = substr($uid, 0, 3);
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		$file = $ucenterurl.'/data/avatar/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).($real ? '_real' : '').'_avatar_'.$size.'.jpg';
		return $returnsrc ? $file : '<img src="'.$file.'" onerror="this.onerror=null;this.src=\''.$ucenterurl.'/images/noavatar_'.$size.'.gif\'" />';
	}
}

function lang($file, $langvar = null, $vars = array(), $default = null) {
	global $_G;
	list($path, $file) = explode('/', $file);
	if(!$file) {
		$file = $path;
		$path = '';
	}

	if($path != 'plugin') {
		$key = $path == '' ? $file : $path.'_'.$file;
		if(!isset($_G['lang'][$key])) {
			include DISCUZ_ROOT.'./source/language/'.($path == '' ? '' : $path.'/').'lang_'.$file.'.php';
			$_G['lang'][$key] = $lang;
		}
		if(defined('IN_MOBILE') && !defined('TPL_DEFAULT')) {
			include DISCUZ_ROOT.'./source/language/mobile/lang_template.php';
			$_G['lang'][$key] = array_merge($_G['lang'][$key], $lang);
		}
		$returnvalue = &$_G['lang'];
	} else {
		if(empty($_G['config']['plugindeveloper'])) {
			loadcache('pluginlanguage_script');
		} elseif(!isset($_G['cache']['pluginlanguage_script'][$file]) && preg_match("/^[a-z]+[a-z0-9_]*$/i", $file)) {
			if(@include(DISCUZ_ROOT.'./data/plugindata/'.$file.'.lang.php')) {
				$_G['cache']['pluginlanguage_script'][$file] = $scriptlang[$file];
			} else {
				loadcache('pluginlanguage_script');
			}
		}
		$returnvalue = & $_G['cache']['pluginlanguage_script'];
		$key = &$file;
	}
	$return = $langvar !== null ? (isset($returnvalue[$key][$langvar]) ? $returnvalue[$key][$langvar] : null) : $returnvalue[$key];
	$return = $return === null ? ($default !== null ? $default : $langvar) : $return;
	$searchs = $replaces = array();
	if($vars && is_array($vars)) {
		foreach($vars as $k => $v) {
			$searchs[] = '{'.$k.'}';
			$replaces[] = $v;
		}
	}
	if(is_string($return) && strpos($return, '{_G/') !== false) {
		preg_match_all('/\{_G\/(.+?)\}/', $return, $gvar);
		foreach($gvar[0] as $k => $v) {
			$searchs[] = $v;
			$replaces[] = getglobal($gvar[1][$k]);
		}
	}
	$return = str_replace($searchs, $replaces, $return);
	return $return;
}

function checktplrefresh($maintpl, $subtpl, $timecompare, $templateid, $cachefile, $tpldir, $file) {
	static $tplrefresh, $timestamp, $targettplname;
	if($tplrefresh === null) {
		$tplrefresh = getglobal('config/output/tplrefresh');
		$timestamp = getglobal('timestamp');
	}

	if(empty($timecompare) || $tplrefresh == 1 || ($tplrefresh > 1 && !($timestamp % $tplrefresh))) {
		if(empty($timecompare) || @filemtime(DISCUZ_ROOT.$subtpl) > $timecompare) {
			require_once DISCUZ_ROOT.'/source/class/class_template.php';
			$template = new template();
			$template->parse_template($maintpl, $templateid, $tpldir, $file, $cachefile);
			if($targettplname === null) {
				$targettplname = getglobal('style/tplfile');
				if(!empty($targettplname)) {
					$targettplname = strtr($targettplname, ':', '_');
					update_template_block($targettplname, $template->blocks);
				}
				$targettplname = true;
			}
			return TRUE;
		}
	}
	return FALSE;
}

function template($file, $templateid = 0, $tpldir = '', $gettplfile = 0, $primaltpl='') {
	global $_G;

	static $_init_style = false;
	if($_init_style === false) {
		$discuz = & discuz_core::instance();
		$discuz->_init_style();
		$_init_style = true;
	}
	$oldfile = $file;
	if(strpos($file, ':') !== false) {
		$clonefile = '';
		list($templateid, $file, $clonefile) = explode(':', $file);
		$oldfile = $file;
		$file = empty($clonefile) || STYLEID != $_G['cache']['style_default']['styleid'] ? $file : $file.'_'.$clonefile;
		if($templateid == 'diy' && STYLEID == $_G['cache']['style_default']['styleid']) {
			$_G['style']['prefile'] = '';
			$diypath = DISCUZ_ROOT.'./data/diy/'; //DIY模板文件目录
			$preend = '_diy_preview';
			$_G['gp_preview'] = !empty($_G['gp_preview']) ? $_G['gp_preview'] : '';
			$curtplname = $oldfile;
			if(isset($_G['cache']['diytemplatename'.$_G['basescript']])) {
				$diytemplatename = &$_G['cache']['diytemplatename'.$_G['basescript']];
			} else {
				$diytemplatename = &$_G['cache']['diytemplatename'];
			}
			$tplsavemod = 0;
			if(isset($diytemplatename[$file]) && file_exists($diypath.$file.'.htm') && ($tplsavemod = 1) || ($file = $primaltpl ? $primaltpl : $oldfile) && isset($diytemplatename[$file]) && file_exists($diypath.$file.'.htm')) {
				$tpldir = 'data/diy';
				!$gettplfile && $_G['style']['tplsavemod'] = $tplsavemod;
				$curtplname = $file;
				if($_G['gp_diy'] == 'yes' || $_G['gp_preview'] == 'yes') { //DIY模式或预览模式下做以下判断
					$flag = file_exists($diypath.$file.$preend.'.htm');
					if($_G['gp_preview'] == 'yes') {
						$file .= $flag ? $preend : '';
					} else {
						$_G['style']['prefile'] = $flag ? 1 : '';
					}
				}
			} else {
				$file = $primaltpl ? $primaltpl : $oldfile;
			}
			$tplrefresh = $_G['config']['output']['tplrefresh'];
			if($tpldir == 'data/diy' && ($tplrefresh ==1 || ($tplrefresh > 1 && !($_G['timestamp'] % $tplrefresh))) && filemtime($diypath.$file.'.htm') < filemtime(DISCUZ_ROOT.TPLDIR.'/'.($primaltpl ? $primaltpl : $oldfile).'.htm')) {
				if (!updatediytemplate($file)) {
					unlink($diypath.$file.'.htm');
					$tpldir = '';
				}
			}

			if (!$gettplfile && empty($_G['style']['tplfile'])) {
				$_G['style']['tplfile'] = empty($clonefile) ? $curtplname : $oldfile.':'.$clonefile;
			}

			$_G['style']['prefile'] = !empty($_G['gp_preview']) && $_G['gp_preview'] == 'yes' ? '' : $_G['style']['prefile'];

		} else {
			$tpldir = './source/plugin/'.$templateid.'/template';
		}
	}

	$file .= !empty($_G['inajax']) && ($file == 'common/header' || $file == 'common/footer') ? '_ajax' : '';
	$tpldir = $tpldir ? $tpldir : (defined('TPLDIR') ? TPLDIR : '');
	$templateid = $templateid ? $templateid : (defined('TEMPLATEID') ? TEMPLATEID : '');
	$filebak = $file;

	if(defined('IN_MOBILE') && !defined('TPL_DEFAULT') && strpos($file, 'mobile/') === false || $_G['forcemobilemessage']) {
		$file = 'mobile/'.$oldfile;
	}

	$tplfile = ($tpldir ? $tpldir.'/' : './template/').$file.'.htm';

	$file == 'common/header' && defined('CURMODULE') && CURMODULE && $file = 'common/header_'.$_G['basescript'].'_'.CURMODULE;

	if(defined('IN_MOBILE') && !defined('TPL_DEFAULT')) {
		if(strpos($tpldir, 'plugin')) {
			if(!file_exists(DISCUZ_ROOT.$tpldir.'/'.$file.'.htm')) {
				require_once libfile('class/error');
				discuz_error::template_error('template_notfound', $tpldir.'/'.$file.'.htm');
			} else {
				$mobiletplfile = $tpldir.'/'.$file.'.htm';
			}
		}
		!$mobiletplfile && $mobiletplfile = $file.'.htm';
		if(strpos($tpldir, 'plugin') && file_exists(DISCUZ_ROOT.$mobiletplfile)) {
			$tplfile = $mobiletplfile;
		} elseif(!file_exists(DISCUZ_ROOT.TPLDIR.'/'.$mobiletplfile)) {
			$mobiletplfile = './template/default/'.$mobiletplfile;
			if(!file_exists(DISCUZ_ROOT.$mobiletplfile) && !$_G['forcemobilemessage']) {
				$tplfile = str_replace('mobile/', '', $tplfile);
				$file = str_replace('mobile/', '', $file);
				define('TPL_DEFAULT', true);
			} else {
				$tplfile = $mobiletplfile;
			}
		} else {
			$tplfile = TPLDIR.'/'.$mobiletplfile;
		}
	}

	$cachefile = './data/template/'.(defined('STYLEID') ? STYLEID.'_' : '_').$templateid.'_'.str_replace('/', '_', $file).'.tpl.php';

	if($templateid != 1 && !file_exists(DISCUZ_ROOT.$tplfile)) {
		$tplfile = './template/default/'.$filebak.'.htm';
	}

	if($gettplfile) {
		return $tplfile;
	}
	checktplrefresh($tplfile, $tplfile, @filemtime(DISCUZ_ROOT.$cachefile), $templateid, $cachefile, $tpldir, $file);
	return DISCUZ_ROOT.$cachefile;
}

function modauthkey($id) {
	global $_G;
	return md5($_G['username'].$_G['uid'].$_G['authkey'].substr(TIMESTAMP, 0, -7).$id);
}

function getcurrentnav() {
	global $_G;
	if(!empty($_G['mnid'])) {
		return $_G['mnid'];
	}
	$mnid = '';
	$_G['basefilename'] = $_G['basefilename'] == $_G['basescript'] ? $_G['basefilename'] : $_G['basescript'].'.php';
	if(isset($_G['setting']['navmns'][$_G['basefilename']])) {
		foreach($_G['setting']['navmns'][$_G['basefilename']] as $navmn) {
			if($navmn[0] == array_intersect_assoc($navmn[0], $_GET)) {
				$mnid = $navmn[1];
			}
		}
	}
	if(!$mnid && isset($_G['setting']['navdms'])) {
		foreach($_G['setting']['navdms'] as $navdm => $navid) {
			if(strpos(strtolower($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']), $navdm) !== false) {
				$mnid = $navid;
				break;
			}
		}
	}
	if(!$mnid && isset($_G['setting']['navmn'][$_G['basefilename']])) {
		$mnid = $_G['setting']['navmn'][$_G['basefilename']];
	}
	return $mnid;
}

function loaducenter() {
	require_once DISCUZ_ROOT.'./config/config_ucenter.php';
	require_once DISCUZ_ROOT.'./uc_client/client.php';
}

function loadcache($cachenames, $force = false) {
	global $_G;
	static $loadedcache = array();
	$cachenames = is_array($cachenames) ? $cachenames : array($cachenames);
	$caches = array();
	foreach ($cachenames as $k) {
		if(!isset($loadedcache[$k]) || $force) {
			$caches[] = $k;
			$loadedcache[$k] = true;
		}
	}

	if(!empty($caches)) {
		$cachedata = cachedata($caches);
		foreach($cachedata as $cname => $data) {
			if($cname == 'setting') {
				$_G['setting'] = $data;
			} elseif(strpos($cname, 'usergroup_'.$_G['groupid']) !== false) {
				$_G['cache'][$cname] = $_G['group'] = $data;
			} elseif($cname == 'style_default') {
				$_G['cache'][$cname] = $_G['style'] = $data;
			} elseif($cname == 'grouplevels') {
				$_G['grouplevels'] = $data;
			} else {
				$_G['cache'][$cname] = $data;
			}
		}
	}
	return true;
}

function cachedata($cachenames) {
	global $_G;
	static $isfilecache, $allowmem;

	if(!isset($isfilecache)) {
		$isfilecache = getglobal('config/cache/type') == 'file';
		$allowmem = memory('check');
	}

	$data = array();
	$cachenames = is_array($cachenames) ? $cachenames : array($cachenames);
	if($allowmem) {
		$newarray = array();
		foreach ($cachenames as $name) {
			$data[$name] = memory('get', $name);
			if($data[$name] === null) {
				$data[$name] = null;
				$newarray[] = $name;
			}
		}
		if(empty($newarray)) {
			return $data;
		} else {
			$cachenames = $newarray;
		}
	}

	if($isfilecache) {
		$lostcaches = array();
		foreach($cachenames as $cachename) {
			if(!@include_once(DISCUZ_ROOT.'./data/cache/cache_'.$cachename.'.php')) {
				$lostcaches[] = $cachename;
			}
		}
		if(!$lostcaches) {
			return $data;
		}
		$cachenames = $lostcaches;
		unset($lostcaches);
	}
	$query = DB::query("SELECT * FROM ".DB::table('common_syscache')." WHERE cname IN ('".implode("','", $cachenames)."')");
	while($syscache = DB::fetch($query)) {
		$data[$syscache['cname']] = $syscache['ctype'] ? unserialize($syscache['data']) : $syscache['data'];
		$allowmem && (memory('set', $syscache['cname'], $data[$syscache['cname']]));
		if($isfilecache) {
			$cachedata = '$data[\''.$syscache['cname'].'\'] = '.var_export($data[$syscache['cname']], true).";\n\n";
			if($fp = @fopen(DISCUZ_ROOT.'./data/cache/cache_'.$syscache['cname'].'.php', 'wb')) {
				fwrite($fp, "<?php\n//Discuz! cache file, DO NOT modify me!\n//Identify: ".md5($syscache['cname'].$cachedata.$_G['config']['security']['authkey'])."\n\n$cachedata?>");
				fclose($fp);
			}
		}
	}

	foreach($cachenames as $name) {
		if($data[$name] === null) {
			$data[$name] = null;
			$allowmem && (memory('set', $name, array()));
		}
	}

	return $data;
}

function dgmdate($timestamp, $format = 'dt', $timeoffset = '9999', $uformat = '') {
	global $_G;
	$format == 'u' && !$_G['setting']['dateconvert'] && $format = 'dt';
	static $dformat, $tformat, $dtformat, $offset, $lang;
	if($dformat === null) {
		$dformat = getglobal('setting/dateformat');
		$tformat = getglobal('setting/timeformat');
		$dtformat = $dformat.' '.$tformat;
		$offset = getglobal('member/timeoffset');
		$lang = lang('core', 'date');
	}
	$timeoffset = $timeoffset == 9999 ? $offset : $timeoffset;
	$timestamp += $timeoffset * 3600;
	$format = empty($format) || $format == 'dt' ? $dtformat : ($format == 'd' ? $dformat : ($format == 't' ? $tformat : $format));
	if($format == 'u') {
		$todaytimestamp = TIMESTAMP - (TIMESTAMP + $timeoffset * 3600) % 86400 + $timeoffset * 3600;
		$s = gmdate(!$uformat ? str_replace(":i", ":i:s", $dtformat) : $uformat, $timestamp);
		$time = TIMESTAMP + $timeoffset * 3600 - $timestamp;
		if($timestamp >= $todaytimestamp) {
			if($time > 3600) {
				return '<span title="'.$s.'">'.intval($time / 3600).'&nbsp;'.$lang['hour'].$lang['before'].'</span>';
			} elseif($time > 1800) {
				return '<span title="'.$s.'">'.$lang['half'].$lang['hour'].$lang['before'].'</span>';
			} elseif($time > 60) {
				return '<span title="'.$s.'">'.intval($time / 60).'&nbsp;'.$lang['min'].$lang['before'].'</span>';
			} elseif($time > 0) {
				return '<span title="'.$s.'">'.$time.'&nbsp;'.$lang['sec'].$lang['before'].'</span>';
			} elseif($time == 0) {
				return '<span title="'.$s.'">'.$lang['now'].'</span>';
			} else {
				return $s;
			}
		} elseif(($days = intval(($todaytimestamp - $timestamp) / 86400)) >= 0 && $days < 7) {
			if($days == 0) {
				return '<span title="'.$s.'">'.$lang['yday'].'&nbsp;'.gmdate($tformat, $timestamp).'</span>';
			} elseif($days == 1) {
				return '<span title="'.$s.'">'.$lang['byday'].'&nbsp;'.gmdate($tformat, $timestamp).'</span>';
			} else {
				return '<span title="'.$s.'">'.($days + 1).'&nbsp;'.$lang['day'].$lang['before'].'</span>';
			}
		} else {
			return $s;
		}
	} else {
		return gmdate($format, $timestamp);
	}
}

function dmktime($date) {
	if(strpos($date, '-')) {
		$time = explode('-', $date);
		return mktime(0, 0, 0, $time[1], $time[2], $time[0]);
	}
	return 0;
}

function save_syscache($cachename, $data) {
	static $isfilecache, $allowmem;
	if(!isset($isfilecache)) {
		$isfilecache = getglobal('config/cache/type') == 'file';
		$allowmem = memory('check');
	}

	if(is_array($data)) {
		$ctype = 1;
		$data = addslashes(serialize($data));
	} else {
		$ctype = 0;
	}

	DB::query("REPLACE INTO ".DB::table('common_syscache')." (cname, ctype, dateline, data) VALUES ('$cachename', '$ctype', '".TIMESTAMP."', '$data')");

	$allowmem && memory('rm', $cachename);
	$isfilecache && @unlink(DISCUZ_ROOT.'./data/cache/cache_'.$cachename.'.php');
}

function block_get($parameter) {
	global $_G;
	static $allowmem;
	if($allowmem === null) {
		include_once libfile('function/block');
		$allowmem = getglobal('setting/memory/diyblock/enable') && memory('check');
	}
	if(!$allowmem) {
		block_get_batch($parameter);
		return true;
	}
	$blockids = explode(',', $parameter);
	$lostbids = array();
	foreach ($blockids as $bid) {
		$bid = intval($bid);
		if($bid) {
			$_G['block'][$bid] = memory('get', 'blockcache_'.$bid);
			if($_G['block'][$bid] === null) {
				$lostbids[] = $bid;
			} else {
				$styleid = $_G['block'][$bid]['styleid'];
				if($styleid && !isset($_G['blockstyle_'.$styleid])) {
					$_G['blockstyle_'.$styleid] = memory('get', 'blockstylecache_'.$styleid);
				}
			}
		}
	}

	if($lostbids) {
		block_get_batch(implode(',', $lostbids));
		foreach ($lostbids as $bid) {
			if(isset($_G['block'][$bid])) {
				memory('set', 'blockcache_'.$bid, $_G['block'][$bid], getglobal('setting/memory/diyblock/ttl'));
				$styleid = $_G['block'][$bid]['styleid'];
				if($styleid && $_G['blockstyle_'.$styleid]) {
					memory('set', 'blockstylecache_'.$styleid, $_G['blockstyle_'.$styleid], getglobal('setting/memory/diyblock/ttl'));
				}
			}
		}
	}
}

function block_display($bid) {
	include_once libfile('function/block');
	block_display_batch($bid);
}

function dimplode($array) {
	if(!empty($array)) {
		return "'".implode("','", is_array($array) ? $array : array($array))."'";
	} else {
		return 0;
	}
}

function libfile($libname, $folder = '') {
	$libpath = DISCUZ_ROOT.'/source/'.$folder;
	if(strstr($libname, '/')) {
		list($pre, $name) = explode('/', $libname);
		return realpath("{$libpath}/{$pre}/{$pre}_{$name}.php");
	} else {
		return realpath("{$libpath}/{$libname}.php");
	}
}

function dstrlen($str) {
	if(strtolower(CHARSET) != 'utf-8') {
		return strlen($str);
	}
	$count = 0;
	for($i = 0; $i < strlen($str); $i++){
		$value = ord($str[$i]);
		if($value > 127) {
			$count++;
			if($value >= 192 && $value <= 223) $i++;
			elseif($value >= 224 && $value <= 239) $i = $i + 2;
			elseif($value >= 240 && $value <= 247) $i = $i + 3;
	    	}
    		$count++;
	}
	return $count;
}

function cutstr($string, $length, $dot = ' ...') {
	if(strlen($string) <= $length) {
		return $string;
	}

	$pre = chr(1);
	$end = chr(1);
	$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end), $string);

	$strcut = '';
	if(strtolower(CHARSET) == 'utf-8') {

		$n = $tn = $noc = 0;
		while($n < strlen($string)) {

			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t <= 239) {
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

			if($noc >= $length) {
				break;
			}

		}
		if($noc > $length) {
			$n -= $tn;
		}

		$strcut = substr($string, 0, $n);

	} else {
		for($i = 0; $i < $length; $i++) {
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}

	$strcut = str_replace(array($pre.'&'.$end, $pre.'"'.$end, $pre.'<'.$end, $pre.'>'.$end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

	$pos = strrpos($strcut, chr(1));
	if($pos !== false) {
		$strcut = substr($strcut,0,$pos);
	}
	return $strcut.$dot;
}

function dstripslashes($string) {
	if(empty($string)) return $string;
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = dstripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}

function aidencode($aid, $type = 0, $tid = 0) {
	global $_G;
	$s = !$type ? $aid.'|'.substr(md5($aid.md5($_G['config']['security']['authkey']).TIMESTAMP.$_G['uid']), 0, 8).'|'.TIMESTAMP.'|'.$_G['uid'].'|'.$tid : $aid.'|'.md5($aid.md5($_G['config']['security']['authkey']).TIMESTAMP).'|'.TIMESTAMP;
	return rawurlencode(base64_encode($s));
}

function getforumimg($aid, $nocache = 0, $w = 140, $h = 140, $type = '') {
	global $_G;
	$key = md5($aid.'|'.$w.'|'.$h);
	return 'forum.php?mod=image&aid='.$aid.'&size='.$w.'x'.$h.'&key='.rawurlencode($key).($nocache ? '&nocache=yes' : '').($type ? '&type='.$type : '');
}

function rewriteoutput($type, $returntype, $host) {
	global $_G;
	$fextra = '';
	if($type == 'forum_forumdisplay') {
		list(,,, $fid, $page, $extra) = func_get_args();
		$r = array(
			'{fid}' => empty($_G['setting']['forumkeys'][$fid]) ? $fid : $_G['setting']['forumkeys'][$fid],
			'{page}' => $page ? $page : 1,
		);
	} elseif($type == 'forum_viewthread') {
		list(,,, $tid, $page, $prevpage, $extra) = func_get_args();
		$r = array(
			'{tid}' => $tid,
			'{page}' => $page ? $page : 1,
			'{prevpage}' => $prevpage && !IS_ROBOT ? $prevpage : 1,
		);
	} elseif($type == 'home_space') {
		list(,,, $uid, $username, $extra) = func_get_args();
		$_G['setting']['rewritecompatible'] && $username = rawurlencode($username);
		$r = array(
			'{user}' => $uid ? 'uid' : 'username',
			'{value}' => $uid ? $uid : $username,
		);
	} elseif($type == 'home_blog') {
		list(,,, $uid, $blogid, $extra) = func_get_args();
		$r = array(
			'{uid}' => $uid,
			'{blogid}' => $blogid,
		);
	} elseif($type == 'group_group') {
		list(,,, $fid, $page, $extra) = func_get_args();
		$r = array(
			'{fid}' => $fid,
			'{page}' => $page ? $page : 1,
		);
	} elseif($type == 'portal_topic') {
		list(,,, $name, $extra) = func_get_args();
		$r = array(
			'{name}' => $name,
		);
	} elseif($type == 'portal_article') {
		list(,,, $id, $page, $extra) = func_get_args();
		$r = array(
			'{id}' => $id,
			'{page}' => $page ? $page : 1,
		);
	} elseif($type == 'forum_archiver') {
		list(,, $action, $value, $page, $extra) = func_get_args();
		$host = '';
		$r = array(
			'{action}' => $action,
			'{value}' => $value,
		);
		if($page) {
			$fextra = '?page='.$page;
		}
	}
	$href = str_replace(array_keys($r), $r, $_G['setting']['rewriterule'][$type]).$fextra;
	if(!$returntype) {
		return '<a href="'.$host.$href.'"'.(!empty($extra) ? stripslashes($extra) : '').'>';
	} else {
		return $host.$href;
	}
}

function mobilereplace($file, $replace) {
	global $_G;
	if(strpos($replace, 'mobile=') === false) {
		if(strpos($replace, '?') === false) {
			$replace = 'href="'.$file.$replace.'?mobile=yes"';
		} else {
			$replace = 'href="'.$file.$replace.'&mobile=yes"';
		}
		return $replace;
	} else {
		return 'href="'.$file.$replace.'"';
	}
}

function mobileoutput() {
	global $_G;
	if(!defined('TPL_DEFAULT')) {
		$content = ob_get_contents();
		ob_end_clean();
		$content = preg_replace("/href=\"(\w+\.php)(.*?)\"/e", "mobilereplace('\\1', '\\2')", $content);

		ob_start();
		$content = '<?xml version="1.0" encoding="utf-8"?>'.$content;
		if('utf-8' != CHARSET) {
			@header('Content-Type: text/html; charset=utf-8');
			$content = diconv($content, CHARSET, 'utf-8');
		}
		echo $content;
		exit();

	} elseif (defined('TPL_DEFAULT') && !$_G['cookie']['dismobilemessage'] && $_G['mobile']) {
		ob_end_clean();
		ob_start();
		$_G['forcemobilemessage'] = true;
		$query_sting_tmp = str_replace(array('&mobile=yes', 'mobile=yes'), array(''), $_SERVER['QUERY_STRING']);
		$_G['setting']['mobile']['pageurl'] = $_G['siteurl'].substr($_G['PHP_SELF'], 1).($query_sting_tmp ? '?'.$query_sting_tmp.'&mobile=no' : '?mobile=no' );
		unset($query_sting_tmp);
		dsetcookie('dismobilemessage', '1', 3600);
		showmessage('not_in_mobile');
		exit;
	}
}

function output() {

	global $_G;


	if(defined('DISCUZ_OUTPUTED')) {
		return;
	} else {
		define('DISCUZ_OUTPUTED', 1);
	}

	if(!empty($_G['blockupdate'])) {
		block_updatecache($_G['blockupdate']['bid']);
	}

	if(defined('IN_MOBILE')) {
		mobileoutput();
	}
	$havedomain = implode('', $_G['setting']['domain']['app']);
	if($_G['setting']['rewritestatus'] || !empty($havedomain)) {
		$content = ob_get_contents();
		$content = output_replace($content);


		ob_end_clean();
		$_G['gzipcompress'] ? ob_start('ob_gzhandler') : ob_start();

		echo $content;
	}
	if($_G['setting']['ftp']['connid']) {
		@ftp_close($_G['setting']['ftp']['connid']);
	}
	$_G['setting']['ftp'] = array();

	if(defined('CACHE_FILE') && CACHE_FILE && !defined('CACHE_FORBIDDEN') && !defined('IN_MOBILE')) {
		if(diskfreespace(DISCUZ_ROOT.'./'.$_G['setting']['cachethreaddir']) > 1000000) {
			if($fp = @fopen(CACHE_FILE, 'w')) {
				flock($fp, LOCK_EX);
				fwrite($fp, empty($content) ? ob_get_contents() : $content);
			}
			@fclose($fp);
			chmod(CACHE_FILE, 0777);
		}
	}

	if(defined('DISCUZ_DEBUG') && DISCUZ_DEBUG && @include(libfile('function/debug'))) {
		function_exists('debugmessage') && debugmessage();
	}
}

function output_replace($content) {
	global $_G;
	if(defined('IN_MODCP') || defined('IN_ADMINCP')) return $content;
	if(!empty($_G['setting']['output']['str']['search'])) {
		if(empty($_G['setting']['domain']['app']['default'])) {
			$_G['setting']['output']['str']['replace'] = str_replace('{CURHOST}', $_G['siteurl'], $_G['setting']['output']['str']['replace']);
		}
		$content = str_replace($_G['setting']['output']['str']['search'], $_G['setting']['output']['str']['replace'], $content);
	}
	if(!empty($_G['setting']['output']['preg']['search'])) {
		if(empty($_G['setting']['domain']['app']['default'])) {
			$_G['setting']['output']['preg']['search'] = str_replace('\{CURHOST\}', preg_quote($_G['siteurl'], '/'), $_G['setting']['output']['preg']['search']);
			$_G['setting']['output']['preg']['replace'] = str_replace('{CURHOST}', $_G['siteurl'], $_G['setting']['output']['preg']['replace']);
		}

		$content = preg_replace($_G['setting']['output']['preg']['search'], $_G['setting']['output']['preg']['replace'], $content);
	}

	return $content;
}

function output_ajax() {
	global $_G;
	$s = ob_get_contents();
	ob_end_clean();
	$s = preg_replace("/([\\x01-\\x08\\x0b-\\x0c\\x0e-\\x1f])+/", ' ', $s);
	$s = str_replace(array(chr(0), ']]>'), array(' ', ']]&gt;'), $s);
	if(defined('DISCUZ_DEBUG') && DISCUZ_DEBUG && @include(libfile('function/debug'))) {
		function_exists('debugmessage') && $s .= debugmessage(1);
	}
	$havedomain = implode('', $_G['setting']['domain']['app']);
	if($_G['setting']['rewritestatus'] || !empty($havedomain)) {
        $s = output_replace($s);
	}
	return $s;
}

function runhooks() {
	if(!defined('HOOKTYPE')) {
		define('HOOKTYPE', !defined('IN_MOBILE') ? 'hookscript' : 'hookscriptmobile');
	}
	if(defined('CURMODULE')) {
		global $_G;
		if($_G['setting']['plugins'][HOOKTYPE.'_common']) {
			hookscript('common', 'global', 'funcs', array(), 'common');
		}
		hookscript(CURMODULE, $_G['basescript']);
	}
}

function hookscript($script, $hscript, $type = 'funcs', $param = array(), $func = '') {
	global $_G;
	static $pluginclasses;
	if($hscript == 'home') {
		if($script != 'spacecp') {
			$script = 'space_'.(!empty($_G['gp_do']) ? $_G['gp_do'] : (!empty($_GET['do']) ? $_GET['do'] : ''));
		} else {
			$script .= !empty($_G['gp_ac']) ? '_'.$_G['gp_ac'] : (!empty($_GET['ac']) ? '_'.$_GET['ac'] : '');
		}
	}
	if(!isset($_G['setting'][HOOKTYPE][$hscript][$script][$type])) {
		return;
	}
	if(!isset($_G['cache']['plugin'])) {
		loadcache('plugin');
	}
	foreach((array)$_G['setting'][HOOKTYPE][$hscript][$script]['module'] as $identifier => $include) {
		$hooksadminid[$identifier] = !$_G['setting'][HOOKTYPE][$hscript][$script]['adminid'][$identifier] || ($_G['setting'][HOOKTYPE][$hscript][$script]['adminid'][$identifier] && $_G['adminid'] > 0 && $_G['setting']['hookscript'][$hscript][$script]['adminid'][$identifier] >= $_G['adminid']);
		if($hooksadminid[$identifier]) {
			@include_once DISCUZ_ROOT.'./source/plugin/'.$include.'.class.php';
		}
	}
	if(@is_array($_G['setting'][HOOKTYPE][$hscript][$script][$type])) {
		$_G['inhookscript'] = true;
		$funcs = !$func ? $_G['setting'][HOOKTYPE][$hscript][$script][$type] : array($func => $_G['setting'][HOOKTYPE][$hscript][$script][$type][$func]);
		foreach($funcs as $hookkey => $hookfuncs) {
			foreach($hookfuncs as $hookfunc) {
				if($hooksadminid[$hookfunc[0]]) {
					$classkey = (HOOKTYPE != 'hookscriptmobile' ? '' : 'mobile').'plugin_'.($hookfunc[0].($hscript != 'global' ? '_'.$hscript : ''));
					if(!class_exists($classkey)) {
						continue;
					}
					if(!isset($pluginclasses[$classkey])) {
						$pluginclasses[$classkey] = new $classkey;
					}
					if(!method_exists($pluginclasses[$classkey], $hookfunc[1])) {
						continue;
					}
					$return = $pluginclasses[$classkey]->$hookfunc[1]($param);

					if(is_array($return)) {
						if(!isset($_G['setting']['pluginhooks'][$hookkey]) || is_array($_G['setting']['pluginhooks'][$hookkey])) {
							foreach($return as $k => $v) {
								$_G['setting']['pluginhooks'][$hookkey][$k] .= $v;
							}
						}
					} else {
						if(!is_array($_G['setting']['pluginhooks'][$hookkey])) {
							$_G['setting']['pluginhooks'][$hookkey] .= $return;
						} else {
							foreach($_G['setting']['pluginhooks'][$hookkey] as $k => $v) {
								$_G['setting']['pluginhooks'][$hookkey][$k] .= $return;
							}
						}
					}
				}
			}
		}
	}
	$_G['inhookscript'] = false;
}

function hookscriptoutput($tplfile) {
	global $_G;
	if(!empty($_G['hookscriptoutput'])) {
		return;
	}
	if(!empty($_G['gp_mobiledata'])) {
		require_once libfile('class/mobiledata');
		$mobiledata = new mobiledata();
		if($mobiledata->validator()) {
			$mobiledata->outputvariables();
		}
	}
	hookscript('global', 'global');
	if(defined('CURMODULE')) {
		$param = array('template' => $tplfile, 'message' => $_G['hookscriptmessage'], 'values' => $_G['hookscriptvalues']);
		hookscript(CURMODULE, $_G['basescript'], 'outputfuncs', $param);
	}
	$_G['hookscriptoutput'] = true;
}

function pluginmodule($pluginid, $type) {
	global $_G;
	if(!isset($_G['cache']['plugin'])) {
		loadcache('plugin');
	}
	list($identifier, $module) = explode(':', $pluginid);
	if(!is_array($_G['setting']['plugins'][$type]) || !array_key_exists($pluginid, $_G['setting']['plugins'][$type])) {
		showmessage('plugin_nonexistence');
	}
	if(!empty($_G['setting']['plugins'][$type][$pluginid]['url'])) {
		dheader('location: '.$_G['setting']['plugins'][$type][$pluginid]['url']);
	}
	$directory = $_G['setting']['plugins'][$type][$pluginid]['directory'];
	if(empty($identifier) || !preg_match("/^[a-z]+[a-z0-9_]*\/$/i", $directory) || !preg_match("/^[a-z0-9_\-]+$/i", $module)) {
		showmessage('undefined_action');
	}
	if(@!file_exists(DISCUZ_ROOT.($modfile = './source/plugin/'.$directory.$module.'.inc.php'))) {
		showmessage('plugin_module_nonexistence', '', array('mod' => $modfile));
	}
	return DISCUZ_ROOT.$modfile;
}
function updatecreditbyaction($action, $uid = 0, $extrasql = array(), $needle = '', $coef = 1, $update = 1, $fid = 0) {

	include_once libfile('class/credit');
	$credit = & credit::instance();
	if($extrasql) {
		$credit->extrasql = $extrasql;
	}
	return $credit->execrule($action, $uid, $needle, $coef, $update, $fid);
}

function checklowerlimit($action, $uid = 0, $coef = 1, $fid = 0, $returnonly = 0) {
	require_once libfile('function/credit');
	return _checklowerlimit($action, $uid, $coef, $fid, $returnonly);
}

function batchupdatecredit($action, $uids = 0, $extrasql = array(), $coef = 1, $fid = 0) {

	include_once libfile('class/credit');
	$credit = & credit::instance();
	if($extrasql) {
		$credit->extrasql = $extrasql;
	}
	return $credit->updatecreditbyrule($action, $uids, $coef, $fid);
}


function updatemembercount($uids, $dataarr = array(), $checkgroup = true, $operation = '', $relatedid = 0, $ruletxt = '') {
	if(!empty($uids) && (is_array($dataarr) && $dataarr)) {
		require_once libfile('function/credit');
		return _updatemembercount($uids, $dataarr, $checkgroup, $operation, $relatedid, $ruletxt);
	}
	return true;
}

function checkusergroup($uid = 0) {
	require_once libfile('class/credit');
	$credit = & credit::instance();
	$credit->checkusergroup($uid);
}

function checkformulasyntax($formula, $operators, $tokens) {
	$var = implode('|', $tokens);
	$operator = implode('', $operators);

	$operator = str_replace(
		array('+', '-', '*', '/', '(', ')', '{', '}', '\''),
		array('\+', '\-', '\*', '\/', '\(', '\)', '\{', '\}', '\\\''),
		$operator
	);

	if(!empty($formula)) {
		if(!preg_match("/^([$operator\.\d\(\)]|(($var)([$operator\(\)]|$)+))+$/", $formula) || !is_null(eval(preg_replace("/($var)/", "\$\\1", $formula).';'))){
			return false;
		}
	}
	return true;
}

function checkformulacredits($formula) {
	return checkformulasyntax(
		$formula,
		array('+', '-', '*', '/', ' '),
		array('extcredits[1-8]', 'digestposts', 'posts', 'threads', 'oltime', 'friends', 'doings', 'polls', 'blogs', 'albums', 'sharings')
	);
}

function debug($var = null, $vardump = false) {
	echo '<pre>';
	if($var === null) {
		print_r($GLOBALS);
	} else {
		if($vardump) {
			var_dump($var);
		} else {
			print_r($var);
		}
	}
	exit();
}

function debuginfo() {
	global $_G;
	if(getglobal('setting/debug')) {
		$db = & DB::object();
		$_G['debuginfo'] = array(
		    'time' => number_format((dmicrotime() - $_G['starttime']), 6),
		    'queries' => $db->querynum,
		    'memory' => ucwords($_G['memory'])
		    );
		if($db->slaveid) {
			$_G['debuginfo']['queries'] = 'Total '.$db->querynum.', Slave '.$db->slavequery;
		}
		return TRUE;
	} else {
		return FALSE;
	}
}

function getfocus_rand($module) {
	global $_G;

	if(empty($_G['setting']['focus']) || !array_key_exists($module, $_G['setting']['focus'])) {
		return null;
	}
	do {
		$focusid = $_G['setting']['focus'][$module][array_rand($_G['setting']['focus'][$module])];
		if(!empty($_G['cookie']['nofocus_'.$focusid])) {
			unset($_G['setting']['focus'][$module][$focusid]);
			$continue = 1;
		} else {
			$continue = 0;
		}
	} while(!empty($_G['setting']['focus'][$module]) && $continue);
	if(!$_G['setting']['focus'][$module]) {
		return null;
	}
	loadcache('focus');
	if(empty($_G['cache']['focus']['data']) || !is_array($_G['cache']['focus']['data'])) {
		return null;
	}
	return $focusid;
}

function check_seccode($value, $idhash) {
	global $_G;
	if(!$_G['setting']['seccodestatus']) {
		return true;
	}
	if(!isset($_G['cookie']['seccode'.$idhash])) {
		return false;
	}
	list($checkvalue, $checktime, $checkidhash, $checkformhash) = explode("\t", authcode($_G['cookie']['seccode'.$idhash], 'DECODE', $_G['config']['security']['authkey']));
	return $checkvalue == strtoupper($value) && TIMESTAMP - 180 > $checktime && $checkidhash == $idhash && FORMHASH == $checkformhash;
}

function check_secqaa($value, $idhash) {
	global $_G;
	if(!$_G['setting']['secqaa']) {
		return true;
	}
	if(!isset($_G['cookie']['secqaa'.$idhash])) {
		return false;
	}
	loadcache('secqaa');
	list($checkvalue, $checktime, $checkidhash, $checkformhash) = explode("\t", authcode($_G['cookie']['secqaa'.$idhash], 'DECODE', $_G['config']['security']['authkey']));
	return $checkvalue == md5($value) && TIMESTAMP - 180 > $checktime && $checkidhash == $idhash && FORMHASH == $checkformhash;
}

function adshow($parameter) {
	global $_G;
	if($_G['inajax']) {
		return;
	}
	$params = explode('/', $parameter);
	$customid = 0;
	$customc = explode('_', $params[0]);
	if($customc[0] == 'custom') {
		$params[0] = $customc[0];
		$customid = $customc[1];
	}
	$adcontent = null;
	if(empty($_G['setting']['advtype']) || !in_array($params[0], $_G['setting']['advtype'])) {
		$adcontent = '';
	}
	if($adcontent === null) {
		loadcache('advs');
		$adids = array();
		$evalcode = &$_G['cache']['advs']['evalcode'][$params[0]];
		$parameters = &$_G['cache']['advs']['parameters'][$params[0]];
		$codes = &$_G['cache']['advs']['code'][$_G['basescript']][$params[0]];
		if(!empty($codes)) {
			foreach($codes as $adid => $code) {
				$parameter = &$parameters[$adid];
				$checked = true;
				@eval($evalcode['check']);
				if($checked) {
					$adids[] = $adid;
				}
			}
			if(!empty($adids)) {
				$adcode = $extra = '';
				@eval($evalcode['create']);
				if(empty($notag)) {
					$adcontent = '<div'.($params[1] != '' ? ' class="'.$params[1].'"' : '').$extra.'>'.$adcode.'</div>';
				} else {
					$adcontent = $adcode;
				}
			}
		}
	}
	$adfunc = 'ad_'.$params[0];
	$_G['setting']['pluginhooks'][$adfunc] = null;
	hookscript('ad', 'global', 'funcs', array('params' => $params, 'content' => $adcontent), $adfunc);
	hookscript('ad', $_G['basescript'], 'funcs', array('params' => $params, 'content' => $adcontent), $adfunc);
	return $_G['setting']['pluginhooks'][$adfunc] === null ? $adcontent : $_G['setting']['pluginhooks'][$adfunc];
}

function showmessage($message, $url_forward = '', $values = array(), $extraparam = array(), $custom = 0) {
	require_once libfile('function/message');
	return dshowmessage($message, $url_forward, $values, $extraparam, $custom);
}

function submitcheck($var, $allowget = 0, $seccodecheck = 0, $secqaacheck = 0) {
	if(!getgpc($var)) {
		return FALSE;
	} else {
		global $_G;
		if(!empty($_G['gp_mobiledata'])) {
			require_once libfile('class/mobiledata');
			$mobiledata = new mobiledata();
			if($mobiledata->validator()) {
				return TRUE;
			}
		}
		if($allowget || ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_G['gp_formhash']) && $_G['gp_formhash'] == formhash() && empty($_SERVER['HTTP_X_FLASH_VERSION']) && (empty($_SERVER['HTTP_REFERER']) ||
		preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])))) {
			if(checkperm('seccode')) {
				if($secqaacheck && !check_secqaa($_G['gp_secanswer'], $_G['gp_sechash'])) {
					showmessage('submit_secqaa_invalid');
				}
				if($seccodecheck && !check_seccode($_G['gp_seccodeverify'], $_G['gp_sechash'])) {
					showmessage('submit_seccode_invalid');
				}
			}
			return TRUE;
		} else {
			showmessage('submit_invalid');
		}
	}
}

function multi($num, $perpage, $curpage, $mpurl, $maxpages = 0, $page = 10, $autogoto = FALSE, $simple = FALSE) {
	global $_G;
	$ajaxtarget = !empty($_G['gp_ajaxtarget']) ? " ajaxtarget=\"".htmlspecialchars($_G['gp_ajaxtarget'])."\" " : '';

	$a_name = '';
	if(strpos($mpurl, '#') !== FALSE) {
		$a_strs = explode('#', $mpurl);
		$mpurl = $a_strs[0];
		$a_name = '#'.$a_strs[1];
	}

	if(defined('IN_ADMINCP')) {
		$shownum = $showkbd = TRUE;
		$lang['prev'] = '&lsaquo;&lsaquo;';
		$lang['next'] = '&rsaquo;&rsaquo;';
	} else {
		$shownum = $showkbd = FALSE;
		if(defined('IN_MOBILE') && !defined('TPL_DEFAULT')) {
			$lang['prev'] = lang('core', 'prevpage');
			$lang['next'] = lang('core', 'nextpage');
		} else {
			$lang['prev'] = '&nbsp;&nbsp;';
			$lang['next'] = lang('core', 'nextpage');
		}
	}
	if(defined('IN_MOBILE') && !defined('TPL_DEFAULT')) {
		$dot = '..';
		$page = intval($page) < 10 && intval($page) > 0 ? $page : 4 ;
	} else {
		$dot = '...';
	}
	$multipage = '';
	$mpurl .= strpos($mpurl, '?') !== FALSE ? '&amp;' : '?';

	$realpages = 1;
	$_G['page_next'] = 0;
	$page -= strlen($curpage) - 1;
	if($page <= 0) {
		$page = 1;
	}
	if($num > $perpage) {

		$offset = floor($page * 0.5);

		$realpages = @ceil($num / $perpage);
		$pages = $maxpages && $maxpages < $realpages ? $maxpages : $realpages;

		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if($to - $from < $page) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}
		$_G['page_next'] = $to;
		$multipage = ($curpage - $offset > 1 && $pages > $page ? '<a href="'.$mpurl.'page=1'.$a_name.'" class="first"'.$ajaxtarget.'>1 '.$dot.'</a>' : '').
		($curpage > 1 && !$simple ? '<a href="'.$mpurl.'page='.($curpage - 1).$a_name.'" class="prev"'.$ajaxtarget.'>'.$lang['prev'].'</a>' : '');
		for($i = $from; $i <= $to; $i++) {
			$multipage .= $i == $curpage ? '<strong>'.$i.'</strong>' :
			'<a href="'.$mpurl.'page='.$i.($ajaxtarget && $i == $pages && $autogoto ? '#' : $a_name).'"'.$ajaxtarget.'>'.$i.'</a>';
		}
		$multipage .= ($to < $pages ? '<a href="'.$mpurl.'page='.$pages.$a_name.'" class="last"'.$ajaxtarget.'>'.$dot.' '.$realpages.'</a>' : '').
		($curpage < $pages && !$simple ? '<a href="'.$mpurl.'page='.($curpage + 1).$a_name.'" class="nxt"'.$ajaxtarget.'>'.$lang['next'].'</a>' : '').
		($showkbd && !$simple && $pages > $page && !$ajaxtarget ? '<kbd><input type="text" name="custompage" size="3" onkeydown="if(event.keyCode==13) {window.location=\''.$mpurl.'page=\'+this.value; doane(event);}" /></kbd>' : '');

		$multipage = $multipage ? '<div class="pg">'.($shownum && !$simple ? '<em>&nbsp;'.$num.'&nbsp;</em>' : '').$multipage.'</div>' : '';
	}
	$maxpage = $realpages;
	return $multipage;
}

function simplepage($num, $perpage, $curpage, $mpurl) {
	$return = '';
	$lang['next'] = lang('core', 'nextpage');
	$lang['prev'] = lang('core', 'prevpage');
	$next = $num == $perpage ? '<a href="'.$mpurl.'&amp;page='.($curpage + 1).'" class="nxt">'.$lang['next'].'</a>' : '';
	$prev = $curpage > 1 ? '<span class="pgb"><a href="'.$mpurl.'&amp;page='.($curpage - 1).'">'.$lang['prev'].'</a></span>' : '';
	if($next || $prev) {
		$return = '<div class="pg">'.$prev.$next.'</div>';
	}
	return $return;
}

function censor($message, $modword = NULL, $return = FALSE) {
	global $_G;
	require_once libfile('class/censor');
	$censor = discuz_censor::instance();
	$censor->check($message, $modword);
	if($censor->modbanned()) {
		$wordbanned = implode(', ', $censor->words_found);
		if($return) {
			return array('message' => lang('message', 'word_banned', array('wordbanned' => $wordbanned)));
		}
		if(!defined('IN_ADMINCP')) {
			showmessage('word_banned', '', array('wordbanned' => $wordbanned));
		} else {
			cpmsg(lang('message', 'word_banned'), '', 'error', array('wordbanned' => $wordbanned));
		}
	}
	if($_G['group']['allowposturl'] == 0 || $_G['group']['allowposturl'] == 2) {
		$urllist = get_url_list($message);
		if(is_array($urllist[1])) foreach($urllist[1] as $key => $val) {
			if(!$val = trim($val)) continue;
			if(!iswhitelist($val)) {
				if($_G['group']['allowposturl'] == 0) {
					showmessage('post_url_nopermission');
				} elseif($_G['group']['allowposturl'] == 2) {
					$message = str_replace('[url]'.$urllist[0][$key].'[/url]', $urllist[0][$key], $message);
					$message = preg_replace(
						array(
							"@\[url=".preg_quote($urllist[0][$key],'@')."\](.*?)\[/url\]@i",
							"@href=('|\")".preg_quote($urllist[0][$key],'@')."\\1@i",
							"@\[url\](.*?".preg_quote($urllist[0][$key],'@').".*?)\[/url\]@i",
						),
						array(
							'\\1',
							'',
							'\\1',
						),
						$message);
				}
			}
		}
	}
	return $message;
}

function censormod($message) {
	global $_G;
	if($_G['group']['ignorecensor']) {
		return false;
	}
	$modposturl = false;
	if($_G['group']['allowposturl'] == 1) {
		$urllist = get_url_list($message);
		if(is_array($urllist[1])) foreach($urllist[1] as $key => $val) {
			if(!$val = trim($val)) continue;
			if(!iswhitelist($val)) {
				$modposturl = true;
			}
		}
	}
	if($modposturl) {
		return true;
	}

	require_once libfile('class/censor');
	$censor = discuz_censor::instance();
	$censor->check($message);
	return $censor->modmoderated();
}

function space_merge(&$values, $tablename) {
	global $_G;

	$uid = empty($values['uid'])?$_G['uid']:$values['uid'];
	$var = "member_{$uid}_{$tablename}";
	if($uid) {
		if(!isset($_G[$var])) {
			$query = DB::query("SELECT * FROM ".DB::table('common_member_'.$tablename)." WHERE uid='$uid'");
			if($_G[$var] = DB::fetch($query)) {
				if($tablename == 'field_home') {
					$_G['setting']['privacy'] = empty($_G['setting']['privacy']) ? array() : (is_array($_G['setting']['privacy']) ? $_G['setting']['privacy'] : unserialize($_G['setting']['privacy']));
					$_G[$var]['privacy'] = empty($_G[$var]['privacy'])? array() : is_array($_G[$var]['privacy']) ? $_G[$var]['privacy'] : unserialize($_G[$var]['privacy']);
					foreach (array('feed','view','profile') as $pkey) {
						if(empty($_G[$var]['privacy'][$pkey]) && !isset($_G[$var]['privacy'][$pkey])) {
							$_G[$var]['privacy'][$pkey] = isset($_G['setting']['privacy'][$pkey]) ? $_G['setting']['privacy'][$pkey] : array();
						}
					}
					$_G[$var]['acceptemail'] = empty($_G[$var]['acceptemail'])? array() : unserialize($_G[$var]['acceptemail']);
					if(empty($_G[$var]['acceptemail'])) {
						$_G[$var]['acceptemail'] = empty($_G['setting']['acceptemail'])?array():unserialize($_G['setting']['acceptemail']);
					}
				}
			} else {
				DB::insert('common_member_'.$tablename, array('uid'=>$uid));
				$_G[$var] = array();
			}
		}
		$values = array_merge($values, $_G[$var]);
	}
}

function runlog($file, $message, $halt=0) {
	global $_G;

	$nowurl = $_SERVER['REQUEST_URI']?$_SERVER['REQUEST_URI']:($_SERVER['PHP_SELF']?$_SERVER['PHP_SELF']:$_SERVER['SCRIPT_NAME']);
	$log = dgmdate($_G['timestamp'], 'Y-m-d H:i:s')."\t".$_G['clientip']."\t$_G[uid]\t{$nowurl}\t".str_replace(array("\r", "\n"), array(' ', ' '), trim($message))."\n";
	writelog($file, $log);
	if($halt) {
		exit();
	}
}

function stripsearchkey($string) {
	$string = trim($string);
	$string = str_replace('*', '%', addcslashes($string, '%_'));
	$string = str_replace('_', '\_', $string);
	return $string;
}

function dmkdir($dir, $mode = 0777, $makeindex = TRUE){
	if(!is_dir($dir)) {
		dmkdir(dirname($dir));
		@mkdir($dir, $mode);
		if(!empty($makeindex)) {
			@touch($dir.'/index.html'); @chmod($dir.'/index.html', 0777);
		}
	}
	return true;
}

function dreferer($default = '') {
	global $_G;

	$default = empty($default) ? $GLOBALS['_t_curapp'] : '';
	$_G['referer'] = !empty($_G['gp_referer']) ? $_G['gp_referer'] : $_SERVER['HTTP_REFERER'];
	$_G['referer'] = substr($_G['referer'], -1) == '?' ? substr($_G['referer'], 0, -1) : $_G['referer'];

	if(strpos($_G['referer'], 'member.php?mod=logging')) {
		$_G['referer'] = $default;
	}
	$_G['referer'] = htmlspecialchars($_G['referer'], ENT_QUOTES);
	$_G['referer'] = str_replace('&amp;', '&', $_G['referer']);
	$reurl = parse_url($_G['referer']);
	if(!empty($reurl['host']) && !in_array($reurl['host'], array($_SERVER['HTTP_HOST'], 'www.'.$_SERVER['HTTP_HOST'])) && !in_array($_SERVER['HTTP_HOST'], array($reurl['host'], 'www.'.$reurl['host']))) {
		if(!in_array($reurl['host'], $_G['setting']['domain']['app']) && !isset($_G['setting']['domain']['list'][$reurl['host']])) {
			$domainroot = substr($_SERVER['HTTP_HOST'], strpos($_SERVER['HTTP_HOST'], '.')+1);
			if(empty($_G['setting']['domain']['root']) || (is_array($_G['setting']['domain']['root']) && !in_array($domainroot, $_G['setting']['domain']['root']))) {
				$_G['referer'] = $_G['setting']['domain']['defaultindex'] ? $_G['setting']['domain']['defaultindex'] : 'index.php';
			}
		}
	} elseif(empty($reurl['host'])) {
		$_G['referer'] = $_G['siteurl'].'./'.$_G['referer'];
	}
	return strip_tags($_G['referer']);
}

function ftpcmd($cmd, $arg1 = '') {
	static $ftp;
	$ftpon = getglobal('setting/ftp/on');
	if(!$ftpon) {
		return $cmd == 'error' ? -101 : 0;
	} elseif($ftp == null) {
		require_once libfile('class/ftp');
		$ftp = & discuz_ftp::instance();
	}
	if(!$ftp->enabled) {
		return $ftp->error();
	} elseif($ftp->enabled && !$ftp->connectid) {
		$ftp->connect();
	}
	switch ($cmd) {
		case 'upload' : return $ftp->upload(getglobal('setting/attachdir').'/'.$arg1, $arg1); break;
		case 'delete' : return $ftp->ftp_delete($arg1); break;
		case 'close'  : return $ftp->ftp_close(); break;
		case 'error'  : return $ftp->error(); break;
		case 'object' : return $ftp; break;
		default       : return false;
	}

}

function diconv($str, $in_charset, $out_charset = CHARSET, $ForceTable = FALSE) {
	global $_G;

	$in_charset = strtoupper($in_charset);
	$out_charset = strtoupper($out_charset);

	if(empty($str) || $in_charset == $out_charset) {
		return $str;
	}

	$out = '';

	if(!$ForceTable) {
		if(function_exists('iconv')) {
			$out = iconv($in_charset, $out_charset.'//IGNORE', $str);
		} elseif(function_exists('mb_convert_encoding')) {
			$out = mb_convert_encoding($str, $out_charset, $in_charset);
		}
	}

	if($out == '') {
		require_once libfile('class/chinese');
		$chinese = new Chinese($in_charset, $out_charset, true);
		$out = $chinese->Convert($str);
	}

	return $out;
}

function renum($array) {
	$newnums = $nums = array();
	foreach ($array as $id => $num) {
		$newnums[$num][] = $id;
		$nums[$num] = $num;
	}
	return array($nums, $newnums);
}

function getonlinenum($fid = 0, $tid = 0) {
	if($fid) {
		$sql = " AND fid='$fid'";
	}
	if($tid) {
		$sql = " AND tid='$tid'";
	}
	return DB::result_first('SELECT count(*) FROM '.DB::table("common_session")." WHERE 1 $sql");
}

function sizecount($size) {
	if($size >= 1073741824) {
		$size = round($size / 1073741824 * 100) / 100 . ' GB';
	} elseif($size >= 1048576) {
		$size = round($size / 1048576 * 100) / 100 . ' MB';
	} elseif($size >= 1024) {
		$size = round($size / 1024 * 100) / 100 . ' KB';
	} else {
		$size = $size . ' Bytes';
	}
	return $size;
}

function swapclass($class1, $class2 = '') {
	static $swapc = null;
	$swapc = isset($swapc) && $swapc != $class1 ? $class1 : $class2;
	return $swapc;
}

function writelog($file, $log) {
	global $_G;
	$yearmonth = dgmdate(TIMESTAMP, 'Ym', $_G['setting']['timeoffset']);
	$logdir = DISCUZ_ROOT.'./data/log/';
	$logfile = $logdir.$yearmonth.'_'.$file.'.php';
	if(@filesize($logfile) > 2048000) {
		$dir = opendir($logdir);
		$length = strlen($file);
		$maxid = $id = 0;
		while($entry = readdir($dir)) {
			if(strpos($entry, $yearmonth.'_'.$file) !== false) {
				$id = intval(substr($entry, $length + 8, -4));
				$id > $maxid && $maxid = $id;
			}
		}
		closedir($dir);

		$logfilebak = $logdir.$yearmonth.'_'.$file.'_'.($maxid + 1).'.php';
		@rename($logfile, $logfilebak);
	}
	if($fp = @fopen($logfile, 'a')) {
		@flock($fp, 2);
		$log = is_array($log) ? $log : array($log);
		foreach($log as $tmp) {
			fwrite($fp, "<?PHP exit;?>\t".str_replace(array('<?', '?>'), '', $tmp)."\n");
		}
		fclose($fp);
	}
}
function getcolorpalette($colorid, $id, $background, $fun = '') {
	return "<input id=\"c$colorid\" onclick=\"c{$colorid}_frame.location='static/image/admincp/getcolor.htm?c{$colorid}|{$id}|{$fun}';showMenu({'ctrlid':'c$colorid'})\" type=\"button\" class=\"colorwd\" value=\"\" style=\"background: $background\"><span id=\"c{$colorid}_menu\" style=\"display: none\"><iframe name=\"c{$colorid}_frame\" src=\"\" frameborder=\"0\" width=\"210\" height=\"148\" scrolling=\"no\"></iframe></span>";
}

function getstatus($status, $position) {
	$t = $status & pow(2, $position - 1) ? 1 : 0;
	return $t;
}

function setstatus($position, $value, $baseon = null) {
	$t = pow(2, $position - 1);
	if($value) {
		$t = $baseon | $t;
	} elseif ($baseon !== null) {
		$t = $baseon & ~$t;
	} else {
		$t = ~$t;
	}
	return $t & 0xFFFF;
}

function notification_add($touid, $type, $note, $notevars = array(), $system = 0) {
	global $_G;

	$tospace = array('uid'=>$touid);
	space_merge($tospace, 'field_home');
	$filter = empty($tospace['privacy']['filter_note'])?array():array_keys($tospace['privacy']['filter_note']);

	if($filter && (in_array($type.'|0', $filter) || in_array($type.'|'.$_G['uid'], $filter))) {
		return false;
	}

	$notevars['actor'] = "<a href=\"home.php?mod=space&uid=$_G[uid]\">".$_G['member']['username']."</a>";
	if(!is_numeric($type)) {
		$vars = explode(':', $note);
		if(count($vars) == 2) {
			$notestring = lang('plugin/'.$vars[0], $vars[1], $notevars);
		} else {
			$notestring = lang('notification', $note, $notevars);
		}
		$frommyapp = false;
	} else {
		$frommyapp = true;
		$notestring = $note;
	}

	$oldnote = array();
	if($notevars['from_id'] && $notevars['from_idtype']) {
		$oldnote = DB::fetch_first("SELECT * FROM ".DB::table('home_notification')."
			WHERE from_id='$notevars[from_id]' AND from_idtype='$notevars[from_idtype]' AND uid='$touid'");
	}
	if(empty($oldnote['from_num'])) $oldnote['from_num'] = 0;
	$notevars['from_num'] = $notevars['from_num'] ? $notevars['from_num'] : 1;
	$setarr = array(
		'uid' => $touid,
		'type' => $type,
		'new' => 1,
		'authorid' => $_G['uid'],
		'author' => $_G['username'],
		'note' => addslashes($notestring),
		'dateline' => $_G['timestamp'],
		'from_id' => $notevars['from_id'],
		'from_idtype' => $notevars['from_idtype'],
		'from_num' => ($oldnote['from_num']+$notevars['from_num'])
	);
	if($system) {
		$setarr['authorid'] = 0;
		$setarr['author'] = '';
	}

	if($oldnote['id']) {
		DB::update('home_notification', $setarr, array('id'=>$oldnote['id']));
	} else {
		$oldnote['new'] = 0;
		DB::insert('home_notification', $setarr);
	}

	if(empty($oldnote['new'])) {
		DB::query("UPDATE ".DB::table('common_member')." SET newprompt=newprompt+1 WHERE uid='$touid'");

		require_once libfile('function/mail');
		$mail_subject = lang('notification', 'mail_to_user');
		sendmail_touser($touid, $mail_subject, $notestring, $frommyapp ? 'myapp' : $type);
	}

	if(!$system && $_G['uid'] && $touid != $_G['uid']) {
		DB::query("UPDATE ".DB::table('home_friend')." SET num=num+1 WHERE uid='$_G[uid]' AND fuid='$touid'");
	}
}

function manage_addnotify($type, $from_num = 0, $langvar = array()) {
	global $_G;
	$notifyusers = unserialize($_G['setting']['notifyusers']);
	$notifytypes = explode(',', $_G['setting']['adminnotifytypes']);
	$notifytypes = array_flip($notifytypes);
	$notearr = array('from_id' => 1,'from_idtype' => $type, 'from_num' => $from_num);
	if($langvar) {
		$langkey = $langvar['langkey'];
		$notearr = array_merge($notearr, $langvar);
	} else {
		$langkey = 'manage_'.$type;
	}
	foreach($notifyusers as $uid => $user) {
		if($user['types'][$notifytypes[$type]]) {
			notification_add($uid, $type, $langkey, $notearr, 1);
		}
	}
}

function sendpm($toid, $subject, $message, $fromid = '', $replypmid = 0, $isusername = 0, $type = 0) {
	global $_G;
	if($fromid === '') {
		$fromid = $_G['uid'];
	}
	loaducenter();
	return uc_pm_send($fromid, $toid, $subject, $message, 1, $replypmid, $isusername, $type);
}

function g_icon($groupid, $return = 0) {
	global $_G;
	if(empty($_G['cache']['usergroups'][$groupid]['icon'])) {
		$s =  '';
	} else {
		if(substr($_G['cache']['usergroups'][$groupid]['icon'], 0, 5) == 'http:') {
			$s = '<img src="'.$_G['cache']['usergroups'][$groupid]['icon'].'" alt="" class="vm" />';
		} else {
			$s = '<img src="'.$_G['setting']['attachurl'].'common/'.$_G['cache']['usergroups'][$groupid]['icon'].'" alt="" class="vm" />';
		}
	}
	if($return) {
		return $s;
	} else {
		echo $s;
	}
}
function updatediytemplate($targettplname = '') {
	global $_G;
	$r = false;
	$where = empty($targettplname) ? '' : " WHERE targettplname='$targettplname'";
	$query = DB::query("SELECT * FROM ".DB::table('common_diy_data')."$where");
	require_once libfile('function/portalcp');
	while($value = DB::fetch($query)) {
		$r = save_diy_data($value['primaltplname'], $value['targettplname'], unserialize($value['diycontent']));
	}
	return $r;
}

function space_key($uid, $appid=0) {
	global $_G;

	$siteuniqueid = DB::result_first("SELECT svalue FROM ".DB::table('common_setting')." WHERE skey='siteuniqueid'");
	return substr(md5($siteuniqueid.'|'.$uid.(empty($appid)?'':'|'.$appid)), 8, 16);
}


function getposttablebytid($tids, $primary = 0) {
	global $_G;

	$isstring = false;
	if(!is_array($tids)) {
		$tids = array(intval($tids));
		$isstring = true;
	}
	$tids = array_unique($tids);
	$tids = array_flip($tids);
	if(!$primary) {
		loadcache('threadtableids');
		$threadtableids = !empty($_G['cache']['threadtableids']) ? $_G['cache']['threadtableids'] : array();
		if(!in_array(0, $threadtableids)) {
			$threadtableids = array_merge(array(0), $threadtableids);
		}
	} else {
		$threadtableids = array(0);
	}
	$tables = array();
	$posttable = '';
	$singletable = count($tids) > 1 ? false : true;
	foreach($threadtableids as $tableid) {
		$threadtable = $tableid ? "forum_thread_$tableid" : 'forum_thread';
		$query = DB::query("SELECT tid, posttableid FROM ".DB::table($threadtable)." WHERE tid IN(".dimplode(array_keys($tids)).")");
		while ($value = DB::fetch($query)) {
			$posttable = 'forum_post'.($value['posttableid'] ? "_$value[posttableid]" : '');
			$tables[$posttable][$value['tid']] = $value['tid'];
			unset($tids[$value['tid']]);
		}
		if(!count($tids)) {
			break;
		}
	}
	if(empty($posttable)) {
		$posttable = 'forum_post';
		$tables[$posttable] = array_flip($tids);
	}
	return $isstring ? $posttable : $tables;
}

function getposttable($tableid = 0, $prefix = false) {
	global $_G;
	$tableid = intval($tableid);
	if($tableid) {
		loadcache('posttableids');
		$tableid = $_G['cache']['posttableids'] && in_array($tableid, $_G['cache']['posttableids']) ? $tableid : 0;
		$tablename = 'forum_post'.($tableid ? "_$tableid" : '');
	} else {
		$tablename = 'forum_post';
	}
	if($prefix) {
		$tablename = DB::table($tablename);
	}
	return $tablename;
}

function memory($cmd, $key='', $value='', $ttl = 0) {
	$discuz = & discuz_core::instance();
	if($cmd == 'check') {
		return  $discuz->mem->enable ? $discuz->mem->type : '';
	} elseif($discuz->mem->enable && in_array($cmd, array('set', 'get', 'rm'))) {
		switch ($cmd) {
			case 'set': return $discuz->mem->set($key, $value, $ttl); break;
			case 'get': return $discuz->mem->get($key); break;
			case 'rm': return $discuz->mem->rm($key); break;
		}
	}
	return null;
}

function ipaccess($ip, $accesslist) {
	return preg_match("/^(".str_replace(array("\r\n", ' '), array('|', ''), preg_quote($accesslist, '/')).")/", $ip);
}

function ipbanned($onlineip) {
	global $_G;

	if($_G['setting']['ipaccess'] && !ipaccess($onlineip, $_G['setting']['ipaccess'])) {
		return TRUE;
	}

	loadcache('ipbanned');
	if(empty($_G['cache']['ipbanned'])) {
		return FALSE;
	} else {
		if($_G['cache']['ipbanned']['expiration'] < TIMESTAMP) {
			require_once libfile('function/cache');
			updatecache('ipbanned');
		}
		return preg_match("/^(".$_G['cache']['ipbanned']['regexp'].")$/", $onlineip);
	}
}

function getcount($tablename, $condition) {
	if(empty($condition)) {
		$where = '1';
	} elseif(is_array($condition)) {
		$where = DB::implode_field_value($condition, ' AND ');
	} else {
		$where = $condition;
	}
	$ret = intval(DB::result_first("SELECT COUNT(*) AS num FROM ".DB::table($tablename)." WHERE $where"));
	return $ret;
}

function sysmessage($message) {
	require libfile('function/sysmessage');
	show_system_message($message);
}

function forumperm($permstr, $groupid = 0) {
	global $_G;

	$groupidarray = array($_G['groupid']);
	if($groupid) {
		return preg_match("/(^|\t)(".$groupid.")(\t|$)/", $permstr);
	}
	foreach(explode("\t", $_G['member']['extgroupids']) as $extgroupid) {
		if($extgroupid = intval(trim($extgroupid))) {
			$groupidarray[] = $extgroupid;
		}
	}
	if($_G['setting']['verify']['enabled']) {
		getuserprofile('verify1');
		foreach($_G['setting']['verify'] as $vid => $verify) {
			if($verify['available'] && $_G['member']['verify'.$vid] == 1) {
				$groupidarray[] = 'v'.$vid;
			}
		}
	}
	return preg_match("/(^|\t)(".implode('|', $groupidarray).")(\t|$)/", $permstr);
}

if(!function_exists('file_put_contents')) {
	if(!defined('FILE_APPEND')) define('FILE_APPEND', 8);
	function file_put_contents($filename, $data, $flag = 0) {
		$return = false;
		if($fp = @fopen($filename, $flag != FILE_APPEND ? 'w' : 'a')) {
			if($flag == LOCK_EX) @flock($fp, LOCK_EX);
			$return = fwrite($fp, is_array($data) ? implode('', $data) : $data);
			fclose($fp);
		}
		return $return;
	}
}

function checkperm($perm) {
	global $_G;
	return (empty($_G['group'][$perm])?'':$_G['group'][$perm]);
}

function periodscheck($periods, $showmessage = 1) {
	global $_G;

	if(!$_G['group']['disableperiodctrl'] && $_G['setting'][$periods]) {
		$now = dgmdate(TIMESTAMP, 'G.i');
		foreach(explode("\r\n", str_replace(':', '.', $_G['setting'][$periods])) as $period) {
			list($periodbegin, $periodend) = explode('-', $period);
			if(($periodbegin > $periodend && ($now >= $periodbegin || $now < $periodend)) || ($periodbegin < $periodend && $now >= $periodbegin && $now < $periodend)) {
				$banperiods = str_replace("\r\n", ', ', $_G['setting'][$periods]);
				if($showmessage) {
					showmessage('period_nopermission', NULL, array('banperiods' => $banperiods), array('login' => 1));
				} else {
					return TRUE;
				}
			}
		}
	}
	return FALSE;
}

function cknewuser($return=0) {
	global $_G;

	$result = true;

	if(!$_G['uid']) return true;

	if(checkperm('disablepostctrl')) {
		return $result;
	}
	$ckuser = $_G['member'];

	if($_G['setting']['newbiespan'] && $_G['timestamp']-$ckuser['regdate']<$_G['setting']['newbiespan']*60) {
		if(empty($return)) showmessage('no_privilege_newbiespan', '', array('newbiespan' => $_G['setting']['newbiespan']), array('return' => true));
		$result = false;
	}
	if($_G['setting']['need_avatar'] && empty($ckuser['avatarstatus'])) {
		if(empty($return)) showmessage('no_privilege_avatar', '', array(), array('return' => true));
		$result = false;
	}
	if($_G['setting']['need_email'] && empty($ckuser['emailstatus'])) {
		if(empty($return)) showmessage('no_privilege_email', '', array(), array('return' => true));
		$result = false;
	}
	if($_G['setting']['need_friendnum']) {
		space_merge($ckuser, 'count');
		if($ckuser['friends'] < $_G['setting']['need_friendnum']) {
			if(empty($return)) showmessage('no_privilege_friendnum', '', array('friendnum' => $_G['setting']['need_friendnum']), array('return' => true));
			$result = false;
		}
	}
	return $result;
}

function manyoulog($logtype, $uids, $action, $fid = '') {
	global $_G;

	if($_G['setting']['my_app_status'] && $logtype == 'user') {
		$action = daddslashes($action);
		$values = array();
		$uids = is_array($uids) ? $uids : array($uids);
		foreach($uids as $uid) {
			$uid = intval($uid);
			$values[$uid] = "('$uid', '$action', '".TIMESTAMP."')";
		}
		if($values) {
			DB::query("REPLACE INTO ".DB::table('common_member_log')." (`uid`, `action`, `dateline`) VALUES ".implode(',', $values));
		}
	}
}

function useractionlog($uid, $action) {
	$uid = intval($uid);
	if(empty($uid) || empty($action)) {
		return false;
	}
	$action = getuseraction($action);
	$timestamp = TIMESTAMP;
	DB::query("INSERT INTO ".DB::table('common_member_action_log')." (`uid`, `action`, `dateline`) VALUES ('$uid', '$action', '$timestamp')");
	return true;
}

function getuseraction($var) {
	$value = false;
	$ops = array('tid', 'pid', 'blogid', 'picid', 'doid', 'sid', 'aid', 'uid_cid', 'blogid_cid', 'sid_cid', 'picid_cid', 'aid_cid', 'topicid_cid');
	if(is_numeric($var)) {
		$value = isset($ops[$var]) ? $ops[$var] : false;
	} else {
		$value = array_search($var, $ops);
	}
	return $value;
}

function getuserapp($panel = 0) {
	require_once libfile('function/manyou');
	manyou_getuserapp($panel);
	return true;
}

function getmyappiconpath($appid, $iconstatus=0) {
	if($iconstatus > 0) {
		return getglobal('setting/attachurl').'./'.'myapp/icon/'.$appid.'.jpg';
	}
	return 'http://appicon.manyou.com/icons/'.$appid;
}

function getexpiration() {
	global $_G;
	$date = getdate($_G['timestamp']);
	return mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']) + 86400;
}

function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val{strlen($val)-1});
    switch($last) {
        case 'g': $val *= 1024;
        case 'm': $val *= 1024;
        case 'k': $val *= 1024;
    }
    return $val;
}

function get_url_list($message) {
	$return = array();

	(strpos($message, '[/img]') || strpos($message, '[/flash]')) && $message = preg_replace("/\[img[^\]]*\]\s*([^\[\<\r\n]+?)\s*\[\/img\]|\[flash[^\]]*\]\s*([^\[\<\r\n]+?)\s*\[\/flash\]/is", '', $message);
	if(preg_match_all("/((https?|ftp|gopher|news|telnet|rtsp|mms|callto):\/\/|www\.)([a-z0-9\/\-_+=.~!%@?#%&;:$\\()|]+\s*)/i", $message, $urllist)) {
		foreach($urllist[0] as $key => $val) {
			$val = trim($val);
			$return[0][$key] = $val;
			if(!preg_match('/^http:\/\//is', $val)) $val = 'http://'.$val;
			$tmp = parse_url($val);
			$return[1][$key] = $tmp['host'];
			if($tmp['port']){
				$return[1][$key] .= ":$tmp[port]";
			}
		}
	}

	return $return;
}

function iswhitelist($host) {
	global $_G;
	static $iswhitelist = array();

	if(isset($iswhitelist[$host])) {
		return $iswhitelist[$host];
	}
	$hostlen = strlen($host);
	$iswhitelist[$host] = false;
	if(is_array($_G['cache']['domainwhitelist'])) foreach($_G['cache']['domainwhitelist'] as $val) {
		$domainlen = strlen($val);
		if($domainlen > $hostlen) {
			continue;
		}
		if(substr($host, -$domainlen) == $val) {
			$iswhitelist[$host] = true;
			break;
		}
	}
	if($iswhitelist[$host] == false) {
		$iswhitelist[$host] = $host == $_SERVER['HTTP_HOST'];
	}
	return $iswhitelist[$host];
}

function update_template_block($targettplname, $blocks) {
	if(!empty($blocks) && !empty($targettplname)) {
		$oldbids = array();
		$query = DB::query('SELECT bid FROM '.DB::table('common_template_block')." WHERE targettplname='$targettplname'");
		while($value = DB::fetch($query)) {
			$oldbids[] = $value['bid'];
		}
		$newaddbids = array_diff($blocks, $oldbids);
		DB::delete('common_template_block', array('targettplname'=>$targettplname));
		$blocks = array_unique($blocks);
		$values = array();
		foreach ($blocks as $bid) {
			$values[] = "('$targettplname','$bid')";
		}
		if (!empty($values)) {
			DB::query("INSERT INTO ".DB::table('common_template_block')." (targettplname,bid) VALUES ".implode(',', $values));
		}
		if(!empty($newaddbids)) {
			require_once libfile('class/blockpermission');
			$tplpermission = & template_permission::instance();
			$tplpermission->add_blocks($targettplname, $newaddbids);
		}
	}
}

if(!function_exists('http_build_query')) {
	function http_build_query($data, $numeric_prefix='', $arg_separator='', $prefix='') {
		$render = array();
		if (empty($arg_separator)) {
			$arg_separator = ini_get('arg_separator.output');
			empty($arg_separator) && $arg_separator = '&';
		}
		foreach ((array) $data as $key => $val) {
			if (is_array($val) || is_object($val)) {
				$_key = empty($prefix) ? "{$key}[%s]" : sprintf($prefix, $key) . "[%s]";
				$_render = http_build_query($val, '', $arg_separator, $_key);
				if (!empty($_render)) {
					$render[] = $_render;
				}
			} else {
				if (is_numeric($key) && empty($prefix)) {
					$render[] = urlencode("{$numeric_prefix}{$key}") . "=" . urlencode($val);
				} else {
					if (!empty($prefix)) {
						$_key = sprintf($prefix, $key);
						$render[] = urlencode($_key) . "=" . urlencode($val);
					} else {
						$render[] = urlencode($key) . "=" . urlencode($val);
					}
				}
			}
		}
		$render = implode($arg_separator, $render);
		if (empty($render)) {
			$render = '';
		}
		return $render;
	}
}

function getrelatedlink($extent) {
	global $_G;
	loadcache('relatedlink');
	$allextent = array('article' => 0, 'forum' => 1, 'group' => 2, 'blog' => 3);
	$links = array();
	if($_G['cache']['relatedlink'] && isset($allextent[$extent])) {
		foreach($_G['cache']['relatedlink'] as $link) {
			$link['extent'] = sprintf('%04b', $link['extent']);
			if($link['extent'][$allextent[$extent]] && $link['name'] && $link['url']) {
				$links[] = daddslashes($link);
			}
		}
	}
	return $links;
}

function getattachtablebyaid($aid) {
	$tableid = DB::result_first("SELECT tableid FROM ".DB::table('forum_attachment')." WHERE aid='$aid'");
	return 'forum_attachment_'.($tableid >= 0 && $tableid < 10 ? intval($tableid) : 'unused');
}

function getattachtableid($tid) {
	$tid = (string)$tid;
	return intval($tid{strlen($tid)-1});
}

function getattachtablebytid($tid) {
	return 'forum_attachment_'.getattachtableid($tid);
}

function getattachtablebypid($pid) {
	$tableid = DB::result_first("SELECT tableid FROM ".DB::table('forum_attachment')." WHERE pid='$pid' LIMIT 1");
	return 'forum_attachment_'.($tableid >= 0 && $tableid < 10 ? intval($tableid) : 'unused');
}

function getattachnewaid($uid = 0) {
	global $_G;
	$uid = !$uid ? $_G['uid'] : $uid;
	return DB::insert('forum_attachment', array('tid' => 0, 'pid' => 0, 'uid' => $uid, 'tableid' => 127), true);
}

function get_seosetting($page, $data = array(), $defset = array()) {
	global $_G;
	$searchs = array('{bbname}');
	$replaces = array($_G['setting']['bbname']);

	$seotitle = $seodescription = $seokeywords = '';
	$titletext = $defset['seotitle'] ? $defset['seotitle'] : $_G['setting']['seotitle'][$page];
	$descriptiontext = $defset['seodescription'] ? $defset['seodescription'] : $_G['setting']['seodescription'][$page];
	$keywordstext = $defset['seokeywords'] ? $defset['seokeywords'] : $_G['setting']['seokeywords'][$page];
	preg_match_all("/\{([a-z0-9_-]+?)\}/", $titletext.$descriptiontext.$keywordstext, $pageparams);
	if($pageparams) {
		foreach($pageparams[1] as $var) {
			$searchs[] = '{'.$var.'}';
			if($var == 'page') {
				$data['page'] = $data['page'] > 1 ? lang('core', 'page', array('page' => $data['page'])) : '';
			}
			$replaces[] = $data[$var] ? strip_tags($data[$var]) : '';
		}
		if($titletext) {
			$seotitle = strreplace_strip_split($searchs, $replaces, $titletext);
		}
		if($descriptiontext && (CURSCRIPT == 'forum' || IS_ROBOT || $_G['adminid'] == 1)) {
			$seodescription = strreplace_strip_split($searchs, $replaces, $descriptiontext);
		}
		if($keywordstext && (CURSCRIPT == 'forum' || IS_ROBOT || $_G['adminid'] == 1)) {
			$seokeywords = strreplace_strip_split($searchs, $replaces, $keywordstext);
		}
	}
	return array($seotitle, $seodescription, $seokeywords);
}


function strreplace_strip_split($searchs, $replaces, $str) {
	$searchspace = array('((\s*\-\s*)+)', '((\s*\,\s*)+)', '((\s*\|\s*)+)', '((\s*\t\s*)+)', '((\s*_\s*)+)');
	$replacespace = array('-', ',', '|', ' ', '_');
	return trim(preg_replace($searchspace, $replacespace, str_replace($searchs, $replaces, $str)), ' ,-|_');
}

function get_title_page($navtitle, $page){
	if($page > 1) {
		$navtitle .= ' - '.lang('core', 'page', array('page' => $page));
	}
	return $navtitle;
}
function getimgthumbname($fileStr, $extend='.thumb.jpg', $holdOldExt=true) {
	if(empty($fileStr)) {
		return '';
	}
	if(!$holdOldExt) {
		$fileStr = substr($fileStr, 0, strrpos($fileStr, '.'));
	}
	$extend = strstr($extend, '.') ? $extend : '.'.$extend;
	return $fileStr.$extend;
}

function updatemoderate($idtype, $ids, $status = 0) {
	global $_G;
	$ids = is_array($ids) ? $ids : array($ids);
	if(!$ids) {
		return;
	}
	if(!$status) {
		foreach($ids as $id) {
			DB::insert('common_moderate', array('id' => $id, 'idtype' => $idtype, 'status' => 0, 'dateline' => $_G['timestamp']), false, true);
		}
	} elseif($status == 1) {
		DB::update('common_moderate', array('status' => 1), "id IN (".dimplode($ids).") AND idtype='$idtype'");
	} elseif($status == 2) {
		DB::delete('common_moderate', "id IN (".dimplode($ids).") AND idtype='$idtype'");
	}
}

function userappprompt() {
	global $_G;

	if($_G['setting']['my_app_status'] && $_G['setting']['my_openappprompt'] && empty($_G['cookie']['userappprompt'])) {
		$sid = $_G['setting']['my_siteid'];
		$ts = $_G['timestamp'];
		$key = md5($sid.$ts.$_G['setting']['my_sitekey']);
		$uchId = $_G['uid'] ? $_G['uid'] : 0;
		echo '<script type="text/javascript" src="http://notice.uchome.manyou.com/notice/userNotice?sId='.$sid.'&ts='.$ts.'&key='.$key.'&uchId='.$uchId.'" charset="UTF-8"></script>';
	}
}


function makeSearchSignUrl() {
	global $_G;

	$url = '';
	$params = array();
	$my_search_data = unserialize($_G['setting']['my_search_data']);
	$my_siteid = $_G['setting']['my_siteid'];
	$my_sitekey= $_G['setting']['my_sitekey'];
	require_once libfile('function/cloud');
	if($my_search_data['status'] && getcloudappstatus('search') && $my_siteid) {
		$my_extgroupids = array();
		$_extgroupids = explode("\t", $_G['member']['extgroupids']);
		foreach($_extgroupids as $v) {
			if ($v) {
				$my_extgroupids[] = $v;
			}
		}
		$my_extgroupids_str = implode(',', $my_extgroupids);
		$params = array('sId' => $my_siteid,
							'ts' => time(),
							'cuId' => $_G['uid'],
							'cuName' => $_G['username'],
							'gId' => intval($_G['groupid']),
							'agId' => intval($_G['adminid']),
							'egIds' => $my_extgroupids_str,
							'fmSign' => '',
						   );
		$groupIds = array($params['gId']);
		if ($params['agId']) {
			$groupIds[] = $params['agId'];
		}
		if ($my_extgroupids) {
			$groupIds = array_merge($groupIds, $my_extgroupids);
		}

		$groupIds = array_unique($groupIds);
		foreach($groupIds as $v) {
			$key = 'ugSign' . $v;
			$params[$key] = '';
		}
		$params['sign'] = md5(implode('|', $params) . '|' . $my_sitekey);

		$params['charset'] = $_G['charset'];
		$mySearchData = unserialize($_G['setting']['my_search_data']);
		if ($mySearchData['domain']) {
			$domain = $mySearchData['domain'];
		} else {
			$domain = 'search.discuz.qq.com';
		}
		$url = 'http://' . $domain . '/f/discuz';
	}
	return !empty($url) ? array($url, $params) : array();
}


/*徐玉枭 2012-1-15
函数说明 根据uid判断，用户分组及模板类型
*/
function getusrarry($uid) {

	global $_G;
	$usrarry = array();
	//得到分组ID
	$groupid = DB::result_first("SELECT groupid FROM ".DB::table('common_member')." WHERE uid='$uid'");

    if(strlen(DB::result_first("SELECT sortname FROM ".DB::table('Team_sort')." WHERE groupid='$groupid'")))
	{
	$groupname = DB::result_first("SELECT sortname FROM ".DB::table('Team_sort')." WHERE groupid='$groupid'");
	$iscomp=1;
	}
	else
	{
	$groupname = DB::result_first("SELECT grouptitle FROM ".DB::table('common_usergroup')." WHERE groupid='$groupid'");
	$iscomp=0;
    }

	//if(strlen(DB::result_first("SELECT theme FROM ".DB::table('common_member_field_home')." WHERE uid='$uid'")))

	  $theme =DB::result_first("SELECT theme FROM ".DB::table('common_member_field_home')." WHERE uid='$uid'");

	$selfuid=$_G['uid'];
	$usrusername = DB::result_first("SELECT username FROM ".DB::table('common_member')." WHERE uid='$uid'");
	$spnickname = DB::result_first("SELECT realname FROM ".DB::table('common_member_profile')." WHERE uid='$uid'");

	if(strlen(DB::result_first("SELECT realname FROM ".DB::table('common_member_profile')." WHERE uid='$uid'")))
	{$spnickname = DB::result_first("SELECT realname FROM ".DB::table('common_member_profile')." WHERE uid='$uid'");}
	else
	{$spnickname = $usrusername;}

	if(strlen(DB::result_first("SELECT realname FROM ".DB::table('common_member_profile')."  WHERE uid='$selfuid'")))
	{$usrnickname = DB::result_first("SELECT realname FROM ".DB::table('common_member_profile')."  WHERE uid='$selfuid'");	}
	else
	{$usrnickname = DB::result_first("SELECT username FROM ".DB::table('common_member')." WHERE uid='$selfuid'");}

$dir='static/space/uidcss_'.$uid;
if (is_dir($dir)){
 $cssid='uidcss_'.$uid;
}else
{
  $cssid=$groupid;
}

	 $usrarry = array('groupid' => $groupid,
	                        'iscomp' =>  $iscomp,
							'groupname' =>  $groupname,
							'cssid' =>  $cssid,
							'theme' =>  $theme,
							'spnickname'=> $spnickname,
							'usrnickname'=> $usrnickname,
							'usrusername'=> $usrusername,
						   );
	return $usrarry;

}

/*徐玉枭 2012-3-30
函数说明 根据uid得到真实姓名
*/
function getrname($uid) {

	$usrusername = DB::result_first("SELECT username FROM ".DB::table('common_member')." WHERE uid='$uid'");
	$realname = DB::result_first("SELECT realname FROM ".DB::table('common_member_profile')." WHERE uid='$uid'");
	if(strlen(DB::result_first("SELECT field1 FROM ".DB::table('common_member_profile')." WHERE uid='$uid'")))
	{$realname = DB::result_first("SELECT field1 FROM ".DB::table('common_member_profile')." WHERE uid='$uid'");}
	elseif(strlen(DB::result_first("SELECT realname FROM ".DB::table('common_member_profile')." WHERE uid='$uid'")))
	{$realname = DB::result_first("SELECT realname FROM ".DB::table('common_member_profile')." WHERE uid='$uid'");}
	else
	{$realname = $usrusername;}

	return $realname;
}


/*徐玉枭 2012-1-15
函数说明  用户分组列表 返回二维数组
*/
function getgrouparry($strwhere = '') {
	global $_G;
	$r = false;
	$where = empty($strwhere) ? '' : " WHERE 1=1 and $strwhere";

	$grouparry = array();

	$query = DB::query("SELECT * FROM ".DB::table('Team_sort')."$where");

	 while($value = DB::fetch($query)) {
			$grouparry[] = $value;
		}
	return $grouparry;

}

 //得到扩展名
// 1: 需转换文件路径 最好为绝对路径
// 2: 转换后文件路径
function flv_convert_get_thumb($srcfile)
{
 if(!is_file($srcfile)){
  return false;
}
$str1=explode(".",$srcfile);
    $newfile=$str1[0];
	$out=str_replace('src','video',$newfile);

 echo $str1[1];
  //视频转换
  if($str1[1]=='flv')
  {
  $cmd = 'cp '.$srcfile.'  '.$out.'.flv ';
  }
  else
  {
  $cmd = '/usr/bin/mencoder '.$srcfile.' -o '.$out.'.flv -of lavf -oac mp3lame -lameopts vbr=3:br=64 -ovc lavc -lavcopts vcodec=flv:vbitrate=1000:trell:v4mv:cbp:last_pred=3  -srate 22050 -ofps 12';  }
  $res = shell_exec($cmd);

 //得到mp4
    //$cmd = '/usr/local/bsrcfile/ffmpeg  -i '.$out.' -y  -ab 56 -ar 22050  -b 1500 -r 15   -qscale  10 -s 480x320 '.$out.'.mp4';
if($str1[1]=='mp4')
  {
  $cmd = 'cp '.$srcfile.'  '.$out.'.mp4 ';
  }
  else
  {
	 $cmd = '/usr/bin/mencoder '.$srcfile.' -o '.$out.'.mp4 -vf scale=640:-10,harddup -lavfopts format=mp4 -faacopts mpeg=4:object=2:raw:br=128 -oac faac -ovc x264 -sws 9 -x264encopts nocabac:level_idc=30:bframes=3:global_header:threads=auto:subq=5:frameref=2:partitions=all:trellis=1:chroma_me:me=umh:bitrate=500 -of lavf';
	 
	 //-vf scale=480:-10,harddup -lavfopts format=mp4 -faacopts mpeg=4:object=2:raw:br=128 -oac faac -ovc x264 -sws 9 -x264encopts nocabac:level_idc=30:bframes=0:global_header:threads=auto:subq=5:frameref=6:partitions=all:trellis=1:chroma_me:me=umh:bitrate=500 -of lavf
  }
  $res = shell_exec($cmd);
 //得到图片
    $cmd = '/usr/bin/ffmpeg  -i '.$out.'.flv -y -f image2 -t 0.001 -s 352x240 '.$out.'.jpg';
  $res = shell_exec($cmd);

}

 //获取当前页面url
function get_current_page_url(){
    $current_page_url = 'http';
    if ($_SERVER["HTTPS"] == "on") {
        $current_page_url .= "s";
    }
     $current_page_url .= "://";
     if ($_SERVER["SERVER_PORT"] != "80") {
    $current_page_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $current_page_url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $current_page_url;
}

 function base64encode($str)
 {
	 return base64_encode($str);

 }
 //add by xgw on 2012年4月6日  excel导出方法
 function download($fname='data',$data=null,$mimeType='application/force-download'){

    if(headers_sent($file,$line)){
        echo 'Header already sent @ '.$file.':'.$line;
        exit();
    }

    //header('Cache-Control: no-cache;must-revalidate'); //fix ie download bug
    header('Pragma: no-cache, no-store');
    header("Expires: Wed, 26 Feb 1997 08:21:57 GMT");

    if(strpos($_SERVER["HTTP_USER_AGENT"],'MSIE')){
        $fname = urlencode($fname);
        header('Content-type: '.$mimeType);
    }else{
        header('Content-type: '.$mimeType.';charset=utf-8');
    }
    header("Content-Disposition: attachment; filename=\"".$fname.'"');
    //header( "Content-Description: File Transfer");

    if($data){
        header('Content-Length: '.strlen($data));
        echo $data;
        exit();
    }
}
 //add by xgw on 2012年4月6日  excel导出方法

function dzexcel($data,$type='') {


	  require_once libfile('class/excel');

    if($type==''){ echo " 参数有误 请连续开发人员functiton_core.php dzexcel()  \$type 丢失 或 没有";}
   /*取得数据源 标识 langues*/
    if($type=='dazbm_export'){
   			 $title_arrs = array(
    								'realname'=>'姓名',
    								'gender'=>'性别',
    								'birth'=>'出生日期',
    								'nationality'=>'国籍',
    								'height'=>'身高',
    								'ball_age'=>'球龄',
    								'cahdian'=>'差点',
    								'hot_district'=>'比赛场地',
    								'moblie'=>'手机号码',
    								'credentials_num'=>'证件号码',
                                    'credentials'=>'证件类型',
    								'company_class'=>'所属行业',
    								'company'=>'公司名称',
    								'position'=>'职务',
    								'income'=>'个人年收入',
    								'address'=>'地址',
    								'email'=>'邮箱',
    								'is_join_c'=>'是否参加去年城市挑战赛',
    								'car_brand'=>'您目前的座驾',
    								'tool_brand'=>'拥有的球具品牌',
    								'is_huang'=>'是否皇冠车主',
    								'h_car_type'=>'皇冠车型',
    								'car_j_type'=>'皇冠车驾号',
    								'car_marking_shop'=>'所属经销店',
    								'h_car_yinx'=>'对皇冠车印象',
    								'bianhua'=>'对皇冠车印象变化',
    								'assess_price'=>'对皇冠的评价',
    								'sure_realize'=>'是否了解皇冠',
    								'sure_drive'=>'是否试驾皇冠',
    								'konw_saishi'=>'知道哪些汽车类赛事',
    								'accept_way'=>'通过何种渠道了解挑战赛',
    								'attract'=>'您参加本次比赛的原因',
    								//'zipcode'=>'邮编',
    								//'num_qiu_hui'=>'会籍个数',
    								//'fax'=>'传真',
    								///'credentials'=>'证件类型',
    								///'best_score'=>'最好成绩',
    								//'faction'=>'拥有的会籍',
    								//'banklist'=>'支付费用银行',
    								//'pay_status'=>'支付状态',
    							);

    }



    /*根据 原数据标识 取对应的数据库字段*/
    if($type=='dazbm_export'){
       //$index_field = array('realname','gender','birth','nationality','height','ball_age','cahdian','moblie','credentials_num','company_class','company','position','income','address','is_join_c','h_car_type','tool_brand','h_car_yinx','konw_saishi','bianhua','zipcode','num_qiu_hui','fax','credentials','is_huang','accept_way','car_j_type','car_marking_shop','best_score','hot_district','faction','sure_realize','sure_drive','assess_price','attract','banklist','pay_status');
    	 $index_field = array_keys($title_arrs);
    }


    /*从传过来的原始数据 进行 重组*/
    foreach($data as $key=>$value){
      foreach($value as $k=>$v){
      	if(in_array($k,$index_field)){
      		//$new_data[$key][$k]=$v;
      		$new_data[$key][$k]= huoqu_zhi($k,$v,$type);
      	}
      }

    }




   /*重新排序*/
    foreach(array_keys($new_data) as $key=>$value){
        foreach($title_arrs as $k=>$v){
         $news_data_list[$key][$k]= $new_data[$key][$k];
        }
    }

    $export = new io_xls();
    $keys = array_values($title_arrs);
    $export->export_begin($keys,'大正报名',count($data));
    $export->export_rows($news_data_list,$index_field);
    $export->export_finish();
}

//判断是否有分割的
function huoqu_zhi($k,$v,$type){

	if(check_explode($k,$type)==1){
		$explode = explode(',',$v);
    if(!is_array($explode[0])){
    	$explode = explode('|',$v);
    }


     if($explode && !empty($explode)){
        foreach($explode as $value){
            $new_v .= search_value($k,$value,$type).'|';
        }
     }
	}else{
			 $new_v  = search_value($k,$v,$type);

	}
	return $new_v;


}

//根据字段名和值去取真实的数据
function search_value($k,$value,$type){

  $valuelist = 	array(
               'dazbm_export'=>array(
												 'hot_district'=>array('tj57'=>'5/5长沙','bj513'=>'5/18广州','gz527'=>'5/27杭州','cs611'=>'6/16天津','qd625'=>'6/29北京','hz72'=>'7/6深圳','sh721'=>'7/14青岛','dl729'=>'7/27上海','sz84'=>'8/3大连','cd812'=>'8/18成都','km827'=>'8/31苏州','fz93'=>'9/8福州'),
										 		 'gender'=>array('1'=>'男','2'=>'女'),
										 		 'position'=>array('0'=>'其他','1'=>'总裁/总经理/CEO','2'=>'副总裁/副总经理/COO','3'=>'总监/分公司负责人','4'=>'合伙人','5'=>'>经理/主管','6'=>'公务人员','7'=>'企业员工'),
										 		 'credentials'=>array('1'=>'身份证','2'=>'驾驶本','3'=>'台胞证','4'=>'回乡证'),
										 		 'is_huang'=>array('0'=>'否','1'=>'是'),
										 		 'company_class'=>array('1'=>'IT/互联网/电子商务','2'=>'通讯/电子/仪器仪表','3'=>'金融/银行/投资','4'=>'保险','5'=>'房地产/建筑/建材/物业','6'=>'广告/设计/公关','7'=>'媒体/出版/影视/文化/印刷','8'=>'机械/重工/制造','9'=>'法律/财会/咨询','10'=>'教育/培训','11'=>'石油/化工/能源/矿产/冶金','12'=>'贸易/进出口','13'=>'零售/批发','14'=>'交通/运输','15'=>'娱乐/体育/休闲','16'=>'旅游服务','17'=>'医疗/医药','18'=>'政府机关/事业单位/非赢利机构','19'=>'其它'),
										 		 'income'=>array('1'=>'15万元以下','2'=>'15-20万元','3'=>'20-30万元','4'=>'30-50万元','5'=>'50万-100万','6'=>'100万以上'),
										 		 'accept_way'=>array('1'=>'手机短信','2'=>'一汽丰田营销店','3'=>'赛事官网','4'=>'球场宣传页','5'=>'其他网站','6'=>'杂志或报刊广告','7'=>'邮件'),
										 		 'sure_realize'=>array('0'=>'不了解','1'=>'了解'),
										 		 'sure_drive'=>array('0'=>'否','1'=>'是'),
										 		 'assess_price'=>array('1'=>'中级车','2'=>'中高级车','3'=>'高级车'),
										 		 'attract'=>array('1'=>'喜爱高尔夫运动','2'=>'本赛事是中国业余顶级赛事，是中高协主办的正规比赛','3'=>'赛事知名度高（宣传力度较大/有知名品牌赞助）','4'=>'经销店邀请','5'=>'朋友介绍'),
										 		 'is_join_c'=>array('0'=>'否','1'=>'是'),
										 		 'banklist'=>array('1'=>'中国工商银行','2'=>'中国招商银行','3'=>'中国建设银行','4'=>'中国农业银行','5'=>'中国银行','6'=>'中国交通银行','7'=>'中国交通银行','8'=>'中国民生银行','9'=>'广东发展银行','10'=>'中信银行','11'=>'华夏银行','12'=>'兴业银行','13'=>'中国交通银行','14'=>'中国邮政','15'=>'其他银行其他银行','16'=>'上海浦发银行','17'=>'快钱信用卡'),
										 		 'pay_status'=>array('0'=>'否','1'=>'是'),'credentials'=>array('1'=>'身份证','2'=>'驾驶本','3'=>'台胞证','4'=>'回乡证')
											),

	     );

	 if($valuelist[$type][$k][$value]){
	 			return $valuelist[$type][$k][$value];
	 }else{
	     return $value;
	 }


}

/*判断是否需要切割*/
function check_explode($k='',$type=''){
	 $check_rows =  array(
	       'dazbm_export'=>array('hot_district'=>'1','attract'=>'1'),
	      );
	return  $check_rows [$type][$k];
}
//获取Ecshop数据库名称
function getecprefix(){

	return 'daz_shop.dz_';
}

/*获取球星推荐 angf do it  2012/8/10*/
function brand_star_list() {
    global $_G;
    $star_list = array() ;
    $rm_member_query = DB::fetch_first("SELECT rm_member FROM ".DB::table("common_member_profile")." WHERE uid =".$_G['gp_uid']);
    if(!empty($rm_member_query['rm_member'])){
        $query = DB::query("select uid , realname from ".DB::table("common_member_profile")."  WHERE  uid  IN(".$rm_member_query['rm_member'].")");
        while($result = DB::fetch($query)){
            $result['avatar'] = avatar($result['uid'],'small',true);
            $star_list[] = $result;
        }
    }
    return $star_list;
}


/*获取 球场频道 成绩卡 排名 angf do it 2012/8/13*/
function scores_ranking_card($sid=1000333) {
   global $_G;
    $num = $_G['gp_more'] ? 50 : 7;
 $query = DB::query("select uid from ".DB::table("common_score")." where sais_id='".$sid."' and fuid='".$_G['gp_uid']."' and status='2'  and ismine=0  and  total_score>50  group by uid order by total_score asc limit ".$num." ");
 //$query = DB::query("select uid from ".DB::table("common_score")." where sais_id='".$sid."' and fuid='".$_G['gp_uid']."'    and (ismine=0 or source='waika')  and  total_score>50  group by uid order by total_score asc limit ".$num." ");
    while($row = DB::fetch($query)) {
        $rank[] = $row;
    }

   foreach($rank as $k=>$v){
        $query = DB::query("select cmp.realname,cs.total_score,cs.id,cs.uid from ".DB::table('common_score')." as cs LEFT JOIN ".DB::table("common_member_profile")." as cmp ON cs.uid=cmp.uid where cs.uid='".$v['uid']."' and cs.status='2' and cs.ismine=0 and cs.sais_id='".$sid."' and cs.fuid='".$_G['gp_uid']."' and   cs.total_score>50  order by cs.total_score asc limit 3");
        $i =0; $j=1;
        while($row = DB::fetch($query)) {
             $i++;
             $datas[$row['uid']][$j++] =$row;
             $user_score[$row['uid']]['tot']+= $row['total_score'];
        }
    }

    foreach($user_score as $k=>$v){
        $score_pm[$k] = $v['tot'];
    }

    foreach($datas as $key=>$value){
        if($value[2] && !$value[3]){
             $score_pm[$value[1]['uid']]+=1000;
        }
        if(!$value[2] && !$value[3] ){
             $score_pm[$value[1]['uid']]+=2000;
        }
    }
    asort($score_pm);
    foreach($score_pm as $uid=>$vl){
        $newdata[$uid]=$datas[$uid];
    }
    return $newdata;
}


//xyx 12/08/23 热门话题
function gettopictag() {

 $dateline = time() - 2592000/2;
$sql = "select `id`,`user_id` as uid,`name`,`topic_count`,`dateline` from `jishigou_tag` where `dateline` > '$dateline' order by `last_post` desc,topic_count desc  limit 6";

	$query = DB::query($sql);
	while($row = mysql_fetch_assoc($query)) {
		$tagtopic[] = $row;
	}

    return $tagtopic;
}




/*
 * 获取对应广告的 信息
 * $id 广告id $type_id 广告类型 $limit 记录条数
 * return rows
 */
function get_vip_ads($id,$type_id="",$limit=4) {

  /*id为单个id*/
  if(is_numeric($id)){
     return  $ads = DB::fetch_first("SELECT advertname,imglogo,adverturl,content FROM ".DB::table("home_advert")." WHERE id=".$id);
  }

  /*id为 array()数组 多个*/
  if(is_array($id)){
     $ids =  implode(",",$id);
     $query = DB::query(" select advertname,imglogo,adverturl,content from ".DB::table("home_advert")." where id IN (".$ids.")");
      while($result = DB::fetch($query)){
        $ads[]= $result;
      }
      return $ads;

  }
  /*获取·广告类型下的所有广告*/
  if($type_id){
      $query = DB::query("select advertname,imglogo,adverturl,content from ".DB::table("home_advert")." where adverttype =".$type_id." order by dateline desc limit ".$limit);
      while($result = DB::fetch($query)){
        $ads[]= $result;
      }
      return $ads;
  }
}


/*获取俱乐部用户 单个  推荐的精品产品2012/8/8*/
function get_best_goods(){
    global $_G, $space;
    $result           =  array();
    $table            = 'daz_shop.dz_order_goods';
    $table_goods      = 'daz_shop.dz_goods';
    $table_goods_comm = "daz_shop.dz_comment";
    $table_users      = "daz_shop.dz_users";
    $group = array('22');
    if(in_array($space['groupid'],$group)){
       if($_G['gp_uid']){
            $goods = DB::fetch_first(" select `goods_id`,`goods_thumb`,`goods_name` from ".$table_goods."  where uid=".$_G['gp_uid']." and is_best =1 order by last_update desc limit 1 ");
            if(!empty($goods)){
                $query = DB::query(" SELECT u.realname,gc.user_id,gc.comment_rank,gc.content from ".$table_goods_comm." as gc  LEFT JOIN ".$table_users." as u ON u.user_id = gc.user_id   where gc.comment_type=0 and gc.id_value=".$goods['goods_id']." limit 2");
                while($result = DB::fetch($query)){
                    $result['avatar']  =  avatar($result['user_id'], 'small', true, false,false);
                    $goods_comment[]   = $result;
                }
            }
       }
       $result['goods_info']    = $goods;
       $result['goods_comment'] = $goods_comment;

    }
    return $result;
}

 
/**
* Curl版本
* 使用方法：
* $post_string = "app=request&version=beta";
* request_by_curl('http://facebook.cn/restServer.php',$post_string);
*/
function request_by_curl($remote_server,$post_string){

 
$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_URL,$remote_server); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
$result = curl_exec($ch);
return $result;
}


?>