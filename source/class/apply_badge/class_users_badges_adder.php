<?php
	class users_badges_adder{
		public function add($uid,$badge_type,$getting_badge_or_not=0,$automatic_verifying=0,$showed_order=1,$company_name_of_course_manager='',$company_address_of_course_manager='',$personal_desc_of_course_manager='',$company_name_of_practitioner='',$duty_of_practitioner='',$company_address_of_practitioner='',$personal_desc_of_practitioner='',$lawn_expert_name_and_duty='',$lawn_expert_personal_desc='',$expert_name_and_duty='',$expert_personal_desc='',$club_place_info1_of_caddie='',$club_place_info2_of_caddie='',$caddie_beginning_working_date='',$caddie_birth_date='',$caddie_working_place_info1_1='',$caddie_working_place_info1_2='',$caddie_working_place_info2_1='',$caddie_working_place_info2_2='',$caddie_working_place_info3_1='',$caddie_working_place_info3_2='',$caddie_personal_desc='',$org_club_trainer_belonging='',$club_trainer_duty='',$club_trainer_teaching_strong_point=''){
			
			$data = array(
				'uid'=>$uid,
				'badge_type'=>$badge_type,
				'getting_badge_or_not'=>$getting_badge_or_not,
				'automatic_verifying'=>$automatic_verifying,
				'showed_order'=>$showed_order,
				'company_name_of_course_manager'=>$company_name_of_course_manager,
				'company_address_of_course_manager'=>$company_address_of_course_manager,
				'personal_desc_of_course_manager'=>$personal_desc_of_course_manager,
				'company_name_of_practitioner'=>$company_name_of_practitioner,
				'duty_of_practitioner'=>$duty_of_practitioner,
				'company_address_of_practitioner'=>$company_address_of_practitioner,
				'personal_desc_of_practitioner'=>$personal_desc_of_practitioner,
				'lawn_expert_name_and_duty'=>$lawn_expert_name_and_duty,
				'lawn_expert_personal_desc'=>$lawn_expert_personal_desc,
				'expert_name_and_duty'=>$expert_name_and_duty,
				'expert_personal_desc'=>$expert_personal_desc,
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
				'caddie_personal_desc'=>$caddie_personal_desc,
				'org_club_trainer_belonging'=>$org_club_trainer_belonging,
				'club_trainer_duty'=>$club_trainer_duty,
				'club_trainer_teaching_strong_point'=>$club_trainer_teaching_strong_point			
			);
			
			DB::insert('users_badges',$data);
			
		}

		public function add_club_trainer_info($uid,$badge_type,$org_club_trainer_belonging,$club_trainer_duty,$club_trainer_teaching_strong_point){
			
			$data = array(
				'uid'=>$uid,
				'badge_type'=>$badge_type,
				'org_club_trainer_belonging'=>$org_club_trainer_belonging,
				'club_trainer_duty'=>$club_trainer_duty,
				'club_trainer_teaching_strong_point'=>$club_trainer_teaching_strong_point
			);

			DB::insert('users_badges',$data);

		}

		public function add_cga_trainer_info($uid,$badge_type,$org_cga_trainer_belonging,$cga_trainer_duty,$cga_trainer_teaching_strong_point){
			
			$data = array(
				'uid'=>$uid,
                                'badge_type'=>$badge_type,
				'org_cga_trainer_belonging'=>$org_cga_trainer_belonging,
				'cga_trainer_duty'=>$cga_trainer_duty,
				'cga_trainer_teaching_strong_point'=>$cga_trainer_teaching_strong_point
			);

			DB::insert('users_badges',$data);

		}

		public function add_hmt_trainer_info($uid,$badge_type,$org_hmt_trainer_belonging,$hmt_trainer_duty,$hmt_trainer_teaching_strong_point){
			
			$data = array(
				'uid'=>$uid,
                                'badge_type'=>$badge_type,
				'org_hmt_trainer_belonging'=>$org_hmt_trainer_belonging,
				'hmt_trainer_duty'=>$hmt_trainer_duty,
				'hmt_trainer_teaching_strong_point'=>$hmt_trainer_teaching_strong_point
			);

			DB::insert('users_badges',$data);

		}

		public function add_foreign_trainer_info($uid,$badge_type,$org_foreign_trainer_belonging,$foreign_trainer_duty,$foreign_trainer_teaching_strong_point){
			
			$data = array(
				'uid'=>$uid,
                                'badge_type'=>$badge_type,
				'org_foreign_trainer_belonging'=>$org_foreign_trainer_belonging,
				'foreign_trainer_duty'=>$foreign_trainer_duty,
				'foreign_trainer_teaching_strong_point'=>$foreign_trainer_teaching_strong_point
			);

			DB::insert('users_badges',$data);

		}

		public function add_cga_referee_info($uid,$badge_type,$cga_referee_level,$cga_referee_judging_game_num,$cga_referee_native_place,$cga_referee_working_place,$cga_referee_personal_desc){
			
			$data = array(
				'uid'=>$uid,
                                'badge_type'=>$badge_type,
				'cga_referee_level'=>$cga_referee_level,
				'cga_referee_judging_game_num'=>$cga_referee_judging_game_num,
				'cga_referee_native_place'=>$cga_referee_native_place,
				'cga_referee_working_place'=>$cga_referee_working_place,
				'cga_referee_personal_desc'=>$cga_referee_personal_desc
			);

			DB::insert('users_badges',$data);

		}

	}
?>
