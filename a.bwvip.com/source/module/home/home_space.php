<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: home_space.php 22839 2011-05-25 08:05:18Z monkey $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


$zyuid = !empty($_GET['uid']) ? $_GET['uid'] : intval($_G['uid']);
//资料
$userinfo = DB::fetch_first("select m.username, m.credits, mp.realname, mp.resideprovince, mp.residecity, mp.company, mp.site, mp.bio, mc.views, jm.fans_count, jm.follow_count, jm.topic_count, jm.validate from ".DB::table('common_member')." as m left join ".DB::table('common_member_profile')." as mp on mp.uid=m.uid left join ".DB::table('common_member_count')." as mc on mc.uid=m.uid left join jishigou_members as jm on jm.uid=m.uid where m.uid='$zyuid' order by m.uid desc");

if($_GET['username']) {
	$member = DB::fetch_first("SELECT uid FROM ".DB::table('common_member')." WHERE username='$_GET[username]' LIMIT 1");
	if(empty($member)) {
		//showmessage('space_does_not_exist');
	 header ( "Location: /errore.php " );
	}
	$uid = $member['uid'];
}

$dos = array('index', 'doing', 'blog', 'album', 'friend', 'wall',
	'notice', 'share', 'home', 'pm', 'videophoto', 'favorite',
	'thread', 'trade', 'poll', 'activity', 'debate', 'reward', 'profile', 'plugin', 'common', 'action', 'saishiinfo', 'video', 'member','ershou','mingpian', 'rank' , 'mxlist','weiyuan','shop');
	//添加action

$do = (!empty($_GET['do']) && in_array($_GET['do'], $dos))?$_GET['do']:'index';

if(in_array($do, array('home', 'doing', 'blog', 'album', 'share', 'wall'))) {
	if(!$_G['setting']['homestatus']) {
		showmessage('home_status_off');
	}
} else {
	$_G['mnid'] = 'mn_common';
}

if(empty($uid) || in_array($do, array('notice', 'pm'))) $uid = $_G['uid'];

if($uid) {
	$space = getspace($uid);
	if(empty($space)) {
		//showmessage('space_does_not_exist');
		//server_transfer("404.html");
header('HTTP/1.1 404 Not Found'); 
header("status: 404 Not Found"); 
server_transfer("404.html");
	 
	}
}

if(empty($space)) {
	if(in_array($do, array('doing', 'blog', 'album', 'share', 'home', 'thread', 'trade', 'poll', 'activity', 'debate', 'reward', 'group'))) {
		$_GET['view'] = 'all';
		$space['uid'] = 0;
	} else {
		showmessage('login_before_enter_home', null, array(), array('showmsg' => true, 'login' => 1));
	}
} else {

	$navtitle = $space['username'];

	if($space['status'] == -1 && $_G['adminid'] != 1) {
		showmessage('space_has_been_locked');
	}

	if(in_array($space['groupid'], array(4, 5, 6)) && ($_G['adminid'] != 1 && $space['uid'] != $_G['uid'])) {
		$_GET['do'] = $do = 'profile';
	}

	if($do != 'profile' && $do != 'index' && !ckprivacy($do, 'view')) {
		$_G['privacy'] = 1;
		require_once libfile('space/profile', 'include');
		include template('home/space_privacy');
		exit();
	}

	if(!$space['self'] && $_GET['view'] != 'eccredit') $_GET['view'] = 'me';

	get_my_userapp();

	get_my_app();
}

$diymode = 0;

$seccodecheck = $_G['setting']['seccodestatus'] & 4;
$secqaacheck = $_G['setting']['secqaa']['status'] & 2;

//add by xgw on 2012年3月14日
$bgset=DB::query("SELECT bgcolor,headerpic,bgpic FROM pre_qiye_logo WHERE uid=".$uid);
$bgsetarr=DB::fetch($bgset);
//echo "SELECT bgcolor,headerpic,bgpic FROM pre_qiye_logo WHERE uid=".$_G["uid"];
//var_dump($bgsetarr);
//add end



/*修改弹窗状态2012/5/8*/
if($_G['gp_update_status']=='window')
{
    if($_G['gp_bugletgx_id']){
      DB::update('buglet_gx',array('is_window'=>1),array('id'=>$_G['gp_bugletgx_id']));exit;
	}
}



/*获取小喇叭的信息·2012/5/8*/
$trumpet_msg = DB::fetch_first(" SELECT  bgx.*,bmsg.*  FROM ".DB::table('buglet_gx')." as bgx LEFT JOIN ".DB::table('buglet_msg')." as bmsg ON bmsg.buglet_id = bgx.msgid where bgx.touid ='".$_G['uid']."' and bgx.is_window = 0 ");
$is_buglet_msg = !empty($trumpet_msg) ? 1 : 0;






$visitname = $space['self'] ? '我' : 'Ta ';

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

function server_transfer($dest)

{

   // global ...; // 把希望在新页面中用到的本页变量或者自定义的全局变量列在这里

    include $dest; // 运行新脚本

    exit; // 退出本脚本

}

 
require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_common_member_profile_getter.php');
$cmpg = new common_member_profile_getter();
$realname = $cmpg->get_realname_by_uid($uid);

$bio = $cmpg->get_bio_by_uid($uid);

$first_six_badge_amount = count($first_six_record);

//

require_once libfile('space/'.$do, 'include');

?>