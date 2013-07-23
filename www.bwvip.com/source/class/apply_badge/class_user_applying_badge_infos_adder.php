<?php
	class user_applying_badge_infos_adder{
		public function add($uid,$badge_id,$tag_name,$tag_value){
			
			$data = array(
				'uid'=>$uid,
				'badge_id'=>$badge_id,
				'tag_name'=>$tag_name,
				'tag_value'=>$tag_value
			);
			
			DB::insert('user_applying_badge_infos',$data);

		}
	}
?>
