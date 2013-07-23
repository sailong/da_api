<?php
	class badges_of_showing_info_updater{
		public function update_badge_type($uid,$badge_type){
			
			$data = array(
				
				'badge_type'=>$badge_type

			);

			$condition = array(
                                'uid'=>$uid
                        );

			DB::update('badges_of_showing_info',$data,$condition);

		}
	}
?>
