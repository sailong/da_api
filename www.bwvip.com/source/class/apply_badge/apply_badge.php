<?php
	define('APPTYPEID', 1);
	define('CURSCRIPT', 'join');
	if(!empty($_GET['mod']) && ($_GET['mod'] == 'misc' || $_GET['mod'] == 'invite')) {
		define('ALLOWGUEST', 1);
	}
	require_once './source/class/class_core.php';
	$discuz = & discuz_core::instance();
	$cachelist = array('magic','userapp','usergroups', 'diytemplatenamehome');
	$discuz->cachelist = $cachelist;
	$discuz->init();

	$uid = $_G['uid'];
	$uid_provided_by_url = getgpc('uid');
	
	if($uid_provided_by_url!=''){
		if($uid_provided_by_url!=$uid){
			if($uid==1){
				$uid = $uid_provided_by_url;
			}
		}
	}

	$mod = getgpc('mod');
	
	if(!in_array($mod, array('list','reading_club_trainer_certificate','uploading_club_trainer_certificate','removing_club_trainer_certificate','reading_club_trainer_certificate_original_size','applying_club_trainer_badge','reading_cga_trainer_certificate','removing_cga_trainer_certificate','uploading_cga_trainer_certificate','reading_cga_trainer_certificate_original_size','applying_cga_trainer_badge','reading_hmt_trainer_certificate','removing_hmt_trainer_certificate','uploading_hmt_trainer_certificate','applying_hmt_trainer_badge','reading_foreign_trainer_certificate','reading_foreign_trainer_certificate_original_size','reading_hmt_trainer_certificate_original_size','uploading_foreign_trainer_certificate','removing_foreign_trainer_certificate','applying_foreign_trainer_badge','applying_toyota_crown_city_challenge_badge','applying_dazheng_shop_badge','applying_course_manager_badge','applying_lawn_expert_badge','applying_expert_badge','applying_microblog_gold_medal','applying_blog_gold_medal','applying_grade_card_gold_medal','reading_toyota_crown_owner_certificate','uploading_toyota_crown_owner_certificate','removing_toyota_crown_owner_certificate','reading_toyota_crown_owner_certificate_original_size','applying_toyota_crown_owner_badge','reading_ct_diamond_card_pic','uploading_ct_diamond_card_pic','reading_ct_diamond_card_pic_original_size','removing_ct_diamond_card_pic','applying_ct_diamond_card_badge','applying_microblog_silver_medal','applying_blog_silver_medal','applying_grade_card_silver_medal','reading_cga_referee_certificate','uploading_cga_referee_certificate','removing_cga_referee_certificate','reading_cga_referee_certificate_original_size','applying_cga_referee_badge','applying_caddie_badge','applying_common_employee_badge','applying_taylormade_badge','applying_china_golf_media_league_badge','applying_rolex_member_badge','applying_ziji_investment_club_member_badge','applying_pamirs_spring_water_club_member_badge','applying_nike_golf_badge','applying_microblog_bronze_medal','applying_blog_bronze_medal','applying_grade_card_bronze_medal','getting_uid','getting_club_trainer_info','getting_cga_trainer_info','getting_hmt_trainer_info','getting_foreign_trainer_info','getting_course_manager_info','getting_lawn_expert_info','getting_expert_info','getting_toyota_crown_owner_info','getting_ct_diamond_card_info','getting_cga_referee_info','getting_caddie_info','getting_common_employee_info','got_badge_list','storing_showed_order','choosing_badge'))) {
        	$mod = 'list';
	}
	
	$getstat = getusrarry($uid);
	$groupid = !empty($getstat['groupid']) ? $getstat['groupid'] : $_G['groupid'];
	if($groupid<20){
		$groupid = 10;
	}

	$uploaddir = '/home/www/tmp/';

	if($mod == 'list'){

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
		$uabg = new user_applying_badges_getter();
		$record = $uabg->get_record_by_uid_and_badge_id($uid,1);
		$getting_certificated_club_badge_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
		$uabg = new user_applying_badges_getter();
		$record = $uabg->get_record_by_uid_and_badge_id($uid,2);
		$getting_cga_trainer_badge_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
		$uabg = new user_applying_badges_getter();
		$record = $uabg->get_record_by_uid_and_badge_id($uid,3);
		$getting_hmt_trainer_badge_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,4);
                $getting_foreign_trainer_badge_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,7);
                $getting_course_manager_badge_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,20);
                $getting_common_employee_badge_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,19);
                $getting_caddie_badge_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,18);
                $getting_cga_referee_badge_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,8);
                $getting_lawn_expert_badge_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,9);
                $getting_expert_badge_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,5);
                $getting_toyota_crown_city_challenge_badge_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,6);
                $getting_dazheng_shop_badge_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,22);
                $getting_china_golf_media_league_badge_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,13);
                $getting_toyota_crown_owner_badge_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,14);
                $getting_ct_diamond_card_badge_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,21);
                $getting_taylormade_badge_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,23);
                $getting_rolex_member_badge_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,24);
                $getting_ziji_investment_club_member_badge_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,25);
                $getting_pamirs_spring_water_club_member_badge_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,26);
                $getting_nike_golf_badge_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,10);
                $getting_microblog_gold_medal_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,11);
                $getting_blog_gold_medal_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,12);
                $getting_grade_card_gold_medal_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,15);
                $getting_microblog_silver_medal_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,16);
                $getting_blog_silver_medal_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,17);
                $getting_grade_card_silver_medal_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,27);
                $getting_microblog_bronze_medal_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,28);
                $getting_blog_bronze_medal_or_not = $record['getting_badge_or_not'];

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record = $uabg->get_record_by_uid_and_badge_id($uid,29);
                $getting_grade_card_bronze_medal_or_not = $record['getting_badge_or_not'];

		$templates = 'apply_badge/'.$groupid.'_list';
		require_once(template($templates));
	}
	
	
	
	if($mod == 'reading_club_trainer_certificate'){

		if($uid == 0){
			echo '需要登录才可以进行此操作';
			exit;
		}

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_avatar_reader.php');
		$ar = new avatar_reader();
		$ar->read($uid,$uploaddir,'_club_trainer_certificate');
	}

	if($mod == 'uploading_club_trainer_certificate'){
		
		if($uid == 0){
			echo '需要登录才可以进行此操作';
			exit;
		}

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
                $uafg = new uploaded_avatar_filename_getter();
                $stored_club_trainer_certificate_filename = $uafg->get($uid,$uploaddir,'_club_trainer_certificate');
                if($stored_club_trainer_certificate_filename != ''){
                        $stored_club_trainer_certificate_md5 = md5_file($stored_club_trainer_certificate_filename);
                }

		$uafg = new uploaded_avatar_filename_getter();
                $stored_club_trainer_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_club_trainer_certificate_original_size');
                if($stored_club_trainer_certificate_original_size_filename != ''){
                        $stored_club_trainer_certificate_original_size_md5 = md5_file($stored_club_trainer_certificate_original_size_filename);
                }

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_avatar_uploader.php');
                $au = new avatar_uploader();
                $au->upload($uid,$uploaddir,'_club_trainer_certificate');	

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
                $uafg = new uploaded_avatar_filename_getter();
                $club_trainer_certificate_filename = $uafg->get($uid,$uploaddir,'_club_trainer_certificate');
                if($club_trainer_certificate_filename != ''){
                        $club_trainer_certificate_md5 = md5_file($club_trainer_certificate_filename);
                }

		$uafg = new uploaded_avatar_filename_getter();
                $club_trainer_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_club_trainer_certificate_original_size');
                if($club_trainer_certificate_original_size_filename != ''){
                        $club_trainer_certificate_original_size_md5 = md5_file($club_trainer_certificate_original_size_filename);
                }

		if($stored_club_trainer_certificate_md5 != $club_trainer_certificate_md5 && $stored_club_trainer_certificate_original_size_md5 != $club_trainer_certificate_original_size_md5){

                        $badge_id = 1;
                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                        $uabu = new user_applying_badges_updater();
                        $uabu->update_getting_badge_or_not($uid,$badge_id,0);
						
						require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                        $uabg = new user_applying_badges_getter();
                        $trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

                        if($trainer_badge_amount == 0){
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                                $gu = new guanxi_updater();
                                $gu->update_iscomp(1899467,$uid,0);
                        }

                }

	}

	if($mod == 'removing_club_trainer_certificate'){
		
		if($uid == 0){
			echo '需要登录才可以进行此操作';
			exit;
		}
				
		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
		$uafg = new uploaded_avatar_filename_getter();
		$trainer_certificate_filename = $uafg->get($uid,$uploaddir,'_club_trainer_certificate');

		$uafg = new uploaded_avatar_filename_getter();
		$trainer_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_club_trainer_certificate_original_size');

		$badge_id = 1;
                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                $uabu = new user_applying_badges_updater();
                $uabu->update_getting_badge_or_not($uid,$badge_id,0);

                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

                if($trainer_badge_amount == 0){
                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                        $gu = new guanxi_updater();
                        $gu->update_iscomp(1899467,$uid,0);
                }

		if(unlink($trainer_certificate_filename) && unlink($trainer_certificate_original_size_filename)){
			echo 'ok';
		}else{
			echo 'error';
		}
	
	}

	if($mod == 'reading_club_trainer_certificate_original_size'){
	
		if($uid == 0){
			echo '需要登录才可以进行此操作';
			exit;
		}

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_avatar_reader.php');
                $ar = new avatar_reader();
                $ar->read($uid,$uploaddir,'_club_trainer_certificate_original_size');
	
	}

	if($mod == 'applying_club_trainer_badge'){
		
		if($uid == 0){
			echo '需要登录才可以进行此操作';
			exit;
		}

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		} 
		
		$org_club_trainer_belonging = getgpc('org_club_trainer_belonging');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_str_len_checker.php');
		$slc = new str_len_checker();
		$tmp = $slc->check($org_club_trainer_belonging,1,255,'所属机构');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }

		$club_trainer_duty = getgpc('club_trainer_duty');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_str_len_checker.php');
                $slc = new str_len_checker();
                $tmp = $slc->check($club_trainer_duty,0,255,'职务');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }

		$club_trainer_teaching_strong_point = getgpc('club_trainer_teaching_strong_point');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_str_len_checker.php');
                $slc = new str_len_checker();
                $tmp = $slc->check($club_trainer_teaching_strong_point,0,255,'教学擅长');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }
		
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
                $uafg = new uploaded_avatar_filename_getter();
                $club_trainer_certificate_filename = $uafg->get($uid,$uploaddir,'_club_trainer_certificate');

                $uafg = new uploaded_avatar_filename_getter();
                $club_trainer_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_club_trainer_certificate_original_size');

		

                if($club_trainer_certificate_filename == '' && $club_trainer_certificate_original_size_filename == ''){
                        echo '需要上传俱乐部教练证书';
                        exit;
                }

		$badge_id = 1;

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
		$uabg = new user_applying_badges_getter();
		$record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
		if($record_amount == 1){

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                                $badge_id = 1;
                                $tag_name = 'org_club_trainer_belonging';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $submitted_org_club_trainer_belonging = $row['tag_value'];
                                
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 1;
                                $tag_name = 'club_trainer_duty';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $submitted_club_trainer_duty = $row['tag_value'];
                                
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 1;
                                $tag_name = 'club_trainer_teaching_strong_point';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $submitted_club_trainer_teaching_strong_point = $row['tag_value'];

			if($submitted_org_club_trainer_belonging != $org_club_trainer_belonging || $submitted_club_trainer_duty != $club_trainer_duty || $submitted_club_trainer_teaching_strong_point != $club_trainer_teaching_strong_point){
				
				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_getting_badge_or_not($uid,$badge_id,0);

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                                $uabg = new user_applying_badges_getter();
                                $trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

                                if($trainer_badge_amount == 0){
                                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                                        $gu = new guanxi_updater();
                                        $gu->update_iscomp(1899467,$uid,0);
                                }

			}
                        
		}else{
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
			$uaba = new user_applying_badges_adder();
			$uaba->add($uid,$badge_id,0,0,2);
		}

		$params = array(
			'0'=>array('tag_name'=>'org_club_trainer_belonging','tag_value'=>$org_club_trainer_belonging),
			'1'=>array('tag_name'=>'club_trainer_duty','tag_value'=>$club_trainer_duty),
			'2'=>array('tag_name'=>'club_trainer_teaching_strong_point','tag_value'=>$club_trainer_teaching_strong_point)
		);

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_updater.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_adder.php');		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');

		for($i=0;$i<count($params);$i++){
			$uabig = new user_applying_badge_infos_getter();
			$record_amount = $uabig->get_record_amount_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$params[$i]['tag_name']);
			if($record_amount == 1){
				$uabiu = new user_applying_badge_infos_updater();
				$uabiu->update_tag_value_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$params[$i]['tag_name'],$params[$i]['tag_value']);
			}else{
				$uabia = new user_applying_badge_infos_adder();
				$uabia->add($uid,$badge_id,$params[$i]['tag_name'],$params[$i]['tag_value']);
			}
	
		}

		echo 'ok';	

	}

	if($mod == 'reading_cga_trainer_certificate'){
	
		if($uid == 0){
			echo '需要登录才可以进行此操作';
			exit;
		}
		
		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_avatar_reader.php');
		$ar = new avatar_reader();
		$ar->read($uid,$uploaddir,'_cga_trainer_certificate');
		
	}

	if($mod == 'removing_cga_trainer_certificate'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
		$uafg = new uploaded_avatar_filename_getter();
		$trainer_certificate_filename = $uafg->get($uid,$uploaddir,'_cga_trainer_certificate');

		$uafg = new uploaded_avatar_filename_getter();
		$trainer_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_cga_trainer_certificate_original_size');

		$badge_id = 2;
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
		$uabu = new user_applying_badges_updater();
		$uabu->update_getting_badge_or_not($uid,$badge_id,0);

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
		$uabg = new user_applying_badges_getter();
		$trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

		if($trainer_badge_amount == 0){
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
			$gu = new guanxi_updater();
			$gu->update_iscomp(1899467,$uid,0);
		}

		if(unlink($trainer_certificate_filename) && unlink($trainer_certificate_original_size_filename)){
			echo 'ok';
		}else{
			echo 'error';
		}	
		
	}

	if($mod == 'uploading_cga_trainer_certificate'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }
		
		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
		$uafg = new uploaded_avatar_filename_getter();
		$stored_cga_trainer_certificate_filename = $uafg->get($uid,$uploaddir,'_cga_trainer_certificate');
		if($stored_cga_trainer_certificate_filename != ''){
			$stored_cga_trainer_certificate_md5 = md5_file($stored_cga_trainer_certificate_filename);	
		}
		
                $uafg = new uploaded_avatar_filename_getter();
                $stored_cga_trainer_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_cga_trainer_certificate_original_size');
		if($stored_cga_trainer_certificate_original_size_filename != ''){
			$stored_cga_trainer_certificate_original_size_md5 = md5_file($stored_cga_trainer_certificate_original_size_filename);	
		}

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_avatar_uploader.php');
		$au = new avatar_uploader();
		$au->upload($uid,$uploaddir,'_cga_trainer_certificate');
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
		$uafg = new uploaded_avatar_filename_getter();
		$cga_trainer_certificate_filename = $uafg->get($uid,$uploaddir,'_cga_trainer_certificate');
		if($cga_trainer_certificate_filename != ''){
			$cga_trainer_certificate_md5 = md5_file($cga_trainer_certificate_filename);
		}		

		$uafg = new uploaded_avatar_filename_getter();
		$cga_trainer_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_cga_trainer_certificate_original_size');
		if($cga_trainer_certificate_original_size_filename != ''){
			$cga_trainer_certificate_original_size_md5 = md5_file($cga_trainer_certificate_original_size_filename);
		}
		
		var_dump($stored_cga_trainer_certificate_md5);
		var_dump($cga_trainer_certificate_md5);
		var_dump($stored_cga_trainer_certificate_original_size_md5);
		var_dump($cga_trainer_certificate_original_size_md5);

		if($stored_cga_trainer_certificate_md5 != $cga_trainer_certificate_md5 && $stored_cga_trainer_certificate_original_size_md5 != $cga_trainer_certificate_original_size_md5){
			
			$badge_id = 2;
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
			$uabu = new user_applying_badges_updater();
			$uabu->update_getting_badge_or_not($uid,$badge_id,0);

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
			$uabg = new user_applying_badges_getter();
			$trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

			if($trainer_badge_amount == 0){
				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
				$gu = new guanxi_updater();
				$gu->update_iscomp(1899467,$uid,0);
			}

		}

	}

	if($mod == 'reading_cga_trainer_certificate_original_size'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_avatar_reader.php');
		$ar = new avatar_reader();
		$ar->read($uid,$uploaddir,'_cga_trainer_certificate_original_size');
		
	}

	if($mod == 'applying_cga_trainer_badge'){
		
		if($uid == 0){
			echo '需要登录才可以进行此操作';
                        exit;
		}

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
		
		$org_cga_trainer_belonging = getgpc('org_cga_trainer_belonging');
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_str_len_checker.php');
                $slc = new str_len_checker();
                $tmp = $slc->check($org_cga_trainer_belonging,1,255,'所属机构');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }

		$cga_trainer_duty = getgpc('cga_trainer_duty');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_str_len_checker.php');
                $slc = new str_len_checker();
                $tmp = $slc->check($cga_trainer_duty,0,255,'职务');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }

		$cga_trainer_teaching_strong_point = getgpc('cga_trainer_teaching_strong_point');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_str_len_checker.php');
                $slc = new str_len_checker();
                $tmp = $slc->check($cga_trainer_teaching_strong_point,0,255,'教学擅长');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
		
		$uafg = new uploaded_avatar_filename_getter();
		$cga_trainer_certificate_filename = $uafg->get($uid,$uploaddir,'_cga_trainer_certificate');		

		$uafg = new uploaded_avatar_filename_getter();
		$cga_trainer_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_cga_trainer_certificate_original_size');
		
		if($cga_trainer_certificate_filename == '' && $cga_trainer_certificate_original_size_filename == ''){
			echo '需要上传中高协教练证书';
			exit;
		}

		$badge_id = 2;

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
		$uabg = new user_applying_badges_getter();
		$record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
		if($record_amount == 1){

                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        $uabig = new user_applying_badge_infos_getter();
                        $badge_id = 2;
                        $tag_name = 'org_cga_trainer_belonging';
                        $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        $submitted_org_cga_trainer_belonging = $row['tag_value'];

                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        $uabig = new user_applying_badge_infos_getter();
                        $badge_id = 2;
                        $tag_name = 'cga_trainer_duty';
                        $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        $submitted_cga_trainer_duty = $row['tag_value'];

                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        $uabig = new user_applying_badge_infos_getter();
                        $badge_id = 2;
                        $tag_name = 'cga_trainer_teaching_strong_point';
                        $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        $submitted_cga_trainer_teaching_strong_point = $row['tag_value'];

			if($submitted_org_cga_trainer_belonging != $org_cga_trainer_belonging || $submitted_cga_trainer_duty != $cga_trainer_duty || $submitted_cga_trainer_teaching_strong_point != $cga_trainer_teaching_strong_point){
				
				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                		$uabu = new user_applying_badges_updater();
                		$uabu->update_getting_badge_or_not($uid,$badge_id,0);

				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
				$uabg = new user_applying_badges_getter();
				$trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

				if($trainer_badge_amount == 0){
					require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
					$gu = new guanxi_updater();
					$gu->update_iscomp(1899467,$uid,0);
				}

			}

		}else{
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
			$uaba = new user_applying_badges_adder();
			$uaba->add($uid,$badge_id,0,0,1);
		}

		$params = array(
			'0'=>array('tag_name'=>'org_cga_trainer_belonging','tag_value'=>$org_cga_trainer_belonging),
			'1'=>array('tag_name'=>'cga_trainer_duty','tag_value'=>$cga_trainer_duty),
			'2'=>array('tag_name'=>'cga_trainer_teaching_strong_point','tag_value'=>$cga_trainer_teaching_strong_point)
		);

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_updater.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_adder.php');		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');

		for($i=0;$i<count($params);$i++){
			$uabig = new user_applying_badge_infos_getter();
			$record_amount = $uabig->get_record_amount_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$params[$i]['tag_name']);
			if($record_amount == 1){
				$uabiu = new user_applying_badge_infos_updater();
				$uabiu->update_tag_value_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$params[$i]['tag_name'],$params[$i]['tag_value']);
			}else{
				$uabia = new user_applying_badge_infos_adder();
				$uabia->add($uid,$badge_id,$params[$i]['tag_name'],$params[$i]['tag_value']);
			}
	
		}	

		echo 'ok';
		
	}

	if($mod == 'reading_hmt_trainer_certificate'){
		
		if($uid == 0){
			echo '需要登录才可以进行此操作';
			exit;
		}

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_avatar_reader.php');
		$ar = new avatar_reader();
		$ar->read($uid,$uploaddir,'_hmt_trainer_certificate');

	}

	if($mod == 'removing_hmt_trainer_certificate'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
		$uafg = new uploaded_avatar_filename_getter();
		$trainer_certificate_filename = $uafg->get($uid,$uploaddir,'_hmt_trainer_certificate');

		$uafg = new uploaded_avatar_filename_getter();
		$trainer_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_hmt_trainer_certificate_original_size');

		$badge_id = 3;
                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                $uabu = new user_applying_badges_updater();
                $uabu->update_getting_badge_or_not($uid,$badge_id,0);

                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

                if($trainer_badge_amount == 0){
                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                        $gu = new guanxi_updater();
                        $gu->update_iscomp(1899467,$uid,0);
                }

		if(unlink($trainer_certificate_filename) && unlink($trainer_certificate_original_size_filename)){
			echo 'ok';
		}else{
			echo 'error';
		}	

	}

	if($mod == 'uploading_hmt_trainer_certificate'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
                $uafg = new uploaded_avatar_filename_getter();
                $stored_hmt_trainer_certificate_filename = $uafg->get($uid,$uploaddir,'_hmt_trainer_certificate');
                if($stored_hmt_trainer_certificate_filename != ''){
                        $stored_hmt_trainer_certificate_md5 = md5_file($stored_hmt_trainer_certificate_filename);
                }

                $uafg = new uploaded_avatar_filename_getter();
                $stored_hmt_trainer_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_hmt_trainer_certificate_original_size');
                if($stored_hmt_trainer_certificate_original_size_filename != ''){
                        $stored_hmt_trainer_certificate_original_size_md5 = md5_file($stored_hmt_trainer_certificate_original_size_filename);
                }

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_avatar_uploader.php');
		$au = new avatar_uploader();
		$au->upload($uid,$uploaddir,'_hmt_trainer_certificate');

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
                $uafg = new uploaded_avatar_filename_getter();
                $hmt_trainer_certificate_filename = $uafg->get($uid,$uploaddir,'_hmt_trainer_certificate');
                if($hmt_trainer_certificate_filename != ''){
                        $hmt_trainer_certificate_md5 = md5_file($hmt_trainer_certificate_filename);
                }

                $uafg = new uploaded_avatar_filename_getter();
                $hmt_trainer_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_hmt_trainer_certificate_original_size');
                if($hmt_trainer_certificate_original_size_filename != ''){
                        $hmt_trainer_certificate_original_size_md5 = md5_file($hmt_trainer_certificate_original_size_filename);
                }

		if($stored_hmt_trainer_certificate_md5 != $hmt_trainer_certificate_md5 && $stored_hmt_trainer_certificate_original_size_md5 != $hmt_trainer_certificate_original_size_md5){

                        $badge_id = 3;
                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                        $uabu = new user_applying_badges_updater();
                        $uabu->update_getting_badge_or_not($uid,$badge_id,0);
						
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                        $uabg = new user_applying_badges_getter();
                        $trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

                        if($trainer_badge_amount == 0){
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                                $gu = new guanxi_updater();
                                $gu->update_iscomp(1899467,$uid,0);
                        }

                }

	}

	if($mod == 'applying_hmt_trainer_badge'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$org_hmt_trainer_belonging = getgpc('org_hmt_trainer_belonging');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_str_len_checker.php');
                $slc = new str_len_checker();
                $tmp = $slc->check($org_hmt_trainer_belonging,1,255,'所属机构');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }

		$hmt_trainer_duty = getgpc('hmt_trainer_duty');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_str_len_checker.php');
                $slc = new str_len_checker();
                $tmp = $slc->check($hmt_trainer_duty,0,255,'职务');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }

		$hmt_trainer_teaching_strong_point = getgpc('hmt_trainer_teaching_strong_point');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_str_len_checker.php');
                $slc = new str_len_checker();
                $tmp = $slc->check($hmt_trainer_teaching_strong_point,0,255,'教学擅长');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
		
		$uafg = new uploaded_avatar_filename_getter();
		$hmt_trainer_certificate_filename = $uafg->get($uid,$uploaddir,'_hmt_trainer_certificate');		

		$uafg = new uploaded_avatar_filename_getter();
		$hmt_trainer_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_hmt_trainer_certificate_original_size');
		
		if($hmt_trainer_certificate_filename == '' && $hmt_trainer_certificate_original_size_filename == ''){
			echo '需要上传港澳台教练证书';
			exit;
		}
		
		$badge_id = 3;

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
		$uabg = new user_applying_badges_getter();
		$record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
		if($record_amount == 1){
			
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        	$uabig = new user_applying_badge_infos_getter();
                        	$badge_id = 3;
                            	$tag_name = 'org_hmt_trainer_belonging';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $submitted_org_hmt_trainer_belonging = $row['tag_value'];
                                
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 3;
                                $tag_name = 'hmt_trainer_duty';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $submitted_hmt_trainer_duty = $row['tag_value'];
                                
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 3;
                                $tag_name = 'hmt_trainer_teaching_strong_point';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $submitted_hmt_trainer_teaching_strong_point = $row['tag_value'];

			if($submitted_org_hmt_trainer_belonging != $org_hmt_trainer_belonging || $submitted_hmt_trainer_duty != $hmt_trainer_duty || $submitted_hmt_trainer_teaching_strong_point != $hmt_trainer_teaching_strong_point){

				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_getting_badge_or_not($uid,$badge_id,0);

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                                $uabg = new user_applying_badges_getter();
                                $trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

                                if($trainer_badge_amount == 0){
                                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                                        $gu = new guanxi_updater();
                                        $gu->update_iscomp(1899467,$uid,0);
                                }

			}
                                
				
		}else{
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
			$uaba = new user_applying_badges_adder();
			$uaba->add($uid,$badge_id,0,0,3);
		}

		$params = array(
			'0'=>array('tag_name'=>'org_hmt_trainer_belonging','tag_value'=>$org_hmt_trainer_belonging),
			'1'=>array('tag_name'=>'hmt_trainer_duty','tag_value'=>$hmt_trainer_duty),
			'2'=>array('tag_name'=>'hmt_trainer_teaching_strong_point','tag_value'=>$hmt_trainer_teaching_strong_point)
		);

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_updater.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_adder.php');		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');

		for($i=0;$i<count($params);$i++){
			$uabig = new user_applying_badge_infos_getter();
			$record_amount = $uabig->get_record_amount_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$params[$i]['tag_name']);
			if($record_amount == 1){
				$uabiu = new user_applying_badge_infos_updater();
				$uabiu->update_tag_value_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$params[$i]['tag_name'],$params[$i]['tag_value']);
			}else{
				$uabia = new user_applying_badge_infos_adder();
				$uabia->add($uid,$badge_id,$params[$i]['tag_name'],$params[$i]['tag_value']);
			}
	
		}	
		
		echo 'ok';	

	}

	if($mod == 'reading_foreign_trainer_certificate'){
		
		if($uid == 0){
			echo '需要登录才可以进行此操作';
			exit;
		}

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_avatar_reader.php');
		$ar = new avatar_reader();
		$ar->read($uid,$uploaddir,'_foreign_trainer_certificate');	

	}

	if($mod == 'reading_foreign_trainer_certificate_original_size'){
		
		if($uid == 0){
			echo '需要登录才可以进行此操作';
			exit;
		}

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_avatar_reader.php');
                $ar = new avatar_reader();
                $ar->read($uid,$uploaddir,'_foreign_trainer_certificate_original_size');

	}

	if($mod == 'reading_hmt_trainer_certificate_original_size'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_avatar_reader.php');
                $ar = new avatar_reader();
                $ar->read($uid,$uploaddir,'_hmt_trainer_certificate_original_size');	

	}

	if($mod == 'uploading_foreign_trainer_certificate'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
                $uafg = new uploaded_avatar_filename_getter();
                $stored_foreign_trainer_certificate_filename = $uafg->get($uid,$uploaddir,'_foreign_trainer_certificate');
                if($stored_foreign_trainer_certificate_filename != ''){
                        $stored_foreign_trainer_certificate_md5 = md5_file($stored_foreign_trainer_certificate_filename);
                }

                $uafg = new uploaded_avatar_filename_getter();
                $stored_foreign_trainer_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_foreign_trainer_certificate_original_size');
                if($stored_foreign_trainer_certificate_original_size_filename != ''){
                        $stored_foreign_trainer_certificate_original_size_md5 = md5_file($stored_foreign_trainer_certificate_original_size_filename);
                }

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_avatar_uploader.php');
		$au = new avatar_uploader();
		$au->upload($uid,$uploaddir,'_foreign_trainer_certificate');
	
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
                $uafg = new uploaded_avatar_filename_getter();
                $foreign_trainer_certificate_filename = $uafg->get($uid,$uploaddir,'_foreign_trainer_certificate');
                if($foreign_trainer_certificate_filename != ''){
                        $foreign_trainer_certificate_md5 = md5_file($foreign_trainer_certificate_filename);
                }

                $uafg = new uploaded_avatar_filename_getter();
                $foreign_trainer_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_foreign_trainer_certificate_original_size');
                if($foreign_trainer_certificate_original_size_filename != ''){
                        $foreign_trainer_certificate_original_size_md5 = md5_file($foreign_trainer_certificate_original_size_filename);
                }

		if($stored_foreign_trainer_certificate_md5 != $foreign_trainer_certificate_md5 && $stored_foreign_trainer_certificate_original_size_md5 != $foreign_trainer_certificate_original_size_md5){

                        $badge_id = 4;
                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                        $uabu = new user_applying_badges_updater();
                        $uabu->update_getting_badge_or_not($uid,$badge_id,0);
						
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                        $uabg = new user_applying_badges_getter();
                        $trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

                        if($trainer_badge_amount == 0){
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                                $gu = new guanxi_updater();
                                $gu->update_iscomp(1899467,$uid,0);
                        }

                }

	}

	if($mod == 'removing_foreign_trainer_certificate'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
		$uafg = new uploaded_avatar_filename_getter();
		$trainer_certificate_filename = $uafg->get($uid,$uploaddir,'_foreign_trainer_certificate');

		$uafg = new uploaded_avatar_filename_getter();
		$trainer_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_foreign_trainer_certificate_original_size');

		$badge_id = 4;
                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                $uabu = new user_applying_badges_updater();
                $uabu->update_getting_badge_or_not($uid,$badge_id,0);

                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

                if($trainer_badge_amount == 0){
                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                        $gu = new guanxi_updater();
                        $gu->update_iscomp(1899467,$uid,0);
                }

		if(unlink($trainer_certificate_filename) && unlink($trainer_certificate_original_size_filename)){
			echo 'ok';
		}else{
			echo 'error';
		}

	}

	if($mod == 'applying_foreign_trainer_badge'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$org_foreign_trainer_belonging = getgpc('org_foreign_trainer_belonging');
                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_str_len_checker.php');
                $slc = new str_len_checker();
                $tmp = $slc->check($org_foreign_trainer_belonging,1,255,'所属机构');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }

		$foreign_trainer_duty = getgpc('foreign_trainer_duty');
                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_str_len_checker.php');
                $slc = new str_len_checker();
                $tmp = $slc->check($foreign_trainer_duty,0,255,'职务');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }

		$foreign_trainer_teaching_strong_point = getgpc('foreign_trainer_teaching_strong_point');
                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_str_len_checker.php');
                $slc = new str_len_checker();
                $tmp = $slc->check($foreign_trainer_teaching_strong_point,0,255,'教学擅长');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }

		
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
		
		$uafg = new uploaded_avatar_filename_getter();
		$hmt_trainer_certificate_filename = $uafg->get($uid,$uploaddir,'_foreign_trainer_certificate');		

		$uafg = new uploaded_avatar_filename_getter();
		$hmt_trainer_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_foreign_trainer_certificate_original_size');
		
		if($hmt_trainer_certificate_filename == '' && $hmt_trainer_certificate_original_size_filename == ''){
			echo '需要上传外籍教练证书';
			exit;
		}
		
		$badge_id = 4;

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
		$uabg = new user_applying_badges_getter();
		$record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
		if($record_amount == 1){

			 require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 4;
                                $tag_name = 'org_foreign_trainer_belonging';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $submitted_org_foreign_trainer_belonging = $row['tag_value'];
                                
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 4;
                                $tag_name = 'foreign_trainer_duty';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $submitted_foreign_trainer_duty = $row['tag_value'];
                                
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 4;
                                $tag_name = 'foreign_trainer_teaching_strong_point';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $submitted_foreign_trainer_teaching_strong_point = $row['tag_value'];
			
			if($submitted_org_foreign_trainer_belonging != $org_foreign_trainer_belonging || $submitted_foreign_trainer_duty != $foreign_trainer_duty || $submitted_foreign_trainer_teaching_strong_point != $foreign_trainer_teaching_strong_point){

				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_getting_badge_or_not($uid,$badge_id,0);

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                                $uabg = new user_applying_badges_getter();
                                $trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

                                if($trainer_badge_amount == 0){
                                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                                        $gu = new guanxi_updater();
                                        $gu->update_iscomp(1899467,$uid,0);
                                }

			}
                                
		}else{
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
			$uaba = new user_applying_badges_adder();
			$uaba->add($uid,$badge_id,0,0,4);
		}

		$params = array(
			'0'=>array('tag_name'=>'org_foreign_trainer_belonging','tag_value'=>$org_foreign_trainer_belonging),
			'1'=>array('tag_name'=>'foreign_trainer_duty','tag_value'=>$foreign_trainer_duty),
			'2'=>array('tag_name'=>'foreign_trainer_teaching_strong_point','tag_value'=>$foreign_trainer_teaching_strong_point)
		);

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_updater.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_adder.php');		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');

		for($i=0;$i<count($params);$i++){
			$uabig = new user_applying_badge_infos_getter();
			$record_amount = $uabig->get_record_amount_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$params[$i]['tag_name']);
			if($record_amount == 1){
				$uabiu = new user_applying_badge_infos_updater();
				$uabiu->update_tag_value_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$params[$i]['tag_name'],$params[$i]['tag_value']);
			}else{
				$uabia = new user_applying_badge_infos_adder();
				$uabia->add($uid,$badge_id,$params[$i]['tag_name'],$params[$i]['tag_value']);
			}
	
		}	
		
		echo 'ok';

	}

	if($mod == 'applying_toyota_crown_city_challenge_badge'){
		
		if($uid == 0){
			echo '需要登录才可以进行此操作';
			exit;
		}

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_home_dazbm_getter.php');
		$hdg = new home_dazbm_getter();
		$record_amount = $hdg->get_record_amount_by_uid_and_apply_status($uid);
		if($record_amount != 1){
			echo '成功报名皇冠杯城市挑战赛后才可以申请此徽章';
			exit;
		}

 		$badge_id = 5;               

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
		$uabg = new user_applying_badges_getter();
		$record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
		if($record_amount == 1){
			echo '已经获取此徽章';
			exit;
		}else{
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
			$uaba = new user_applying_badges_adder();
			$uaba->add($uid,$badge_id,1,1,11);
		}

		echo 'ok';
		
	}

	if($mod == 'applying_dazheng_shop_badge'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$badge_id = 6;
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_dz_order_info_getter.php');
		$doig = new dz_order_info_getter();
		$record_amount = $doig->get_record_amount_by_user_id($uid);

		if($record_amount>=1){

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                	$uabg = new user_applying_badges_getter();
                	$record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
                	if($record_amount == 1){

                	}else{
                        	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
                        	$uaba = new user_applying_badges_adder();
                        	$uaba->add($uid,$badge_id,1,1,12);
                	}

			echo 'ok';
		}else{
			echo '在大正商城完成交易后才可以申请此徽章';
		}
		
		
	}

	if($mod == 'applying_course_manager_badge'){

		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$company_name_of_course_manager = getgpc('company_name_of_course_manager');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_str_len_checker.php');
		$slc = new str_len_checker();
                $tmp = $slc->check($company_name_of_course_manager,1,255,'公司名称');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }

		$company_address_of_course_manager = getgpc('company_address_of_course_manager');
		$slc = new str_len_checker();
                $tmp = $slc->check($company_address_of_course_manager,0,255,'公司地址');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }	

		$personal_desc_of_course_manager = getgpc('personal_desc_of_course_manager');
		$slc = new str_len_checker();
                $tmp = $slc->check($personal_desc_of_course_manager,0,255,'个人简介');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }

		$badge_id = 7;

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
		$uabg = new user_applying_badges_getter();
		$record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
		if($record_amount == 1){

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 7;
                                $tag_name = 'company_name_of_course_manager';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_company_name_of_course_manager = $row['tag_value'];

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 7;
                                $tag_name = 'company_address_of_course_manager';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_company_address_of_course_manager = $row['tag_value'];

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 7;
                                $tag_name = 'personal_desc_of_course_manager';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_personal_desc_of_course_manager = $row['tag_value'];

			if($stored_company_name_of_course_manager != $company_name_of_course_manager || $stored_company_address_of_course_manager != $company_address_of_course_manager || $stored_personal_desc_of_course_manager != $personal_desc_of_course_manager){
				
				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_getting_badge_or_not($uid,$badge_id,0);

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                                $uabg = new user_applying_badges_getter();
                                $trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

                                if($trainer_badge_amount == 0){
                                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                                        $gu = new guanxi_updater();
                                        $gu->update_iscomp(1899467,$uid,0);
                                }

			}

		}else{
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
			$uaba = new user_applying_badges_adder();
			$uaba->add($uid,$badge_id,0,0,5);
		}

		$params = array(
			'0'=>array('tag_name'=>'company_name_of_course_manager','tag_value'=>$company_name_of_course_manager),
			'1'=>array('tag_name'=>'company_address_of_course_manager','tag_value'=>$company_address_of_course_manager),
			'2'=>array('tag_name'=>'personal_desc_of_course_manager','tag_value'=>$personal_desc_of_course_manager)
		);

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_updater.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_adder.php');		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');

		for($i=0;$i<count($params);$i++){
			$uabig = new user_applying_badge_infos_getter();
			$record_amount = $uabig->get_record_amount_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$params[$i]['tag_name']);
			if($record_amount == 1){
				$uabiu = new user_applying_badge_infos_updater();
				$uabiu->update_tag_value_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$params[$i]['tag_name'],$params[$i]['tag_value']);
			}else{
				$uabia = new user_applying_badge_infos_adder();
				$uabia->add($uid,$badge_id,$params[$i]['tag_name'],$params[$i]['tag_value']);
			}
	
		}	
		
		echo 'ok';	

	}

	if($mod == 'applying_lawn_expert_badge'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$lawn_expert_name_and_duty = getgpc('lawn_expert_name_and_duty');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_str_len_checker.php');
		$slc = new str_len_checker();
                $tmp = $slc->check($lawn_expert_name_and_duty,1,255,'姓名及职务');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }

		$lawn_expert_personal_desc = getgpc('lawn_expert_personal_desc');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_str_len_checker.php');
		$slc = new str_len_checker();
                $tmp = $slc->check($lawn_expert_personal_desc,1,255,'个人简介');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }

		$badge_id = 8;

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
		$uabg = new user_applying_badges_getter();
		$record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
		if($record_amount == 1){

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 8;
                                $tag_name = 'lawn_expert_name_and_duty';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_lawn_expert_name_and_duty = $row['tag_value'];

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 8;
                                $tag_name = 'lawn_expert_personal_desc';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_lawn_expert_personal_desc = $row['tag_value'];

			if($stored_lawn_expert_name_and_duty != $lawn_expert_name_and_duty || $stored_lawn_expert_personal_desc != $lawn_expert_personal_desc){
				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_getting_badge_or_not($uid,$badge_id,0);

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                                $uabg = new user_applying_badges_getter();
                                $trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

                                if($trainer_badge_amount == 0){
                                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                                        $gu = new guanxi_updater();
                                        $gu->update_iscomp(1899467,$uid,0);
                                }
			}


		}else{
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
			$uaba = new user_applying_badges_adder();
			$uaba->add($uid,$badge_id,0,0,9);
		}

		$params = array(
			'0'=>array('tag_name'=>'lawn_expert_name_and_duty','tag_value'=>$lawn_expert_name_and_duty),
			'1'=>array('tag_name'=>'lawn_expert_personal_desc','tag_value'=>$lawn_expert_personal_desc)
		);

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_updater.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_adder.php');		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');

		for($i=0;$i<count($params);$i++){
			$uabig = new user_applying_badge_infos_getter();
			$record_amount = $uabig->get_record_amount_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$params[$i]['tag_name']);
			if($record_amount == 1){
				$uabiu = new user_applying_badge_infos_updater();
				$uabiu->update_tag_value_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$params[$i]['tag_name'],$params[$i]['tag_value']);
			}else{
				$uabia = new user_applying_badge_infos_adder();
				$uabia->add($uid,$badge_id,$params[$i]['tag_name'],$params[$i]['tag_value']);
			}
	
		}

		echo 'ok';

	}

	if($mod == 'applying_expert_badge'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$expert_name_and_duty = getgpc('expert_name_and_duty');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_str_len_checker.php');
		$slc = new str_len_checker();
                $tmp = $slc->check($expert_name_and_duty,1,255,'姓名及职务');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }

		$expert_personal_desc = getgpc('expert_personal_desc');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_str_len_checker.php');
		$slc = new str_len_checker();
                $tmp = $slc->check($expert_personal_desc,1,255,'个人简介');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }

		$badge_id = 9;

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
		$uabg = new user_applying_badges_getter();
		$record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
		if($record_amount == 1){

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 9;
                                $tag_name = 'expert_name_and_duty';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_expert_name_and_duty = $row['tag_value'];

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 9;
                                $tag_name = 'expert_personal_desc';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_expert_personal_desc = $row['tag_value'];

			if($expert_name_and_duty != $stored_expert_name_and_duty || $expert_personal_desc != $stored_expert_personal_desc){
				
				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_getting_badge_or_not($uid,$badge_id,0);

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                                $uabg = new user_applying_badges_getter();
                                $trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

                                if($trainer_badge_amount == 0){
                                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                                        $gu = new guanxi_updater();
                                        $gu->update_iscomp(1899467,$uid,0);
                                }

			}

		}else{
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
			$uaba = new user_applying_badges_adder();
			$uaba->add($uid,$badge_id,0,0,10);
		}

		$params = array(
			'0'=>array('tag_name'=>'expert_name_and_duty','tag_value'=>$expert_name_and_duty),
			'1'=>array('tag_name'=>'expert_personal_desc','tag_value'=>$expert_personal_desc)
		);

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_updater.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_adder.php');		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');

		for($i=0;$i<count($params);$i++){
			$uabig = new user_applying_badge_infos_getter();
			$record_amount = $uabig->get_record_amount_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$params[$i]['tag_name']);
			if($record_amount == 1){
				$uabiu = new user_applying_badge_infos_updater();
				$uabiu->update_tag_value_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$params[$i]['tag_name'],$params[$i]['tag_value']);
			}else{
				$uabia = new user_applying_badge_infos_adder();
				$uabia->add($uid,$badge_id,$params[$i]['tag_name'],$params[$i]['tag_value']);
			}
	
		}

		echo 'ok';

	}

	if($mod == 'applying_microblog_gold_medal'){
	
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$badge_id = 10;

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_jishigou_topic_getter.php');
		$jtg = new jishigou_topic_getter();
		$record_amount = $jtg->get_record_by_uid($uid);
		if($record_amount >= 100){
	
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                	$uabg = new user_applying_badges_getter();
                	$record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
                	if($record_amount == 1){

                	}else{
                        	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
                        	$uaba = new user_applying_badges_adder();
                        	$uaba->add($uid,$badge_id,1,1,21);
				
                	}
			echo 'ok';
			exit;
		
		}else{
			echo '您目前已经发送'.$record_amount.'条微博，发送100条微博后才可以申请此徽章';
			exit;
		}

	}

	if($mod == 'applying_blog_gold_medal'){

		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$badge_id = 11;

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_home_blog_getter.php');
		$hbg = new home_blog_getter();
		$record_amount = $hbg->get_record_amount_by_uid($uid);
		if($record_amount >= 20){
			
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                        $uabg = new user_applying_badges_getter();
                        $record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
                        if($record_amount == 1){

                        }else{
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
                                $uaba = new user_applying_badges_adder();
                                $uaba->add($uid,$badge_id,1,1,22);
                        }
			echo 'ok';
			exit;

		}else{
			echo '您目前已经发布'.$record_amount.'篇博文，发布20篇博文后才可以申请此徽章';
			exit;
		}
		
	}

	if($mod == 'applying_grade_card_gold_medal'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$badge_id = 12;

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_common_score_getter.php');
		$csg = new common_score_getter();
		$sais_id = 0;
		$record_amount = $csg->get_record_amount_by_uid_and_sais_id($uid,$sais_id);
		
		if($record_amount >= 3){
			
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                        $uabg = new user_applying_badges_getter();
                        $record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
                        if($record_amount == 1){

                        }else{
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
                                $uaba = new user_applying_badges_adder();
                                $uaba->add($uid,$badge_id,1,1,23);
                        }
			echo 'ok';
			exit;
		}else{
			echo '您目前已经上传'.$record_amount.'张成绩卡，上传成绩卡20张后才可以申请此徽章';
			exit;
		}

	}

	if($mod == 'reading_toyota_crown_owner_certificate'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_avatar_reader.php');
		$ar = new avatar_reader();
		$ar->read($uid,$uploaddir,'_toyota_crown_owner_certificate');

	}

	if($mod == 'uploading_toyota_crown_owner_certificate'){
	
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
                $uafg = new uploaded_avatar_filename_getter();
                $stored_toyota_crown_owner_certificate_filename = $uafg->get($uid,$uploaddir,'_toyota_crown_owner_certificate');
                if($stored_toyota_crown_owner_certificate_filename != ''){
                        $stored_toyota_crown_owner_certificate_md5 = md5_file($stored_toyota_crown_owner_certificate_filename);
                }

                $uafg = new uploaded_avatar_filename_getter();
                $stored_toyota_crown_owner_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_toyota_crown_owner_certificate_original_size');
                if($stored_toyota_crown_owner_certificate_original_size_filename != ''){
                        $stored_toyota_crown_owner_certificate_original_size_md5 = md5_file($stored_toyota_crown_owner_certificate_original_size_filename);
                }

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_avatar_uploader.php');
		$au = new avatar_uploader();
		$au->upload($uid,$uploaddir,'_toyota_crown_owner_certificate');
	
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
                $uafg = new uploaded_avatar_filename_getter();
                $toyota_crown_owner_certificate_filename = $uafg->get($uid,$uploaddir,'_toyota_crown_owner_certificate');
                if($toyota_crown_owner_certificate_filename != ''){
                        $toyota_crown_owner_certificate_md5 = md5_file($toyota_crown_owner_certificate_filename);
                }

                $uafg = new uploaded_avatar_filename_getter();
                $toyota_crown_owner_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_toyota_crown_owner_certificate_original_size');
                if($toyota_crown_owner_certificate_original_size_filename != ''){
                        $toyota_crown_owner_certificate_original_size_md5 = md5_file($toyota_crown_owner_certificate_original_size_filename);
                }

		if($stored_toyota_crown_owner_certificate_md5 != $toyota_crown_owner_certificate_md5 && $stored_toyota_crown_owner_certificate_original_size_md5 != $toyota_crown_owner_certificate_original_size_md5){

                        $badge_id = 13;
                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                        $uabu = new user_applying_badges_updater();
                        $uabu->update_getting_badge_or_not($uid,$badge_id,0);
						
						require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                        $uabg = new user_applying_badges_getter();
                        $trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

                        if($trainer_badge_amount == 0){
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                                $gu = new guanxi_updater();
                                $gu->update_iscomp(1899467,$uid,0);
                        }

                }

	}

	if($mod == 'removing_toyota_crown_owner_certificate'){

		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
		$uafg = new uploaded_avatar_filename_getter();
		$toyota_crown_owner_certificate_filename = $uafg->get($uid,$uploaddir,'_toyota_crown_owner_certificate');

		$uafg = new uploaded_avatar_filename_getter();
		$toyota_crown_owner_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_toyota_crown_owner_certificate_original_size');

		$badge_id = 13;
                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                $uabu = new user_applying_badges_updater();
                $uabu->update_getting_badge_or_not($uid,$badge_id,0);

                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

                if($trainer_badge_amount == 0){
                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                        $gu = new guanxi_updater();
                        $gu->update_iscomp(1899467,$uid,0);
                }

		if(unlink($toyota_crown_owner_certificate_filename) && unlink($toyota_crown_owner_certificate_original_size_filename)){
			echo 'ok';
		}else{
			echo 'error';
		}	

	}

	if($mod == 'reading_toyota_crown_owner_certificate_original_size'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_avatar_reader.php');
                $ar = new avatar_reader();
                $ar->read($uid,$uploaddir,'_toyota_crown_owner_certificate_original_size');	

	}

	if($mod == 'applying_toyota_crown_owner_badge'){
	
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$badge_id = 13;
	
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
		
		$uafg = new uploaded_avatar_filename_getter();
		$toyota_crown_owner_certificate_filename = $uafg->get($uid,$uploaddir,'_toyota_crown_owner_certificate');		

		$uafg = new uploaded_avatar_filename_getter();
		$toyota_crown_owner_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_toyota_crown_owner_certificate_original_size');
		
		if($toyota_crown_owner_certificate_filename == '' && $toyota_crown_owner_certificate_original_size_filename == ''){
			echo '需要上传皇冠车主行驶证';
			exit;
		}
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
		$uabg = new user_applying_badges_getter();
		$record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
		if($record_amount == 1){

		}else{
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
			$uaba = new user_applying_badges_adder();
			$uaba->add($uid,$badge_id,0,0,14);
		}
		
		echo 'ok';	

	}
	
	if($mod == 'reading_ct_diamond_card_pic'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_avatar_reader.php');
		$ar = new avatar_reader();
		$ar->read($uid,$uploaddir,'_ct_diamond_card_pic');	
	
	}

	if($mod == 'uploading_ct_diamond_card_pic'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
                $uafg = new uploaded_avatar_filename_getter();
                $stored_ct_diamond_card_pic_filename = $uafg->get($uid,$uploaddir,'_ct_diamond_card_pic');
                if($stored_ct_diamond_card_pic_filename != ''){
                        $stored_ct_diamond_card_pic_md5 = md5_file($stored_ct_diamond_card_pic_filename);
                }

                $uafg = new uploaded_avatar_filename_getter();
                $stored_ct_diamond_card_pic_original_size_filename = $uafg->get($uid,$uploaddir,'_ct_diamond_card_pic_original_size');
                if($stored_ct_diamond_card_pic_original_size_filename != ''){
                        $stored_ct_diamond_card_pic_original_size_md5 = md5_file($stored_ct_diamond_card_pic_original_size_filename);
                }

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_avatar_uploader.php');
		$au = new avatar_uploader();
		$au->upload($uid,$uploaddir,'_ct_diamond_card_pic');

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
                $uafg = new uploaded_avatar_filename_getter();
                $ct_diamond_card_pic_filename = $uafg->get($uid,$uploaddir,'_ct_diamond_card_pic');
                if($ct_diamond_card_pic_filename != ''){
                        $ct_diamond_card_pic_md5 = md5_file($ct_diamond_card_pic_filename);
                }

                $uafg = new uploaded_avatar_filename_getter();
                $ct_diamond_card_pic_original_size_filename = $uafg->get($uid,$uploaddir,'_ct_diamond_card_pic_original_size');
                if($ct_diamond_card_pic_original_size_filename != ''){
                        $ct_diamond_card_pic_original_size_md5 = md5_file($ct_diamond_card_pic_original_size_filename);
                }

		if($stored_cga_trainer_certificate_md5 != $cga_trainer_certificate_md5 && $stored_cga_trainer_certificate_original_size_md5 != $cga_trainer_certificate_original_size_md5){

                        $badge_id = 14;
                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                        $uabu = new user_applying_badges_updater();
                        $uabu->update_getting_badge_or_not($uid,$badge_id,0);
						
						require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                        $uabg = new user_applying_badges_getter();
                        $trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

                        if($trainer_badge_amount == 0){
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                                $gu = new guanxi_updater();
                                $gu->update_iscomp(1899467,$uid,0);
                        }

                }

	}

	if($mod == 'reading_ct_diamond_card_pic_original_size'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_avatar_reader.php');
                $ar = new avatar_reader();
                $ar->read($uid,$uploaddir,'_ct_diamond_card_pic_original_size');	

	}

	if($mod == 'removing_ct_diamond_card_pic'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
		$uafg = new uploaded_avatar_filename_getter();
		$ct_diamond_card_pic_filename = $uafg->get($uid,$uploaddir,'_ct_diamond_card_pic');

		$uafg = new uploaded_avatar_filename_getter();
		$ct_diamond_card_pic_original_size_filename = $uafg->get($uid,$uploaddir,'_ct_diamond_card_pic_original_size');

		$badge_id = 14;
                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                $uabu = new user_applying_badges_updater();
                $uabu->update_getting_badge_or_not($uid,$badge_id,0);

                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

                if($trainer_badge_amount == 0){
                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                        $gu = new guanxi_updater();
                        $gu->update_iscomp(1899467,$uid,0);
                }

		if(unlink($ct_diamond_card_pic_filename) && unlink($ct_diamond_card_pic_original_size_filename)){
			echo 'ok';
		}else{
			echo 'error';
		}	

	}

	if($mod == 'applying_ct_diamond_card_badge'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}		
		
		$badge_id = 14;

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
		
		$uafg = new uploaded_avatar_filename_getter();
		$ct_diamond_card_pic_filename = $uafg->get($uid,$uploaddir,'_ct_diamond_card_pic');		

		$uafg = new uploaded_avatar_filename_getter();
		$ct_diamond_card_pic_original_size_filename = $uafg->get($uid,$uploaddir,'_ct_diamond_card_pic_original_size');
		
		if($ct_diamond_card_pic_filename == '' && $ct_diamond_card_pic_original_size_filename == ''){
			echo '需要上传电信钻石卡正面图片';
			exit;
		}
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
		$uabg = new user_applying_badges_getter();
		$record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
		if($record_amount == 1){

		}else{
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
			$uaba = new user_applying_badges_adder();
			$uaba->add($uid,$badge_id,0,0,14);
		}	
		
		echo 'ok';	

	}

	if($mod == 'applying_microblog_silver_medal'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
		
		$badge_id = 15;

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_jishigou_topic_getter.php');
		$jtg = new jishigou_topic_getter();
		$record_amount = $jtg->get_record_by_uid($uid);
		if($record_amount >= 50){
			
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
			$uabg = new user_applying_badges_getter();
			$record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
			if($record_amount == 1){

			}else{
				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
				$uaba = new user_applying_badges_adder();
				$uaba->add($uid,$badge_id,1,1,24);
			}

			echo 'ok';
			exit;

		}else{
			echo '您目前已经发布'.$record_amount.'条微博，发布微博50条后才可以申请此徽章';
			exit;
		}

	}

	if($mod == 'applying_blog_silver_medal'){
	
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$badge_id = 16;

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_home_blog_getter.php');
		$hbg = new home_blog_getter();
		$record_amount = $hbg->get_record_amount_by_uid($uid);
		if($record_amount >= 10){
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                        $uabg = new user_applying_badges_getter();
                        $record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
                        if($record_amount == 1){

                        }else{
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
                                $uaba = new user_applying_badges_adder();
                                $uaba->add($uid,$badge_id,1,1,25);
                        }
			echo 'ok';
			exit;
		}else{
			echo '您目前已经发布'.$record_amount.'篇博文，发布博文10篇后才可以申请此徽章';
			exit;
		}
		
	}

	if($mod == 'applying_grade_card_silver_medal'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$badge_id = 17;

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_common_score_getter.php');
		$csg = new common_score_getter();
		$sais_id = 0;
		$record_amount = $csg->get_record_amount_by_uid_and_sais_id($uid,$sais_id);
		
		if($record_amount>=3){
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                        $uabg = new user_applying_badges_getter();
                        $record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
                        if($record_amount == 1){

				

                        }else{
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
                                $uaba = new user_applying_badges_adder();
                                $uaba->add($uid,$badge_id,1,1,26);
                        }
			echo 'ok';
			exit;
		}else{
			echo '您目前已经上传'.$record_amount.'张成绩卡，上传成绩卡10张后才可以申请此徽章';
			exit;
		}
	}

	if($mod == 'reading_cga_referee_certificate'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_avatar_reader.php');
		$ar = new avatar_reader();
		$ar->read($uid,$uploaddir,'_cga_referee_certificate');

	}

	if($mod == 'uploading_cga_referee_certificate'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
                $uafg = new uploaded_avatar_filename_getter();
                $stored_cga_referee_certificate_filename = $uafg->get($uid,$uploaddir,'_cga_referee_certificate');
                if($stored_cga_referee_certificate_filename != ''){
                        $stored_cga_referee_certificate_md5 = md5_file($stored_cga_referee_certificate_filename);
                }

                $uafg = new uploaded_avatar_filename_getter();
                $stored_cga_referee_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_cga_referee_certificate_original_size');
                if($stored_cga_referee_certificate_original_size_filename != ''){
                        $stored_cga_referee_certificate_original_size_md5 = md5_file($stored_cga_referee_certificate_original_size_filename);
                }

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_avatar_uploader.php');
		$au = new avatar_uploader();
		$au->upload($uid,$uploaddir,'_cga_referee_certificate');	

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
                $uafg = new uploaded_avatar_filename_getter();
                $cga_referee_certificate_filename = $uafg->get($uid,$uploaddir,'_cga_referee_certificate');
                if($cga_referee_certificate_filename != ''){
                        $cga_referee_certificate_md5 = md5_file($cga_referee_certificate_filename);
                }

                $uafg = new uploaded_avatar_filename_getter();
                $cga_referee_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_cga_referee_certificate_original_size');
                if($cga_referee_certificate_original_size_filename != ''){
                        $cga_referee_certificate_original_size_md5 = md5_file($cga_referee_certificate_original_size_filename);
                }

		if($stored_cga_referee_certificate_md5 != $cga_referee_certificate_md5 && $stored_cga_referee_certificate_original_size_md5 != $cga_referee_certificate_original_size_md5){

                        $badge_id = 18;
                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                        $uabu = new user_applying_badges_updater();
                        $uabu->update_getting_badge_or_not($uid,$badge_id,0);
						
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                        $uabg = new user_applying_badges_getter();
                        $trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

                        if($trainer_badge_amount == 0){
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                                $gu = new guanxi_updater();
                                $gu->update_iscomp(1899467,$uid,0);
                        }

                }

	}

	if($mod == 'removing_cga_referee_certificate'){

		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
		$uafg = new uploaded_avatar_filename_getter();
		$cga_referee_certificate_filename = $uafg->get($uid,$uploaddir,'_cga_referee_certificate');

		$uafg = new uploaded_avatar_filename_getter();
		$cga_referee_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_cga_referee_certificate_original_size');

		$badge_id = 18;
                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                $uabu = new user_applying_badges_updater();
                $uabu->update_getting_badge_or_not($uid,$badge_id,0);

                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

                if($trainer_badge_amount == 0){
                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                        $gu = new guanxi_updater();
                        $gu->update_iscomp(1899467,$uid,0);
                }

		if(unlink($cga_referee_certificate_filename) && unlink($cga_referee_certificate_original_size_filename)){
			echo 'ok';
		}else{
			echo 'error';
		}	

	}

	if($mod == 'reading_cga_referee_certificate_original_size'){
	
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_avatar_reader.php');
                $ar = new avatar_reader();
                $ar->read($uid,$uploaddir,'_cga_referee_certificate_original_size');
	
	}

	if($mod == 'applying_cga_referee_badge'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$cga_referee_level = getgpc('cga_referee_level');
		if(!in_array($cga_referee_level,array('初级','中级','国家C级','国家B级','国家A级','国际级','其他'))){
			echo '请选择提供的裁判级别';
			exit;
		}

		$cga_referee_judging_game_num = getgpc('cga_referee_judging_game_num');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_str_len_checker.php');
                $slc = new str_len_checker();
                $tmp = $slc->check($cga_referee_judging_game_num,1,255,'执裁场次');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }

		$cga_referee_native_place = getgpc('cga_referee_native_place');
		$cga_referee_working_place = getgpc('cga_referee_working_place');

		$cga_referee_personal_desc = getgpc('cga_referee_personal_desc');
		$slc = new str_len_checker();
                $tmp = $slc->check($cga_referee_personal_desc,0,255,'个人描述');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
		
		$uafg = new uploaded_avatar_filename_getter();
		$cga_referee_certificate_filename = $uafg->get($uid,$uploaddir,'_cga_referee_certificate');		

		$uafg = new uploaded_avatar_filename_getter();
		$cga_referee_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_cga_referee_certificate_original_size');
		
		if($cga_referee_certificate_filename == '' && $cga_referee_certificate_original_size_filename == ''){
			echo '需要上传中高协裁判证书';
			exit;
		}
		
		$badge_id = 18;

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
                if($record_amount == 1){

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 18;
                                $tag_name = 'cga_referee_level';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_cga_referee_level = $row['tag_value'];

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 18;
                                $tag_name = 'cga_referee_judging_game_num';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_cga_referee_judging_game_num = $row['tag_value'];

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 18;
                                $tag_name = 'cga_referee_native_place';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_cga_referee_native_place = $row['tag_value'];

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 18;
                                $tag_name = 'cga_referee_working_place';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_cga_referee_working_place = $row['tag_value'];

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 18;
                                $tag_name = 'cga_referee_personal_desc';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_cga_referee_personal_desc = $row['tag_value'];

				if($stored_cga_referee_level != $cga_referee_level || $stored_cga_referee_judging_game_num != $cga_referee_judging_game_num || $stored_cga_referee_native_place != $cga_referee_native_place || $stored_cga_referee_working_place != $cga_referee_working_place || $stored_cga_referee_personal_desc != $cga_referee_personal_desc){
					require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_getting_badge_or_not($uid,$badge_id,0);

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                                $uabg = new user_applying_badges_getter();
                                $trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

                                	if($trainer_badge_amount == 0){
                                        	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                                        	$gu = new guanxi_updater();
                                        	$gu->update_iscomp(1899467,$uid,0);
                                	}
					
				}

                }else{
                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
                        $uaba = new user_applying_badges_adder();
                        $uaba->add($uid,$badge_id,0,0,8);
                }

                $params = array(
                        '0'=>array('tag_name'=>'cga_referee_level','tag_value'=>$cga_referee_level),
                        '1'=>array('tag_name'=>'cga_referee_judging_game_num','tag_value'=>$cga_referee_judging_game_num),
                        '2'=>array('tag_name'=>'cga_referee_native_place','tag_value'=>$cga_referee_native_place),
			'3'=>array('tag_name'=>'cga_referee_working_place','tag_value'=>$cga_referee_working_place),
			'4'=>array('tag_name'=>'cga_referee_personal_desc','tag_value'=>$cga_referee_personal_desc),
                );

                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_updater.php');
                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_adder.php');
                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');

                for($i=0;$i<count($params);$i++){
                        $uabig = new user_applying_badge_infos_getter();
                        $record_amount = $uabig->get_record_amount_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$params[$i]['tag_name']);
                        if($record_amount == 1){
                                $uabiu = new user_applying_badge_infos_updater();
                                $uabiu->update_tag_value_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$params[$i]['tag_name'],$params[$i]['tag_value']);
                        }else{
                                $uabia = new user_applying_badge_infos_adder();
                                $uabia->add($uid,$badge_id,$params[$i]['tag_name'],$params[$i]['tag_value']);
                        }

                }
		
		echo 'ok';	

	}

	if($mod == 'applying_caddie_badge'){

		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$club_place_info1_of_caddie = getgpc('club_place_info1_of_caddie');
		if($club_place_info1_of_caddie == 0){
			echo '请选择球童所属俱乐部位于哪个省或直辖市';
			exit;
		}

		$club_place_info2_of_caddie = getgpc('club_place_info2_of_caddie');
		if($club_place_info2_of_caddie == 0){
			echo '请选择球童所属俱乐部';
			exit;
		}

		$caddie_beginning_working_date = getgpc('caddie_beginning_working_date');

		$pattern = "^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$";
		if(!ereg($pattern,$caddie_beginning_working_date)){
			echo '入行时间错误';
			exit;
		}else{
			list($year,$month,$day) = explode("-",$caddie_beginning_working_date);
			if(!checkdate($month,$day,$year)){
				echo '入行时间无效';
				exit;
			}
		}
                
		$caddie_birth_date = getgpc('caddie_birth_date');
		$pattern = "^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$";
                if(!ereg($pattern,$caddie_birth_date)){
                        echo '出生日期错误';
                        exit;
                }else{
                        list($year,$month,$day) = explode("-",$caddie_birth_date);
                        if(!checkdate($month,$day,$year)){
                                echo '出生日期无效';
                                exit;
                        }
                }

		$caddie_working_place_info1_1 = getgpc('caddie_working_place_info1_1');
		$caddie_working_place_info1_2 = getgpc('caddie_working_place_info1_2');
		
		$caddie_working_place_info2_1 = getgpc('caddie_working_place_info2_1');
		$caddie_working_place_info2_2 = getgpc('caddie_working_place_info2_2');
		$caddie_working_place_info3_1 = getgpc('caddie_working_place_info3_1');
		$caddie_working_place_info3_2 = getgpc('caddie_working_place_info3_2');

		$caddie_personal_desc = getgpc('caddie_personal_desc');
		if($caddie_personal_desc != ''){
			if(mb_strlen($caddie_personal_desc,"UTF-8") > 255){
				echo '个人简介字数不可以超过255';
				exit;
			}
		}

		$badge_id = 19;

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
                if($record_amount == 1){

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                        $uabig = new user_applying_badge_infos_getter();
                        $badge_id = 19;
                        $tag_name = 'club_place_info1_of_caddie';
                        $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                        $stored_club_place_info1_of_caddie = $row['tag_value'];

			
#

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 19;
                                $tag_name = 'caddie_working_place_info1_1';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_caddie_working_place_info1_1 = $row['tag_value'];
				
				

#

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 19;
                                $tag_name = 'caddie_working_place_info2_1';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_caddie_working_place_info2_1 = $row['tag_value'];

				

#

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 19;
                                $tag_name = 'caddie_working_place_info3_1';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_caddie_working_place_info3_1 = $row['tag_value'];
                                

#
				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 19;
                                $tag_name = 'club_place_info2_of_caddie';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_club_place_info2_of_caddie = $row['tag_value'];

                            

#

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 19;
                                $tag_name = 'caddie_working_place_info1_2';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_caddie_working_place_info1_2 = $row['tag_value'];


#

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 19;
                                $tag_name = 'caddie_working_place_info2_2';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_caddie_working_place_info2_2 = $row['tag_value'];

                                
#

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 19;
                                $tag_name = 'caddie_working_place_info3_2';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_caddie_working_place_info3_2 = $row['tag_value'];


#

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 19;
                                $tag_name = 'caddie_beginning_working_date';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_caddie_beginning_working_date = $row['tag_value'];

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 19;
                                $tag_name = 'caddie_birth_date';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_caddie_birth_date = $row['tag_value'];

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 19;
                                $tag_name = 'caddie_personal_desc';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_caddie_personal_desc = $row['tag_value'];

			if($stored_club_place_info1_of_caddie != $club_place_info1_of_caddie || $stored_caddie_working_place_info1_1 != $caddie_working_place_info1_1 || $stored_caddie_working_place_info2_1 != $caddie_working_place_info2_1 || $stored_caddie_working_place_info3_1 != $caddie_working_place_info3_1 || $stored_club_place_info2_of_caddie != $club_place_info2_of_caddie || $stored_caddie_working_place_info1_2 != $caddie_working_place_info1_2 || $stored_caddie_working_place_info2_2 != $caddie_working_place_info2_2 || $stored_caddie_working_place_info3_2 != $caddie_working_place_info3_2 || $stored_caddie_beginning_working_date != $caddie_beginning_working_date || $stored_caddie_birth_date != $caddie_birth_date || $stored_caddie_personal_desc != $caddie_personal_desc){

				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_getting_badge_or_not($uid,$badge_id,0);

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                                $uabg = new user_applying_badges_getter();
                                $trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

                                if($trainer_badge_amount == 0){
                                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                                        $gu = new guanxi_updater();
                                        $gu->update_iscomp(1899467,$uid,0);
                                }

			}

                }else{
                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
                        $uaba = new user_applying_badges_adder();
                        $uaba->add($uid,$badge_id,0,0,7);
                }

                $params = array(
                        '0'=>array('tag_name'=>'club_place_info1_of_caddie','tag_value'=>$club_place_info1_of_caddie),
                        '1'=>array('tag_name'=>'club_place_info2_of_caddie','tag_value'=>$club_place_info2_of_caddie),
                        '2'=>array('tag_name'=>'caddie_beginning_working_date','tag_value'=>$caddie_beginning_working_date),
			'3'=>array('tag_name'=>'caddie_birth_date','tag_value'=>$caddie_birth_date),
			'4'=>array('tag_name'=>'caddie_working_place_info1_1','tag_value'=>$caddie_working_place_info1_1),
			'5'=>array('tag_name'=>'caddie_working_place_info1_2','tag_value'=>$caddie_working_place_info1_2),
			'6'=>array('tag_name'=>'caddie_working_place_info2_1','tag_value'=>$caddie_working_place_info2_1),
			'7'=>array('tag_name'=>'caddie_working_place_info2_2','tag_value'=>$caddie_working_place_info2_2),
			'8'=>array('tag_name'=>'caddie_working_place_info3_1','tag_value'=>$caddie_working_place_info3_1),
			'9'=>array('tag_name'=>'caddie_working_place_info3_2','tag_value'=>$caddie_working_place_info3_2),
			'10'=>array('tag_name'=>'caddie_personal_desc','tag_value'=>$caddie_personal_desc),
                );

                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_updater.php');
                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_adder.php');
                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');

                for($i=0;$i<count($params);$i++){
                        $uabig = new user_applying_badge_infos_getter();
                        $record_amount = $uabig->get_record_amount_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$params[$i]['tag_name']);
                        if($record_amount == 1){
                                $uabiu = new user_applying_badge_infos_updater();
                                $uabiu->update_tag_value_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$params[$i]['tag_name'],$params[$i]['tag_value']);
                        }else{
                                $uabia = new user_applying_badge_infos_adder();
                                $uabia->add($uid,$badge_id,$params[$i]['tag_name'],$params[$i]['tag_value']);
                        }

                }

		echo 'ok';

	}

	if($mod == 'applying_common_employee_badge'){

		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$company_name_of_practitioner = getgpc('company_name_of_practitioner');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_str_len_checker.php');
		$slc = new str_len_checker();
                $tmp = $slc->check($company_name_of_practitioner,1,255,'公司名称');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }

		$duty_of_practitioner = getgpc('duty_of_practitioner');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_str_len_checker.php');
                $slc = new str_len_checker();
                $tmp = $slc->check($duty_of_practitioner,0,255,'职务');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }

		$company_address_of_practitioner = getgpc('company_address_of_practitioner');
		$slc = new str_len_checker();
                $tmp = $slc->check($company_address_of_practitioner,0,255,'公司地址');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }	

		$personal_desc_of_practitioner = getgpc('personal_desc_of_practitioner');
		$slc = new str_len_checker();
                $tmp = $slc->check($personal_desc_of_practitioner,0,255,'个人简介');
                if($tmp!==true){
                        echo $tmp;
                        exit;
                }

		$badge_id = 20;

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
                if($record_amount == 1){

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 20;
                                $tag_name = 'company_name_of_practitioner';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_company_name_of_practitioner = $row['tag_value'];

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 20;
                                $tag_name = 'duty_of_practitioner';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_duty_of_practitioner = $row['tag_value'];

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 20;
                                $tag_name = 'company_address_of_practitioner';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_company_address_of_practitioner = $row['tag_value'];

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                                $uabig = new user_applying_badge_infos_getter();
                                $badge_id = 20;
                                $tag_name = 'personal_desc_of_practitioner';
                                $row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                                $stored_personal_desc_of_practitioner = $row['tag_value'];

			if($stored_company_name_of_practitioner != $company_name_of_practitioner || $stored_duty_of_practitioner != $duty_of_practitioner || $stored_company_address_of_practitioner != $company_address_of_practitioner || $stored_personal_desc_of_practitioner != $personal_desc_of_practitioner){
				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_getting_badge_or_not($uid,$badge_id,0);

                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                                $uabg = new user_applying_badges_getter();
                                $trainer_badge_amount = $uabg->get_trainer_badge_amount($uid);

                                if($trainer_badge_amount == 0){
                                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                                        $gu = new guanxi_updater();
                                        $gu->update_iscomp(1899467,$uid,0);
                                }
			}

                }else{
                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
                        $uaba = new user_applying_badges_adder();
                        $uaba->add($uid,$badge_id,0,0,6);
                }

                $params = array(
                        '0'=>array('tag_name'=>'company_name_of_practitioner','tag_value'=>$company_name_of_practitioner),
                        '1'=>array('tag_name'=>'duty_of_practitioner','tag_value'=>$duty_of_practitioner),
                        '2'=>array('tag_name'=>'company_address_of_practitioner','tag_value'=>$company_address_of_practitioner),
			'3'=>array('tag_name'=>'personal_desc_of_practitioner','tag_value'=>$personal_desc_of_practitioner)
                );

                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_updater.php');
                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_adder.php');
                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');

                for($i=0;$i<count($params);$i++){
                        $uabig = new user_applying_badge_infos_getter();
                        $record_amount = $uabig->get_record_amount_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$params[$i]['tag_name']);
                        if($record_amount == 1){
                                $uabiu = new user_applying_badge_infos_updater();
                                $uabiu->update_tag_value_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$params[$i]['tag_name'],$params[$i]['tag_value']);
                        }else{
                                $uabia = new user_applying_badge_infos_adder();
                                $uabia->add($uid,$badge_id,$params[$i]['tag_name'],$params[$i]['tag_value']);
                        }

                }
		
		echo 'ok';	
	}

	if($mod == 'applying_taylormade_badge'){

		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }
		
		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
		
		$badge_id = 21;

                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
                if($record_amount == 1){

                }else{
                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
                        $uaba = new user_applying_badges_adder();
                        $uaba->add($uid,$badge_id,0,0,16);
                }
		
		
		echo '申请成功提交，请耐心等待审核';
		exit;	

	}

	if($mod == 'applying_china_golf_media_league_badge'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$badge_id = 22;

                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
                if($record_amount == 1){

                }else{
                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
                        $uaba = new user_applying_badges_adder();
                        $uaba->add($uid,$badge_id,0,0,13);
                }
		
		echo '申请成功提交，请耐心等待审核';
		exit;

	}

	if($mod == 'applying_rolex_member_badge'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$badge_id = 23;

                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
                if($record_amount == 1){

                }else{
                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
                        $uaba = new user_applying_badges_adder();
                        $uaba->add($uid,$badge_id,0,0,17);
                }
		
		echo '申请成功提交，请耐心等待审核';
		exit;

	}

	if($mod == 'applying_ziji_investment_club_member_badge'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$badge_id = 24;

                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
                if($record_amount == 1){

                }else{
                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
                        $uaba = new user_applying_badges_adder();
                        $uaba->add($uid,$badge_id,0,0,18);
                }
		
		echo '申请成功提交，请耐心等待审核';
		exit;

	}

	if($mod == 'applying_pamirs_spring_water_club_member_badge'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$badge_id = 25;

                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
                if($record_amount == 1){

                }else{
                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
                        $uaba = new user_applying_badges_adder();
                        $uaba->add($uid,$badge_id,0,0,19);
                }
		
		echo '申请成功提交，请耐心等待审核';
		exit;

	}

	if($mod == 'applying_nike_golf_badge'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$badge_id = 26;

                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                $uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
                if($record_amount == 1){

                }else{
                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
                        $uaba = new user_applying_badges_adder();
                        $uaba->add($uid,$badge_id,0,0,20);
                }
		
		echo '申请成功提交，请耐心等待审核';
		exit;

	}

	if($mod == 'applying_microblog_bronze_medal'){
		
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$badge_id = 27;

                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_jishigou_topic_getter.php');
                $jtg = new jishigou_topic_getter();
                $record_amount = $jtg->get_record_by_uid($uid);
                if($record_amount >= 10){

                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                        $uabg = new user_applying_badges_getter();
                        $record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
                        if($record_amount == 1){

                        }else{
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
                                $uaba = new user_applying_badges_adder();
                                $uaba->add($uid,$badge_id,1,1,27);
                        }
			echo 'ok';
                        exit;
                }else{
                        echo '您目前已经发送'.$record_amount .'条微博，发送10条微博后才可以申请此徽章';
                        exit;
                }
		
	}

	if($mod == 'applying_blog_bronze_medal'){
	
		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$badge_id = 28;

                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_home_blog_getter.php');
		$hbg = new home_blog_getter();
		$record_amount = $hbg->get_record_amount_by_uid($uid);
		
                if($record_amount >= 3){

                        require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                        $uabg = new user_applying_badges_getter();
                        $record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
                        if($record_amount == 1){

                        }else{
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
                                $uaba = new user_applying_badges_adder();
                                $uaba->add($uid,$badge_id,1,1,28);
                        }
			echo 'ok';
                        exit;
                }else{
                        echo '您目前已经发布'.$record_amount.'篇博文，发布博文3篇后才可以申请此徽章';
                        exit;
                }
		
	}

	if($mod == 'applying_grade_card_bronze_medal'){

		if($uid == 0){
                        echo '需要登录才可以进行此操作';
                        exit;
                }

		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
				
		$badge_id = 29;
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/apply_class_common_score_getter.php');
                $csg = new common_score_getter();
                $sais_id = 0;
                $record_amount = $csg->get_record_amount_by_uid_and_sais_id($uid,$sais_id);
		if($record_amount >= 3){
	
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
                        $uabg = new user_applying_badges_getter();
                        $record_amount = $uabg->get_record_amount_by_uid_and_badge_id($uid,$badge_id);
                        if($record_amount == 1){

                        }else{
                                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_adder.php');
                                $uaba = new user_applying_badges_adder();
                                $uaba->add($uid,$badge_id,1,1,29);
                        }
			echo 'ok';
                        exit;
		}else{
			echo '您目前已经上传'.$record_amount.'张成绩卡，上传成绩卡3张后才可以申请此徽章';
			exit;	
		}

	}

	if($mod == 'getting_uid'){
		//echo $uid;
		
		if($uid == 0){
			echo '需要登录才可以进行此操作';
			exit;
		}
		
		if($groupid != 10){
			echo '个人用户才可以进行此操作';
			exit;
		}
		
		if($uid == 0){
			$tmp = array('login_or_not'=>0);
		}else{
			$tmp = array('login_or_not'=>1);
		}

		echo json_encode($tmp);

	}

	if($mod == 'getting_club_trainer_info'){
		
		if($uid == 0){
			$tmp = array('login_or_not'=>0);
		}else{
			
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');

			$uafg = new uploaded_avatar_filename_getter();
                	$club_trainer_certificate_filename = $uafg->get($uid,$uploaddir,'_club_trainer_certificate');

                	$uafg = new uploaded_avatar_filename_getter();
                	$club_trainer_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_club_trainer_certificate_original_size');

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 1;
			$tag_name = 'org_club_trainer_belonging';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$org_club_trainer_belonging = $row['tag_value'];

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 1;
			$tag_name = 'club_trainer_duty';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$club_trainer_duty = $row['tag_value'];

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
			$uabig = new user_applying_badge_infos_getter();
			$badge_id = 1;
			$tag_name = 'club_trainer_teaching_strong_point';
			$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$club_trainer_teaching_strong_point = $row['tag_value'];
	
			if($club_trainer_certificate_filename != '' && $club_trainer_certificate_original_size_filename != ''){
				$upload_filename_or_not = 1;	
			}else{
				$upload_filename_or_not = 0;
			}
		
			$tmp = array(
				'login_or_not'=>1,
				'upload_filename_or_not'=>$upload_filename_or_not,
				'org_club_trainer_belonging'=>$org_club_trainer_belonging,
				'club_trainer_duty'=>$club_trainer_duty,
				'club_trainer_teaching_strong_point'=>$club_trainer_teaching_strong_point
			);

		}

		echo json_encode($tmp);

	}

	if($mod == 'getting_cga_trainer_info'){
		
		if($uid == 0){
			$tmp = array(
				'login_or_not'=>0,
				
			);
		}else{
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');

                	$uafg = new uploaded_avatar_filename_getter();
                	$cga_trainer_certificate_filename = $uafg->get($uid,$uploaddir,'_cga_trainer_certificate');

                	$uafg = new uploaded_avatar_filename_getter();
                	$cga_trainer_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_cga_trainer_certificate_original_size');

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                	$uabig = new user_applying_badge_infos_getter();
                	$badge_id = 2;
                	$tag_name = 'org_cga_trainer_belonging';
                	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                	$org_cga_trainer_belonging = $row['tag_value'];

                	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                	$uabig = new user_applying_badge_infos_getter();
                	$badge_id = 2;
                	$tag_name = 'cga_trainer_duty';
                	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                	$cga_trainer_duty = $row['tag_value'];

                	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                	$uabig = new user_applying_badge_infos_getter();
                	$badge_id = 2;
                	$tag_name = 'cga_trainer_teaching_strong_point';
                	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                	$cga_trainer_teaching_strong_point = $row['tag_value'];		
	
			if($cga_trainer_certificate_filename != '' && $cga_trainer_certificate_original_size_filename != ''){
				$upload_filename_or_not = 1; 
			}else{
				$upload_filename_or_not = 0;
			}

			$tmp = array(
				'login_or_not'=>1,
				'upload_filename_or_not'=>$upload_filename_or_not,
				'org_cga_trainer_belonging'=>$org_cga_trainer_belonging,
				'cga_trainer_duty'=>$cga_trainer_duty,
				'cga_trainer_teaching_strong_point'=>$cga_trainer_teaching_strong_point
			);
		}
		
		echo json_encode($tmp);

	}

	if($mod == 'getting_hmt_trainer_info'){
		
		if($uid == 0){
                        $tmp = array('login_or_not'=>0);
                }else{
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
			$uafg = new uploaded_avatar_filename_getter();
                	$hmt_trainer_certificate_filename = $uafg->get($uid,$uploaddir,'_hmt_trainer_certificate');

                	$uafg = new uploaded_avatar_filename_getter();
                	$hmt_trainer_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_hmt_trainer_certificate_original_size');

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                	$uabig = new user_applying_badge_infos_getter();
                	$badge_id = 3;
                	$tag_name = 'org_hmt_trainer_belonging';
                	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                	$org_hmt_trainer_belonging = $row['tag_value'];

                	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                	$uabig = new user_applying_badge_infos_getter();
                	$badge_id = 3;
                	$tag_name = 'hmt_trainer_duty';
                	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                	$hmt_trainer_duty = $row['tag_value'];

                	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                	$uabig = new user_applying_badge_infos_getter();
                	$badge_id = 3;
                	$tag_name = 'hmt_trainer_teaching_strong_point';
                	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                	$hmt_trainer_teaching_strong_point = $row['tag_value'];

			if($hmt_trainer_certificate_filename != '' && $hmt_trainer_certificate_original_size_filename != ''){
				$upload_filename_or_not = 1;
			}else{
				$upload_filename_or_not = 0;
			}

			$tmp = array(
				'login_or_not'=>1,
				'upload_filename_or_not'=>$upload_filename_or_not,
				'org_hmt_trainer_belonging'=>$org_hmt_trainer_belonging,
				'hmt_trainer_duty'=>$hmt_trainer_duty,
				'hmt_trainer_teaching_strong_point'=>$hmt_trainer_teaching_strong_point
			);
	
		}

		echo json_encode($tmp);

	}

	if($mod == 'getting_foreign_trainer_info'){
	
		if($uid == 0){
                        $tmp = array('login_or_not'=>0);
                }else{
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');		
		
			$uafg = new uploaded_avatar_filename_getter();
                	$foreign_trainer_certificate_filename = $uafg->get($uid,$uploaddir,'_foreign_trainer_certificate');

                	$uafg = new uploaded_avatar_filename_getter();
                	$foreign_trainer_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_foreign_trainer_certificate_original_size');

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                	$uabig = new user_applying_badge_infos_getter();
                	$badge_id = 4;
                	$tag_name = 'org_foreign_trainer_belonging';
                	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                	$org_foreign_trainer_belonging = $row['tag_value'];

                	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                	$uabig = new user_applying_badge_infos_getter();
                	$badge_id = 4;
                	$tag_name = 'foreign_trainer_duty';
                	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                	$foreign_trainer_duty = $row['tag_value'];

                	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                	$uabig = new user_applying_badge_infos_getter();
                	$badge_id = 4;
                	$tag_name = 'foreign_trainer_teaching_strong_point';
                	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                	$foreign_trainer_teaching_strong_point = $row['tag_value'];

			if($foreign_trainer_certificate_filename != '' && $foreign_trainer_certificate_original_size_filename != ''){
				$upload_filename_or_not = 1;
			}else{
				$upload_filename_or_not = 0;
			}

			$tmp = array(
				'login_or_not'=>1,
				'upload_filename_or_not'=>$upload_filename_or_not,
				'org_foreign_trainer_belonging'=>$org_foreign_trainer_belonging,
				'foreign_trainer_duty'=>$foreign_trainer_duty,
				'foreign_trainer_teaching_strong_point'=>$foreign_trainer_teaching_strong_point
			);

		}
	
		echo json_encode($tmp);

	}

	if($mod == 'getting_course_manager_info'){
	
		if($uid == 0){
                        $tmp = array('login_or_not'=>0);
                }else{

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
		
			$tmp = array(
				'login_or_not'=>1,
				'company_name_of_course_manager'=>$company_name_of_course_manager,
				'company_address_of_course_manager'=>$company_address_of_course_manager,
				'personal_desc_of_course_manager'=>$personal_desc_of_course_manager
			);

		}
	
		echo json_encode($tmp);

	}

	if($mod == 'getting_lawn_expert_info'){
		
		if($uid == 0){
                        $tmp = array('login_or_not'=>0);
                }else{
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

			$tmp = array(
				'login_or_not'=>1,
				'lawn_expert_name_and_duty'=>$lawn_expert_name_and_duty,
				'lawn_expert_personal_desc'=>$lawn_expert_personal_desc
			);

		}

		echo json_encode($tmp);

	}

	if($mod == 'getting_expert_info'){
		
		if($uid == 0){
			$tmp = array('login_or_not'=>0);
		}else{
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

			$tmp = array(
				'login_or_not'=>1,
				'expert_name_and_duty'=>$expert_name_and_duty,
				'expert_personal_desc'=>$expert_personal_desc
			);

		}

		echo json_encode($tmp);

	}

	if($mod == 'getting_toyota_crown_owner_info'){
		if($uid == 0){
                        $tmp = array('login_or_not'=>0);
                }else{
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
			$uafg = new uploaded_avatar_filename_getter();
                	$toyota_crown_owner_certificate_filename = $uafg->get($uid,$uploaddir,'_toyota_crown_owner_certificate');

                	$uafg = new uploaded_avatar_filename_getter();
                	$toyota_crown_owner_certificate_original_size_filename = $uafg->get($uid,$uploaddir,'_toyota_crown_owner_certificate_original_size');		
	
			if($toyota_crown_owner_certificate_filename != '' && $toyota_crown_owner_certificate_original_size_filename != ''){
				$upload_filename_or_not = 1;
			}else{
				$upload_filename_or_not = 0;
			}
		
			$tmp = array(
				'login_or_not'=>1,
				'upload_filename_or_not'=>$upload_filename_or_not
			);
		}

		echo json_encode($tmp);

	}

	if($mod == 'getting_ct_diamond_card_info'){
		if($uid == 0){
                        $tmp = array('login_or_not'=>0);
                }else{
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
			$uafg = new uploaded_avatar_filename_getter();
                	$ct_diamond_card_pic_filename = $uafg->get($uid,$uploaddir,'_ct_diamond_card_pic');

                	$uafg = new uploaded_avatar_filename_getter();
                	$ct_diamond_card_pic_original_size_filename = $uafg->get($uid,$uploaddir,'_ct_diamond_card_pic_original_size');		
			
			if($ct_diamond_card_pic_filename != '' && $ct_diamond_card_pic_original_size_filename != ''){
				$upload_filename_or_not = 1;
			}else{
				$upload_filename_or_not = 0;
			}

			$tmp = array(
                                'login_or_not'=>1,
                                'upload_filename_or_not'=>$upload_filename_or_not
                        );

		}

		echo json_encode($tmp);

	}

	if($mod == 'getting_cga_referee_info'){
		if($uid == 0){
                        $tmp = array('login_or_not'=>0);
                }else{
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

			if($cga_referee_certificate_filename != '' && $cga_referee_certificate_original_size_filename != ''){
				$upload_filename_or_not = 1;
			}else{
				$upload_filename_or_not = 0;
			}

			$tmp = array(
				'login_or_not'=>1,
				'upload_filename_or_not'=>$upload_filename_or_not,
				'cga_referee_level'=>$cga_referee_level,
				'cga_referee_judging_game_num'=>$cga_referee_judging_game_num,
				'cga_referee_native_place'=>$cga_referee_native_place,
				'cga_referee_working_place'=>$cga_referee_working_place,
				'cga_referee_personal_desc'=>$cga_referee_personal_desc
			);

		}

		echo json_encode($tmp);

	}

	if($mod == 'getting_caddie_info'){
		
		if($uid == 0){
                        $tmp = array('login_or_not'=>0);
                }else{
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                	$uabig = new user_applying_badge_infos_getter();
                	$badge_id = 19;
                	$tag_name = 'club_place_info1_of_caddie';
                	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                	$club_place_info1_of_caddie = $row['tag_value'];

                	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                	$uabig = new user_applying_badge_infos_getter();
                	$badge_id = 19;
                	$tag_name = 'club_place_info2_of_caddie';
                	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                	$club_place_info2_of_caddie = $row['tag_value'];

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
                	$tag_name = 'caddie_working_place_info1_1';
                	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                	$caddie_working_place_info1_1 = $row['tag_value'];

                	require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                	$uabig = new user_applying_badge_infos_getter();
                	$badge_id = 19;
                	$tag_name = 'caddie_working_place_info1_2';
                	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
                	$caddie_working_place_info1_2 = $row['tag_value'];

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                	$uabig = new user_applying_badge_infos_getter();
                	$badge_id = 19;
                	$tag_name = 'caddie_working_place_info2_1';
                	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$caddie_working_place_info2_1 = $row['tag_value'];

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                	$uabig = new user_applying_badge_infos_getter();
                	$badge_id = 19;
                	$tag_name = 'caddie_working_place_info2_2';
                	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$caddie_working_place_info2_2 = $row['tag_value'];

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                	$uabig = new user_applying_badge_infos_getter();
                	$badge_id = 19;
                	$tag_name = 'caddie_working_place_info3_1';
                	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$caddie_working_place_info3_1 = $row['tag_value'];

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                	$uabig = new user_applying_badge_infos_getter();
                	$badge_id = 19;
                	$tag_name = 'caddie_working_place_info3_2';
                	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$caddie_working_place_info3_2 = $row['tag_value'];

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badge_infos_getter.php');
                	$uabig = new user_applying_badge_infos_getter();
                	$badge_id = 19;
                	$tag_name = 'caddie_personal_desc';
                	$row = $uabig->get_record_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name);
			$caddie_personal_desc = $row['tag_value'];

			$tmp = array(
				'login_or_not'=>1,
				'club_place_info1_of_caddie'=>$club_place_info1_of_caddie,
				'club_place_info2_of_caddie'=>$club_place_info2_of_caddie,
				'caddie_beginning_working_date'=>$caddie_beginning_working_date,
				'caddie_birth_date'=>$caddie_birth_date,
				'caddie_working_place_info1_1'=>$caddie_working_place_info1_1,
				'caddie_working_place_info1_2'=>$caddie_working_place_info1_2,
				'caddie_working_place_info2_1'=>$caddie_working_place_info2_1,
				'caddie_working_place_info2_2'=>$caddie_working_place_info2_2,
				'caddie_working_place_info3_1'=>$caddie_working_place_info3_1,
				'caddie_working_place_info3_2'=>$caddie_working_place_info3_2,
				'caddie_personal_desc'=>$caddie_personal_desc
			);	

		}

		echo json_encode($tmp);

	}

	if($mod == 'getting_common_employee_info'){

		if($uid == 0){
                        $tmp = array('login_or_not'=>0);
                }else{
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

			$tmp = array(
				'login_or_not'=>1,
				'company_name_of_practitioner'=>$company_name_of_practitioner,
				'duty_of_practitioner'=>$duty_of_practitioner,
				'company_address_of_practitioner'=>$company_address_of_practitioner,
				'personal_desc_of_practitioner'=>$personal_desc_of_practitioner
			);

		}
		
		echo json_encode($tmp);	
			
	}

	if($mod == 'got_badge_list'){

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
		$uabg = new user_applying_badges_getter();
		$rows = $uabg->get_record_by_uid_and_getting_badge_or_not($uid,1);
		$badge_amount = 0;

		$cga_trainer_badge_amount = 0;
		$certificated_club_badge_amount = 0;
		$foreign_trainer_badge_amount = 0;
		$hmt_trainer_badge_amount = 0;
		$cga_referee_badge_amount = 0;
		$common_employee_badge_amount = 0;
		$course_manager_badge_amount = 0;
		$caddie_badge_amount = 0;
		$lawn_expert_badge_amount = 0;
		$expert_badge_amount = 0;

		for($i=0;$i<count($rows);$i++){

			if($rows[$i]['getting_badge_or_not'] == 1){
				$badge_amount++;
			}

			if($rows[$i]['badge_id'] == 2){
				$cga_trainer_badge_amount++;
			}

			if($rows[$i]['badge_id'] == 1){
				$certificated_club_badge_amount++;
			}

			if($rows[$i]['badge_id'] == 4){
				$foreign_trainer_badge_amount++;
			}

			if($rows[$i]['badge_id'] == 3){
				$hmt_trainer_badge_amount++;
			}
			
			if($rows[$i]['badge_id'] == 18){
				$cga_referee_badge_amount++;
			}

			if($rows[$i]['badge_id'] == 20){
				$common_employee_badge_amount++;
			}

			if($rows[$i]['badge_id'] == 7){
				$course_manager_badge_amount++;
			}

			if($rows[$i]['badge_id'] == 19){
				$caddie_badge_amount++;
			}

			if($rows[$i]['badge_id'] == 8){
				$lawn_expert_badge_amount++;
			}

			if($rows[$i]['badge_id'] == 9){
				$expert_badge_amount++;
			}

		}

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_badges_related_to_page_getter.php');
		$brtpg = new badges_related_to_page_getter();
		$row = $brtpg->get_record_by_uid($uid);

		$templates = 'apply_badge/'.$groupid.'_got_badge_list';
                require_once(template($templates));
	}

	if($mod == 'storing_showed_order'){
		
		$certificated_club_badge_showed_order = getgpc('certificated_club_badge_showed_order');
		$cga_trainer_badge_showed_order = getgpc('cga_trainer_badge_showed_order');
		$hmt_trainer_badge_showed_order = getgpc('hmt_trainer_badge_showed_order');
		$foreign_trainer_badge_showed_order = getgpc('foreign_trainer_badge_showed_order');
		$toyota_crown_city_challenge_badge_showed_order = getgpc('toyota_crown_city_challenge_badge_showed_order');#
		$dazheng_shop_badge_showed_order = getgpc('dazheng_shop_badge_showed_order');#
		$course_manager_badge_showed_order = getgpc('course_manager_badge_showed_order');
		$lawn_expert_badge_showed_order = getgpc('lawn_expert_badge_showed_order');
		$expert_badge_showed_order = getgpc('expert_badge_showed_order');
		$microblog_gold_medal_showed_order = getgpc('microblog_gold_medal_showed_order');
		$blog_gold_medal_showed_order = getgpc('blog_gold_medal_showed_order');
		$grade_card_gold_medal_showed_order = getgpc('grade_card_gold_medal_showed_order');
		$toyota_crown_owner_badge_showed_order = getgpc('toyota_crown_owner_badge_showed_order');
		$ct_diamond_card_badge_showed_order = getgpc('ct_diamond_card_badge_showed_order');
		$microblog_silver_medal_showed_order = getgpc('microblog_silver_medal_showed_order');
		$blog_silver_medal_showed_order = getgpc('blog_silver_medal_showed_order');
		$grade_card_silver_medal_showed_order = getgpc('grade_card_silver_medal_showed_order');
		$cga_referee_badge_showed_order = getgpc('cga_referee_badge_showed_order');
		$caddie_badge_showed_order = getgpc('caddie_badge_showed_order');
		$common_employee_badge_showed_order = getgpc('common_employee_badge_showed_order');
		$taylormade_badge_showed_order = getgpc('taylormade_badge_showed_order');
		$china_golf_media_league_badge_showed_order = getgpc('china_golf_media_league_badge_showed_order');
		$rolex_member_badge_showed_order = getgpc('rolex_member_badge_showed_order');
		$ziji_investment_club_member_badge_showed_order = getgpc('ziji_investment_club_member_badge_showed_order');
		$pamirs_spring_water_club_member_badge_showed_order = getgpc('pamirs_spring_water_club_member_badge_showed_order');
		$nike_golf_badge_showed_order = getgpc('nike_golf_badge_showed_order');
		$microblog_bronze_medal_showed_order = getgpc('microblog_bronze_medal_showed_order');
		$blog_bronze_medal_showed_order = getgpc('blog_bronze_medal_showed_order');
		$grade_card_bronze_medal_showed_order = getgpc('grade_card_bronze_medal_showed_order');
		$certificated_name_badge_showed_order = getgpc('certificated_name_badge_showed_order');

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_integer_validator.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_updater.php');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');

		if($certificated_club_badge_showed_order == '' &&
		$cga_trainer_badge_showed_order == '' &&
		$hmt_trainer_badge_showed_order == '' &&
		$foreign_trainer_badge_showed_order == '' &&
		$toyota_crown_city_challenge_badge_showed_order == '' &&
		$dazheng_shop_badge_showed_order == '' &&
		$course_manager_badge_showed_order == '' &&
		$lawn_expert_badge_showed_order == '' &&
		$expert_badge_showed_order == '' &&
		$microblog_gold_medal_showed_order == '' &&
		$blog_gold_medal_showed_order == '' &&
		$grade_card_gold_medal_showed_order == '' &&
		$toyota_crown_owner_badge_showed_order == '' &&
		$ct_diamond_card_badge_showed_order == '' &&
		$microblog_silver_medal_showed_order == '' &&
		$blog_silver_medal_showed_order == '' &&
		$grade_card_silver_medal_showed_order == '' &&
		$cga_referee_badge_showed_order == '' &&
		$caddie_badge_showed_order == '' &&
		$common_employee_badge_showed_order == '' &&
		$taylormade_badge_showed_order == '' &&
		$china_golf_media_league_badge_showed_order =='' &&
		$rolex_member_badge_showed_order == '' &&
		$ziji_investment_club_member_badge_showed_order == '' &&
		$pamirs_spring_water_club_member_badge_showed_order == '' &&
		$nike_golf_badge_showed_order == '' &&
		$microblog_bronze_medal_showed_order == '' &&
		$blog_bronze_medal_showed_order == '' &&
		$grade_card_bronze_medal_showed_order == '' &&
		$certificated_name_badge_showed_order == ''
		){
			echo '至少需要为一个徽章指定显示顺序';
			exit;
		}

		$data = array();

		$uabg = new user_applying_badges_getter();
		$record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,1,1);

		if($record_amount == 1){
			$iv = new integer_validator();
			if($iv->validate($certificated_club_badge_showed_order)==false){
				echo '俱乐部认证教练徽章显示顺序必须是整数';
				exit;
			}

			

			$data['certificated_club_badge_showed_order'] = $certificated_club_badge_showed_order;

		}
		
		$uabg = new user_applying_badges_getter();
		$record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,2,1);
		if($record_amount == 1){
			$iv = new integer_validator();
			if($iv->validate($cga_trainer_badge_showed_order)==false){
				echo '中高协教练徽章显示顺序必须是整数';
				exit;
			}

			

			$data['cga_trainer_badge_showed_order'] = $cga_trainer_badge_showed_order;

		}

		$uabg = new user_applying_badges_getter();
		$record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,3,1);
		if($record_amount == 1){
			$iv = new integer_validator();
			if($iv->validate($hmt_trainer_badge_showed_order)==false){
				echo '港澳台教练徽章显示顺序必须是整数';
				exit;
			}		
			$data['hmt_trainer_badge_showed_order']	= $hmt_trainer_badge_showed_order;	
		}

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,4,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($foreign_trainer_badge_showed_order)==false){
                                echo '外籍教练徽章显示顺序必须是整数';
                                exit;
                        }

			

                        $data['foreign_trainer_badge_showed_order'] = $foreign_trainer_badge_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,5,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($toyota_crown_city_challenge_badge_showed_order)==false){
                                echo '皇冠杯城市挑战赛徽章显示顺序必须是整数';
                                exit;
                        }

			

                        $data['toyota_crown_city_challenge_badge_showed_order'] = $toyota_crown_city_challenge_badge_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,6,1);
                if($record_amount == 1){
                        $iv = new integer_validator($dazheng_shop_badge_showed_order);
                        if($iv->validate()==false){
                                echo '大正商城用户徽章显示顺序必须是整数';
                                exit;
                        }

			

                        $data['dazheng_shop_badge_showed_order'] = $dazheng_shop_badge_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,7,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($course_manager_badge_showed_order)==false){
                                echo '球场总经理徽章显示顺序必须是整数';
                                exit;
                        }

			

                        $data['course_manager_badge_showed_order'] = $course_manager_badge_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,8,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($lawn_expert_badge_showed_order)==false){
                                echo '草坪专家徽章显示顺序必须是整数';
                                exit;
                        }

			

                        $data['lawn_expert_badge_showed_order'] = $lawn_expert_badge_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,9,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($expert_badge_showed_order)==false){
                                echo '专家徽章显示顺序必须是整数';
                                exit;
                        }

			

                        $data['expert_badge_showed_order'] = $expert_badge_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,10,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($microblog_gold_medal_showed_order)==false){
                                echo '金牌微博达人徽章显示顺序必须是整数';
                                exit;
                        }

			

                        $data['microblog_gold_medal_showed_order'] = $microblog_gold_medal_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,11,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($blog_gold_medal_showed_order)==false){
                                echo '金牌博客达人徽章显示顺序必须是整数';
                                exit;
                        }

			

                        $data['blog_gold_medal_showed_order'] = $blog_gold_medal_showed_order;

                }

		$uabg = new user_applying_badges_getter();
		$record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,12,1);
		if($record_amount == 1){
			$iv = new integer_validator();
			if($iv->validate($grade_card_gold_medal_showed_order)==false){
				echo '金牌成绩卡达人徽章显示顺序必须是整数';
				exit;
			}
			$data['grade_card_gold_medal_showed_order'] = $grade_card_gold_medal_showed_order;
		}

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,13,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($toyota_crown_owner_badge_showed_order)==false){
                                echo '皇冠车主徽章显示顺序必须是整数';
                                exit;
                        }

			

                        $data['toyota_crown_owner_badge_showed_order'] = $toyota_crown_owner_badge_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,14,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($ct_diamond_card_badge_showed_order)==false){
                                echo '电信钻石卡用户徽章显示顺序必须是整数';
                                exit;
                        }

			

                        $data['ct_diamond_card_badge_showed_order'] = $ct_diamond_card_badge_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,15,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($microblog_silver_medal_showed_order)==false){
                                echo '银牌微博达人徽章显示顺序必须是整数';
                                exit;
                        }

			

                        $data['microblog_silver_medal_showed_order'] = $microblog_silver_medal_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,16,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($blog_silver_medal_showed_order)==false){
                                echo '银牌博客达人徽章显示顺序必须是整数';
                                exit;
                        }

			

                        $data['blog_silver_medal_showed_order'] = $blog_silver_medal_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,17,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($grade_card_silver_medal_showed_order)==false){
                                echo '银牌成绩卡达人徽章显示顺序必须是整数';
                                exit;
                        }

			

                        $data['grade_card_silver_medal_showed_order'] = $grade_card_silver_medal_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,18,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($cga_referee_badge_showed_order)==false){
                                echo '中高协裁判徽章显示顺序必须是整数';
                                exit;
                        }

			

                        $data['grade_card_silver_medal_showed_order'] = $grade_card_silver_medal_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,19,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($caddie_badge_showed_order)==false){
                                echo '球童徽章显示顺序必须是整数';
                                exit;
                        }

			$data['caddie_badge_showed_order'] = $caddie_badge_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,20,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($common_employee_badge_showed_order)==false){
                                echo '高尔夫从业者徽章显示顺序必须是整数';
                                exit;
                        }

			$data['common_employee_badge_showed_order'] = $common_employee_badge_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,21,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($taylormade_badge_showed_order)==false){
                                echo '泰勒梅会员徽章显示顺序必须是整数';
                                exit;
                        }

			$data['taylormade_badge_showed_order'] = $taylormade_badge_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,22,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($china_golf_media_league_badge_showed_order)==false){
                                echo '中国高尔夫媒体联盟会员徽章显示顺序必须是整数';
                                exit;
                        }

			$data['china_golf_media_league_badge_showed_order'] = $china_golf_media_league_badge_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,23,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($rolex_member_badge_showed_order)==false){
                                echo '劳力士会员徽章显示顺序必须是整数';
                                exit;
                        }

			$data['rolex_member_badge_showed_order'] = $rolex_member_badge_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,24,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($ziji_investment_club_member_badge_showed_order)==false){
                                echo '紫金理财会员徽章显示顺序必须是整数';
                                exit;
                        }

			$data['ziji_investment_club_member_badge_showed_order'] = $ziji_investment_club_member_badge_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,25,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($pamirs_spring_water_club_member_badge_showed_order)==false){
                                echo '帕米尔矿泉水会员徽章显示顺序必须是整数';
                                exit;
                        }

			$data['pamirs_spring_water_club_member_badge_showed_order'] = $pamirs_spring_water_club_member_badge_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,26,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($nike_golf_badge_showed_order)==false){
                                echo '耐克高尔夫会员徽章显示顺序必须是整数';
                                exit;
                        }
			
			$data['nike_golf_badge_showed_order'] = $nike_golf_badge_showed_order;			

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,27,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($microblog_bronze_medal_showed_order)==false){
                                echo '铜牌微博达人徽章显示顺序必须是整数';
                                exit;
                        }

			$data['microblog_bronze_medal_showed_order'] = $microblog_bronze_medal_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,28,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($blog_bronze_medal_showed_order)==false){
                                echo '铜牌博客达人徽章显示顺序必须是整数';
                                exit;
                        }

			$data['blog_bronze_medal_showed_order'] = $blog_bronze_medal_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,29,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($grade_card_bronze_medal_showed_order)==false){
                                echo '铜牌成绩卡达人徽章显示顺序必须是整数';
                                exit;
                        }

			$data['grade_card_bronze_medal_showed_order'] = $grade_card_bronze_medal_showed_order;

                }

		$uabg = new user_applying_badges_getter();
                $record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,30,1);
                if($record_amount == 1){
                        $iv = new integer_validator();
                        if($iv->validate($blog_bronze_medal_showed_order)==false){
                                echo '实名认证徽章显示顺序必须是整数';
                                exit;
                        }

			$data['certificated_name_badge_showed_order'] = $certificated_name_badge_showed_order;

                }

		foreach ($data as $key => $value) {
			
			if($key == 'certificated_club_badge_showed_order'){
				$uabu = new user_applying_badges_updater();
				$uabu->update_showed_order($uid,1,$value);
			}

			if($key == 'cga_trainer_badge_showed_order'){
				$uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,2,$value);
			}

			if($key == 'hmt_trainer_badge_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,3,$value);
                        }

			if($key == 'foreign_trainer_badge_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,4,$value);
                        }

			if($key == 'toyota_crown_city_challenge_badge_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,5,$value);
                        }

			if($key == 'dazheng_shop_badge_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,6,$value);
                        }

			if($key == 'course_manager_badge_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,7,$value);
                        }

			if($key == 'lawn_expert_badge_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,8,$value);
                        }

			if($key == 'expert_badge_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,9,$value);
                        }

			if($key == 'microblog_gold_medal_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,10,$value);
                        }

			if($key == 'blog_gold_medal_showed_order'){
				$uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,11,$value);
			}

			if($key == 'grade_card_gold_medal_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,12,$value);
                        }

			if($key == 'toyota_crown_owner_badge_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,13,$value);
                        }

			if($key == 'ct_diamond_card_badge_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,14,$value);
                        }

			if($key == 'microblog_silver_medal_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,15,$value);
                        }

			if($key == 'blog_silver_medal_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,16,$value);
                        }

			if($key == 'grade_card_silver_medal_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,17,$value);
                        }

			if($key == 'cga_referee_badge_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,18,$value);
                        }

			if($key == 'caddie_badge_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,19,$value);
                        }

			if($key == 'common_employee_badge_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,20,$value);
                        }

			if($key == 'taylormade_badge_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,21,$value);
                        }

			if($key == 'china_golf_media_league_badge_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,22,$value);
                        }

			if($key == 'rolex_member_badge_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,23,$value);
                        }

			if($key == 'ziji_investment_club_member_badge_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,24,$value);
                        }

			if($key == 'pamirs_spring_water_club_member_badge_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,25,$value);
                        }

			if($key == 'nike_golf_badge_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,26,$value);
                        }

			if($key == 'microblog_bronze_medal_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,27,$value);
                        }

			if($key == 'blog_bronze_medal_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,28,$value);
                        }

			if($key == 'grade_card_bronze_medal_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,29,$value);
                        }

			if($key == 'certificated_name_badge_showed_order'){
                                $uabu = new user_applying_badges_updater();
                                $uabu->update_showed_order($uid,30,$value);
                        }

		}

		echo 'ok';

	}

	if($mod == 'choosing_badge'){
		$badge_id = getgpc('badge_id');
		var_dump($badge_id);
		if(!in_array($badge_id,array(0,1,2,3,4,7,8,9,18,19,20))){
			echo '选择的徽章不存在';
                        exit;
		}

		if($badge_id == 0){
			
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_badges_related_to_page_deleter.php');
			$brtpd = new badges_related_to_page_deleter();
			$brtpd->delete_by_uid($uid);

			echo 'ok';

		}else{
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_user_applying_badges_getter.php');
			$uabg = new user_applying_badges_getter();
			$record_amount = $uabg->get_record_amount_by_uid_and_badge_id_and_getting_badge_or_not($uid,$badge_id,1);
			if($record_amount == 1){
				
				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_badges_related_to_page_getter.php');
				$brtpg = new badges_related_to_page_getter();
				$record_amount = $brtpg->get_record_amount_by_uid($uid);

				if($record_amount == 0){
					require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_badges_related_to_page_adder.php');
					$brtpa = new badges_related_to_page_adder();
					$brtpa->add($uid,$badge_id);
				}else{
					require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/apply_badge/class_badges_related_to_page_updater.php');
					$brtpu = new badges_related_to_page_updater();
					$brtpu->update_badge_id_by_uid($uid,$badge_id);
				}
				
				echo 'ok';

			}else{
				echo '没有取得此徽章';
                        	exit;
			}
		}	
	}

?>
