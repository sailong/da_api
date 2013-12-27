<?php
	class users_badges_deleter{
		public function delete($uid,$badge_type){
			$condition = array(
				'uid'=>$uid,
				'badge_type'=>$badge_type
			);
			DB::delete('users_badges', $condition);
		}
	}
?>