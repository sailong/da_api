<?php
	class volunteers_and_players_updater{
		public function update_paying_or_not_by_uid($uid,$paying_or_not){
			$data = array(
				'paying_or_not'=>$paying_or_not			
			);

			$condition = array(
                                'uid'=>$uid
                        );

			DB::update('volunteers_and_players',$data,$condition);
		}

		public function update_passing_or_not_by_uid($uid,$passing_or_not){
			$data = array(
                                'passing_or_not'=>$passing_or_not
                        );

			$condition = array(
                                'uid'=>$uid
                        );

                        DB::update('volunteers_and_players',$data,$condition);
		}

		public function update_game_beginning_or_not_by_uid($uid,$game_beginning_or_not){
			$data = array(
                                'game_beginning_or_not'=>$game_beginning_or_not
                        );

			$condition = array(
                                'uid'=>$uid
                        );

                        DB::update('volunteers_and_players',$data,$condition);

		}

	}
?>
