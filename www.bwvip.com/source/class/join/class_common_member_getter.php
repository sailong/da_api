<?php
	class common_member_getter{
		public function get_record_by_uid($uid){
			$cm = DB::table('common_member');
			$sql = "SELECT * FROM ".$cm." WHERE uid=".$uid;
			$tmp = DB::query($sql);
                        while($r = DB::fetch($tmp)){
                                $rows[] = $r;
                        }
                        return $rows[0];
		}
	}
?>
