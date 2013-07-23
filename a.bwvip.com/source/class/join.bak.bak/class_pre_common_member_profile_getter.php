<?php
	class pre_common_member_profile_getter{
		
		public function get_by_uid($uid){
			$cmp = DB::table('common_member_profile');
			$sql = "SELECT * FROM ".$cmp." WHERE uid=".$uid;
			$tmp = DB::query($sql);
			return DB::fetch($tmp);
		}

	}
?>
