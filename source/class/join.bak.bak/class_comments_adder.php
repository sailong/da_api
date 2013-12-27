<?php
	class comments_adder{
		public function add($type,$title,$content,$uid){
			$data = array(
				'type'=>$type,
				'title'=>$title,
				'content'=>$content,
				'uid'=>$uid
			);
			DB::insert('comments',$data);
		}
	}
?>