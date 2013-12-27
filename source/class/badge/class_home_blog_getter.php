<?php
	class home_blog_getter{
		
		public function get_record_amount_by_uid($uid){
			$hb = DB::table('home_blog');
			$sql = "SELECT COUNT(*) FROM ".$hb." WHERE uid=".$uid;
			$tmp = DB::query($sql);
                        $row =  DB::fetch($tmp);
                        return $row['COUNT(*)'];
		}
	
	}
?>
