<?php
	class users_badges_updater{

		public function update_info_of_club_trainer($uid,$badge_type,$org_club_trainer_belonging,$club_trainer_duty,$club_trainer_teaching_strong_point){
			
			$data = array(
                                'org_club_trainer_belonging'=>$org_club_trainer_belonging,
                                'club_trainer_duty'=>$club_trainer_duty,
                                'club_trainer_teaching_strong_point'=>$club_trainer_teaching_strong_point
                        );

			$condition = array(
                                'uid'=>$uid,
                                'badge_type'=>$badge_type
                        );

			DB::update('users_badges',$data,$condition);

		}

		public function update_info_of_cga_trainer($uid,$badge_type,$org_cga_trainer_belonging,$cga_trainer_duty,$cga_trainer_teaching_strong_point){
			
			$data = array(
                                'org_cga_trainer_belonging'=>$org_cga_trainer_belonging,
                                'cga_trainer_duty'=>$cga_trainer_duty,
                                'cga_trainer_teaching_strong_point'=>$cga_trainer_teaching_strong_point
                        );

			$condition = array(
                                'uid'=>$uid,
                                'badge_type'=>$badge_type
                        );

                        DB::update('users_badges',$data,$condition);

		}

		public function update_info_of_hmt_trainer($uid,$badge_type,$org_hmt_trainer_belonging,$hmt_trainer_duty,$hmt_trainer_teaching_strong_point){
			
			$data = array(
                                'org_hmt_trainer_belonging'=>$org_hmt_trainer_belonging,
                                'hmt_trainer_duty'=>$hmt_trainer_duty,
                                'hmt_trainer_teaching_strong_point'=>$hmt_trainer_teaching_strong_point
                        );

			$condition = array(
                                'uid'=>$uid,
                                'badge_type'=>$badge_type
                        );

                        DB::update('users_badges',$data,$condition);

		}

		public function update_foreign_trainer_info($uid,$badge_type,$org_foreign_trainer_belonging,$foreign_trainer_duty,$foreign_trainer_teaching_strong_point){
			
			$data = array(
                                'org_foreign_trainer_belonging'=>$org_foreign_trainer_belonging,
                                'foreign_trainer_duty'=>$foreign_trainer_duty,
                                'foreign_trainer_teaching_strong_point'=>$foreign_trainer_teaching_strong_point
                        );

			$condition = array(
                                'uid'=>$uid,
                                'badge_type'=>$badge_type
                        );

                        DB::update('users_badges',$data,$condition);

		}

		public function update_cga_referee_info($uid,$badge_type,$cga_referee_level,$cga_referee_judging_game_num,$cga_referee_native_place,$cga_referee_working_place,$cga_referee_personal_desc){
			
			$data = array(
                                'cga_referee_level'=>$cga_referee_level,
                                'cga_referee_judging_game_num'=>$cga_referee_judging_game_num,
                                'cga_referee_native_place'=>$cga_referee_native_place,
                                'cga_referee_working_place'=>$cga_referee_working_place,
                                'cga_referee_personal_desc'=>$cga_referee_personal_desc
                        );

			$condition = array(
                                'uid'=>$uid,
                                'badge_type'=>$badge_type
                        );

                        DB::update('users_badges',$data,$condition);

		}

		public function update_info_of_course_manager($uid,$badge_type,$company_name_of_course_manager,$company_address_of_course_manager,$personal_desc_of_course_manager){

			$data = array(
				
				'company_name_of_course_manager'=>$company_name_of_course_manager,
				'company_address_of_course_manager'=>$company_address_of_course_manager,
				'personal_desc_of_course_manager'=>$personal_desc_of_course_manager
				
                        );

			$condition = array(
                                'uid'=>$uid,
                                'badge_type'=>$badge_type
                        );
			
			DB::update('users_badges',$data,$condition);

		}
	
		public function update_info_of_lawn_expert($uid,$badge_type,$lawn_expert_name_and_duty,$lawn_expert_personal_desc){
			
			$data = array(

				'lawn_expert_name_and_duty'=>$lawn_expert_name_and_duty,
				'lawn_expert_personal_desc'=>$lawn_expert_personal_desc

			);

			$condition = array(
                                'uid'=>$uid,
                                'badge_type'=>$badge_type
                        );
			
			DB::update('users_badges',$data,$condition);

		}
	
		public function update_info_of_expert($uid,$badge_type,$expert_name_and_duty,$expert_personal_desc){
			
			$data = array(
				'expert_name_and_duty'=>$expert_name_and_duty,
				'expert_personal_desc'=>$expert_personal_desc
			);

			$condition = array(
                                'uid'=>$uid,
                                'badge_type'=>$badge_type
                        );
			
			DB::update('users_badges',$data,$condition);

		}

		public function update_info_of_caddie($uid,$badge_type,$club_place_info1_of_caddie,$club_place_info2_of_caddie,$caddie_beginning_working_date,$caddie_birth_date,$caddie_working_place_info1_1,$caddie_working_place_info1_2,$caddie_working_place_info2_1,$caddie_working_place_info2_2,$caddie_working_place_info3_1,$caddie_working_place_info3_2,$caddie_personal_desc){
			
			$data = array(
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

			$condition = array(
                                'uid'=>$uid,
                                'badge_type'=>$badge_type
                        );
			
			DB::update('users_badges',$data,$condition);

		}

		public function update_info_of_practitioner($uid,$badge_type,$company_name_of_practitioner,$duty_of_practitioner,$company_address_of_practitioner,$personal_desc_of_practitioner){
			
			$data = array(
				
				'company_name_of_practitioner'=>$company_name_of_practitioner,
				'duty_of_practitioner'=>$duty_of_practitioner,
				'company_address_of_practitioner'=>$company_address_of_practitioner,
				'personal_desc_of_practitioner'=>$personal_desc_of_practitioner

			);

			$condition = array(
                                'uid'=>$uid,
                                'badge_type'=>$badge_type
                        );
			
			DB::update('users_badges',$data,$condition);

		}

		public function update_getting_badge_or_not($uid,$badge_type,$getting_badge_or_not){
			
			$data = array(
				'getting_badge_or_not'=>$getting_badge_or_not,
			);
			
			$condition = array(
				'uid'=>$uid,
				'badge_type'=>$badge_type
			);
			
			DB::update('users_badges',$data,$condition);
		}

		public function update_showed_order($uid,$badge_type,$showed_order){
			
			$data = array(
				'showed_order'=>$showed_order
			);

			$condition = array(
				'uid'=>$uid,
				'badge_type'=>$badge_type
			);
			
			DB::update('users_badges',$data,$condition);
			
		}

	}
?>
