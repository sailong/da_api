<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: home_spacecp.php 22021 2011-04-20 07:00:41Z congyushuai $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require_once libfile('function/spacecp');
require_once libfile('function/magic');

$zyuid = empty($_G['uid']) ? 0 : intval($_G['uid']);
//资料（用户名，积分，真实姓名，现居地，公司，官网，简介，访问量，粉丝数，关注数，话题数，加v）
$userinfo = DB::fetch_first("select m.username, m.credits, mp.realname, mp.resideprovince, mp.residecity, mp.company, mp.site, mp.bio, mc.views, jm.fans_count, jm.follow_count, jm.topic_count, jm.validate from ".DB::table('common_member')." as m left join ".DB::table('common_member_profile')." as mp on mp.uid=m.uid left join ".DB::table('common_member_count')." as mc on mc.uid=m.uid left join jishigou_members as jm on jm.uid=m.uid where m.uid='$zyuid' order by m.uid desc");


$acs = array('space', 'doing', 'upload', 'comment', 'blog', 'album', 'relatekw', 'common', 'class',
	'swfupload', 'poke', 'friend', 'eccredit', 'favorite',
	'avatar', 'profile', 'theme', 'feed', 'privacy', 'pm', 'share', 'invite','sendmail',
	'credit', 'usergroup', 'domain', 'click','magic', 'top', 'videophoto', 'index', 'plugin', 'search', 'promotion', 'myscore', 'score', 'video', 'weibo', 'spacelink','jigou','huiyuan','qiye','csqy','jbqc','test','ssinfo','action','vupload','hygl', 'apply', 'field','zanzhushang','jguser','qylogo','bgset','ershou','laba','blog_recommend','yuangong','mingpian','mingpianall','resultcard', 'scoreall','usefiled','xiaolaba','adminavatar','weiyuan','shopping','shop','ulist','fenzu','dz_space_recommend','event_data_imp');

$ac = (empty($_GET['ac']) || !in_array($_GET['ac'], $acs))?'profile':$_GET['ac'];
$op = empty($_GET['op'])?'':$_GET['op'];
$_G['mnid'] = 'mn_common';

if(in_array($ac, array('privacy'))) {
	if(!$_G['setting']['homestatus']) {
		showmessage('home_status_off');
	}
}

if(empty($_G['uid'])) {
	if($_SERVER['REQUEST_METHOD'] == 'GET') {
		dsetcookie('_refer', rawurlencode($_SERVER['REQUEST_URI']));
	} else {
		dsetcookie('_refer', rawurlencode('home.php?mod=spacecp&ac='.$ac));
	}
	showmessage('to_login', '', array(), array('showmsg' => true, 'login' => 1));
}

$space = getspace($_G['uid']);
if(empty($space)) {
	showmessage('space_does_not_exist');
}
space_merge($space, 'field_home');

if(($space['status'] == -1 || in_array($space['groupid'], array(4, 5, 6))) && $ac != 'usergroup') {
	showmessage('space_has_been_locked');
}

$actives = array($ac => ' class="a"');

$seccodecheck = $_G['group']['seccode'] ? $_G['setting']['seccodestatus'] & 4 : 0;
$secqaacheck = $_G['group']['seccode'] ? $_G['setting']['secqaa']['status'] & 2 : 0;

$navtitle = lang('core', 'title_setup');

if(lang('core', 'title_memcp_'.$ac)) {
	$navtitle = lang('core', 'title_memcp_'.$ac);
}

//判断这个用户有没有商城的权限  add by xgw on 2012年6月18日
$shop_authority_flag=0;
$shop_authority_query="SELECT uid FROM `pre_shoping_user` where uid='".$_G["uid"]."'";
$shop_authority_rows=DB::fetch_first($shop_authority_query);
if($shop_authority_rows["uid"]>0)	$shop_authority_flag=1;

//echo libfile('spacecp/'.$ac, 'include');

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

require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_common_member_profile_getter.php');
$cmpg = new common_member_profile_getter();
$realname = $cmpg->get_realname_by_uid($uid);

$bio = $cmpg->get_bio_by_uid($uid);

$first_six_badge_amount = count($first_six_record);
//

#echo libfile('spacecp/'.$ac, 'include');

require_once libfile('spacecp/'.$ac, 'include');

?>
