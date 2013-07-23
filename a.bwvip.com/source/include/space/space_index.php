<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: space_index.php 19160 2010-12-20 08:57:24Z liulanbo $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if(($_G['adminid'] == 1 && $_G['setting']['allowquickviewprofile'] && $_G['gp_view'] != 'admin' && $_G['gp_diy'] != 'yes') || defined('IN_MOBILE')) {
	dheader("Location:home.php?mod=space&uid=$space[uid]&do=profile");
}

require_once libfile('function/space');
$space = getspace($uid);
space_merge($space, 'field_home');
$userdiy = getuserdiydata($space);

if ($_GET['op'] == 'getmusiclist') {
	if(empty($space['uid'])) {
		exit();
	}
	$reauthcode = substr(md5($_G['authkey'].$space['uid']), 6, 16);
	if($reauthcode == $_GET['hash']) {
		space_merge($space,'field_home');
		$userdiy = getuserdiydata($space);
		$musicmsgs = $userdiy['parameters']['music'];
		$outxml = '<?xml version="1.0" encoding="UTF-8" ?>'."\n";
		$outxml .= '<playlist version="1">'."\n";
		$outxml .= '<mp3config>'."\n";
		$showmod = 'big' == $musicmsgs['config']['showmod'] ? 'true' : 'false';
		$outxml .= '<showdisplay>'.$showmod.'</showdisplay>'."\n";
		$outxml .= '<autostart>'.$musicmsgs['config']['autorun'].'</autostart>'."\n";
		$outxml .= '<showplaylist>true</showplaylist>'."\n";
		$outxml .= '<shuffle>'.$musicmsgs['config']['shuffle'].'</shuffle>'."\n";
		$outxml .= '<repeat>all</repeat>'."\n";
		$outxml .= '<volume>100</volume>';
		$outxml .= '<linktarget>_top</linktarget> '."\n";
		$outxml .= '<backcolor>0x'.substr($musicmsgs['config']['crontabcolor'], -6).'</backcolor> '."\n";
		$outxml .= '<frontcolor>0x'.substr($musicmsgs['config']['buttoncolor'], -6).'</frontcolor>'."\n";
		$outxml .= '<lightcolor>0x'.substr($musicmsgs['config']['fontcolor'], -6).'</lightcolor>'."\n";
		$outxml .= '<jpgfile>'.$musicmsgs['config']['crontabbj'].'</jpgfile>'."\n";
		$outxml .= '<callback></callback> '."\n";
		$outxml .= '</mp3config>'."\n";
		$outxml .= '<trackList>'."\n";
		foreach ($musicmsgs['mp3list'] as $value){
			$outxml .= '<track><annotation>'.$value['mp3name'].'</annotation><location>'.$value['mp3url'].'</location><image>'.$value['cdbj'].'</image></track>'."\n";
		}
		$outxml .= '</trackList></playlist>';
		$outxml = diconv($outxml, CHARSET, 'UTF-8');
		obclean();
		@header("Expires: -1");
		@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
		@header("Pragma: no-cache");
		@header("Content-type: application/xml; charset=utf-8");
		echo $outxml;
	}
	exit();

}else{

	$viewuids = $_G['cookie']['viewuids']?explode('_', $_G['cookie']['viewuids']):array();
	if(!$_G['setting']['preventrefresh'] || ($_G['uid'] && !$space['self'] && !in_array($space['uid'], $viewuids))) {
		member_count_update($space['uid'], array('views' => 1));
		$viewuids[$space['uid']] = $space['uid'];
		dsetcookie('viewuids', implode('_', $viewuids));
	}

	if(!$space['self'] && $_G['uid']) {
		$query = DB::query("SELECT dateline FROM ".DB::table('home_visitor')." WHERE uid='$space[uid]' AND vuid='$_G[uid]'");
		$visitor = DB::fetch($query);
		$is_anonymous = empty($_G['cookie']['anonymous_visit_'.$_G['uid'].'_'.$space['uid']]) ? 0 : 1;
		if(empty($visitor['dateline'])) {
			$setarr = array(
				'uid' => $space['uid'],
				'vuid' => $_G['uid'],
				'vusername' => $is_anonymous ? '' : $_G['username'],
				'dateline' => $_G['timestamp']
			);
			DB::insert('home_visitor', $setarr, 0, true);
			show_credit();
		} else {
			if($_G['timestamp'] - $visitor['dateline'] >= 300) {
				DB::update('home_visitor', array('dateline'=>$_G['timestamp'], 'vusername'=>$is_anonymous ? '' : $_G['username']), array('uid'=>$space['uid'], 'vuid'=>$_G['uid']));
			}
			if($_G['timestamp'] - $visitor['dateline'] >= 3600) {
				show_credit();
			}
		}
		updatecreditbyaction('visit', 0, array(), $space['uid']);
	}

	if($do != 'profile' && !ckprivacy($do, 'view')) {
		$_G['privacy'] = 1;
		require_once libfile('space/profile', 'include');
		include template('home/space_privacy');
		exit();
	}

	$widths = getlayout($userdiy['currentlayout']);
	$leftlist = formatdata($userdiy, 'left', $space);
	$centerlist = formatdata($userdiy, 'center', $space);
	$rightlist = formatdata($userdiy, 'right', $space);

	dsetcookie('home_diymode', 1);



	//相册，动态，博客，访客，好友，群组
	$spacealbum = getval($leftlist['album'], '</div>');
	$spacefeed = getval($centerlist['feed'], '</div>');
	$spaceblog = getval($centerlist['blog'], '</div>');
	$spacevisitor = getval($rightlist['visitor'], '</div>');
	$spacefriend = getval($rightlist['friend'], '</div>');
	$spacegroup = getval($rightlist['group'], '</div>');
}

$navtitle = !empty($space['spacename']) ? $space['spacename'] : lang('space', 'sb_space', array('who' => $space['username']));
$metakeywords = lang('space', 'sb_space', array('who' => $space['username']));
$metadescription = lang('space', 'sb_space', array('who' => $space['username']));
$space['medals'] = getuserprofile('medals');
if($space['medals']) {
	loadcache('medals');
	foreach($space['medals'] = explode("\t", $space['medals']) as $key => $medalid) {
		list($medalid, $medalexpiration) = explode("|", $medalid);
		if(isset($_G['cache']['medals'][$medalid]) && (!$medalexpiration || $medalexpiration > TIMESTAMP)) {
			$space['medals'][$key] = $_G['cache']['medals'][$medalid];
		} else {
			unset($space['medals'][$key]);
		}
	}
}

/*查询 用户的身份 Angf do it 2012/4/3*/
$user_apply_type ='';
$user_identity_result = DB::fetch_first(" SELECT `applytype` from ".DB::table('home_apply')." where uid = '".$_G['uid']."'");
if($user_identity_result){
	$user_apply_type = $user_identity_result['applytype']==0 ? 'qc_card': null;
}



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

			if(mb_strlen($cga_referee_judging_game_num,"UTF-8") > 10){
				$judging_game_num = mb_substr($cga_referee_judging_game_num,0,10,"UTF-8").'...';
			}else{
				$judging_game_num = $cga_referee_judging_game_num;
			}

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

			if(mb_strlen($cga_referee_personal_desc,"UTF-8") > 44){
				$personal_desc = mb_substr($cga_referee_personal_desc,0,44,"UTF-8").'...';
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

			if(mb_strlen($personal_desc_of_practitioner,"UTF-8") > 44){
				$personal_desc = mb_substr($personal_desc_of_practitioner,0,44,"UTF-8").'...';
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

			if(mb_strlen($personal_desc_of_course_manager,"UTF-8") > 44){
				$personal_desc = mb_substr($personal_desc_of_course_manager,0,44,"UTF-8").'...';
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
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_common_district_getter.php');
			$cdg = new common_district_getter();

			if($club_place_info1_of_caddie != ''){
				$club_place_info1_of_caddie = $cdg->get_name_by_id($club_place_info1_of_caddie);
			}

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 19;
			$tag_name = 'caddie_working_place_info1_1';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$caddie_working_place_info1_1 = $row['tag_value'];

			if($caddie_working_place_info1_1 != ''){
				$caddie_working_place_info1_1 = $cdg->get_name_by_id($caddie_working_place_info1_1);
			}

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 19;
			$tag_name = 'caddie_working_place_info2_1';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$caddie_working_place_info2_1 = $row['tag_value'];
			if($caddie_working_place_info2_1 != ''){
				$caddie_working_place_info2_1 = $cdg->get_name_by_id($caddie_working_place_info2_1);
			}

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 19;
			$tag_name = 'caddie_working_place_info3_1';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$caddie_working_place_info3_1 = $row['tag_value'];
			if($caddie_working_place_info3_1 != ''){
				$caddie_working_place_info3_1 = $cdg->get_name_by_id($caddie_working_place_info3_1);
			}

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 19;
			$tag_name = 'club_place_info2_of_caddie';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$club_place_info2_of_caddie = $row['tag_value'];
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_common_field_getter.php');
			$cfg = new common_field_getter();

			if($club_place_info2_of_caddie != '' && $club_place_info2_of_caddie!=''){
				$club_place_info2_of_caddie = $cfg->get_fieldname_by_id($club_place_info2_of_caddie).$cfg->get_fieldname_by_uid($club_place_info2_of_caddie);
			}

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

			if($caddie_working_place_info1_2 != ''){
					$caddie_working_place_info1_2 = $cfg->get_fieldname_by_id($caddie_working_place_info1_2).$cfg->get_fieldname_by_uid($caddie_working_place_info1_2);
				}



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
			if($caddie_working_place_info2_2 != ''){
					$caddie_working_place_info2_2 = $cfg->get_fieldname_by_id($caddie_working_place_info2_2).$cfg->get_fieldname_by_uid($caddie_working_place_info2_2);
				}


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

			if($caddie_working_place_info3_2 != ''){
					$caddie_working_place_info3_2 = $cfg->get_fieldname_by_id($caddie_working_place_info3_2).$cfg->get_fieldname_by_uid($caddie_working_place_info3_2);
				}


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

			if(mb_strlen($caddie_personal_desc,"UTF-8") > 22){
				$personal_desc = mb_substr($caddie_personal_desc,0,22,"UTF-8").'...';
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

			if(mb_strlen($lawn_expert_name_and_duty,"UTF-8") > 22){
				$name_and_duty = mb_substr($lawn_expert_name_and_duty,0,22,"UTF-8").'...';
			}else{
				$name_and_duty = $lawn_expert_name_and_duty;
			}

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 8;
			$tag_name = 'lawn_expert_personal_desc';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$lawn_expert_personal_desc = $row['tag_value'];

			if(mb_strlen($lawn_expert_personal_desc,"UTF-8") > 88){
				$personal_desc = mb_substr($lawn_expert_personal_desc,0,88,"UTF-8").'...';
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

			if(mb_strlen($expert_name_and_duty,"UTF-8") > 22){
				$name_and_duty = mb_substr($expert_name_and_duty,0,22,"UTF-8").'...';
			}else{
				$name_and_duty = $expert_name_and_duty;
			}

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 9;
			$tag_name = 'expert_personal_desc';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$expert_personal_desc = $row['tag_value'];

			if(mb_strlen($expert_personal_desc,"UTF-8") > 88){
				$personal_desc = mb_substr($expert_personal_desc,0,88,"UTF-8").'...';
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

if($badge_id_related_to_page == ''){
	$badge_id_related_to_page = 0;
}

$templates='home/'.$gropid.'_index';

require_once(template($templates));
//include_once(template('home/space_index'));



function getval($str, $flag) {
	$len = stripos($str, $flag);
	$val = substr($str, $len);
	return $val;
}
function formatdata($data, $position, $space) {
	$list = array();
	foreach ((array)$data['block']['frame`frame1']['column`frame1_'.$position] as $blockname => $blockdata) {
		if (strpos($blockname, 'block`') === false || empty($blockdata) || !isset($blockdata['attr']['name'])) continue;
		$name = $blockdata['attr']['name'];
		if(check_ban_block($name, $space)) {
			$list[$name] = getblockhtml($name, $data['parameters'][$name]);
		}
	}
	return $list;
}

function show_credit() {
	global $_G, $space;

	$showinfo = DB::fetch_first("SELECT credit, unitprice FROM ".DB::table('home_show')." WHERE uid='$space[uid]'");
	if($showinfo['credit'] > 0) {
		$showinfo['unitprice'] = intval($showinfo['unitprice']);
		if($showinfo['credit'] <= $showinfo['unitprice']) {
			notification_add($space['uid'], 'show', 'show_out');
			DB::delete('home_show', array('uid' => $space['uid']));
		} else {
			DB::query("UPDATE ".DB::table('home_show')." SET credit=credit-'$showinfo[unitprice]' WHERE uid='{$space[uid]}' AND credit>0");
		}
	}
}



?>