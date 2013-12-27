<?php
	class home_dazbm_getter{
		
		public function get_record_amount_by_uid_and_apply_status($uid,$apply_status){
			$hd = DB::table('home_dazbm');
			$sql = "SELECT COUNT(*) FROM ".$hd." WHERE uid=".$uid." AND apply_status=".$apply_status;
			$tmp = DB::query($sql);
                        $row =  DB::fetch($tmp);
                        return $row['COUNT(*)'];
		}

	}
?>
