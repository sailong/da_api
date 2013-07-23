<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spacecp_profile.php 24010 2011-08-19 07:35:13Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$result = DB::fetch_first("SELECT * FROM ".DB::table('common_setting')." WHERE skey='profilegroup'");
$defaultop = '';
if(!empty($result['svalue'])) {
	$profilegroup = unserialize($result['svalue']);
	foreach($profilegroup as $key => $value) {
		if($value['available']) {
			$defaultop = $key;
			break;
		}
	}
}

$operation = in_array($_GET['op'], array('base', 'contact', 'edu', 'work', 'info', 'password', 'verify')) ? trim($_GET['op']) : $defaultop;
$space = getspace($_G['uid']);
space_merge($space, 'field_home');
space_merge($space, 'profile');
$seccodecheck = $_G['setting']['seccodestatus'] & 8;
$secqaacheck = $_G['setting']['secqaa']['status'] & 4;
$_G['group']['seccode'] = 1;
@include_once DISCUZ_ROOT.'./data/cache/cache_domain.php';
$spacedomain = isset($rootdomain['home']) && $rootdomain['home'] ? $rootdomain['home'] : array();
if($operation != 'password') {

	include_once libfile('function/profile');

	loadcache('profilesetting');
	if(empty($_G['cache']['profilesetting'])) {
		require_once libfile('function/cache');
		updatecache('profilesetting');
		loadcache('profilesetting');
	}
}

$allowcstatus = !empty($_G['group']['allowcstatus']) ? true : false;
$verify = DB::fetch_first("SELECT * FROM ".DB::table("common_member_verify")." WHERE uid='$_G[uid]'");
$validate = array();
if($_G['setting']['regverify'] == 2 && $_G['groupid'] == 8) {
	$validate = DB::fetch_first("SELECT * FROM ".DB::table('common_member_validate')." WHERE uid='$_G[uid]' AND status='1'");
}

$conisregister = $operation == 'password' && $_G['setting']['connect']['allow'] && DB::result_first("SELECT conisregister FROM ".DB::table('common_member_connect')." WHERE uid='$_G[uid]'");

if(submitcheck('profilesubmit')) {

	require_once libfile('function/discuzcode');

	$forum = $setarr = $verifyarr = $errorarr = array();
	$forumfield = array('customstatus', 'sightml');

	if(!class_exists('discuz_censor')) {
		include libfile('class/censor');
	}
	$censor = discuz_censor::instance();

	if($_G['gp_vid']) {
		$vid = intval($_G['gp_vid']);
		$verifyconfig = $_G['setting']['verify'][$vid];
		if($verifyconfig['available']) {
			$verifyinfo = DB::fetch_first("SELECT * FROM ".DB::table("common_member_verify_info")." WHERE uid='$_G[uid]' AND verifytype='$vid'");
			if(!empty($verifyinfo)) {
				$verifyinfo['field'] = unserialize($verifyinfo['field']);
			}
			foreach($verifyconfig['field'] as $key => $field) {
				if(!isset($verifyinfo['field'][$key])) {
					$verifyinfo['field'][$key] = $key;
				}
			}
		} else {
			$vid = 0;
		}
	}
	if(isset($_POST['birthprovince'])) {
		$initcity = array('birthprovince', 'birthcity', 'birthdist', 'birthcommunity');
		foreach($initcity as $key) {
			$_G['gp_'.$key] = $_POST[$key] = !empty($_POST[$key]) ? $_POST[$key] : '';
		}
	}
	if(isset($_POST['resideprovince'])) {
		$initcity = array('resideprovince', 'residecity', 'residedist', 'residecommunity');
		foreach($initcity as $key) {
			$_G['gp_'.$key] = $_POST[$key] = !empty($_POST[$key]) ? $_POST[$key] : '';
		}
	}
	foreach($_POST as $key => $value) {
		$field = $_G['cache']['profilesetting'][$key];
		if(in_array($field['formtype'], array('text', 'textarea')) || in_array($key, $forumfield)) {
			$censor->check($value);
			if($censor->modbanned() || $censor->modmoderated()) {
				profile_showerror($key, lang('spacecp', 'profile_censor'));
			}
		}
		if(in_array($key, $forumfield)) {
			if($key == 'sightml') {
				loadcache(array('smilies', 'smileytypes'));
				$value = cutstr($value, $_G['group']['maxsigsize'], '');
				foreach($_G['cache']['smilies']['replacearray'] AS $skey => $smiley) {
					$_G['cache']['smilies']['replacearray'][$skey] = '[img]'.$_G['siteurl'].'static/image/smiley/'.$_G['cache']['smileytypes'][$_G['cache']['smilies']['typearray'][$skey]]['directory'].'/'.$smiley.'[/img]';
				}
				$value = preg_replace($_G['cache']['smilies']['searcharray'], $_G['cache']['smilies']['replacearray'], trim($value));
				$forum[$key] = addslashes(discuzcode(stripslashes($value), 1, 0, 0, 0, $_G['group']['allowsigbbcode'], $_G['group']['allowsigimgcode'], 0, 0, 1));
			} elseif($key=='customstatus' && $allowcstatus) {
				$forum[$key] = dhtmlspecialchars(trim($value));
			}
			continue;
		} elseif($field && !$field['available']) {
			continue;
		} elseif($key == 'timeoffset') {
			DB::update('common_member', array('timeoffset' => intval($value)), array('uid'=>$_G['uid']));
		}
		if($field['formtype'] == 'file') {
			if((!empty($_FILES[$key]) && $_FILES[$key]['error'] == 0) || (!empty($space[$key]) && empty($_G['gp_deletefile'][$key]))) {
				$value = '1';
			} else {
				$value = '';
			}
		}
		if(empty($field)) {
			continue;
		} elseif(profile_check($key, $value, $space)) {
			$setarr[$key] = dhtmlspecialchars(trim($value));
		} else {
			if($key=='birthprovince') {
				$key = 'birthcity';
			} elseif($key=='resideprovince' || $key=='residecommunity'||$key=='residedist') {
				$key = 'residecity';
			} elseif($key=='birthyear' || $key=='birthmonth') {
				$key = 'birthday';
			}
			profile_showerror($key);
		}
		if($field['formtype'] == 'file') {
			unset($setarr[$key]);
		}
		if($vid && $verifyconfig['available'] && isset($verifyconfig['field'][$key])) {
			if(isset($verifyinfo['field'][$key]) && $setarr[$key] !== $space[$key]) {
				$verifyarr[$key] = $setarr[$key];
			}
			unset($setarr[$key]);
		}
		if(isset($setarr[$key]) && $_G['cache']['profilesetting'][$key]['needverify']) {
			if($setarr[$key] !== $space[$key]) {
				$verifyarr[$key] = $setarr[$key];
			}
			unset($setarr[$key]);
		}
	}
	if($_G['gp_deletefile'] && is_array($_G['gp_deletefile'])) {
		foreach($_G['gp_deletefile'] as $key => $value) {
			if(isset($_G['cache']['profilesetting'][$key])) {
				@unlink(getglobal('setting/attachdir').'./profile/'.$space[$key]);
				@unlink(getglobal('setting/attachdir').'./profile/'.$verifyinfo['field'][$key]);
				$verifyarr[$key] = $setarr[$key] = '';
			}
		}
	}
	if($_FILES) {
		require_once libfile('class/upload');
		$upload = new discuz_upload();

		foreach($_FILES as $key => $file) {
			if(!isset($_G['cache']['profilesetting'][$key])) {
				continue;
			}
			if((!empty($file) && $file['error'] == 0) || (!empty($space[$key]) && empty($_G['gp_deletefile'][$key]))) {
				$value = '1';
			} else {
				$value = '';
			}
			if(profile_check($key, $value, $space)) {
				$upload->init($file, 'profile');
				$attach = $upload->attach;

				if(!$upload->error()) {
					$upload->save();

					if(!$upload->get_image_info($attach['target'])) {
						@unlink($attach['target']);
						continue;
					}
					$setarr[$key] = '';
					$attach['attachment'] = dhtmlspecialchars(trim($attach['attachment']));
					if($vid && $verifyconfig['available'] && isset($verifyconfig['field'][$key])) {
						if(isset($verifyinfo['field'][$key])) {
							@unlink(getglobal('setting/attachdir').'./profile/'.$verifyinfo['field'][$key]);
							$verifyarr[$key] = $attach['attachment'];
						}
						continue;
					}
					if(isset($setarr[$key]) && $_G['cache']['profilesetting'][$key]['needverify']) {
						@unlink(getglobal('setting/attachdir').'./profile/'.$verifyinfo['field'][$key]);
						$verifyarr[$key] = $attach['attachment'];
						continue;
					}
					@unlink(getglobal('setting/attachdir').'./profile/'.$space[$key]);
					$setarr[$key] = $attach['attachment'];
				}
			}
		}
	}
	if($vid && !empty($verifyinfo['field']) && is_array($verifyinfo['field'])) {
		foreach($verifyinfo['field'] as $key => $fvalue) {
			if(!isset($verifyconfig['field'][$key])) {
				unset($verifyinfo['field'][$key]);
				continue;
			}
			if(empty($verifyarr[$key]) && !isset($verifyarr[$key]) && isset($verifyinfo['field'][$key])) {
				$verifyarr[$key] = !empty($fvalue) && $key != $fvalue ? $fvalue : $space[$key];
			}
		}
	}
	if($forum) {
		if(!$_G['group']['maxsigsize']) {
			$forum['sightml'] = '';
		}
		DB::update('common_member_field_forum', $forum, array('uid'=>$_G['uid']));
	}

	if(isset($_POST['birthmonth']) && ($space['birthmonth'] != $_POST['birthmonth'] || $space['birthday'] != $_POST['birthday'])) {
		$setarr['constellation'] = get_constellation($_POST['birthmonth'], $_POST['birthday']);
	}
	if(isset($_POST['birthyear']) && $space['birthyear'] != $_POST['birthyear']) {
		$setarr['zodiac'] = get_zodiac($_POST['birthyear']);
	}

	if($setarr) {
		DB::update('common_member_profile', $setarr, array('uid'=>$_G['uid']));
	}
	if($_POST['realname']) {
		DB::query("update jishigou_members set nickname='".$_POST['realname']."' where uid='".$_G['uid']."'");
	}
	//增加公司名称
   if($_POST['field1']) {
		DB::query("update jishigou_members set nickname='".$_POST['field1']."' where uid='".$_G['uid']."'");
	}
	if($verifyarr) {
		DB::query('DELETE FROM '.DB::table('common_member_verify_info')." WHERE uid='$_G[uid]' AND verifytype='$vid'");
		$setverify = array(
				'uid' => $_G['uid'],
				'username' => $_G['username'],
				'verifytype' => $vid,
				'field' => daddslashes(serialize($verifyarr)),
				'dateline' => $_G['timestamp']
			);

		DB::insert('common_member_verify_info', $setverify);
		$count = DB::result(DB::query("SELECT COUNT(*) FROM ".DB::table('common_member_verify')." WHERE uid='$_G[uid]'"), 0);
		if(!$count) {
			DB::insert('common_member_verify', array('uid' => $_G['uid']));
		}
		if($_G['setting']['verify'][$vid]['available']) {
			manage_addnotify('verify_'.$vid, 0, array('langkey' => 'manage_verify_field', 'verifyname' => $_G['setting']['verify'][$vid]['title'], 'doid' => $vid));
		}
	}

	if(isset($_POST['privacy'])) {
		foreach($_POST['privacy'] as $key=>$value) {
			if(isset($_G['cache']['profilesetting'][$key])) {
				$space['privacy']['profile'][$key] = intval($value);
			}
		}
		DB::update('common_member_field_home', array('privacy'=>addslashes(serialize($space['privacy']))), array('uid'=>$space['uid']));
	}

	manyoulog('user', $_G['uid'], 'update');

	include_once libfile('function/feed');
	feed_add('profile', 'feed_profile_update_'.$operation, array('hash_data'=>'profile'));
	countprofileprogress();
	$message = $vid ? lang('spacecp', 'profile_verify_verifying', array('verify' => $verifyconfig['title'])) : '';
	profile_showsuccess($message);

} elseif(submitcheck('passwordsubmit', 0, $seccodecheck, $secqaacheck)) {

	$membersql = $memberfieldsql = $authstradd1 = $authstradd2 = $newpasswdadd = '';
	$setarr = array();
	$emailnew = dhtmlspecialchars($_G['gp_emailnew']);
	$ignorepassword = 0;
	if($_G['setting']['connect']['allow'] && DB::result_first("SELECT conisregister FROM ".DB::table('common_member_connect')." WHERE uid='$_G[uid]'")) {
		$_G['gp_oldpassword'] = '';
		$ignorepassword = 1;
		if(empty($_G['gp_newpassword'])) {
			showmessage('profile_passwd_empty');
		}
	}

	if($_G['gp_questionidnew'] === '') {
		$_G['gp_questionidnew'] = $_G['gp_answernew'] = '';
	} else {
		$secquesnew = $_G['gp_questionidnew'] > 0 ? random(8) : '';
	}

	if(!empty($_G['gp_newpassword']) && $_G['gp_newpassword'] != addslashes($_G['gp_newpassword'])) {
		showmessage('profile_passwd_illegal', '', array(), array('return' => true));
	}
	if(!empty($_G['gp_newpassword']) && $_G['gp_newpassword'] != $_G['gp_newpassword2']) {
		showmessage('profile_passwd_notmatch', '', array(), array('return' => true));
	}

	loaducenter();
	$ucresult = uc_user_edit($_G['username'], $_G['gp_oldpassword'], $_G['gp_newpassword'], $emailnew != $_G['member']['email'] ? $emailnew : '', $ignorepassword, $_G['gp_questionidnew'], $_G['gp_answernew']);
	if($ucresult == -1) {
		showmessage('profile_passwd_wrong', '', array(), array('return' => true));
	} elseif($ucresult == -4) {
		showmessage('profile_email_illegal', '', array(), array('return' => true));
	} elseif($ucresult == -5) {
		showmessage('profile_email_domain_illegal', '', array(), array('return' => true));
	} elseif($ucresult == -6) {
		showmessage('profile_email_duplicate', '', array(), array('return' => true));
	}

	if(!empty($_G['gp_newpassword']) || $secquesnew) {
		$setarr['password'] = md5(random(10));
	}
	if($_G['setting']['connect']['allow']) {
		DB::update('common_member_connect', array('conisregister' => 0), array('uid' => $_G['uid']));
	}

	$authstr = false;
	if($emailnew != $_G['member']['email']) {
		$authstr = true;
		emailcheck_send($space['uid'], $emailnew);
		dsetcookie('newemail', "$space[uid]\t$emailnew\t$_G[timestamp]", 31536000);
	}
	if($setarr) {
		DB::update('common_member', $setarr, array('uid' => $_G['uid']));
	}

	if($authstr) {
		showmessage('profile_email_verify', 'home.php?mod=spacecp&ac=profile&op=password');
	} else {
		showmessage('profile_succeed', 'home.php?mod=spacecp&ac=profile&op=password');
	}
}

if($operation == 'password') {

	$resend = getcookie('resendemail');
	$resend = empty($resend) ? true : (TIMESTAMP - $resend) > 300;
	$newemail = getcookie('newemail');
	$space['newemail'] = !$space['emailstatus'] ? $space['email'] : '';
	if(!empty($newemail)) {
		$mailinfo = explode("\t", $newemail);
		$space['newemail'] = $mailinfo[0] == $_G['uid'] && isemail($mailinfo[1]) && $mailinfo[1] != $space['email'] ? $mailinfo[1] : '';
	}

	if($_G['gp_resend'] && $resend) {
		$toemail = $space['newemail'] ? $space['newemail'] : $space['email'];
		emailcheck_send($space['uid'], $toemail);
		dsetcookie('resendemail', TIMESTAMP);
		showmessage('send_activate_mail_succeed', "home.php?mod=spacecp&ac=profile&op=password");
	} elseif ($_G['gp_resend']) {
		showmessage('send_activate_mail_error', "home.php?mod=spacecp&ac=profile&op=password");
	}
	if(!empty($space['newemail'])) {
		$acitvemessage = lang('spacecp', 'email_acitve_message', array('newemail' => $space['newemail'], 'imgdir' => $_G['style']['imgdir']));
	}
	$actives = array('password' =>' class="a"');
	$navtitle = lang('core', 'title_password_security');

} else {

	space_merge($space, 'field_home');
	space_merge($space, 'field_forum');

	require_once libfile('function/editor');
	$space['sightml'] = html2bbcode($space['sightml']);

	$vid = $_G['gp_vid'] ? intval($_G['gp_vid']) : 0;

	$privacy = $space['privacy']['profile'] ? $space['privacy']['profile'] : array();
	$_G['setting']['privacy'] = $_G['setting']['privacy'] ? $_G['setting']['privacy'] : array();
	$_G['setting']['privacy'] = is_array($_G['setting']['privacy']) ? $_G['setting']['privacy'] : unserialize($_G['setting']['privacy']);
	$_G['setting']['privacy']['profile'] = !empty($_G['setting']['privacy']['profile']) ? $_G['setting']['privacy']['profile'] : array();
	$privacy = array_merge($_G['setting']['privacy']['profile'], $privacy);

	$actives = array('profile' =>' class="a"');
	$opactives = array($operation =>' class="a"');
	$allowitems = array();
	if(in_array($operation, array('base', 'contact', 'edu', 'work', 'info'))) {
		$allowitems = $profilegroup[$operation]['field'];
	} elseif($operation == 'verify') {
		if($vid == 0) {
			foreach($_G['setting']['verify'] as $key => $setting) {
				if($setting['available']) {
					$_G['gp_vid'] = $vid = $key;
					break;
				}
			}
		}
		$actives = array('verify' =>' class="a"');
		$opactives = array($operation.$vid =>' class="a"');
		$allowitems = $_G['setting']['verify'][$vid]['field'];
	}
	$showbtn = ($vid && $verify['verify'.$vid] != 1) || empty($vid);
	if(!empty($verify) && is_array($verify)) {
		foreach($verify as $key => $flag) {
			if(in_array($key, array('verify1', 'verify2', 'verify3', 'verify4', 'verify5', 'verify6', 'verify7')) && $flag == 1) {
				$verifyid = intval(substr($key, -1, 1));
				if($_G['setting']['verify'][$verifyid]['available']) {
					foreach($_G['setting']['verify'][$verifyid]['field'] as $field) {
						$_G['cache']['profilesetting'][$field]['unchangeable'] = 1;
					}
				}
			}
		}
	}


	if($vid) {
		$query = DB::query('SELECT field FROM '.DB::table('common_member_verify_info')." WHERE uid='$_G[uid]' AND verifytype='$vid'");
		while($value = DB::fetch($query)) {
			$field = unserialize($value['field']);
			foreach($field as $key => $fvalue) {
				$space[$key] = $fvalue;
			}
		}
	}
	$htmls = $settings = array();
	foreach($allowitems as $fieldid) {
		if(!in_array($fieldid, array('sightml', 'customstatus', 'timeoffset'))) {
			$html = profile_setting($fieldid, $space, $vid ? false : true);
			if($html) {
				$settings[$fieldid] = $_G['cache']['profilesetting'][$fieldid];
				$htmls[$fieldid] = $html;
			}
		}
	}

}

//
if(getgpc('uid') == ''){
	$uid = $_G['uid'];
}else{
	$uid = getgpc('uid');
}

//
require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_badges_related_to_page_getter.php');
$brtpg = new badges_related_to_page_getter();
$row = $brtpg->get_record_by_uid($uid);
$badge_id_related_to_page = $row['badge_id'];

if($badge_id_related_to_page != ''){
	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
	$uabg = new user_applying_badges_getter();
	$record = $uabg->get_record_by_uid_and_badge_id($uid,$badge_id_related_to_page);
	if($record['getting_badge_or_not']==1){
		if($badge_id_related_to_page == 2){
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
       			$uabig = new user_applying_badge_infos_getter();
        		$badge_id = 2;
        		$tag_name = 'org_cga_trainer_belonging';
        		$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
        		$org_cga_trainer_belonging = $row['tag_value'];

				if(mb_strlen($org_cga_trainer_belonging,"UTF-8") > 10){
					$org = mb_substr($org_cga_trainer_belonging,0,10,"UTF-8").'...';
				}else{
					$org = $org_cga_trainer_belonging;
				}

        		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
        		$uabig = new user_applying_badge_infos_getter();
        		$badge_id = 2;
        		$tag_name = 'cga_trainer_duty';
        		$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
        		$cga_trainer_duty = $row['tag_value'];

				if(mb_strlen($cga_trainer_duty,"UTF-8") > 10){
					$duty = mb_substr($cga_trainer_duty,0,10,"UTF-8").'...';
				}else{
					$duty = $cga_trainer_duty;
				}

        		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
        		$uabig = new user_applying_badge_infos_getter();
        		$badge_id = 2;
        		$tag_name = 'cga_trainer_teaching_strong_point';
        		$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
        		$cga_trainer_teaching_strong_point = $row['tag_value'];

				if(mb_strlen($cga_trainer_teaching_strong_point,"UTF-8") > 10){
					$strong_point = mb_substr($cga_trainer_teaching_strong_point,0,10,"UTF-8").'...';
				}else{
					$strong_point = $cga_trainer_teaching_strong_point;
				}

		}

		if($badge_id_related_to_page == 1){

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 1;
			$tag_name = 'org_club_trainer_belonging';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$org_club_trainer_belonging = $row['tag_value'];

			if(mb_strlen($org_club_trainer_belonging,"UTF-8") > 10){
				$org = mb_substr($org_club_trainer_belonging,0,10,"UTF-8").'...';
			}else{
				$org = $org_club_trainer_belonging;
			}

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 1;
			$tag_name = 'club_trainer_duty';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$club_trainer_duty = $row['tag_value'];

			if(mb_strlen($club_trainer_duty,"UTF-8") > 10){
				$duty = mb_substr($club_trainer_duty,0,10,"UTF-8").'...';
			}else{
				$duty = $club_trainer_duty;
			}

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 1;
			$tag_name = 'club_trainer_teaching_strong_point';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$club_trainer_teaching_strong_point = $row['tag_value'];

			if(mb_strlen($club_trainer_teaching_strong_point,"UTF-8") > 10){
				$strong_point = mb_substr($club_trainer_teaching_strong_point,0,10,"UTF-8").'...';
			}else{
				$strong_point = $club_trainer_teaching_strong_point;
			}

		}

		if($badge_id_related_to_page == 4){

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 4;
			$tag_name = 'org_foreign_trainer_belonging';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$org_foreign_trainer_belonging = $row['tag_value'];

			if(mb_strlen($org_foreign_trainer_belonging,"UTF-8") > 10){
				$org = mb_substr($org_foreign_trainer_belonging,0,10,"UTF-8").'...';
			}else{
				$org = $org_foreign_trainer_belonging;
			}

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 4;
			$tag_name = 'foreign_trainer_duty';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$foreign_trainer_duty = $row['tag_value'];

			if(mb_strlen($foreign_trainer_duty,"UTF-8") > 10){
				$duty = mb_substr($foreign_trainer_duty,0,10,"UTF-8").'...';
			}else{
				$duty = $foreign_trainer_duty;
			}

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 4;
			$tag_name = 'foreign_trainer_teaching_strong_point';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$foreign_trainer_teaching_strong_point = $row['tag_value'];

			if(mb_strlen($foreign_trainer_teaching_strong_point,"UTF-8") > 10){
				$strong_point = mb_substr($foreign_trainer_teaching_strong_point,0,10,"UTF-8").'...';
			}else{
				$strong_point = $foreign_trainer_teaching_strong_point;
			}

		}

		if($badge_id_related_to_page == 3){

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        $uabig = new user_applying_badge_infos_getter();
                        $badge_id = 3;
                        $tag_name = 'org_hmt_trainer_belonging';
                        $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        $org_hmt_trainer_belonging = $row['tag_value'];

						if(mb_strlen($org_hmt_trainer_belonging,"UTF-8") > 10){
							$org = mb_substr($org_hmt_trainer_belonging,0,10,"UTF-8").'...';
						}else{
							$org = $org_hmt_trainer_belonging;
						}

                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        $uabig = new user_applying_badge_infos_getter();
                        $badge_id = 3;
                        $tag_name = 'hmt_trainer_duty';
                        $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        $hmt_trainer_duty = $row['tag_value'];

						if(mb_strlen($hmt_trainer_duty,"UTF-8") > 10){
							$duty = mb_substr($hmt_trainer_duty,0,10,"UTF-8").'...';
						}else{
							$duty = $hmt_trainer_duty;
						}

                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        $uabig = new user_applying_badge_infos_getter();
                        $badge_id = 3;
                        $tag_name = 'hmt_trainer_teaching_strong_point';
                        $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        $hmt_trainer_teaching_strong_point = $row['tag_value'];

						if(mb_strlen($hmt_trainer_teaching_strong_point,"UTF-8") > 10){
							$strong_point = mb_substr($hmt_trainer_teaching_strong_point,0,10,"UTF-8").'...';
						}else{
							$strong_point = $hmt_trainer_teaching_strong_point;
						}
		}

		if($badge_id_related_to_page == 18){

			$table = array(
				'1'=>'北京市',
				'2'=>'天津市',
				'3'=>'河北省',
				'4'=>'山西省',
				'5'=>'内蒙古自治区',
				'6'=>'辽宁省',
				'7'=>'吉林省',
				'8'=>'黑龙江省',
				'9'=>'上海市',
				'10'=>'江苏省',
				'11'=>'浙江省',
				'12'=>'安徽省',
				'13'=>'福建省',
				'14'=>'江西省',
				'15'=>'山东省',
				'16'=>'河南省',
				'17'=>'湖北省',
				'18'=>'湖南省',
				'19'=>'广东省',
				'20'=>'广西壮族自治区',
				'21'=>'海南省',
				'22'=>'重庆市',
				'23'=>'四川省',
				'24'=>'贵州省',
				'25'=>'云南省',
				'26'=>'西藏自治区',
				'27'=>'陕西省',
				'28'=>'甘肃省',
				'29'=>'青海省',
				'30'=>'宁夏回族自治区',
				'31'=>'新疆维吾尔自治区',
				'32'=>'台湾省',
				'33'=>'香港特别行政区',
				'34'=>'澳门特别行政区',
				'35'=>'海外',
				'36'=>'其他'
			);

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 18;
			$tag_name = 'cga_referee_level';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$cga_referee_level = $row['tag_value'];

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 18;
			$tag_name = 'cga_referee_judging_game_num';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$cga_referee_judging_game_num = $row['tag_value'];

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 18;
			$tag_name = 'cga_referee_native_place';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$cga_referee_native_place = $row['tag_value'];
			$cga_referee_native_place = $table[$cga_referee_native_place];

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 18;
			$tag_name = 'cga_referee_working_place';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$cga_referee_working_place = $row['tag_value'];
			$cga_referee_working_place = $table[$cga_referee_working_place];

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 18;
			$tag_name = 'cga_referee_personal_desc';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$cga_referee_personal_desc = $row['tag_value'];

			if(mb_strlen($cga_referee_personal_desc,"UTF-8") > 10){
				$personal_desc = mb_substr($cga_referee_personal_desc,0,10,"UTF-8").'...';
			}else{
				$personal_desc = $cga_referee_personal_desc;
			}

		}

		if($badge_id_related_to_page == 20){

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 20;
			$tag_name = 'company_name_of_practitioner';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$company_name_of_practitioner = $row['tag_value'];

			if(mb_strlen($company_name_of_practitioner,"UTF-8") > 10){
				$company_name = mb_substr($company_name_of_practitioner,0,10,"UTF-8").'...';
			}else{
				$company_name = $company_name_of_practitioner;
			}

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 20;
			$tag_name = 'duty_of_practitioner';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$duty_of_practitioner = $row['tag_value'];

			if(mb_strlen($duty_of_practitioner,"UTF-8") > 10){
				$duty = mb_substr($duty_of_practitioner,0,10,"UTF-8").'...';
			}else{
				$duty = $duty_of_practitioner;
			}

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 20;
			$tag_name = 'company_address_of_practitioner';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$company_address_of_practitioner = $row['tag_value'];

			if(mb_strlen($company_address_of_practitioner,"UTF-8") > 10){
				$company_address = mb_substr($company_address_of_practitioner,0,10,"UTF-8").'...';
			}else{
				$company_address = $company_address_of_practitioner;
			}

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 20;
			$tag_name = 'personal_desc_of_practitioner';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$personal_desc_of_practitioner = $row['tag_value'];

			if(mb_strlen($personal_desc_of_practitioner,"UTF-8") > 10){
				$personal_desc = mb_substr($personal_desc_of_practitioner,0,10,"UTF-8").'...';
			}else{
				$personal_desc = $personal_desc_of_practitioner;
			}

		}

		if($badge_id_related_to_page == 7){

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 7;
			$tag_name = 'company_name_of_course_manager';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$company_name_of_course_manager = $row['tag_value'];

			if(mb_strlen($company_name_of_course_manager,"UTF-8") > 10){
				$company_name = mb_substr($company_name_of_course_manager,0,10,"UTF-8").'...';
			}else{
				$company_name = $company_name_of_course_manager;
			}

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 7;
			$tag_name = 'company_address_of_course_manager';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$company_address_of_course_manager = $row['tag_value'];

			if(mb_strlen($company_address_of_course_manager,"UTF-8") > 10){
				$company_address = mb_substr($company_address_of_course_manager,0,10,"UTF-8").'...';
			}else{
				$company_address = $company_address_of_course_manager;
			}

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 7;
			$tag_name = 'personal_desc_of_course_manager';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$personal_desc_of_course_manager = $row['tag_value'];

			if(mb_strlen($personal_desc_of_course_manager,"UTF-8") > 10){
				$personal_desc = mb_substr($personal_desc_of_course_manager,0,10,"UTF-8").'...';
			}else{
				$personal_desc = $personal_desc_of_course_manager;
			}
		}

		if($badge_id_related_to_page == 19){

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 19;
			$tag_name = 'club_place_info1_of_caddie';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$club_place_info1_of_caddie = $row['tag_value'];
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/badge/class_common_district_getter.php');
			$cdg = new common_district_getter();
			$club_place_info1_of_caddie = $cdg->get_name_by_id($club_place_info1_of_caddie);

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 19;
			$tag_name = 'caddie_working_place_info1_1';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$caddie_working_place_info1_1 = $row['tag_value'];
			$caddie_working_place_info1_1 = $cdg->get_name_by_id($caddie_working_place_info1_1);

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 19;
			$tag_name = 'caddie_working_place_info2_1';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$caddie_working_place_info2_1 = $row['tag_value'];
			$caddie_working_place_info2_1 = $cdg->get_name_by_id($caddie_working_place_info2_1);

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 19;
			$tag_name = 'caddie_working_place_info3_1';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$caddie_working_place_info3_1 = $row['tag_value'];
			$caddie_working_place_info3_1 = $cdg->get_name_by_id($caddie_working_place_info3_1);

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 19;
			$tag_name = 'club_place_info2_of_caddie';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$club_place_info2_of_caddie = $row['tag_value'];
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/badge/class_common_field_getter.php');
			$cfg = new common_field_getter();
			$club_place_info2_of_caddie = $cfg->get_fieldname_by_id($club_place_info2_of_caddie).$cfg->get_fieldname_by_uid($club_place_info2_of_caddie);

			$club_place_info_of_caddie = $club_place_info1_of_caddie.'-'.$club_place_info2_of_caddie;
			if(mb_strlen($club_place_info_of_caddie,"UTF-8") > 10){
				$club_place_info = mb_substr($club_place_info_of_caddie,0,10,"UTF-8").'...';
			}else{
				$club_place_info = $club_place_info_of_caddie;
			}

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 19;
			$tag_name = 'caddie_working_place_info1_2';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$caddie_working_place_info1_2 = $row['tag_value'];
			$caddie_working_place_info1_2 = $cfg->get_fieldname_by_id($caddie_working_place_info1_2).$cfg->get_fieldname_by_uid($caddie_working_place_info1_2);

			$caddie_working_place_info1 = $caddie_working_place_info1_1.'-'.$caddie_working_place_info1_2;
			if(mb_strlen($caddie_working_place_info1,"UTF-8") > 10){
				$working_place_info1 = mb_substr($caddie_working_place_info1,0,10,"UTF-8").'...';
			}else{
				$working_place_info1 = $caddie_working_place_info1;
			}

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 19;
			$tag_name = 'caddie_working_place_info2_2';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$caddie_working_place_info2_2 = $row['tag_value'];
			$caddie_working_place_info2_2 = $cfg->get_fieldname_by_id($caddie_working_place_info2_2).$cfg->get_fieldname_by_uid($caddie_working_place_info2_2);

			$caddie_working_place_info2 = $caddie_working_place_info2_1.'-'.$caddie_working_place_info2_2;
			if(mb_strlen($caddie_working_place_info2,"UTF-8") > 10){
				$working_place_info2 = mb_substr($caddie_working_place_info2,0,10,"UTF-8").'...';
			}else{
				$working_place_info2 = $caddie_working_place_info2;
			}

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 19;
			$tag_name = 'caddie_working_place_info3_2';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$caddie_working_place_info3_2 = $row['tag_value'];
			$caddie_working_place_info3_2 = $cfg->get_fieldname_by_id($caddie_working_place_info3_2).$cfg->get_fieldname_by_uid($caddie_working_place_info3_2);

			$caddie_working_place_info3 = $caddie_working_place_info3_1.'-'.$caddie_working_place_info3_2;
			if(mb_strlen($caddie_working_place_info3,"UTF-8") > 10){
				$working_place_info3 = mb_substr($caddie_working_place_info3,0,10,"UTF-8").'...';
			}else{
				$working_place_info3 = $caddie_working_place_info3;
			}

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 19;
			$tag_name = 'caddie_beginning_working_date';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$caddie_beginning_working_date = $row['tag_value'];

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 19;
			$tag_name = 'caddie_birth_date';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$caddie_birth_date = $row['tag_value'];

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 19;
			$tag_name = 'caddie_personal_desc';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$caddie_personal_desc = $row['tag_value'];

			if(mb_strlen($caddie_personal_desc,"UTF-8") > 10){
				$personal_desc = mb_substr($caddie_personal_desc,0,10,"UTF-8").'...';
			}else{
				$personal_desc = $caddie_personal_desc;
			}

		}

		if($badge_id_related_to_page == 8){
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 8;
			$tag_name = 'lawn_expert_name_and_duty';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$lawn_expert_name_and_duty = $row['tag_value'];

			if(mb_strlen($lawn_expert_name_and_duty,"UTF-8") > 10){
				$name_and_duty = mb_substr($lawn_expert_name_and_duty,0,10,"UTF-8").'...';
			}else{
				$name_and_duty = $lawn_expert_name_and_duty;
			}

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 8;
			$tag_name = 'lawn_expert_personal_desc';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$lawn_expert_personal_desc = $row['tag_value'];

			if(mb_strlen($lawn_expert_personal_desc,"UTF-8") > 10){
				$personal_desc = mb_substr($lawn_expert_personal_desc,0,10,"UTF-8").'...';
			}else{
				$personal_desc = $lawn_expert_personal_desc;
			}
		}

		if($badge_id_related_to_page == 9){
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 9;
			$tag_name = 'expert_name_and_duty';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$expert_name_and_duty = $row['tag_value'];

			if(mb_strlen($expert_name_and_duty,"UTF-8") > 10){
				$name_and_duty = mb_substr($expert_name_and_duty,0,10,"UTF-8").'...';
			}else{
				$name_and_duty = $expert_name_and_duty;
			}

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 9;
			$tag_name = 'expert_personal_desc';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$expert_personal_desc = $row['tag_value'];

			if(mb_strlen($expert_personal_desc,"UTF-8") > 10){
				$personal_desc = mb_substr($expert_personal_desc,0,10,"UTF-8").'...';
			}else{
				$personal_desc = $expert_personal_desc;
			}
		}

		$first_six_record = $uabg->get_first_six_record_by_uid($uid);

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_judges_and_trainers_getter.php');
		$jatg = new judges_and_trainers_getter();
		$avg_score = round($jatg->get_avg_score_by_trainer_uid($uid),1);
		$rating_amount = $jatg->get_record_amount_by_trainer_uid($uid);

	}else{
		$badge_id_related_to_page = '';

	}
}else{
	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
	$uabg = new user_applying_badges_getter();
	$first_six_record = $uabg->get_first_six_record_by_uid($uid);
}

require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_common_member_profile_getter.php');
$cmpg = new common_member_profile_getter();
$realname = $cmpg->get_realname_by_uid($uid);
//

$templates='home/spacecp_profile';

include_once(template($templates));
//include template("home/spacecp_profile");

function profile_showerror($key, $extrainfo) {
	echo '<script>';
	echo 'parent.show_error("'.$key.'", "'.$extrainfo.'");';
	echo '</script>';
	exit();
}

function profile_showsuccess($message = '') {
	echo '<script type="text/javascript">';
	echo "parent.show_success('$message');";
	echo '</script>';
	exit();
}

?>