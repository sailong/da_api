<?php
	class players_adder{
		public function add($uid,$email,$fax_num,$getting_game_info_by_website,$getting_game_info_by_inews,$getting_game_info_by_microblog,$getting_game_info_by_tmk,$getting_game_info_by_short_message,$getting_game_info_by_friend,$getting_game_info_by_other_way,$trainer_joining_game,$referee_joining_game){
			
			$data = array(
				'uid'=>$uid,
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
				'referee_joining_game'=>$referee_joining_game,
				'volunteer_or_player'=>'球员',
				'enrolling_time'=>date('Y-m-d H:i:s')
			);
			DB::insert('volunteers_and_players',$data);
			
		}

		public function add_for_page3($uid,$game_area,$joining_golf_equipment_forum,$joining_golf_rules_forum,$joining_golf_etiquette_forum,$joining_teaching_forum,$joining_sports_injury_forum,$joining_lawn_care_forum,$joining_course_design_research_forum,$joining_trying_play_party_forum,$joining_fun_golf_game_forum,$loving_Adams,$loving_Callaway,$loving_Cleverland,$loving_Co_bra,$loving_Dunlop,$loving_ECCO,$loving_FootJoy,$loving_HONMA,$loving_Kasco,$loving_MAC_GREGON,$loving_Mizuno,$loving_MURUMAN,$loving_Nike_Golf,$loving_Odyssey,$loving_PING,$loving_PRGR,$loving_SRIXON,$loving_S_YARD,$loving_Taylormade,$loving_Titleist,$loving_XXIO,$loving_other_brand,$loving_reason,$accept_or_not){
			
			$data = array(
				'uid'=>$uid,
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
                                'accept_or_not'=>$accept_or_not,
				'volunteer_or_player'=>'球员',
				'enrolling_time'=>date('Y-m-d H:i:s')
			);

			DB::insert('volunteers_and_players',$data);

		}

	}
?>
