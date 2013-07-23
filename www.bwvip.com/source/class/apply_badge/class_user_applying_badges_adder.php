<?php
	class user_applying_badges_adder{
		public function add($uid,$badge_id,$getting_badge_or_not,$automatic_verifying,$showed_order){
			
			$data = array(
				'uid'=>$uid,
				'badge_id'=>$badge_id,
				'getting_badge_or_not'=>$getting_badge_or_not,
				'automatic_verifying'=>$automatic_verifying,
				'showed_order'=>$showed_order
			);
			
			DB::insert('user_applying_badges',$data);

		}
	}
?>
