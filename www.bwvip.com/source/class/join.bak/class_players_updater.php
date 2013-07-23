<?php
	class players_updater{
		public function update_by_uid($uid,$email,$fax_num,$getting_game_info_by_website,$getting_game_info_by_inews,$getting_game_info_by_microblog,$getting_game_info_by_tmk,$getting_game_info_by_short_message,$getting_game_info_by_friend,$getting_game_info_by_other_way,$trainer_joining_game,$referee_joining_game){
			
			$data = array(
                                'email'=>$email,
                                'fax_num'=>$fax_num,
                                'getting_game_info_by_website'=>$getting_game_info_by_website,
                                'getting_game_info_by_inews'=>$getting_game_info_by_inews,
                                'getting_game_info_by_microblog'=>$getting_game_info_by_microblog,
                                'getting_game_info_by_tmk'=>$getting_game_info_by_tmk,
                                'getting_game_info_by_short_message'=>$getting_game_info_by_short_message,
                                'getting_game_info_by_friend'=>$getting_game_info_by_friend,
                                'getting_game_info_by_other_way'=>$getting_game_info_by_other_way,
                                'trainer_joining_game'=>$trainer_joining_game,
                                'referee_joining_game'=>$referee_joining_game
                        );
			
			$condition = array(
                                'uid'=>$uid
                        );

                        DB::update('volunteers_and_players',$data,$condition);

		}
		
		public function update_trainer_by_uid_for_page2($uid,$trainer_organization,$trainer_duty,$trainer_teaching_yearsexp,$trainer_playing_yearsexp,$trainer_handicap,$trainer_personal_best,$trainer_course_info1_of_getting_personal_best,$trainer_course_info2_of_getting_personal_best,$trainer_good_at_teaching,$trainer_certificate_inexistence=1,$recommendation_letter_inexistence=1){
			
			$data = array(
				'trainer_organization'=>$trainer_organization,
				'trainer_duty'=>$trainer_duty,
				'trainer_teaching_yearsexp'=>$trainer_teaching_yearsexp,
				'playing_yearsexp'=>$trainer_playing_yearsexp,
				'handicap'=>$trainer_handicap,
				'personal_best'=>$trainer_personal_best,
				'course_info1_of_getting_personal_best'=>$trainer_course_info1_of_getting_personal_best,
				'course_info2_of_getting_personal_best'=>$trainer_course_info2_of_getting_personal_best,
				'trainer_good_at_teaching'=>$trainer_good_at_teaching,
				'trainer_certificate_inexistence'=>$trainer_certificate_inexistence,
				'recommendation_letter_inexistence'=>$recommendation_letter_inexistence
			);

			$condition = array(
                                'uid'=>$uid
                        );

                        DB::update('volunteers_and_players',$data,$condition);
	
		}
		
		public function update_referee_by_uid_for_page2($uid,$referee_level,$referee_getting_certificate_date,$referee_judging_game_num,$playing_yearsexp,$handicap,$personal_best,$course_info1_of_getting_personal_best,$course_info2_of_getting_personal_best,$referee_certificate_inexistence=1){
			
			$data = array(
				'referee_level'=>$referee_level,
				'referee_getting_certificate_date'=>$referee_getting_certificate_date,
				'referee_judging_game_num'=>$referee_judging_game_num,
				'playing_yearsexp'=>$playing_yearsexp,
				'handicap'=>$handicap,
				'personal_best'=>$personal_best,
				'course_info1_of_getting_personal_best'=>$course_info1_of_getting_personal_best,
				'course_info2_of_getting_personal_best'=>$course_info2_of_getting_personal_best,
				'referee_certificate_inexistence'=>$referee_certificate_inexistence
			);

			$condition = array(
                                'uid'=>$uid
                        );

                        DB::update('volunteers_and_players',$data,$condition);

		}

		public function update_trainer_and_referee_by_uid_for_page2($uid,$trainer_organization,$trainer_duty,$trainer_teaching_yearsexp,$trainer_good_at_teaching,$referee_level,$referee_getting_certificate_date,$referee_judging_game_num,$playing_yearsexp,$handicap,$personal_best,$course_info1_of_getting_personal_best,$course_info2_of_getting_personal_best,$trainer_certificate_inexistence=1,$recommendation_letter_inexistence=1,$referee_certificate_inexistence=1){
			$data = array(
				'trainer_organization'=>$trainer_organization,
				'trainer_duty'=>$trainer_duty,
				'trainer_teaching_yearsexp'=>$trainer_teaching_yearsexp,
				'trainer_good_at_teaching'=>$trainer_good_at_teaching,
				'referee_level'=>$referee_level,
				'referee_getting_certificate_date'=>$referee_getting_certificate_date,
				'referee_judging_game_num'=>$referee_judging_game_num,
				'playing_yearsexp'=>$playing_yearsexp,
				'handicap'=>$handicap,
				'personal_best'=>$personal_best,
				'course_info1_of_getting_personal_best'=>$course_info1_of_getting_personal_best,
				'course_info2_of_getting_personal_best'=>$course_info2_of_getting_personal_best,
				'trainer_certificate_inexistence'=>$trainer_certificate_inexistence,
				'recommendation_letter_inexistence'=>$recommendation_letter_inexistence,
				'referee_certificate_inexistence'=>$referee_certificate_inexistence
			);
			
			$condition = array(
                                'uid'=>$uid
                        );

                        DB::update('volunteers_and_players',$data,$condition);
		}

		public function update_for_page3($uid,$game_area,$joining_golf_equipment_forum,$joining_golf_rules_forum,$joining_golf_etiquette_forum,$joining_teaching_forum,$joining_sports_injury_forum,$joining_lawn_care_forum,$joining_course_design_research_forum,$joining_trying_play_party_forum,$joining_fun_golf_game_forum,$loving_Adams,$loving_Callaway,$loving_Cleverland,$loving_Co_bra,$loving_Dunlop,$loving_ECCO,$loving_FootJoy,$loving_HONMA,$loving_Kasco,$loving_MAC_GREGON,$loving_Mizuno,$loving_MURUMAN,$loving_Nike_Golf,$loving_Odyssey,$loving_PING,$loving_PRGR,$loving_SRIXON,$loving_S_YARD,$loving_Taylormade,$loving_Titleist,$loving_XXIO,$loving_other_brand,$loving_reason,$accept_or_not){
			
			$data = array(
                                'game_area'=>$game_area,
                                'joining_golf_equipment_forum'=>$joining_golf_equipment_forum,
                                'joining_golf_rules_forum'=>$joining_golf_rules_forum,
                                'joining_golf_etiquette_forum'=>$joining_golf_etiquette_forum,
                                'joining_teaching_forum'=>$joining_teaching_forum,
                                'joining_sports_injury_forum'=>$joining_sports_injury_forum,
                                'joining_lawn_care_forum'=>$joining_lawn_care_forum,
                                'joining_course_design_research_forum'=>$joining_course_design_research_forum,
                                'joining_trying_play_party_forum'=>$joining_trying_play_party_forum,
                                'joining_fun_golf_game_forum'=>$joining_fun_golf_game_forum,
                                'loving_Adams'=>$loving_Adams,
                                'loving_Callaway'=>$loving_Callaway,
                                'loving_Cleverland'=>$loving_Cleverland,
                                'loving_Co_bra'=>$loving_Co_bra,
                                'loving_Dunlop'=>$loving_Dunlop,
                                'loving_ECCO'=>$loving_ECCO,
                                'loving_FootJoy'=>$loving_FootJoy,
                                'loving_HONMA'=>$loving_HONMA,
                                'loving_Kasco'=>$loving_Kasco,
                                'loving_MAC_GREGON'=>$loving_MAC_GREGON,
                                'loving_Mizuno'=>$loving_Mizuno,
                                'loving_MURUMAN'=>$loving_MURUMAN,
                                'loving_Nike_Golf'=>$loving_Nike_Golf,
                                'loving_Odyssey'=>$loving_Odyssey,
                                'loving_PING'=>$loving_PING,
                                'loving_PRGR'=>$loving_PRGR,
                                'loving_SRIXON'=>$loving_SRIXON,
                                'loving_S_YARD'=>$loving_S_YARD,
                                'loving_Taylormade'=>$loving_Taylormade,
                                'loving_Titleist'=>$loving_Titleist,
                                'loving_XXIO'=>$loving_XXIO,
                                'loving_other_brand'=>$loving_other_brand,
                                'loving_reason'=>$loving_reason,
                                'accept_or_not'=>$accept_or_not
                        );

			$condition = array(
                                'uid'=>$uid
                        );

                        DB::update('volunteers_and_players',$data,$condition);

		}

	}
?>
