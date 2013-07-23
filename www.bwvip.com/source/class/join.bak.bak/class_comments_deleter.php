<?php
	class comments_deleter{
		public function delete_by_comment_id($comment_id){
			$condition = array(
				'id'=>$comment_id
			);
			DB::delete('comments',$condition);
		}
	}
?>