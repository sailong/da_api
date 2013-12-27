<?php
	class badges_related_to_page_updater{
		public function update_badge_id_by_uid($uid,$badge_id){
			
			$data = array(
                                'badge_id'=>$badge_id
                        );

			$condition = array(
                                'uid'=>$uid
                        );

			DB::update('badges_related_to_page',$data,$condition);

		}
	}
?>
