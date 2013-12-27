<?php
	class user_applying_badge_infos_updater{

		public function update_tag_value_by_uid_and_badge_id_and_tag_name($uid,$badge_id,$tag_name,$tag_value){
		
			$data = array(
				'tag_value'=>$tag_value
			);
	
			$condition = array(
                                'uid'=>$uid,
                                'badge_id'=>$badge_id,
				'tag_name'=>$tag_name
                        );

                        DB::update('user_applying_badge_infos',$data,$condition);

		}

		
	}
?>
