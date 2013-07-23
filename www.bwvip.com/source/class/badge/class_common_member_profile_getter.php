<?php
	class common_member_profile_getter{
		
		public function get_realname_by_uid($uid){
			$cmp = DB::table('common_member_profile');
			$sql = "SELECT realname FROM ".$cmp." WHERE uid=".$uid;
			$tmp = DB::query($sql);
                        $row =  DB::fetch($tmp);
                        return $row['realname'];
		}

	}
?>
