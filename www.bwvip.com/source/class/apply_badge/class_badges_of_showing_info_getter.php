<?php
	class badges_of_showing_info_getter{
		
		public function get_record_amount_by_uid($uid){
			$ub = DB::table('badges_of_showing_info');
                        $sql = "SELECT COUNT(*) FROM ".$ub." WHERE uid=".$uid;
                        $tmp = DB::query($sql);
                        $row =  DB::fetch($tmp);
                        return $row['COUNT(*)'];
		}

		public function get_record_by_uid($uid){
			$ub = DB::table('badges_of_showing_info');
                        $sql = "SELECT * FROM ".$ub." WHERE uid=".$uid;
			$tmp = DB::query($sql);
                        while($r = DB::fetch($tmp)){
                                $rows[] = $r;
                        }
                        return $rows[0];
		}

	}
?>
