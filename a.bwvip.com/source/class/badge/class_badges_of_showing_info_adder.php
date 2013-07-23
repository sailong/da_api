<?php
	class badges_of_showing_info_adder{
		public function add($uid,$badge_type){
			
			$data = array(
                                'uid'=>$uid,
                                'badge_type'=>$badge_type
			);

			DB::insert('badges_of_showing_info',$data);

		}
	}
?>
