<?php
	class badges_related_to_page_adder{
		public function add($uid,$badge_id){
			$data = array(
                                'uid'=>$uid,
                                'badge_id'=>$badge_id
                        );

			DB::insert('badges_related_to_page',$data);

		}
	}
?>
