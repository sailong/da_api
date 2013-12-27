<?php
	
	if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
		exit('Access Denied');
	}
	
	cpheader();

	if($operation == 'list') {
		$len_per_page = 20;
		
		$page = max(1, $_G['page']);
		$start = ($page - 1) * $len_per_page;

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
		$uabg = new user_applying_badges_getter();
		$record_amount = $uabg->get_record_amount_by_automatic_verifying(0);

		$multipage = multi($record_amount, $len_per_page, $page, ADMINSCRIPT."?action=badge&operation=list&submit=yes".$urladd.'&want_search_num='.$len_per_page);

		$_G['lang']['admincp']['badge_list'] = '徽章申请管理';
		showsubmenu('badge_list');

		$uabg = new user_applying_badges_getter();
		$rows = $uabg->get_record_by_automatic_verifying_and_start_and_len_per_page(0,$start,$len_per_page);

		$_G['lang']['admincp']['badge_list_amount_desc'] = ' ';
		$table_header = '';
		showtableheader(cplang('badge_list_amount_desc', array()).$table_header);
		$_G['lang']['admincp']['badge_list_realname_desc'] = '姓名';
		$_G['lang']['admincp']['badge_list_badge_type_desc'] = '申请的徽章类型';
		$_G['lang']['admincp']['badge_list_certificate_desc'] = '申请信息';
		$_G['lang']['admincp']['badge_list_operating_desc'] = '操作';
		showsubtitle(array('badge_list_realname_desc','badge_list_badge_type_desc','badge_list_certificate_desc','badge_list_operating_desc'));

		
		
		for($i=0;$i<count($rows);$i++){
			$uid = $rows[$i]["uid"];
			
			
			$handle_passing_link = '<a href="admin.php?action=badge&operation=handle_passing&uid='.$uid.'&badge_id='.$rows[$i]["badge_id"].'&page='.$page.'">审核通过</a>';
			$handle_canceling_passing_link = '<a href="admin.php?action=badge&operation=handle_canceling_passing&uid='.$uid.'&badge_id='.$rows[$i]["badge_id"].'&page='.$page.'">取消审核通过</a>';
			
			//$handle_deleting_link = '<a href="admin.php?action=badge&operation=handle_deleting&uid='.$uid.'&badge_type='.$rows[$i]["badge_type"].'">删除</a>';
			$handle_deleting_link = '';
			
			if($rows[$i]["badge_id"] == 2){

				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 2;
                        	$tag_name = 'org_cga_trainer_belonging';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$org_cga_trainer_belonging = $row['tag_value'];
				$el = '<p>所属机构：'.$org_cga_trainer_belonging.'</p>';

				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 2;
                        	$tag_name = 'cga_trainer_duty';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$cga_trainer_duty = $row['tag_value'];
				$el = $el.'<p>职务：'.$cga_trainer_duty.'</p>';

                        	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 2;
                        	$tag_name = 'cga_trainer_teaching_strong_point';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$cga_trainer_teaching_strong_point = $row['tag_value'];
				$el = $el.'<p>教学擅长：'.$cga_trainer_teaching_strong_point.'</p>';

				$badge = '中高协教练徽章'.$rows[$i]["badge_id"];
				$img_url = 'apply_badge.php?mod=reading_cga_trainer_certificate&uid='.$uid;
				$img_original_size_url = 'apply_badge.php?mod=reading_cga_trainer_certificate_original_size&uid='.$uid;
				
				$el = $el.'<p><a target="_blank" href="'.$img_original_size_url.'"><img src="'.$img_url.'"/></a></p>';

				

			}

			if($rows[$i]["badge_id"] == 1){

				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 1;
                        	$tag_name = 'org_club_trainer_belonging';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$org_club_trainer_belonging = $row['tag_value'];
				$el = '<p>所属机构：'.$org_club_trainer_belonging.'</p>';

                        	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 1;
                        	$tag_name = 'club_trainer_duty';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$club_trainer_duty = $row['tag_value'];
				$el = $el.'<p>职务：'.$club_trainer_duty.'</p>';

                        	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 1;
                        	$tag_name = 'club_trainer_teaching_strong_point';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$club_trainer_teaching_strong_point = $row['tag_value'];
				$el = $el.'<p>教学擅长：'.$club_trainer_teaching_strong_point.'</p>';

				$badge = '俱乐部教练徽章'.$rows[$i]["badge_id"];
				$img_url = 'apply_badge.php?mod=reading_club_trainer_certificate&uid='.$uid;
				$img_original_size_url = 'apply_badge.php?mod=reading_club_trainer_certificate_original_size&uid='.$uid;
				$el = $el.'<p><a target="_blank" href="'.$img_original_size_url.'"><img src="'.$img_url.'"/></a></p>';
				
			}
			
			if($rows[$i]["badge_id"] == 3){

				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 3;
                        	$tag_name = 'org_hmt_trainer_belonging';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$org_hmt_trainer_belonging = $row['tag_value'];
				$el = '<p>所属机构：'.$org_hmt_trainer_belonging.'</p>';

                        	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 3;
                        	$tag_name = 'hmt_trainer_duty';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$hmt_trainer_duty = $row['tag_value'];
				$el = $el.'<p>职务：'.$hmt_trainer_duty.'</p>';

                        	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 3;
                        	$tag_name = 'hmt_trainer_teaching_strong_point';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$hmt_trainer_teaching_strong_point = $row['tag_value'];
				$el = $el.'<p>教学擅长：'.$hmt_trainer_teaching_strong_point.'</p>';

				$badge = '港澳台教练徽章'.$rows[$i]["badge_id"];
				$img_url = 'apply_badge.php?mod=reading_hmt_trainer_certificate&uid='.$uid;
				$img_original_size_url = 'apply_badge.php?mod=reading_hmt_trainer_certificate_original_size&uid='.$uid;
				$el = $el.'<p><a target="_blank" href="'.$img_original_size_url.'"><img src="'.$img_url.'"/></a></p>';
                        }

			if($rows[$i]["badge_id"] == 4){

				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 4;
                        	$tag_name = 'org_foreign_trainer_belonging';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$org_foreign_trainer_belonging = $row['tag_value'];
				$el = '<p>所属机构：'.$org_foreign_trainer_belonging.'</p>';

                        	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 4;
                        	$tag_name = 'foreign_trainer_duty';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$foreign_trainer_duty = $row['tag_value'];
				$el = $el.'<p>职务：'.$foreign_trainer_duty.'</p>';

                        	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 4;
                        	$tag_name = 'foreign_trainer_teaching_strong_point';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$foreign_trainer_teaching_strong_point = $row['tag_value'];
				$el = $el.'<p>教学擅长：'.$foreign_trainer_teaching_strong_point.'</p>';

				$badge = '外籍教练徽章'.$rows[$i]["badge_id"];
				$img_url = 'apply_badge.php?mod=reading_foreign_trainer_certificate&uid='.$uid;
				$img_original_size_url = 'apply_badge.php?mod=reading_foreign_trainer_certificate_original_size&uid='.$uid;
				$el = $el.'<p><a target="_blank" href="'.$img_original_size_url.'"><img src="'.$img_url.'"/></a></p>';
			}

			if($rows[$i]["badge_id"] == 7){

				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 7;
                        	$tag_name = 'company_name_of_course_manager';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$company_name_of_course_manager = $row['tag_value'];

                        	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 7;
                        	$tag_name = 'company_address_of_course_manager';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$company_address_of_course_manager = $row['tag_value'];

                        	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 7;
                        	$tag_name = 'personal_desc_of_course_manager';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$personal_desc_of_course_manager = $row['tag_value'];

                                $badge = '球场总经理徽章'.$rows[$i]["badge_id"];
				$el = '<p>公司名称：'.$company_name_of_course_manager.'</p>';
                                $el = $el.'<p>公司地址：'.$company_address_of_course_manager.'</p>';
                                $el = $el.'<p>个人简介：'.$personal_desc_of_course_manager.'</p>';
                        }

			if($rows[$i]["badge_id"] == 20){

				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 20;
                        	$tag_name = 'company_name_of_practitioner';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$company_name_of_practitioner = $row['tag_value'];

                        	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 20;
                        	$tag_name = 'duty_of_practitioner';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$duty_of_practitioner = $row['tag_value'];

                        	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 20;
                        	$tag_name = 'company_address_of_practitioner';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$company_address_of_practitioner = $row['tag_value'];

				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 20;
                        	$tag_name = 'personal_desc_of_practitioner';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$personal_desc_of_practitioner = $row['tag_value'];

                                $badge = '高尔夫从业者徽章'.$rows[$i]["badge_id"];
				$el = '<p>公司名称：'.$company_name_of_practitioner.'</p>';
                                $el = $el.'<p>职务：'.$duty_of_practitioner.'</p>';
                                $el = $el.'<p>公司地址：'.$company_address_of_practitioner.'</p>';
                                $el = $el.'<p>个人简介：'.$personal_desc_of_practitioner.'</p>';
                        }

			if($rows[$i]["badge_id"] == 19){

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

				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/badge/class_common_field_getter.php');
				$cfg = new common_field_getter();
				$club_place_info2_of_caddie = $cfg->get_fieldname_by_id($club_place_info2_of_caddie).$cfg->get_fieldname_by_uid($club_place_info2_of_caddie);

				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 19;
                        	$tag_name = 'caddie_working_place_info1_2';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$caddie_working_place_info1_2 = $row['tag_value'];

				if($caddie_working_place_info1_2 != ''){
					$caddie_working_place_info1_2 = $cfg->get_fieldname_by_id($caddie_working_place_info1_2).$cfg->get_fieldname_by_uid($caddie_working_place_info1_2);
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
				

				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 19;
                        	$tag_name = 'caddie_working_place_info3_2';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$caddie_working_place_info3_2 = $row['tag_value'];

				if($caddie_working_place_info3_2 != ''){
					$caddie_working_place_info3_2 = $cfg->get_fieldname_by_id($caddie_working_place_info3_2).$cfg->get_fieldname_by_uid($caddie_working_place_info3_2);
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

                                $badge = '球童徽章';
				$el = '<p>所属俱乐部：'.$club_place_info1_of_caddie.'-'.$club_place_info2_of_caddie.'</p>';
                                $el = $el.'<p>入行时间：'.$caddie_beginning_working_date.'</p>';
                                $el = $el.'<p>出生日期：'.$caddie_birth_date.'</p>';
                                $el = $el.'<p>工作地点1：'.$caddie_working_place_info1_1.'-'.$caddie_working_place_info1_2.'</p>';
                                $el = $el.'<p>工作地点2：'.$caddie_working_place_info2_1.'-'.$caddie_working_place_info2_2.'</p>';
                                $el = $el.'<p>工作地点3：'.$caddie_working_place_info3_1.'-'.$caddie_working_place_info3_2.'</p>';
				$el = $el.'<p>个人简介：'.$caddie_personal_desc.'</p>';
				
                        }

			if($rows[$i]["badge_id"] == 18){

				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
                        	$uafg = new uploaded_avatar_filename_getter();
                        	$cga_referee_certificate_filename = $uafg->get($uid,$uploaddir,'_cga_referee_certificate');

                        	$uafg = new uploaded_avatar_filename_getter();
                        	$cga_referee_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_cga_referee_certificate_original_size');

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

                        	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 18;
                        	$tag_name = 'cga_referee_working_place';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$cga_referee_working_place = $row['tag_value'];

                        	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 18;
                        	$tag_name = 'cga_referee_personal_desc';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$cga_referee_personal_desc = $row['tag_value'];

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

				$el = '<p>裁判级别：'.$cga_referee_level.'</p>';
				$el = $el.'<p>执裁场次：'.$cga_referee_judging_game_num.'</p>';
				$el = $el.'<p>籍贯：'.$table[$cga_referee_native_place].'</p>';
				$el = $el.'<p>工作地点：'.$table[$cga_referee_working_place].'</p>';
				$el = $el.'<p>个人简介：'.$cga_referee_personal_desc.'</p>';

				$badge = '中高协裁判徽章'.$rows[$i]["badge_id"];
				$img_url = 'apply_badge.php?mod=reading_cga_referee_certificate&uid='.$uid;
				$img_original_size_url = 'apply_badge.php?mod=reading_cga_referee_certificate_original_size&uid='.$uid;
				$el = $el.'<p><a target="_blank" href="'.$img_original_size_url.'"><img src="'.$img_url.'"/></a></p>';
			}

			if($rows[$i]["badge_id"] == 8){

				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 8;
                        	$tag_name = 'lawn_expert_name_and_duty';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$lawn_expert_name_and_duty = $row['tag_value'];

                        	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 8;
                        	$tag_name = 'lawn_expert_personal_desc';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$lawn_expert_personal_desc = $row['tag_value'];

                                $badge = '草坪专家徽章'.$rows[$i]["badge_id"];
				$el = '<p>姓名及职务：'.$lawn_expert_name_and_duty.'</p>';
                                $el = $el.'<p>个人简介：'.$lawn_expert_personal_desc.'</p>';
                        }

			if($rows[$i]["badge_id"] == 9){

				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 9;
                        	$tag_name = 'expert_name_and_duty';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$expert_name_and_duty = $row['tag_value'];

                        	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 9;
                        	$tag_name = 'expert_personal_desc';
                        	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        	$expert_personal_desc = $row['tag_value'];

                                $badge = '专家徽章'.$rows[$i]["badge_id"];
				$el = '<p>姓名及职务：'.$expert_name_and_duty.'</p>';
                                $el = $el.'<p>个人简介：'.$expert_personal_desc.'</p>';
                        }

			if($rows[$i]["badge_id"] == 22){
                                $badge = '中国高尔夫媒体联盟会员徽章'.$rows[$i]["badge_id"];
				$el = '';
                        }

			if($rows[$i]["badge_id"] == 13){
				$badge = '皇冠车主徽章'.$rows[$i]["badge_id"];
				$img_url = 'apply_badge.php?mod=reading_toyota_crown_owner_certificate&uid='.$uid;
				$img_original_size_url = 'apply_badge.php?mod=reading_toyota_crown_owner_certificate_original_size&uid='.$uid;
				$el = '<p><a target="_blank" href="'.$img_original_size_url.'"><img src="'.$img_url.'"/></a></p>';
			}

			if($rows[$i]["badge_id"] == 14){
				$badge = '电信钻石卡用户徽章'.$rows[$i]["badge_id"];
				$img_url = 'apply_badge.php?mod=reading_ct_diamond_card_pic&uid='.$uid;
				$img_original_size_url = 'apply_badge.php?mod=reading_ct_diamond_card_pic_original_size&uid='.$uid;
				//$el = '<img src="'.$img_url.'"/>';
				$el = '<p><a target="_blank" href="'.$img_original_size_url.'"><img src="'.$img_url.'"/></a></p>';
			}

			if($rows[$i]["badge_id"] == 21){
                                $badge = '泰勒梅会员徽章'.$rows[$i]["badge_id"];
				$el = '';
                        }

			if($rows[$i]["badge_id"] == 23){
                                $badge = '劳力士会员徽章'.$rows[$i]["badge_id"];
				$el = '';
                        }

			if($rows[$i]["badge_id"] == 24){
                                $badge = '紫金理财会员徽章'.$rows[$i]["badge_id"];
				$el = '';
                        }

			if($rows[$i]["badge_id"] == 25){
                                $badge = '帕米尔矿泉水会员徽章'.$rows[$i]["badge_id"];
				$el = '';
                        }

			if($rows[$i]["badge_id"] == 26){
                                $badge = '耐克高尔夫会员徽章'.$rows[$i]["badge_id"];
				$el = '';
                        }

			if($rows[$i]["getting_badge_or_not"] == 0){
				echo showtablerow('', array('class="td25"', 'class="td28"'),array($rows[$i]["realname"],$badge,$el,$handle_passing_link.'&nbsp;'.$handle_deleting_link));
			}elseif($rows[$i]["getting_badge_or_not"] == 1){
				echo showtablerow('', array('class="td25"', 'class="td28"'),array($rows[$i]["realname"],$badge,$el,$handle_canceling_passing_link.'&nbsp;'.$handle_deleting_link));
			}
			
			
			
		}
		showtablefooter();
		echo $multipage;
	}

	if($operation == 'handle_canceling_passing'){
		$uid = getgpc('uid');
		$badge_id = getgpc('badge_id');

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
		$uabu = new user_applying_badges_updater();
		$uabu->update_getting_badge_or_not($uid,$badge_id,0);

		if($badge_id == 1 || $badge_id == 2 || $badge_id == 3 || $badge_id == 4){
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                        $gu = new guanxi_updater();
                        $gu->update_iscomp(1899467,$uid,0);
		}

		if($badge_id == 18){
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                        $gu = new guanxi_updater();
                        $gu->update_iscomp(1899466,$uid,0);
		}

		$page = getgpc('page');
		header('Location: admin.php?action=badge&operation=list&page='.$page);
	}

	if($operation == 'handle_passing'){
		
		$uid = getgpc('uid');
		$badge_id = getgpc('badge_id');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                $uabu = new user_applying_badges_updater();
                $uabu->update_getting_badge_or_not($uid,$badge_id,1);

		if($badge_id == 1 || $badge_id == 2 || $badge_id == 3 || $badge_id == 4){
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_getter.php');
                        $gg = new guanxi_getter();
                        $tmp = $gg->get_record_amount(1899467,$uid);
                        if($tmp==0){
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_adder.php');
                                $ga = new guanxi_adder();
                                $ga->add(1899467,$uid,1);
                        }else{
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                                $gu = new guanxi_updater();
                                $gu->update_iscomp(1899467,$uid,1);
                        }
		}

		if($badge_id == 18){
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_getter.php');
                        $gg = new guanxi_getter();
                        $tmp = $gg->get_record_amount(1899466,$uid);
                        if($tmp==0){
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_adder.php');
                                $ga = new guanxi_adder();
                                $ga->add(1899466,$uid,1);
                        }else{
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                                $gu = new guanxi_updater();
                                $gu->update_iscomp(1899466,$uid,1);
                        }
		}

		$page = getgpc('page');
		header('Location: admin.php?action=badge&operation=list&page='.$page);
	}

?>
