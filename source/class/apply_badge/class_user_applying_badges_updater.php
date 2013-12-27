<?php
	class user_applying_badges_updater{
		public function update_showed_order($uid,$badge_id,$showed_order){
			$data = array(
                                'showed_order'=>$showed_order
                        );

                        $condition = array(
                                'uid'=>$uid,
                                'badge_id'=>$badge_id
                        );

                        DB::update('user_applying_badges',$data,$condition);
		}

		public function update_getting_badge_or_not($uid,$badge_id,$getting_badge_or_not){
			
			$data = array(
                                'getting_badge_or_not'=>$getting_badge_or_not
                        );

			$condition = array(
                                'uid'=>$uid,
                                'badge_id'=>$badge_id
                        );

			DB::update('user_applying_badges',$data,$condition);

		}

	}

?>
