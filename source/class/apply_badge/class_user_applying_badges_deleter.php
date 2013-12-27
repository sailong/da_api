<?php
	class user_applying_badges_deleter{
		public function delete_by_uid($uid){
			$condition = array(
                                'uid'=>$uid
                        );
                        DB::delete('badges_related_to_page', $condition);
		}
	}
?>
