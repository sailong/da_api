<?php
	class comments_getter{
		public function get_by_uid($uid){
			$c = DB::table('comments');
			$sql = "SELECT id,type,title,content FROM ".$c." WHERE uid=".$uid;
			$tmp = DB::query($sql);
			while($r = DB::fetch($tmp)){
				$rows[] = $r;
			}
			return $rows;		
		}
	}
?>