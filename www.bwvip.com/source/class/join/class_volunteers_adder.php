<?php
	class volunteers_adder{
		public function add($uid,$realname,$gender,$birthyear,$birthmonth,$birthday,$nationality,$mobile,$email,$telephone,$fax_num,$idcardtype,$idcard,$address,$zipcode,$getting_game_info_by_website,$getting_game_info_by_inews,$getting_game_info_by_microblog,$getting_game_info_by_tmk,$getting_game_info_by_short_message,$getting_game_info_by_friend,$getting_game_info_by_other_way){
			
			
			$data = array(
				'uid'=>$uid,
				#'realname'=>$realname,
				#'gender'=>$gender,
				#'birthyear'=>$birthyear,
				#'birthmonth'=>$birthmonth,
				#'birthday'=>$birthday,
				#'nationality'=>$nationality,
				#'mobile'=>$mobile,
				'email'=>$email,
				#'telephone'=>$telephone,
				'fax_num'=>$fax_num,
				#'idcardtype'=>$idcardtype,
				#'idcard'=>$idcard,
				#'address'=>$address,
				#'zipcode'=>$zipcode,
				'getting_game_info_by_website'=>$getting_game_info_by_website,
				'getting_game_info_by_inews'=>$getting_game_info_by_inews,
				'getting_game_info_by_microblog'=>$getting_game_info_by_microblog,
				'getting_game_info_by_tmk'=>$getting_game_info_by_tmk,
				'getting_game_info_by_short_message'=>$getting_game_info_by_short_message,
				'getting_game_info_by_friend'=>$getting_game_info_by_friend,
				'getting_game_info_by_other_way'=>$getting_game_info_by_other_way,
				'volunteer_or_player'=>'志愿者',
				'enrolling_time'=>date('Y-m-d H:i:s')
			);
			
			DB::insert('volunteers_and_players',$data);
			
		}

		public function add_for_page2($uid,$level,$getting_certificate_date,$judging_game_num,$playing_yearsexp,$handicap,$personal_best,$course_info1_of_getting_personal_best,$course_info2_of_getting_personal_best){
			
			$data = array(
				'uid'=>$uid,
				'referee_level'=>$level,
				'referee_getting_certificate_date'=>$getting_certificate_date,
				'referee_judging_game_num'=>$judging_game_num,
				'playing_yearsexp'=>$playing_yearsexp,
				'handicap'=>$handicap,
				'personal_best'=>$personal_best,
				'course_info1_of_getting_personal_best'=>$course_info1_of_getting_personal_best,
				'course_info2_of_getting_personal_best'=>$course_info2_of_getting_personal_best,
				'volunteer_or_player'=>'志愿者',
				'enrolling_time'=>date('Y-m-d H:i:s'),
				'referee_certificate_inexistence'=>0
			);

			DB::insert('volunteers_and_players',$data);
			
		}

		public function add_for_page3($uid,$judging_game_round1_in_north_china,$judging_game_round2_in_north_china,$judging_game_round1_in_east_china,$judging_game_round2_in_east_china,$judging_game_round1_in_south_china,$judging_game_round2_in_south_china,$judging_game_round1_in_south_central_china,$judging_game_round2_in_south_central_china,$judging_final_game_round1,$judging_final_game_round2,$judging_final_game_round3,$joining_golf_equipment_forum,$joining_golf_rules_forum,$joining_golf_etiquette_forum,$joining_teaching_forum,$joining_sports_injury_forum,$joining_lawn_care_forum,$joining_course_design_research_forum,$joining_trying_play_party_forum,$joining_fun_golf_game_forum,$loving_Adams,$loving_Callaway,$loving_Cleverland,$loving_Co_bra,$loving_Dunlop,$loving_ECCO,$loving_FootJoy,$loving_HONMA,$loving_Kasco,$loving_MAC_GREGON,$loving_Mizuno,$loving_MURUMAN,$loving_Nike_Golf,$loving_Odyssey,$loving_PING,$loving_PRGR,$loving_SRIXON,$loving_S_YARD,$loving_Taylormade,$loving_Titleist,$loving_XXIO,$loving_other_brand,$loving_reason,$accept_or_not){
			
			$data = array(
				'uid'=>$uid,
				'referee_judging_game_round1_in_north_china'=>$judging_game_round1_in_north_china,
				'referee_judging_game_round2_in_north_china'=>$judging_game_round2_in_north_china,
				'referee_judging_game_round1_in_east_china'=>$judging_game_round1_in_east_china,
				'referee_judging_game_round2_in_east_china'=>$judging_game_round2_in_east_china,
				'referee_judging_game_round1_in_south_china'=>$judging_game_round1_in_south_china,
				'referee_judging_game_round2_in_south_china'=>$judging_game_round2_in_south_china,
				'referee_judging_game_round1_in_south_central_china'=>$judging_game_round1_in_south_central_china,
				'referee_judging_game_round2_in_south_central_china'=>$judging_game_round2_in_south_central_china,
				'referee_judging_final_game_round1'=>$judging_final_game_round1,
				'referee_judging_final_game_round2'=>$judging_final_game_round2,
				'referee_judging_final_game_round3'=>$judging_final_game_round3,
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
				'accept_or_not'=>$accept_or_not,
				'volunteer_or_player'=>'志愿者',
				'enrolling_time'=>date('Y-m-d H:i:s')
			);

			DB::insert('volunteers_and_players',$data);

		}

	}
?>
